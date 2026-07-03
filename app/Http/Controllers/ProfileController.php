<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Enrolled / Accessible Courses
        // If regular user, they can access:
        // - Courses whererequired_membership is 'regular'
        // - Courses they bought individually (recorded in StudentGrade with Matrícula Inicial or other grades)
        // - Courses unlocked by their membership (regular, estandar, pro, vip)
        $membershipLevels = ['regular' => 0, 'estandar' => 1, 'pro' => 2, 'vip' => 3];
        $userLevel = $membershipLevels[$user->membership] ?? 0;

        $allCourses = Course::where('status', 'active')->get();
        $enrolledCourses = [];

        // Check grades/attempts
        $studentGrades = StudentGrade::where('user_id', $user->id)->get();

        foreach ($allCourses as $course) {
            $hasAccess = false;
            $courseRequiredLevel = $membershipLevels[$course->required_membership] ?? 0;

            if ($user->isAdmin()) {
                $hasAccess = true;
            } else {
                // If they have any grade/attempt record, they have access
                $hasEnrollment = $studentGrades->where('course_id', $course->id)->isNotEmpty();
                if ($hasEnrollment) {
                    $hasAccess = true;
                } elseif ($userLevel >= $courseRequiredLevel && $courseRequiredLevel > 0) {
                    $hasAccess = true;
                }
            }

            if ($hasAccess) {
                // Calculate progress
                $courseGrades = $studentGrades->where('course_id', $course->id);
                $maxGrade = $courseGrades->max('grade') ?? 0;
                
                $isCompleted = $maxGrade >= 80;
                $progressPercent = $isCompleted ? 100 : ($maxGrade > 0 ? $maxGrade : 45);

                $course->max_grade = $maxGrade;
                $course->is_completed = $isCompleted;
                $course->progress_percent = $progressPercent;
                $enrolledCourses[] = $course;
            }
        }

        // 2. Metrics Widgets
        $totalQuizzes = $studentGrades->where('module_name', '!=', 'Matrícula Inicial')->count();
        $avgGrade = $totalQuizzes > 0 ? round($studentGrades->where('module_name', '!=', 'Matrícula Inicial')->avg('grade')) : 0;
        
        // 3. System Alerts / Notifications
        $notifications = $user->systemNotifications;

        return view('profile.index', compact('user', 'enrolledCourses', 'avgGrade', 'totalQuizzes', 'notifications'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
            'country' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:1024'], // Max 1MB
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'country' => $request->country,
        ];

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $data['avatar_path'] = $request->file('foto')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.index')->with('success', '¡Perfil actualizado correctamente!');
    }

    public function getPerformanceData()
    {
        $user = Auth::user();
        
        // Return grades history for the Chart.js line chart
        $grades = StudentGrade::where('user_id', $user->id)
            ->where('module_name', '!=', 'Matrícula Inicial')
            ->orderBy('created_at', 'asc')
            ->get(['module_name', 'grade', 'created_at']);

        $labels = $grades->map(fn($g) => $g->module_name);
        $data = $grades->map(fn($g) => $g->grade);

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
