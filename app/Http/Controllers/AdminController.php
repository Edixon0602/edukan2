<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\PendingPayment;
use App\Models\StudentGrade;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use App\Models\WrittenAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total stats
        $totalCourses = Course::count();
        $totalUsers = User::whereHas('role', function ($query) {
            $query->where('name', '!=', 'admin');
        })->count();
        
        $limitDate = now()->subDays(30);
        $activeStudents = User::whereHas('role', function ($query) {
            $query->where('name', '!=', 'admin');
        })
            ->where('last_login_at', '>=', $limitDate)
            ->count();
        
        $inactiveStudents = $totalUsers - $activeStudents;

        // Total sales (Sum of approved payments)
        // For simplicity, we calculate from payments approved (mocked or completed)
        $totalSales = Setting::getVal('total_ventas_simuladas', 12450.00); 

        // Web traffic metrics
        $trafico = Setting::getVal('contador_general', ['visitasTotales' => 1250]);
        $visitas = $trafico['visitasTotales'] ?? 1250;
        
        $tasaConversion = $visitas > 0 ? round(($totalUsers / $visitas) * 100, 1) : 0;

        // BCV rate
        $tasas = Setting::getVal('tasas', ['euroBCV' => 36.50]);
        $bcvRate = $tasas['euroBCV'] ?? 36.50;

        // Proof social stats
        $proofSocial = Setting::getVal('landing_metrics', [
            'alumnos' => '1,250+',
            'exito' => '98%',
            'paises' => '15+'
        ]);

        // Categories, Courses, Memberships for forms
        $categories = Category::all();
        $courses = Course::all();
        $memberships = Membership::all();

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalUsers',
            'activeStudents',
            'inactiveStudents',
            'totalSales',
            'visitas',
            'tasaConversion',
            'bcvRate',
            'proofSocial',
            'categories',
            'courses',
            'memberships'
        ));
    }

    public function updateBcv(Request $request)
    {
        $request->validate([
            'euroBCV' => ['required', 'numeric', 'min:0'],
        ]);

        Setting::setVal('tasas', ['euroBCV' => (float)$request->euroBCV]);

        return back()->with('success', 'Tasa oficial BCV actualizada.');
    }

    public function updateHeroStats(Request $request)
    {
        $request->validate([
            'alumnos' => ['required', 'string'],
            'exito' => ['required', 'string'],
            'paises' => ['required', 'string'],
        ]);

        Setting::setVal('landing_metrics', [
            'alumnos' => $request->alumnos,
            'exito' => $request->exito,
            'paises' => $request->paises
        ]);

        return back()->with('success', 'Métricas de prueba social en portada actualizadas.');
    }

    // CRM Actions
    public function crmList(Request $request)
    {
        $query = User::with('role')->whereHas('role', function ($q) {
            $q->where('name', '!=', 'admin');
        });

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('phone', 'like', $search);
            });
        }

        if ($request->filled('membership') && $request->membership !== 'todos') {
            $query->where('membership', $request->membership);
        }

        if ($request->filled('status') && $request->status !== 'todos') {
            $limitDate = now()->subDays(30);
            if ($request->status === 'activo') {
                $query->where('last_login_at', '>=', $limitDate);
            } else {
                $query->where(function($q) use ($limitDate) {
                    $q->where('last_login_at', '<', $limitDate)
                      ->orWhereNull('last_login_at');
                });
            }
        }

        $students = $query->paginate(20);

        return response()->json($students);
    }

    public function auditStudent(User $student)
    {
        $grades = $student->grades()->orderBy('created_at', 'desc')->get();
        return response()->json($grades);
    }

    public function giftCourse(Request $request, User $student)
    {
        $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $course = Course::findOrFail($request->course_id);

        // Access check or create matrícula inicial
        $enrollment = StudentGrade::updateOrCreate([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'module_name' => 'Matrícula Inicial',
        ], [
            'course_title' => $course->title,
            'grade' => 45,
            'requires_revision' => false,
            'attempt_date' => now(),
        ]);

        // Add custom system notification for student
        Notification::create([
            'user_id' => $student->id,
            'type' => 'success',
            'title' => '🎁 Curso de Regalo Obtenido',
            'message' => "Un administrador te ha regalado acceso al curso: {$course->title}."
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Acceso de regalo concedido correctamente.'
        ]);
    }

    public function assignDiscount(Request $request, User $student)
    {
        $request->validate([
            'discount' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $student->update([
            'global_discount' => $request->discount
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Descuento global asignado correctamente.'
        ]);
    }

    // Payments Queue
    public function paymentsQueue()
    {
        $payments = PendingPayment::with('user:id,name,email')
            ->where('status', 'en_revision')
            ->latest()
            ->get();

        return response()->json($payments);
    }

    public function approvePayment(PendingPayment $payment)
    {
        $student = $payment->user;
        $itemName = $payment->item_name;

        // Check if item is a membership plan
        if (str_contains(strtolower($itemName), 'membresía') || str_contains(strtolower($itemName), 'plan')) {
            $rango = 'regular';
            if (str_contains($itemName, 'Estándar')) $rango = 'estandar';
            if (str_contains($itemName, 'Pro')) $rango = 'pro';
            if (str_contains($itemName, 'VIP')) $rango = 'vip';

            $roleEstudiante = Role::where('name', 'estudiante')->first();
            $student->update([
                'membership' => $rango,
                'role_id' => $roleEstudiante ? $roleEstudiante->id : null,
            ]);
        } else {
            // Find course matching title
            $course = Course::where('title', $itemName)->first();
            
            // Grant access by creating Matrícula Inicial grade
            StudentGrade::create([
                'user_id' => $student->id,
                'course_id' => $course?->id,
                'course_title' => $itemName,
                'module_name' => 'Matrícula Inicial',
                'grade' => 45, // default starting progress
                'attempt_date' => now(),
            ]);
        }

        // Send alert
        Notification::create([
            'user_id' => $student->id,
            'type' => 'success',
            'title' => '💳 Pago Aprobado',
            'message' => "Tu comprobante de pago por '{$itemName}' ha sido verificado y aprobado. ¡Ya tienes tus accesos activos!"
        ]);

        // Mark approved
        $payment->update(['status' => 'aprobado']);

        // Sum simulations
        $montoLimpio = (float) filter_var($payment->amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $totalSales = Setting::getVal('total_ventas_simuladas', 12450.00);
        Setting::setVal('total_ventas_simuladas', $totalSales + $montoLimpio);

        return response()->json([
            'status' => 'success',
            'message' => 'Transacción aprobada y acceso liberado al alumno.'
        ]);
    }

    public function rejectPayment(PendingPayment $payment)
    {
        $student = $payment->user;
        
        Notification::create([
            'user_id' => $student->id,
            'type' => 'warning',
            'title' => '⚠️ Pago Rechazado',
            'message' => "Tu comprobante de pago por '{$payment->item_name}' ha sido revisado y rechazado por inconsistencias. Por favor, comunícate con soporte."
        ]);

        $payment->update(['status' => 'rechazado']);

        return response()->json([
            'status' => 'success',
            'message' => 'Transacción rechazada y comprobante descartado.'
        ]);
    }

    // Written answers corrections
    public function listPendingWrittenAnswers()
    {
        $answers = WrittenAnswer::with('user:id,email')
            ->where('status', 'pendiente')
            ->latest()
            ->get();
            
        return response()->json($answers);
    }

    public function gradeWrittenAnswer(Request $request, WrittenAnswer $answer)
    {
        $request->validate([
            'grade' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $grade = $request->grade;

        // Create student grade record
        StudentGrade::create([
            'user_id' => $answer->user_id,
            'course_id' => $answer->course_id,
            'course_title' => $answer->course->title,
            'module_name' => "Evaluación de Desarrollo (Profesor)",
            'grade' => $grade,
            'requires_revision' => false,
            'attempt_date' => now(),
        ]);

        $answer->update([
            'status' => 'corregida',
            'final_grade' => $grade,
        ]);

        Notification::create([
            'user_id' => $answer->user_id,
            'type' => 'info',
            'title' => '📝 Examen Corregido',
            'message' => "Tu respuesta de desarrollo en el curso '{$answer->course->title}' ha sido calificada con {$grade} puntos."
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Respuesta evaluada y nota guardada.'
        ]);
    }

    // CRUD Courses details
    public function saveCourse(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
            'duration' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'required_membership' => ['required', 'in:regular,estandar,pro,vip'],
        ]);

        $course = Course::updateOrCreate(
            ['id' => $request->id],
            $request->only(['title', 'description', 'short_description', 'duration', 'price', 'category_id', 'required_membership'])
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Curso guardado con éxito.',
            'course' => $course
        ]);
    }

    public function deleteCourse(Course $course)
    {
        $course->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Curso eliminado correctamente.'
        ]);
    }
}
