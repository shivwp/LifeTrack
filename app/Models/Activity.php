<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
     protected $table = "activitys";

      protected $fillable = [

     'username',  'activity', 'starttime', 'endtime', 'selectcolor', 'selectprivacy','slug', 'parent_id','catgory', 'parent_catgory', 'sub_category',

       ];

   
}




   