<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public static function getReportAbuseOptionList(){
        return [
           ['name' => 'Nudity','id' => 1],
           ['name' => 'Promotes hate, violence or illegal/offensive activities', 'id' => 2],
           ['name' => 'Spam, malware or "phishing" (fake login)', 'id' => 3],
           ['name' => 'Private and confidential information', 'id' => 4],
           ['name' => 'Copyright infringement', 'id' => 5],
        ];
    }
}
