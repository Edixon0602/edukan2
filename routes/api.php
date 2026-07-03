<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CoursePlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Models\StudentGrade;
use App\Models\Module;
use Illuminate\Support\Facades\Route;

// Public rates conversion
Route::get('/checkout/bcv-rate', [CheckoutController::class, 'getBcvRate']);

// Protected Student API operations
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Checkout operations
    Route::post('/checkout/validate-coupon', [CheckoutController::class, 'validateCoupon']);
    Route::post('/checkout/submit-payment', [CheckoutController::class, 'submitPayment']);
    
    // Enrollment verify
    Route::get('/user/has-enrollment/{courseId}', function ($courseId) {
        $enrolled = StudentGrade::where('user_id', auth()->id())
            ->where('course_id', $courseId)
            ->exists();
        return response()->json(['enrolled' => $enrolled]);
    });

    // Modules quizzes loader
    Route::get('/modules/{module}/quizzes', function (Module $module) {
        return response()->json($module->quizzes()->orderBy('order_index')->get());
    });

    // Student taken status check
    Route::get('/user/grades/{moduleId}', function ($moduleId) {
        $module = Module::findOrFail($moduleId);
        $gradeRecord = StudentGrade::where('user_id', auth()->id())
            ->where('course_id', $module->course_id)
            ->where('module_name', $module->title)
            ->first();

        return response()->json([
            'completed' => !is_null($gradeRecord),
            'grade' => $gradeRecord ? $gradeRecord->grade : 0
        ]);
    });

    // Submitting assessments and course reviews
    Route::post('/courses/{course}/modules/{module}/evaluate', [CoursePlayerController::class, 'submitEvaluation']);
    Route::get('/courses/{course}/reviews', [CoursePlayerController::class, 'getReviews']);
    Route::post('/courses/{course}/reviews', [CoursePlayerController::class, 'submitReview']);
    
    // Performance Chart data
    Route::get('/user/performance-data', [ProfileController::class, 'getPerformanceData']);
    
    // Notification dismiss
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
});

// Admin-Only APIs
Route::middleware(['auth:sanctum'])->group(function () {
    // Basic inline check or using the EnsureUserIsAdmin logic
    Route::middleware([\App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
        
        // CRM Search students
        Route::get('/admin/students', [AdminController::class, 'crmList']);
        Route::get('/admin/students/{student}/audit', [AdminController::class, 'auditStudent']);
        Route::post('/admin/students/{student}/gift', [AdminController::class, 'giftCourse']);
        Route::post('/admin/students/{student}/discount', [AdminController::class, 'assignDiscount']);
        
        // Payments verification queue
        Route::get('/admin/payments', [AdminController::class, 'paymentsQueue']);
        Route::post('/api/admin/payments/{payment}/approve', [AdminController::class, 'approvePayment']);
        Route::post('/api/admin/payments/{payment}/reject', [AdminController::class, 'rejectPayment']);
        
        // Alert system broadcasts
        Route::post('/admin/notifications/send', [NotificationController::class, 'send']);
    });
});
