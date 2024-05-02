<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Falta extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function students(){
        return $this->belongsTo(Student::class, 'students_id', 'id');
    }

    public function aulas(){
        return $this->belongsTo(Aula::class, 'aulas_id', 'id');
    }

}
