<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactForm extends Model
{
    protected $fillable = ['name','email','phone','subject','message'];
    public $timestamps = true;
}
