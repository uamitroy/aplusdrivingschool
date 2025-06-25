<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\MediaFileRequest;
use Session;
use File;


class MediaController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }

    public function index() {

        return view('admin.resources.index');
    }

    public function create() {

        return view('admin.resources.create');
       
    }
    
    public function store(MediaFileRequest $request) {

       if($request->hasFile('images')) {

            $terms = count($request->file('images'));         

            for($i=0;$i<$terms;$i++){

                $destinationPath = 'uploads/metas';
                $file[$i] = $request->file('images')[$i];
                $original_name[$i] = pathinfo($file[$i]->getClientOriginalName(), PATHINFO_FILENAME);
                $extension[$i] = $file[$i]->getClientOriginalExtension();
                $filename[$i]=time().rand(100,999).$original_name[$i].'.'.$extension[$i];
                
                $result = $file[$i]->move(
                    base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$destinationPath, $filename[$i]
                );
            }

        if($result){
           Session::flash('success', 'Meta image(s) uploaded successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }

    } else{

        Session::flash('danger', 'Please select atleast one image');
    }
        return redirect()->route('admin.resources.list');
        
        
    }

    public function multiple_file_delete(MediaFileRequest $request) {

        $items = $request->only('item');
        $terms = count($items['item']);
        $result = false;
        for($i=0;$i<$terms;$i++){
            if (file_exists(public_path('uploads/metas\\' . $items['item'][$i]))) {
                     File::delete(public_path('uploads/metas\\' . $items['item'][$i]));
                }
          $result = true;      
        }
        if($result){
           Session::flash('success', $terms.' Meta image(s) deleted successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        
        return redirect()->route('admin.resources.list');


    }


}
