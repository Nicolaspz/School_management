<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function class(): BelongsTo
    {
    return $this->belongsTo(Classroom::class, 'class_id','id');
    }
    public function student(): BelongsTo
    {
    return $this->belongsTo(Student::class);
    }
    public function periode(): BelongsTo
    {
    return $this->belongsTo(Periode::class, 'periode_id','id');
    }
    public function subject(): BelongsTo
    {
    return $this->belongsTo(Subject::class ,'subject_id','id');
    }
    public function category_nilai(): BelongsTo
    {
    return $this->belongsTo(CategoryNilai::class, 'category_notas_id', 'id');
    }

}
