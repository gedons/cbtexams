<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exam;

class ExamAnswer extends Model
{
    use HasFactory;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'exam_id',
        'start_date',
        'end_date',
        
    ];

   
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
