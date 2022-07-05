<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrivacySettings extends Model
{
    use HasFactory;
    protected $table="user_privacy_setting";

    protected $fillable=[
        'id',
        'user_id',
        'profile_photo',
        'mood_update',
        'charts',
        'activity',
        'group_access'
    ];

}
