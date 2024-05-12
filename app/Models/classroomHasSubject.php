<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mockery\Matcher\Subset;

class classroomHasSubject extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'classroom_subject';

    public function subjects(){
        return $this->belongsTo(Subject::class, 'subjects_id', 'id');
    }
    public function subject(){
        return $this->belongsTo(Subject::class);
    }
    public function teachers(){
        return $this->belongsTo(Teacher::class, 'teachers_id', 'id');
    }


    public function class(){
        return $this->belongsTo(Classroom::class,'classroom_id', 'id');
    }



}
