<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Segment;

class Package extends Model
{
    protected $fillable = ['segment_id','name','price','description'];

    public $timestamps = true;

    public function segment(){

    	return $this->belongsTo(Segment::class);
    }

}
