<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

     protected $table='group';
    protected $fillable=[ 'user_id', 'groupname', 'descrption', 'create_by', 'contact_list'];


    public function members()
    {
        return $this->hasMany('App\Models\Contactlist', 'group_id');
    }

}
