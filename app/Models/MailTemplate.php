<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplate  extends Model
{
    use HasFactory;
     protected $table = "mails";

    protected $fillable = [
       'form_name','subject', 'massage_category', 'form_email', 'reply_email', 'email_to', 'massage_content'
    ];
}
