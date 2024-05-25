<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class psicologo extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function department()
    {
        return $this->belongsTo(Department::class, 'departments_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
