<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function classrooms(){
        return $this->belongsToMany(Classroom::class);
    }
    public function grades()
{
    return $this->belongsToMany(Grade::class, 'grade_subject', 'subject_id', 'grade_id');
}
}
