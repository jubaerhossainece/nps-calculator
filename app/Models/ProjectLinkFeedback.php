<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectLinkFeedback extends Model
{
    const RATING_VALUE = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    const DETRACTOR = [0, 1, 2, 3, 4, 5, 6];
    const PASSIVE = [7, 8];
    const PROMOTER = [9, 10];

    protected $fillable = [
        'project_link_id',
        'project_id',
        'name',
        'email',
        'rating',
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

    protected function type($rating)
    {
        if (in_array($rating, ProjectLinkFeedback::DETRACTOR)) {
            return 'detractor';
        } elseif (in_array($rating, ProjectLinkFeedback::PASSIVE)) {
            return 'passive';
        } elseif (in_array($rating, ProjectLinkFeedback::PROMOTER)) {
            return 'promoter';
        } else {
            return '';
        }
    }
}
