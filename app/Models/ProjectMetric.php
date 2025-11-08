<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMetric extends Model
{
    use HasFactory;

    protected $table = 'project_metrics';

    protected $fillable = [
        'project_id',
        'lineas_analizadas',
        'funcionalidad',
        'eficiencia',
        'compatibilidad',
        'usabilidad',
        'fiabilidad',
        'seguridad',
        'mantenibilidad',
        'portabilidad',
        'codigo',
        'analisis',
        'resumen',
    ];
}
