<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectLink extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'code',
        'response'
    ];

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = Str::random(10);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(ProjectLinkFeedback::class);
    }

}
