<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'wt_visibility',
        'name_field_visibility',
        'email_field_visibility',
        'comment_field_visibility',
        'welcome_text',
        'question',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function link()
    {
        return $this->hasOne(ProjectLink::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(ProjectLinkFeedback::class);
    }

   public function reports()
   {
       return $this->hasMany(ReportAbuseForProjectLink::class);
   }
}
