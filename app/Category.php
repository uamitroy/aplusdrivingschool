<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['parent','title','slug','description','image'];

    public $timestamps = true;
}
