<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Post;
use Session;
use File;


class UserController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }

    public function index() {

		$users = User::orderBy('created_at', 'desc')->get();
    	return view('admin.users.index',compact('users'));
        
    }

    public function create() {

    	return view('admin.users.create');
       
    }

    public function store(UserRequest $request) {

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['dob'] = date('Y-m-d', strtotime(str_replace('-', '/', $request->dob)));
        $result = User::create($input);

        if($result){
           Session::flash('success', 'User Created Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.users.list');
    }

    public function show($id) {
        
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id) {

         $user = User::findOrFail($id);
         $user->dob = date("m/d/Y", strtotime($user->dob));
         return view('admin.users.edit',compact('user'));
    }

    public function update(UserRequest $request, $id) {

        $input = $request->all();
        $input['dob'] = date('Y-m-d', strtotime(str_replace('-', '/', $request->dob)));
        $user = User::findOrFail($id);
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

            if ($user->image) {
                    File::delete(public_path('uploads\\' . $user->image));
                }
        }
        $result = $user->update($input);
        if($result){
           Session::flash('success', 'User Updated Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.users.list');
        
    }

    public function destroy($id) {

        $result = User::findOrFail($id)->delete();
        Post::where('user_id',$id)->update(['user_id' => 0]);
        if($result){
           Session::flash('success', 'User Deleted Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.users.list');
    }  

    public function showPasswordFrom($id) {

    	 $user = User::findOrFail($id);
         return view('admin.users.edit',compact('user'));

    }

    public function changePassword(UserRequest $request, $id) {

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->input('password'));
        $result = $user->update();
        if($result){
           Session::flash('success', 'User Password Created Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.users.list');

    }

    public function changeStatus(UserRequest $request) {

        if ($request->ajax()) {
                $user = User::findOrFail($request->input('id'));
                $user->status = $request->input('status');
                $result = $user->update();
                        if($result) {
                                return response()->json(['status' => true, 'success' => 'User Status Updated Successfully']);
                        } else {
                                return response()->json(['status' => false, 'danger' => 'Error Encounterd']);
                        }
                    }
        }

     public function multiple_user_delete(UserRequest $request) {

        $items = $request->only('item');
        $result = User::destroy($items['item']);
        Post::whereIn('user_id',$items['item'])->update(['user_id' => 0]);
        if($result){
           Session::flash('success', count($items['item']).' User Deleted Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.users.list');


    }

    public function truncate() {

        $result = User::truncate();
        DB::table('posts')->update(['user_id' => 0]);

        if($result){
           Session::flash('success', 'User Data Table is Empty Now'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        
        return redirect()->route('admin.users.list');


    }
}
