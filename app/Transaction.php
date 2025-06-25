<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Segment;
use App\Package;
use App\Slot;
use App\User;

class Transaction extends Model
{
    protected $fillable = ['gateway','name','hash','tx_id','amount','currency_code','status','user_id','package_id','slot_id','segment_id','invoice'];
    public $timestamps = true;

    public function segment(){

    	return $this->belongsTo(Segment::class);
    }

    public function package(){

    	return $this->belongsTo(Package::class);
    }

    public function slot(){

    	return $this->belongsTo(Slot::class)->withDefault([
            'year'=> 'N.A',
            'month'=> 'N.A',
            'start_time'=> 'N.A',
            'end_time'=> 'N.A',
            'dates'=> 'N.A',
            'seat_allotted  '=> 'N.A',
            'enrolled' => 'N.A'
        ]);
    }

    public function user(){

    	return $this->belongsTo(User::class);
    }
}
