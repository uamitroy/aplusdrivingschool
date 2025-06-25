<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileRequest;
use Illuminate\Support\Facades\Hash;
use App\Admin;
use App\Post;
use App\PostCategoryRelationship;
use App\PostMeta;
use Session;
use File;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cat_id = 2;
        $posts = Post::whereHas('categories', function($query) use($cat_id) {
                    $query->where('category_id','=',$cat_id);
            })->with('metas')->where('post_type','=','post')->get();
        return view('admin.dashboard',compact('posts'));
    }

    public function profile(AdminProfileRequest $request)
    {
        if ($request->isMethod('PATCH')) {
            $input = $request->all();
            $profile = Admin::firstOrFail();
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

            if ($profile->image) {
                    File::delete(public_path('uploads\\' . $profile->image));
                }
        }
            $result = $profile->update($input);
            if($result){
               Session::flash('success', 'Admin profile updated successfully'); 
            } else {
               Session::flash('danger', 'Error encounterd');
            }
               return redirect()->route('admin.profile');
            } else {

            $profile = Admin::firstOrFail();
            return view('admin.profile.edit',compact('profile'));
        }
        
    }

    public function change_password(AdminProfileRequest $request)
    {
       if ($request->isMethod('PATCH')) {
            $user = Admin::firstOrFail();
            $check = Hash::check($request->old_password, $user->password);
            if($check){
                $user->password = Hash::make($request->input('password'));
                $result = $user->update();
                if($result){
                   Session::flash('success', 'Admin password updated Successfully'); 
                } else {
                   Session::flash('danger', 'Error Encounterd');
                }
             } else{
                Session::flash('warning', 'Your current password is not mathching'); 
             }
             return redirect()->route('admin.change_password');
        }else {
            $user = Admin::firstOrFail();
            return view('admin.profile.edit',compact('user'));
        }
        
    }
}
