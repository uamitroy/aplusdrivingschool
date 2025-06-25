<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\TagRequest;
use App\Http\Controllers\Controller;
use App\Tag;
use App\PostTagRelationship;
use Session;


class TagController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
    public function index() {

        $tags = Tag::with('posts')->get();
        return view('admin.tags.index',compact('tags'));
        
    }

    public function create() {

        
        return view('admin.tags.create');
       
    }

    public function store(TagRequest $request) {

        $input = $request->only('title');
        $slug = str_replace([' ','/','#'], '-', strtolower($request->title));
            $count_slug = Tag::where('slug', $slug)->count();
            if($count_slug > 1){
               $slug =  $slug.$count_slug;
            }
        $input['slug'] = $slug;
        $result = Tag::create($input);
        if($result){
           Session::flash('success', 'Tag created successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.tags.list');
    }

    public function show($id) {
        
        $tag = Tag::findOrFail($id);
        return view('admin.tags.show', compact('tag'));
    }

    public function edit($id) {

         $tag = Tag::findOrFail($id);
         return view('admin.tags.edit',compact('tag'));
    }

    public function update(TagRequest $request, $id) {

        $input = $request->all();
        $tag = Tag::findOrFail($id);
        $result = $tag->update($input);
        if($result){
           Session::flash('success', 'Tag updated successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.tags.list');
        
    } 

    public function slug(TagRequest $request) {

       if ($request->ajax()) {
            $id = $request->id;
            $slug = str_replace([' ','/','#'], '-', strtolower($request->value));
            $count_slug = Tag::where('slug', $slug)->count();
            if($count_slug > 1){
               $slug =  $slug.$count_slug;
            }
            $input['slug'] = $slug;
            $tag = Tag::findOrFail($id);
            $result = $tag->update($input);
            if($result) {
                return response()->json(['status' => true, 'success' => 'Tag slug information updated successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Error encounterd']);
            }
        }       
    } 

    public function destroy($id) {

        $result = Tag::findOrFail($id)->delete();
        if($result){
           Session::flash('success', 'Tag deleted Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.tags.list');
    }  

    public function multiple_tags_delete(TagRequest $request) {

        $items = $request->only('item');    
        $result = Tag::destroy($items['item']);
        $result2 = PostTagRelationship::whereIn('tag_id', $items['item'])->delete();
            if($result){
               Session::flash('success', count($items['item']).' Tag(s) deleted successfully'); 
            } else {
               Session::flash('danger', 'Error encounterd');
            }

          return redirect()->route('admin.tags.list');
        
        
    }

    public function truncate() {

        $result = Tag::truncate();
        $result2 = PostTagRelationship::truncate();
        if($result && $result2){
           Session::flash('success', 'Tag data table is empty Now'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        
        return redirect()->route('admin.tags.list');


    }


}
