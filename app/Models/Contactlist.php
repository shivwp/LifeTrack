<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactlist extends Model
{
    use HasFactory;
    protected $table='contactlist';
    protected $fillable=['user_id', 'friend_id', 'admin', 'accpet','group_id'];

     
}
