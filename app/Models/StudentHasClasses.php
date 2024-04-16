<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHasClasses extends Model
{
    protected $table = 'student_has_classes';
    use HasFactory;

    protected $guarded = [];

   /* protected $fillable = [
        'students_id',
        'classrooms_id',
        'periodes_id',
        'is_open'
    ];*/

    public function students(){
        return $this->belongsTo(Student::class);
    }

    public function classrooms(){
        return $this->belongsTo(Classroom::class, 'classrooms_id', 'id');
    }

    public function periodes(){
        return $this->belongsTo(periode::class);
    }



}
