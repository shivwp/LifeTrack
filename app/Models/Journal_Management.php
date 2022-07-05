<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal_Management extends Model
{
    use HasFactory;

      protected $fillable = [
        'name', 'description', 'date','user_id','status'
    ];
    
     Protected $table='journal_managements';
}
