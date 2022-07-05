<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fillings extends Model
{
    use HasFactory;

    protected $table='fillings';
    protected $fillable=['user_id', 'review_massge', 'review_date', 'status'];
}

