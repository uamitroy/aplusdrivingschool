<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Segment;
use App\Transaction;
use App\Slot;
use Session;


class TransactionController extends Controller
{
  
    public function __construct()
    {
       $this->middleware('auth:admin');
    }
 
     public function index(Request $request) {

     	if ($request->isMethod('POST')) {
     		$input = $request->only('segment','year','month','slot','status','type');
     		if($request->has('status')){
     			$status = $request->status == 1 ? 'Completed' : 'Not Completed';
     		}else{
     			$status = NULL;
     		}
     		if($input['segment'] != 3){
	     		$transactions = Transaction::whereHas('segment',function($query) use($input){
	     			if($input['segment']){
	     				$query->where('id',$input['segment']);
	     			}
	     		})->whereHas('slot',function($query) use($input) {
	     			if($input['type']){
	 					$query->where('type',$input['type']);
	     			}
	 				if($input['year']){
	 					$query->where('year',$input['year']);
	     			}
	     			if($input['month']){
	     				$query->where('month',$input['month']);
	     			}
	     			if($input['slot']){
	     				$query->whereIn('id',$input['slot']);
	     			}	     			
	     			
	     		})->when($status, function($q) use($status){
	     			return $q->where('status', '=', $status);
	     		})->orderBy('created_at', 'desc')->get();

     		}else{

     		   $transactions = Transaction::where('slot_id',0)->orderBy('created_at', 'desc')->get();

     		}

     	}else{
     		$transactions = Transaction::with('segment','package','slot','user')->orderBy('created_at', 'desc')->get();
     	}
     	$slots = Slot::orderBy('created_at', 'desc')->get();
        $segments = Segment::pluck('name', 'id')->toArray();
        $years = Slot::orderBy('created_at', 'desc')->pluck('year','year')->unique()->toArray();     
        return view('admin.transactions.index', compact('transactions','segments','slots','years'));
    }

    public function changeStatus(Request $request) {

       if ($request->ajax()) {
            $id = $request->id;
            if($request->status == 1){

            	return response()->json(['status' => false, 'danger' => 'Only complete tx can be changed to Not Complete ']);

            }else{

            	$status = $request->status == 1 ? 'Completed' : 'Not Completed';
	            $tx = Transaction::findOrFail($id);
	            if($tx->slot->enrolled != 'N.A'){
	            	$tx->slot->update(['enrolled' => $tx->slot->enrolled+1]);
	            }
	            $result = $tx->update(['status' => $status]);
	            if($result) {
	                return response()->json(['status' => true, 'success' => 'Transaction status information updated successfully']);
	                } else {
	                return response()->json(['status' => false, 'danger' => 'Error encounterd']);
	            }

            }
            
        }       
    } 

     

}
