<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subject(){
        return $this->belongsToMany(Subject::class)->withPivot('description');
    }
    public function students():BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_has_classes', 'classrooms_id',
                'students_id');
    }
    public function grade():BelongsTo
    {
        return $this->belongsTo(grade::class, 'grades_id', 'id');
    }
    public function curso():BelongsTo
    {
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class);
    }
}
