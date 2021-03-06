<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table='tags';
    protected $fillable=['user_id', 'tag_id', 'date', 'bst', 'bet', 'ast', 'aet'];
}
