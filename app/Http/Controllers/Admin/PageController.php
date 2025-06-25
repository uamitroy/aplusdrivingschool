<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Post;
use App\User;
use App\PostMeta;
use App\PostMetaElement;
use App\Menu;
use Session;
use File;


class PageController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
    public function index() {

        $pages = Post::where('post_type', 'page')->orderBy('created_at', 'desc')->get();
        return view('admin.pages.index',compact('pages'));
        
    }

    public function create() {

        return view('admin.pages.create');
       
    }

    public function store(PageRequest $request) {

        $input['title'] = $request->title;
        $input['post_type'] = 'page';
        $slug = str_replace([' ','/','#'], '-', strtolower($request->title));
            $count_slug = 0;
            $count_slug = Post::where('slug', $slug)->count();
            if($count_slug > 0){
               $count_slug++;
               $slug =  $slug.$count_slug;
            }
        $input['slug'] = $slug;
        $input['is_front_page'] = 0;
        $input['user_id'] = 0;
        $result = Post::create($input);
        $result2 = PostMetaElement::create(['post_id' => $result->id,'title' => $request->title]);
        $result3 = Menu::create(['post_id' => $result->id,'flag' => 1,'parent_page' => 0]);
        if($result && $result2 && $result3){
           Session::flash('success', 'Page created successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.pages.list');
    }

    public function show($id) {
        
        $page = Post::findOrFail($id);
        return view('admin.page.show', compact('page'));
    }

    public function edit($id) {

         $page = Post::findOrFail($id);
         $users = DB::table('users')->pluck('fname','id')->toArray();
         $postmeta = $page->metas;
         $page_meta_element = $page->post_meta_element;
         $menu = $page->menu;
         $all_pages = Post::where(['post_type' => 'page'])->where('id','!=',$id)->pluck('title','id')->toArray();
         $meta_keys = DB::table('posts')->rightJoin('post_metas', function ($join) {
                $join->on('posts.id', '=', 'post_metas.post_id');
            })->where('posts.post_type', '=', 'page')->distinct()->get(['meta_key']);
         return view('admin.pages.edit',compact('page','users','postmeta','meta_keys','page_meta_element','menu','all_pages'));
    }

    public function update(PageRequest $request, $id) {

        $page = Post::findOrFail($id);
        $input['title'] = $request->title;
        $input['post_type'] = 'page';
        $input['template'] = $request->template;
        $input['user_id'] = $request->user_id;
        $input['content'] = $request->content;
        $input['menu_order'] = $request->menu_order;
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

            if ($page->image) {
                    File::delete(public_path('uploads\\' . $page->image));
                }
        }
        $result = $page->update($input);
        $menu['flag'] = $request->flag;
        $menu['parent_page'] = $request->parent_page;
        $result2 = Menu::where('post_id','=',$page->id)->update($menu);
        if($result){
           Session::flash('success', 'Page updated successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.pages.list');
        
    } 

    public function slug(PageRequest $request) {

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
            $page = Post::findOrFail($id);
            $result = $page->update($input);
            if($result) {
                return response()->json(['status' => true, 'success' => 'Page slug information updated successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Error encounterd']);
            }
        }       
    } 

    public function update_page_status(PageRequest $request) {

       if ($request->ajax()) {
            $id = $request->id;
            $page = Post::findOrFail($id);
            $result = $page->update(['is_front_page' => 1, 'slug' => '']);
            $result2 = Post::whereNotIn('id', array($id))->where('post_type','=','page')->update(['is_front_page' => 0]);
            if($result && $result2) {
                return response()->json(['status' => true, 'success' => 'Page status information updated successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Error encounterd']);
            }
        }       
    } 
	
	public function meta_create(PageRequest $request){
       
        if ($request->ajax()) {
        $input['post_id'] = $request->post_id;   
        $input['meta_key'] = $request->meta_key;
        $input['meta_value'] = $request->meta_value;
        $result = PostMeta::create($input);
        if($result) {
                return response()->json(['status' => true, 'success' => 'Page meta information added successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Page meta information not added successfully']);
                 }
        }
    }

    
    public function meta_update(PageRequest $request, $id){

        if ($request->ajax()) {
        $input['meta_key'] = $request->meta_key;
        $input['meta_value'] = $request->meta_value;
        $post_meta = PostMeta::findOrFail($id);
        $result = $post_meta->update($input);
        if($result) {
                return response()->json(['status' => true, 'success' => 'Page meta information updated successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Page meta information not added successfully']);
                 }
        }
    
    }
    
    public function meta_delete($id){

        $result = PostMeta::findOrFail($id)->delete();
        if($result) {
                return response()->json(['status' => true, 'success' => 'Page meta information deleted successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Page meta information not deleted successfully']);
                 }
    }

    public function destroy($id) {

        $result = Post::destroy($id);
        $result2 = PostMeta::where('post_id', $id)->delete();
        $result3 = PostMetaElement::where('post_id', $id)->delete();
        if($result){
           Session::flash('success', 'Page deleted successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.pages.list');
    }

    public function multiple_pages_delete(PageRequest $request) {

        
        $items = $request->only('item');
        $result = Post::destroy($items['item']);
        $result2 = PostMeta::whereIn('post_id', $items['item'])->delete();
        $result3 = PostMetaElement::whereIn('post_id', $items['item'])->delete();
        if($result){
           Session::flash('success', count($items['item']).' Page(s) deleted successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.pages.list');


    }

    public function deleteall() {

        $items = Post::where('post_type', 'page')->pluck('id');
        $result = Post::destroy($items);
        $result2 = PostMeta::whereIn('post_id', $items)->delete();
        if($result){
           Session::flash('success', 'All pages deleteed'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        
        return redirect()->route('admin.pages.list');
        

    }

    public function meta_element_update(PageRequest $request, $id){

        $page = Post::findOrFail($id);
        $input = $request->except('_method','_token','og_url','canonical');
        $result = PostMetaElement::where('post_id','=',$page->id)->update($input);
        if($result){
           Session::flash('success', 'Page meta properties updated successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return back();
    }

     public function changeStatus(PageRequest $request) {

        if ($request->ajax()) {
                $page = Post::findOrFail($request->input('id'));
                $page->status = $request->input('status');
                $result = $page->update();
                        if($result) {
                                return response()->json(['status' => true, 'success' => 'Page Status Updated Successfully']);
                        } else {
                                return response()->json(['status' => false, 'danger' => 'Error Encounterd']);
                        }
                    }
        }

    


}
