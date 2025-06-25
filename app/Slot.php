<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Segment;

class Slot extends Model
{
    protected $fillable = ['segment_id','year','month','start_time','end_time','dates','seat_allotted','enrolled','type'];

    public $timestamps = true;

    public function segment(){

    	return $this->belongsTo(Segment::class);
    }

}
