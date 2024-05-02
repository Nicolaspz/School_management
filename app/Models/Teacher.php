<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class);
    }

    public function aulas()
    {
        return $this->hasManyThrough(Aula::class, Classroom::class);
    }

   /* public function ClassDisciplina(){
        return $this->belongsTo(ClassDisciplina::class, 'teachers_id', 'id');
    }*/




}
