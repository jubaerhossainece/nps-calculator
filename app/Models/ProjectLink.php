<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLink extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'code',
        'response'
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
