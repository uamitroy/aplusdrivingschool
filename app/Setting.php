<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['title','tagline','emails','cc_emails','bcc_emails','logo','favicon','loginbg','site_key','secret_key','google_analytics'];
    public $timestamps = false;
}
