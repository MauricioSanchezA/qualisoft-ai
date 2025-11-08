<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'codigo',
        'respuesta',
        'modelo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
