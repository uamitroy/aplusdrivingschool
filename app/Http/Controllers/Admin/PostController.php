<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Post;
use App\Category;
use App\User;
use App\PostCategoryRelationship;
use App\PostMeta;
use App\PostFile;
use App\PostMetaElement;
use Session;
use File;


class PostController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
    public function index($cat_id= null,$tag_id= null) {

        if($cat_id){
            $posts = Post::whereHas('categories', function($query) use($cat_id) {
                    $query->where('category_id',$cat_id);
            })->with('tags','user:id,fname')->where('post_type','=','post')->get();
            return view('admin.posts.index',compact('posts'));
        }elseif($tag_id){
            $posts = Post::whereHas('tags', function($query) use($tag_id) {
                    $query->where('tag_id',$tag_id);
            })->with('categories','user:id,fname')->where('post_type','=','post')->get();
            return view('admin.posts.index',compact('posts'));
        }else{
            $posts = Post::with('categories','tags','user:id,fname')->where('post_type','=','post')->get();
            return view('admin.posts.index',compact('posts'));
        }
        
    }

    public function create() {

        $categories = fetchCategoryTree();
        return view('admin.posts.create',compact('categories'));
       
    }

    public function store(PostRequest $request) {

		$input['title'] = $request->title;
        $input['post_type'] = 'post';
        $input['template'] = -1;
        $slug = str_replace([' ','/','#'], '-', strtolower($request->title));
            $count_slug = 0;
            $count_slug = Post::where('slug', $slug)->count();
            if($count_slug > 0){
               $count_slug++;
               $slug =  $slug.$count_slug;
            }
        $input['slug'] = $slug;
        $input['is_front_page'] = -1;

        $result = Post::create($input);
        $result2 = PostMetaElement::create(['post_id' => $result->id,'title' => $request->title]);
        $post =  Post::find($result->id);
		if($request->category_id != NULL){
			$cat_id = $request->category_id;
		}else{
			$cat_id = 1;
		}
        $post->categories()->attach($cat_id);
        if($result){
           Session::flash('success', 'Post created successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.posts.list');
    }

    public function show($id) {
        
        $post = Post::findOrFail($id);
        return view('admin.post.show', compact('post'));
    }

    public function edit($id) {

         $post = Post::findOrFail($id);
         $post_categories = $post->categories->pluck('id')->toArray();
         $post_tags = $post->tags->pluck('id')->toArray();
         $postmeta = $post->metas;
         $postfiles = $post->files;
         $post_meta_element = $post->post_meta_element;
         $categories = get_categories_listing();
         $users = DB::table('users')->pluck('fname','id')->toArray();
         $tags = DB::table('tags')->pluck('title','id')->toArray();
         $meta_keys = DB::table('posts')->rightJoin('post_metas', function ($join) {
                $join->on('posts.id', '=', 'post_metas.post_id');
            })->where('posts.post_type', '=', 'post')->distinct()->get(['meta_key']);
         if($post->user_id == 0){
                $user_posts = 0;
            } else{
                $user_posts = $post->user->id;
        }
         return view('admin.posts.edit',compact('post','categories','users','post_categories','user_posts','tags','post_tags','postmeta','meta_keys','postfiles','post_meta_element'));
    }

    public function update(PostRequest $request, $id) {

        $post = Post::findOrFail($id);
        $input['title'] = $request->title;
        $input['post_type'] = 'post';
        $input['is_front_page'] = -1;
        $input['user_id'] = $request->user_id;
        $input['content'] = $request->content;
        if($request->hasFile('image'))
        {
            $destinationPath = 'uploads';
            $file = $request->file('image');
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename=time().rand(100,999).$original_name.'.'.$extension;
            
            $file->move(
                base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$destinationPath, $filename
            );

            $input['image'] = $filename;

            if ($post->image) {
                    File::delete(public_path('uploads\\' . $post->image));
                }
        }
        $result = $post->update($input);
        $result2 = PostCategoryRelationship::where('post_id', $post->id)->delete();
		if($request->category_id != NULL){
			$terms = count($request->category_id);
			for($i=0;$i<$terms;$i++){
				$input2 = [     
					'post_id' => $post->id,  
					'category_id' => $request->category_id[$i]     
				];
			   $result3 = PostCategoryRelationship::create($input2); 
			}
		}else{
			$input2 = [     
					'post_id' => $post->id,  
					'category_id' => 1     
				];
			$result3 = PostCategoryRelationship::create($input2); 
		}
        
        if($request->tag_id){
            $result4 = $post->tags()->sync($request->tag_id);
        }
        if($result && $result2 && $result3){
           Session::flash('success', 'Post updated successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.posts.list');
        
    } 

    public function slug(PostRequest $request) {

       if ($request->ajax()) {
            $id = $request->id;
            $slug = str_replace([' ','/','#'], '-', strtolower($request->value));
            $count_slug = 0;
            $count_slug = Post::where('slug', $slug)->count();
            if($count_slug > 0){
               $count_slug++;
               $slug =  $slug.$count_slug;
            }
            $input['slug'] = $slug;
            $post = Post::findOrFail($id);
            $result = $post->update($input);
            if($result) {
                return response()->json(['status' => true, 'success' => 'Post slug information updated successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Error encounterd']);
            }
        }       
    } 
	
	public function meta_create(PostRequest $request){
	   
        if ($request->ajax()) {
        $input['post_id'] = $request->post_id;   
        $input['meta_key'] = $request->meta_key;
        $input['meta_value'] = $request->meta_value;
        $result = PostMeta::create($input);
        if($result) {
                return response()->json(['status' => true, 'success' => 'Post meta information added successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Post meta information not added successfully']);
                 }
    	}
    }

	
	public function meta_update(PostRequest $request, $id){

        if ($request->ajax()) {
        $input['meta_key'] = $request->meta_key;
        $input['meta_value'] = $request->meta_value;
        $post_meta = PostMeta::findOrFail($id);
        $result = $post_meta->update($input);
        if($result) {
                return response()->json(['status' => true, 'success' => 'Post meta information updated successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Post meta information not added successfully']);
                 }
        }
	
	}
	
	public function meta_delete($id){

        $result = PostMeta::findOrFail($id)->delete();
        if($result) {
                return response()->json(['status' => true, 'success' => 'Post meta information deleted successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Post meta information not deleted successfully']);
                 }
	}

    public function multiple_file_upload(PostRequest $request){

        if($request->hasFile('images')) {

            $post = Post::findOrFail($request->post_id);

            $terms = count($request->file('images'));         

            for($i=0;$i<$terms;$i++){

                $destinationPath = 'uploads';
                $file[$i] = $request->file('images')[$i];
                $original_name[$i] = pathinfo($file[$i]->getClientOriginalName(), PATHINFO_FILENAME);
                $extension[$i] = $file[$i]->getClientOriginalExtension();
                $filename[$i]=time().rand(100,999).$original_name[$i].'.'.$extension[$i];
                
                $file[$i]->move(
                    base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$destinationPath, $filename[$i]
                );
                $input = ['file' => $filename[$i]];
                $result = $post->files()->create($input);
            }

        if($result){
           Session::flash('success', 'Post image(s) added successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }

    } else{

        Session::flash('danger', 'Please select atleast one image');
    }
         return back();

        
    }

    public function file_delete($id){

        $post_image = PostFile::findOrFail($id)->file;
        if ($post_image) {
                
                unlink(public_path('uploads\\' . $post_image));
        }

        $result = PostFile::findOrFail($id)->delete();
        if($result){
           Session::flash('success', 'Post image deleted successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
         return back();
       
    }

    public function destroy($id) {

        $result = Post::destroy($id);
        $result2 = PostCategoryRelationship::where('post_id', $id)->delete();
        $result3 = PostMeta::where('post_id', $id)->delete();
        $result4 = PostMetaElement::where('post_id', $id)->delete();
        if($result){
           Session::flash('success', 'Post deleted successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.posts.list');
    }

    public function multiple_posts_delete(PostRequest $request) {

        $items = $request->only('item');
        $result = Post::destroy($items['item']);
        $result2 = PostCategoryRelationship::whereIn('post_id', $items['item'])->delete();
        $result3 = PostMeta::whereIn('post_id', $items['item'])->delete();
        $result4 = PostMetaElement::whereIn('post_id', $items['item'])->delete();
        if($result){
           Session::flash('success', count($items['item']).' Post(s) deleted successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.posts.list');


    }

    public function deleteall() {

        $items = Post::where('post_type', 'post')->pluck('id');
        $result = Post::destroy($items);
        $result2 = PostCategoryRelationship::truncate();
        $result3 = PostMeta::whereIn('post_id', $items)->delete();

        if($result && $result2 ){
           Session::flash('success', 'All posts deleteed'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        
        return redirect()->route('admin.posts.list');

    }

    public function meta_element_update(PostRequest $request, $id){

        $post = Post::findOrFail($id);
        $input = $request->except('_method','_token','og_url','canonical');
        $result = PostMetaElement::where('post_id','=',$post->id)->update($input);
        if($result){
           Session::flash('success', 'Post meta properties updated successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return back();
    }

    public function changeStatus(PostRequest $request) {

        if ($request->ajax()) {
                $post = Post::findOrFail($request->input('id'));
                $post->status = $request->input('status');
                $result = $post->update();
                        if($result) {
                                return response()->json(['status' => true, 'success' => 'Post Status Updated Successfully']);
                        } else {
                                return response()->json(['status' => false, 'danger' => 'Error Encounterd']);
                        }
                    }
        }


}
