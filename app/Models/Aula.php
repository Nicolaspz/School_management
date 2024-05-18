<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aula extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function classroom():BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classrooms_id', 'id');
    }
    public function faltas(){
        return $this->belongsTo(Falta::class,'aulas_id', 'id');
    }
    public function teachers(){
        return $this->belongsTo(Teacher::class,'teachers_id','id');
    }

    protected static function boot()
    {
        parent::boot();


        static::creating(function ($aula) {
            // Certifica-se de que o usuário está autenticado e que é um professor
            if (auth()->check() && auth()->user()->teacher) {
                $aula->teachers_id = auth()->user()->teacher->id;
            }
        });
    }
}
