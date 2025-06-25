<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Segment;
use App\Package;
use Session;


class PackageController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
     public function index() {

        $packages = Package::with('segment:id,name')->orderBy('created_at', 'desc')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create() {

        $segments = Segment::pluck('name', 'id')->toArray();
        $selected_segment = null;
        return view('admin.packages.create', compact('segments', 'selected_segment'));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'segment_id' => 'required|integer',
            'name'       => 'required|string',
            'price'       => 'nullable|numeric',
            'description' => 'required|string'
        ]);

        $input = $request->all();
        $result = Package::create($input);
        if ($result) {
            Session::flash('success', 'Package created successfully');
        } else {
            Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.packages.list');
    }

    public function edit($id) {

        $package = Package::findOrFail($id);
        $segments = Segment::pluck('name', 'id')->toArray();
        $selected_segment = $package->segment->id;
        return view('admin.packages.edit', compact('package', 'segments', 'selected_segment'));
    }

    public function update(Request $request, $id) {

        $this->validate($request, [
            'segment_id' => 'required|integer',
            'name'       => 'required|string',
            'price'       => 'nullable|numeric',
            'description' => 'required|string'
        ]);

        $package = Package::findOrFail($id);
        $input = $request->all();
        $result = $package->update($input);
        if ($result) {
            Session::flash('success', 'Package updated successfully');
        } else {
            Session::flash('danger', 'Error encounterd');
        }
        return redirect()->route('admin.packages.list');
    } 

    public function destroy($id) {

        $result = Package::findOrFail($id)->delete();
        if($result){
           Session::flash('success', 'Package deleted Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.packages.list');
    }  

}
