<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class homerooms extends Model
{
    protected $table= 'home_rooms';
    use HasFactory;
    protected $guarded = [];

    public function teacher(){
        return $this->belongsTo(Teacher::class, 'teachers_id','id');
    }
    public function classroom(){
        return $this->belongsTo(classroom::class, 'classrooms_id','id');
    }
    public function periode(){
        return $this->belongsTo(Periode::class, 'periodes_id','id');
    }
}
