<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;

    protected $table='usercards';
   protected $fillable=[ 'user_id', 'user_customer_id', 'card_token'];

  
}
