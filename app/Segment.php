<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Package;

class Segment extends Model
{
    protected $fillable = ['name'];

    public $timestamps = true;

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

}
