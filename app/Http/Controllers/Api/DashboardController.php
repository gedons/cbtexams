<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Http\Resources\ExamResourceDashboard;
use App\Http\Resources\ExamAnswerResource;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        //total number of exams
            $total = Exam::query()->where('user_id', $user->id)->count();

        //latest exams
            $latest = Exam::query()->where('user_id', $user->id)->latest('created_at')->first();

        //total number of answers
            $totalAnswers = ExamAnswer::query()->join('exams', 'exam_answers.exam_id', '=', 'exams.id')
            ->where('exams.user_id', $user->id)->count();

        //latest  answers
        $latestAnswers = ExamAnswer::query()->join('exams', 'exam_answers.exam_id', '=', 'exams.id')
        ->where('exams.user_id', $user->id)->orderBy('end_date', 'DESC')->limit(5)
        ->getModels('exam_answers.*');

        return[
            'totalExams' => $total,
            'latestExam' => $latest ? new ExamResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => ExamAnswerResource::collection($latestAnswers)
        ];
    }
}
