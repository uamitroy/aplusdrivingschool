<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Segment;
use App\Package;
use App\Slot;
use Session;


class SegmentController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
    public function index() {

        $segments = Segment::orderBy('created_at', 'desc')->get();
        return view('admin.segments.index',compact('segments'));
        
    }

    public function create() {

        
        return view('admin.segments.create');
       
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required|string|unique:segments,name'
        ]);

        $input = $request->only('name');
        $result = Segment::create($input);
        if($result){
           Session::flash('success', 'Segment created successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.segments.list');
    }


    public function edit($id) {

         $segment = Segment::findOrFail($id);
         return view('admin.segments.edit',compact('segment'));
    }

    public function update(Request $request, $id) {

        $this->validate($request, [
            'name' => 'required|string|unique:segments,name,'.$id
        ]);

        $input = $request->only('name');
        $segment = Segment::findOrFail($id);
        $result = $segment->update($input);
        if($result){
           Session::flash('success', 'Segment updated successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.segments.list');
        
    } 

    public function destroy($id) {

        $result = Segment::findOrFail($id)->delete();
        $result2 = Package::where('segment_id', $id)->delete();
        $result3 = Slot::where('segment_id', $id)->delete();
        if($result){
           Session::flash('success', 'Segment deleted Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.segments.list');
    }  

}
