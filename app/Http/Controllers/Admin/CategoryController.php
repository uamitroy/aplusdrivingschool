<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\PostCategoryRelationship;
use Session;
use File;


class CategoryController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
    public function index() {

        $categories    = get_categories_listing();
		$category_tree = fetchCategoryTreeList();
        return view('admin.categories.index',compact('categories','category_tree'));
        
    }

    public function create() {

        $data['category'] = DB::table('categories')->pluck('title','id')->toArray();
        return view('admin.categories.create',compact('data'));
       
    }

    public function store(CategoryRequest $request) {

        $input = $request->only('title','parent');
        $input['slug'] = str_replace([' ','/','#'], '-', strtolower($request->title));
        $result = Category::create($input);
        if($result){
           Session::flash('success', 'Category created successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.categories.list');
    }

    public function show($id) {
        
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    public function edit($id) {

         $category = Category::findOrFail($id);
         $data['category'] = DB::table('categories')->pluck('title','id')->toArray();
         return view('admin.categories.edit',compact('category','data'));
    }

    public function update(CategoryRequest $request, $id) {

        $input = $request->all();
        $slug = str_replace([' ','/','#'], '-', strtolower($request->title));
            $count_slug = Category::where('slug', $slug)->count();
            if($count_slug > 1){
               $slug =  $slug.$count_slug;
            }
        $input['slug'] = $slug;
        $category = Category::findOrFail($id);
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

            if ($category->image) {
                    File::delete(public_path('uploads\\' . $category->image));
                }
        }
        $result = $category->update($input);
        if($result){
           Session::flash('success', 'Category updated successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.categories.list');
        
    } 

    public function slug(CategoryRequest $request) {

       if ($request->ajax()) {
            $id = $request->id;
            $slug = str_replace([' ','/','#'], '-', strtolower($request->value));
            $count_slug = Category::where('slug', $slug)->count();
            if($count_slug > 1){
               $slug =  $slug.$count_slug;
            }
            $input['slug'] = $slug;
            $category = Category::findOrFail($id);
            $result = $category->update($input);
            if($result) {
                return response()->json(['status' => true, 'success' => 'category slug information updated successfully']);
                } else {
                return response()->json(['status' => false, 'danger' => 'Error encounterd']);
            }
        }       
    } 

    public function multiple_categories_delete(CategoryRequest $request) {

        $items = $request->only('item');    
        $result = Category::destroy($items['item']);
        $result2 = PostCategoryRelationship::whereIn('category_id', $items['item'])->update(['category_id' => 1]);
        $result3 = Category::whereIn('parent', $items['item'])->update(['parent' => 1]);
            if($result){
               Session::flash('success', count($items['item']).' Category deleted successfully'); 
            } else {
               Session::flash('danger', 'Error encounterd');
            }

          return redirect()->route('admin.categories.list');
        
        
    }


}
