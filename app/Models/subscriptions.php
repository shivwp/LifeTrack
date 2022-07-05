<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscriptions extends Model
{
    use HasFactory;

    protected $table = "subscriptions";

    protected $fillable = [
         'user_id', 'user_name', 'addfriends', 'creategroup', 'subscriptions_type','name',
    ];

    
}





