<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Tag;
use App\User;
use App\PostMeta;
use App\PostFile;
use App\PostMetaElement;
use App\Menu;

class Post extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public $timestamps = true;

    public function categories()
    {
        return $this->belongsToMany(Category::class,'post_category_relationships');

    }

     public function tags()
    {
        return $this->belongsToMany(Tag::class,'post_tag_relationships');

    }

    public function user(){

    	return $this->belongsTo(User::class)->withDefault(['name' => 'Admin']);
    }

    public function metas()
    {
        return $this->hasMany(PostMeta::class);
    }

    public function files()
    {
        return $this->hasMany(PostFile::class);
    }
	
	public function post_meta_element()
    {
        return $this->hasOne(PostMetaElement::class);
    }

    public function menu()
    {
        return $this->hasOne(Menu::class);
    }
}
