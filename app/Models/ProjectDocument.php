<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'section_key',
        'content',
        'status',
    ];

    // Relación inversa
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
