<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCover extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'titulo_trabajo',
        'autor',
        'institucion',
        'facultad',
        'asignatura',
        'docente',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date', // 👈 convierte automáticamente a objeto Carbon
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
