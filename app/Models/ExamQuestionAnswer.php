<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestionAnswer extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'exam_question_id',
        'exam_answer_id',
        'answer'
    ];
}
