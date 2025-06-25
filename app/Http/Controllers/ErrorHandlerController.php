<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ErrorHandlerController extends Controller

{

    public function __construct()
    {
        
    }
    public function errorCode404()

    {

    	return view('errors.404');

    }

}

?>