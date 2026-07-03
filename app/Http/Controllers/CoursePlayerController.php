<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Review;
use App\Models\StudentGrade;
use App\Models\WrittenAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursePlayerController extends Controller
{
    public function show(Course $course)
    {
        $user = Auth::user();
        
        // Access Check: Admin or matching membership level
        $hasAccess = false;
        if ($user->isAdmin()) {
            $hasAccess = true;
        } else {
            // Check if student has a grade/enrollment record for this course
            $hasEnrollment = StudentGrade::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->exists();

            if ($hasEnrollment) {
                $hasAccess = true;
            } else {
                // Check if membership is enough
                $membershipLevels = ['regular' => 0, 'estandar' => 1, 'pro' => 2, 'vip' => 3];
                $userLevel = $membershipLevels[$user->membership] ?? 0;
                $courseRequiredLevel = $membershipLevels[$course->required_membership] ?? 0;

                if ($userLevel >= $courseRequiredLevel && $courseRequiredLevel > 0) {
                    $hasAccess = true;
                }
            }
        }

        if (!$hasAccess) {
            return redirect()->route('courses.index')->with('error', 'Debes adquirir este curso o mejorar tu plan de membresía para acceder.');
        }

        // Load modules, lessons and reviews
        $course->load(['modules.lessons', 'modules.quizzes']);
        $averageRating = $course->average_rating;
        $reviewsCount = $course->reviews()->count();

        return view('courses.player', compact('course', 'averageRating', 'reviewsCount'));
    }

    public function submitEvaluation(Request $request, Course $course, Module $module)
    {
        $user = Auth::user();
        
        // Double Check: Has already completed it?
        $existingGrade = StudentGrade::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('module_name', $module->title)
            ->first();

        if ($existingGrade) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ya has realizado la evaluación de este módulo con una nota de ' . $existingGrade->grade . '%.'
            ], 422);
        }

        $quizzes = $module->quizzes;
        $totalQuestions = $quizzes->count();
        if ($totalQuestions === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Este módulo no cuenta con evaluaciones en este momento.'
            ], 422);
        }

        $correctAnswersCount = 0;
        $hasWrittenAnswer = false;
        $writtenAnswersData = [];

        foreach ($quizzes as $index => $quiz) {
            $answerInput = $request->input('question_' . $quiz->id);

            if ($quiz->type === 'desarrollo') {
                $hasWrittenAnswer = true;
                if (!empty($answerInput)) {
                    $writtenAnswersData[] = [
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'module_id' => $module->id,
                        'question' => $quiz->question,
                        'student_answer' => trim($answerInput),
                        'status' => 'pendiente',
                    ];
                }
            } else {
                if ($answerInput === $quiz->correct_answer) {
                    $correctAnswersCount++;
                }
            }
        }

        $grade = $totalQuestions > 0 ? round(($correctAnswersCount / $totalQuestions) * 100) : 0;

        // 1. Save grade
        StudentGrade::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'course_title' => $course->title,
            'module_name' => $module->title,
            'grade' => $grade,
            'requires_revision' => $hasWrittenAnswer,
            'attempt_date' => now(),
        ]);

        // 2. Save written answers for teacher correction
        if ($hasWrittenAnswer && count($writtenAnswersData) > 0) {
            foreach ($writtenAnswersData as $answer) {
                WrittenAnswer::create($answer);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Evaluación enviada con éxito.',
            'grade' => $grade,
            'has_written_answers' => $hasWrittenAnswer
        ]);
    }

    public function submitReview(Request $request, Course $course)
    {
        $request->validate([
            'stars' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5'],
        ]);

        $user = Auth::user();

        // Create or Update review
        $review = Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'stars' => $request->stars,
                'comment' => $request->comment,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => '¡Gracias por compartir tu opinión!',
            'review' => [
                'stars' => $review->stars,
                'comment' => $review->comment,
                'user_name' => $user->name,
                'date' => $review->updated_at->diffForHumans()
            ]
        ]);
    }

    public function getReviews(Course $course)
    {
        $reviews = $course->reviews()->with('user:id,name')->get()->map(function($r) {
            return [
                'id' => $r->id,
                'stars' => $r->stars,
                'comment' => $r->comment,
                'user_name' => $r->user->name,
                'date' => $r->created_at->format('d/m/Y')
            ];
        });

        return response()->json($reviews);
    }
}
