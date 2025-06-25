<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\SiteSettingRequest;
use App\Http\Controllers\Controller;
use App\Setting;
use Session;
use File;

class SettingController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }

    
    public function update(SiteSettingRequest $request) {
        if ($request->isMethod('PATCH')) {
        $input = $request->all();
        $setting = Setting::firstOrFail();
        if($request->hasFile('logo'))
        {
            $destinationPath = 'uploads';
            $file = $request->file('logo');
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename=time().rand(100,999).$original_name.'.'.$extension;
            
            $file->move(
                base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$destinationPath, $filename
            );

            $input['logo'] = $filename;

            if ($setting->logo) {
                     File::delete(public_path('uploads\\' . $setting->logo));
                }
        }

        if($request->hasFile('favicon'))
        {
            $destinationPath = 'uploads';
            $file = $request->file('favicon');
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename=time().rand(100,999).$original_name.'.'.$extension;
            
            $file->move(
                base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$destinationPath, $filename
            );

            $input['favicon'] = $filename;

            if ($setting->favicon) {
                    File::delete(public_path('uploads\\' . $setting->favicon));
                }


        }

        if($request->hasFile('loginbg'))
        {
            $destinationPath = 'uploads';
            $file = $request->file('loginbg');
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename=time().rand(100,999).$original_name.'.'.$extension;
            
            $file->move(
                base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$destinationPath, $filename
            );

            $input['loginbg'] = $filename;

            if ($setting->loginbg) {
                    File::delete(public_path('uploads\\' . $setting->loginbg));
                }
        }

        if($setting == null){
            $result = Setting::create($input);
        } else {
            $result = $setting->update($input);
        }
        if($result){
           Session::flash('success', 'Site settings updated successfully'); 
        } else {
           Session::flash('danger', 'Error encounterd');
        }
           return redirect()->route('admin.site.setting');
    } else {

        $setting = Setting::firstOrFail();
        return view('admin.settings.edit',compact('setting'));
    }
        
    }

}
