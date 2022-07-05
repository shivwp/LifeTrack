<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

     protected $fillable = ['user_id', 'friend_from', 'friend_to', 'status'];
     
     protected $table='friends';

}
