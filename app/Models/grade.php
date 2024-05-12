<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  grade extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function subject(){
        return $this->belongsToMany(Subject::class,'grade_subject', 'subject_id', 'grade_id');
    }

    public function curso(){
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }



}
