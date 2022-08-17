<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exam;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'data',
        'type',
        'exam_id',
        'description'
        
    ];

    
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
