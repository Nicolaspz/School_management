<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class consulta extends Model
{

    use HasFactory;
    protected $guarded=[];

    public function psicologo(){

        return $this->belongsTo(psicologo::class, 'psicologo_id', 'id');
    }

    public function student(): BelongsTo
    {
    return $this->belongsTo(Student::class,'students_id', 'id');
    }

    /*public function estudante(){

        return $this->belongsTo(psicologo::class, 'psicologo_id', 'id');
    }*/

}
