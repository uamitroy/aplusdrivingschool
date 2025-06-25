<?php 

use App\Category;
use App\PostCategoryRelationship;
use App\Post;
use App\PostMeta;
use App\Menu;
use App\Slot;

function generateBreadcrumb(){

for($i = 1; $i <= count(Request::segments()); $i++):?>
    <li>
        <?= Request::segment($i); ?>
    </li>
<?php endfor;

}

function get_slug($id){
   $post = Post::select('slug')->where('id', $id)->where('status','1')->first(); 
   return $post ? url($post->slug) : 'javascript:void(0)';
}

function get_post_meta($post_id,$meta_key){
   $post_meta = PostMeta::select('meta_value')->where([['post_id', $post_id],['meta_key', $meta_key]])->first(); 
   return $post_meta ? $post_meta->meta_value : '';
}

function get_post_title($id){
   $post = Post::select('title')->where('id', $id)->first(); 
   return $post ? $post->title : '';
}

function isThereAnySlot($segment_id,$year,$month){
   $slot = Slot::where([['segment_id',$segment_id],['year',$year],['month',$month]])->count();
   return $slot ? true : false; 
}

function getSlots($segment_id,$year,$month){
  $slots = Slot::where([['segment_id',$segment_id],['year',$year],['month',$month]])->get();
  $curr_day = strtotime("today");
  $rem_slots = [];
  $i=0;
  foreach($slots as $slot){
    $dates = explode(",",$slot->dates);
    $f_date = $dates[0];
    $slot_st_day = strtotime($f_date.'/'.$year);
    if ($slot_st_day > $curr_day){
      $rem_slots[$i]['id'] = $slot->id;
      $rem_slots[$i]['segment_id'] = $slot->segment_id;
      $rem_slots[$i]['type'] = $slot->type;
      $rem_slots[$i]['year'] = $slot->year;
      $rem_slots[$i]['month'] = $slot->month;
      $rem_slots[$i]['start_time'] = $slot->start_time;
      $rem_slots[$i]['end_time'] = $slot->end_time;
      $rem_slots[$i]['dates'] = $slot->dates;
      $rem_slots[$i]['seat_allotted'] = $slot->seat_allotted;
      $rem_slots[$i]['enrolled'] = $slot->enrolled;
    }
    $i++;
  }
  return $rem_slots;

}

function getSlotsWithCurrentMonth($segment_id,$year,$month){
  $slots = Slot::where([['segment_id',$segment_id],['year',$year],['month',$month]])->get();
  $curr_day = strtotime("today");
  $rem_slots = [];
  $i=0;
  foreach($slots as $slot){
    $dates = explode(",",$slot->dates);
    $f_date = $dates[0];
    $slot_st_day = strtotime($f_date.'/'.$year);
    //if ($slot_st_day > $curr_day){
      $rem_slots[$i]['id'] = $slot->id;
      $rem_slots[$i]['segment_id'] = $slot->segment_id;
      $rem_slots[$i]['type'] = $slot->type;
      $rem_slots[$i]['year'] = $slot->year;
      $rem_slots[$i]['month'] = $slot->month;
      $rem_slots[$i]['start_time'] = $slot->start_time;
      $rem_slots[$i]['end_time'] = $slot->end_time;
      $rem_slots[$i]['dates'] = $slot->dates;
      $rem_slots[$i]['seat_allotted'] = $slot->seat_allotted;
      $rem_slots[$i]['enrolled'] = $slot->enrolled;
    //}
    $i++;
  }
  return $rem_slots;

}

function fetchCategoryTreeList($parent = 0, $category_tree_array = '') {

    if (!is_array($category_tree_array))
    $category_tree_array = array();

  $categories = Category::where('parent', '=', $parent)->select('id', 'title', 'parent')->get();
  $category_items = Category::where('parent', $parent)->count();
 
  if ($category_items > 0) {
     $category_tree_array[] = "<ul>";
   foreach($categories as $category){
	  $category_tree_array[] = "<li>". $category->title."</li>";
      $category_tree_array = fetchCategoryTreeList($category->id, $category_tree_array);
    }
	$category_tree_array[] = "</ul>";
  }
  return $category_tree_array;
}

function get_categories_listing($parent = 0, $title='', $spacing = '', $cat = array()) {
        
        $categories = Category::where('parent', '=', $parent)->select('id', 'title')->orderBy('id', 'asc')->get();
        foreach ($categories as $category) {
            $cat_temp = array();
            $cat_temp['id'] = $category->id;
            $cat_temp['title'] = $spacing . $category->title;
            $cat_temp['post_count'] = PostCategoryRelationship::where('category_id', $category->id)->count();
            if($parent == 0) {
                $cat_temp['cat_tree'] = $title . $category->title;
            } else {
                $cat_temp['cat_tree'] = $title . ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' . $category->title;
            }
            
            $cat[] = $cat_temp;
            $cat = get_categories_listing($cat_temp['id'], $cat_temp['cat_tree'], $spacing, $cat);
        }

        return $cat;
    
    }

function fetchCategoryTree($parent = 0, $spacing = '', $category_tree_array = '') {

  if (!is_array($category_tree_array))
  $category_tree_array = array();
  $categories = Category::where('parent', '=', $parent)->select('id', 'title', 'parent')->get();
  $category_items = Category::where('parent', $parent)->count();
  if ($category_items > 0) {
    foreach($categories as $category){
      $category_tree_array[] = array("id" => $category->id, "title" => $spacing . $category->title);
      $category_tree_array = fetchCategoryTree($category->id, $spacing . '&nbsp;&nbsp;&nbsp;&nbsp;', $category_tree_array);
    }
  }
  return $category_tree_array;
}

function header_menu($parent = 0, $page_tree_array = '', $count = 0) {

    if (!is_array($page_tree_array))
    $page_tree_array = array();

   $menus= DB::table('menus')->join('posts', function ($join) {
            $join->on('menus.post_id', '=', 'posts.id')->where('posts.post_type', '=', 'page')->where('posts.status', '=', '1');
            })->select('posts.title', 'posts.slug', 'menus.parent_page','menus.post_id')
           ->where([['menus.parent_page', '=', $parent],['menus.flag', '=', 0]])->orderBy('menu_order', 'asc')->get();
    
   $sub_items = DB::table('menus')->join('posts', function ($join) {
            $join->on('menus.post_id', '=', 'posts.id')->where('posts.post_type', '=', 'page');
            })->select('posts.title', 'posts.slug', 'menus.parent_page','menus.post_id')
           ->where([['menus.parent_page', '=', $parent],['menus.flag', '=', 0]])->whereNotIn('menus.post_id', array(1))->count();
 
  if ($sub_items > 0) {
  	 if($parent == 0){
	 	$page_tree_array[] = "<ul class='nav navbar-nav navbar-right'>";
	 }else{
     	$page_tree_array[] = "<ul class='dropdown-menu'>";
	 }
   foreach($menus as $menu){
            
       $sub_items1 = DB::table('menus as a')->join('menus as b', function ($join) use($menu) {
            $join->on('a.post_id', '=', 'b.parent_page')->where('b.parent_page', '=', $menu->post_id);
            })->count();
       if($sub_items1 == 0) {
           $page_tree_array[] = "<li><a href='".url($menu->slug)."'>". $menu->title."</a>"; 
       }else{
	   	  $class = ($menu->parent_page == 0)? '':'dropdown';
		  if($menu->slug == 'javascript:void(0)'){
		  $page_tree_array[] = "<li class='".$class."'><a class='dropdown-toggle'  data-toggle='dropdown' href='".$menu->slug."'>". $menu->title."  <span class='caret'></span></a>";
		  }else{
          $page_tree_array[] = "<li class='".$class."'><a class='dropdown-toggle'  data-toggle='dropdown' href='".url($menu->slug)."'>". $menu->title."  <span class='caret'></span></a>";
		  }
       }          
      
      $page_tree_array = header_menu($menu->post_id, $page_tree_array,$count);
    }
	$page_tree_array[] = "</li></ul>";
  }
  return  $page_tree_array;
}
?>