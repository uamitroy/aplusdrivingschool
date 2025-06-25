<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Widget;
use Session;


class WidgetController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }

    
    public function manage(Request $request) {

        if ($request->isMethod('PATCH')) {
            $this->validate($request, [
                'title'   => 'required|string|max:100|unique:widgets,title,'.$request->widget_id
            ]);
            $input = $request->all();
            $setting = Widget::findOrFail($input['widget_id']);
            $result = $setting->update($input);
            if($result){
               Session::flash('success', 'Widget updated successfully'); 
            } else {
               Session::flash('danger', 'Error encounterd');
            }
               return redirect()->route('admin.widgets');
        } 
        elseif($request->isMethod('POST')){
            $this->validate($request, [
                'title'  => 'required|string|max:100|distinct'
            ]);
            $input = $request->all();
            $result = Widget::create($input);
            if($result){
               Session::flash('success', 'Widget created successfully'); 
            } else {
               Session::flash('danger', 'Error encounterd');
            }
            return redirect()->route('admin.widgets');
        }

        else {

            $widgets = Widget::all();
            return view('admin.widgets.widget',compact('widgets'));
        }
        
    }

    public function destroy($id) {

        $result = Widget::findOrFail($id)->delete();
        if($result){
           Session::flash('success', 'Widget Deleted Successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.widgets');
    } 

     public function truncate() {

        $result = Widget::truncate();
        if($result){
           Session::flash('success', 'Widget table is Empty Now'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        
        return redirect()->route('admin.widgets');


    }

}
