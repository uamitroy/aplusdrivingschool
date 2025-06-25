<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;

class Tag extends Model
{
    protected $guarded = ['id'];

    public $timestamps = true;

    public function posts()
    {
        return $this->belongsToMany(Post::class,'post_tag_relationships');

    }

}
