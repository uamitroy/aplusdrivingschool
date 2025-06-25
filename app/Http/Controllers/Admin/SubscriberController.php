<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subscriber;
use Session;


class SubscriberController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
    public function index() {

        $subscribers = Subscriber::orderBy('created_at', 'desc')->get();
        return view('admin.subscribers.index',compact('subscribers'));
        
    }


    
}
