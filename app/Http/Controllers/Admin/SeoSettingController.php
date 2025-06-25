<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use File;


class SeoSettingController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }

    
    public function manage(Request $request) {


        if($request->isMethod('POST')){

            $result1 = false;
            $result2 = false;
            if($request->hasFile('sitemap'))
        {

        if($request->sitemap->getClientOriginalName() == 'sitemap.xml'){
            $destinationPath = '/';
            $file = $request->file('sitemap');
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename=$original_name.'.'.$extension;

            if(file_exists(public_path('\\' .$filename))) 
            {
                File::delete(public_path('\\' .$filename));
            }
            
            $result1 = $file->move(
                 public_path().$destinationPath, $filename
            );

        }
 
        }
        if($request->hasFile('robots'))
        {

        if($request->robots->getClientOriginalName() == 'robots.txt'){
            $destinationPath = '/';
            $file = $request->file('robots');
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename=$original_name.'.'.$extension;

            if(file_exists(public_path('\\' .$filename))) 
            {
                File::delete(public_path('\\' .$filename));
            }
            
           $result2 =  $file->move(
                public_path().$destinationPath, $filename
            );
        } 
 
        }
            if ($request->hasFile('sitemap') || $request->hasFile('robots')) {
                if($result1 || $result2){
                   Session::flash('success', 'Files uploaded successfully'); 
                } else {
                   Session::flash('danger', 'sitemap file name should be sitemap.xml and robots file name should be robots.txt');
                }
            }else{
                Session::flash('warning', 'Nothing has selected');
            }
            return redirect()->route('admin.seo.setting');

        }

        else {

            return view('admin.seo.index');
        }
        
    }


}
