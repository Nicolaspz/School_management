<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassDisciplina extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'grade_subject';

    public function subjects(){
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
    public function teachers(){
        return $this->belongsTo(Teacher::class, 'teachers_id', 'id');
    }
    public function class(){
        return $this->belongsTo(grade::class,'grade_id', 'id');
    }
}
