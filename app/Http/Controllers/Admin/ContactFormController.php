<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ContactForm;
use Session;


class ContactFormController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
    public function index() {

        $enquires = ContactForm::orderBy('created_at', 'desc')->get();
        return view('admin.contact_forms.index',compact('enquires'));
        
    }

    public function show($id) {
        
        $enquiry = ContactForm::findOrFail($id);
        return view('admin.contact_forms.show', compact('enquiry'));
    }

    public function destroy($id) {

        $result = ContactForm::destroy($id);
        if($result){
           Session::flash('success', 'Contact Enquiry deleted successfully'); 
        } else {
           Session::flash('danger', 'Error Encounterd');
        }
        return redirect()->route('admin.contact.forms.list');
    }

    
}
