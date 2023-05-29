<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAbuseForProjectLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_link_id',
        'project_id',
        'report_abuse_option_id',
        'comment',
        'data'
    ];

    public function projectLink(): BelongsTo
    {
        return $this->belongsTo(ProjectLink::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
