<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoursePlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Models\Course;
use App\Models\Category;
use App\Models\Membership;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

// 🏠 Vista de Inicio (Home)
Route::get('/', function () {
    $proofSocial = Setting::getVal('landing_metrics', [
        'alumnos' => '1,250+',
        'exito' => '98%',
        'paises' => '15+'
    ]);
    
    return view('home', [
        'alumnos' => $proofSocial['alumnos'],
        'exito' => $proofSocial['exito'],
        'paises' => $proofSocial['paises'],
    ]);
})->name('home');

// 🔒 Autenticación Nativa de Laravel
Route::post('/login', [AuthController::class, 'handleLogin'])->name('login.submit');
Route::post('/register', [AuthController::class, 'handleRegister'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.submit');

// 🌐 Autenticación Social (Google OAuth)
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// 📚 Catálogo de Cursos Público
Route::get('/courses', function () {
    $courses = Course::where('status', 'active')->get();
    $categories = Category::all();
    return view('courses.index', compact('courses', 'categories'));
})->name('courses.index');

// 💎 Membresías Públicas
Route::get('/memberships', function () {
    $memberships = Membership::all();
    return view('memberships.index', compact('memberships'));
})->name('memberships.index');

// 🔒 Rutas Privadas del Estudiante (Protegidas por Auth)
Route::middleware(['auth'])->group(function () {
    
    // Perfil e Historial
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Generador Local de Certificados (PDF)
    Route::get('/profile/certificate/{course}', function (Course $course) {
        $user = auth()->user();
        
        // Render simple certificate view
        $html = "
        <div style='border: 10px solid #0d2145; padding: 50px; text-align: center; font-family: sans-serif; background: #020b1a; color: white;'>
            <h1 style='color: #29abff; font-size: 42px;'>Edukan2</h1>
            <p style='font-size: 18px; color: #8a8a93;'>Certificado Oficial de Aprobación</p>
            <br/><br/>
            <p>Otorgado con distinción a:</p>
            <h2 style='font-size: 32px; color: #ffd700;'>{$user->name}</h2>
            <p>Por haber completado exitosamente la asignatura de alto valor:</p>
            <h3 style='font-size: 24px; color: white;'>{$course->title}</h3>
            <br/><br/>
            <p style='font-size: 12px; color: #8a8a93;'>Fecha de expedición: " . now()->format('d/m/Y') . "</p>
            <p style='font-size: 10px; color: #29abff;'>Verificación ID: edk2-" . md5($user->id . $course->id) . "</p>
        </div>";

        // If dompdf is installed, we can generate PDF:
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            return $pdf->download("certificado-{$course->id}.pdf");
        }

        // Fallback to simple HTML stream
        return response($html)->header('Content-Type', 'text/html');
    })->name('profile.certificate');

    // Reproductor de Clases (LMS)
    Route::get('/courses/{course}', [CoursePlayerController::class, 'show'])->name('courses.player');
});

// 🛡️ Rutas de Administración (Protegidas por Auth y Rol Admin)
Route::middleware(['auth'])->group(function () {
    Route::middleware([\App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/bcv', [AdminController::class, 'updateBcv'])->name('admin.update-bcv');
        Route::post('/admin/hero-stats', [AdminController::class, 'updateHeroStats'])->name('admin.update-hero-stats');
    });
});
