<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'section_key',
        'score',
        'comments',
        'status',
    ];

    /**
     * Relación: una Evaluación pertenece a un Proyecto.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
