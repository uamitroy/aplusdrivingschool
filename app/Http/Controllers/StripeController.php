<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Package;
use App\Slot;
use App\Setting;
use DateTime;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Validator;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    protected $emails = null;
    protected $cc_emails = null;
    protected $bcc_emails = null;
    
    public function __construct()
    {
        $this->middleware('auth');
        $setting = Setting::firstOrFail();
        $this->emails =  $setting->emails;
        $this->cc_emails = $setting->cc_emails;
        $this->bcc_emails = $setting->bcc_emails;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.home');
    }


    public function stripe(Request $request){

        $hash = $request->hash;
        $message = Crypt::decrypt($request->hash);
        $hash = explode(",",$message);
        $package_id = $hash[0];
        $slot_id = $hash[1];
        $card_option = $hash[2];
        $addtional_info = $hash[3];



        $package = Package::where('id',$package_id)->firstOrFail();
        //$slot = Slot::where('id',$slot_id)->firstOrFail();
        
        if($addtional_info == 1){
        $price = $package->price + 50;
        }else{
        $price = $package->price;
        }
        

        $description = $package->segment->name.' | '.$package->name;

        \Stripe\Stripe::setApiKey ( env('STRIPE_SECRET_KEY') );

        try {

        if(session()->has('xstrID')){
            session()->forget('xstrID');
        }

        $session = \Stripe\Checkout\Session::create([
          "success_url" => route('stripe.segment.success'),
          "cancel_url" => route('stripe.cancel'),
          "metadata" => ['hash' => $request->hash],
          "payment_method_types" => ["card"],
          "line_items" => [[
            "name" => $description,
            "description" => $description,
            "amount" =>  $price * 100,//$total,
            "currency" => "USD",
            "quantity" => 1 //$quantity
          ]]
        ]);

        session()->put('xstrID',$session["id"]);

        return redirect()->route('stripe.checkout');

        } catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];
        
          //print('Status is:' . $e->getHttpStatus() . "\n");
          //print('Type is:' . $err['type'] . "\n");
          //print('Code is:' . $err['code'] . "\n");
          // param is '' in this case
          //print('Param is:' . $err['param'] . "\n");
          //print('Message is:' . $err['message'] . "\n");

          Session::flash ( 'danger', 'Since it\'s a decline' . 'Error : ' . $err['message'] . "\n" );
          return redirect()->back();
        } catch (\Stripe\Error\RateLimit $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Too many requests made to the API too quickly' );
            return redirect()->back();
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Invalid parameters were supplied to Stripe\'s API' );
            return redirect()->back();
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Authentication with Stripe\'s API failed' );
            return redirect()->back();
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Network communication with Stripe failed' );
            return redirect()->back();
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Display a very generic error to the user, and maybe send' );
            return redirect()->back();
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (\Exception $e) {
            Log::debug($e);
            // Session::flash ( 'danger', 'Something else happened, completely unrelated to Stripe' );
            Session::flash ( 'success', 'Payment Successfull' );
            return redirect()->back();
          // Something else happened, completely unrelated to Stripe
        }    

    }

    public function stripe_payment($payment_data){

         $message = Crypt::decrypt($payment_data['metadata']->hash);
         $hash = explode(",",$message);
         $package_id = $hash[0];
         $slot_id = $hash[1];
         $card_option = $hash[2];
         $addtional_info = $hash[3];
        
         $package = Package::where('id',$package_id)->firstOrFail();
         if($addtional_info == 1){
            $price = $package->price + 50;
         }else{
            $price = $package->price;
         }
    
        if($slot_id == 0){

         $slot = NULL;
         $name = $package->segment->name.' | '.$package->name;
         $currency_code = 'USD';
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id);
         $input['gateway'] = 'Stripe';
         $input['name'] = $name;
         $input['currency_code'] = $currency_code;
         $input['hash'] = $newhash;
         $input['amount'] = $price;
         $input['user_id'] = Auth::guard('web')->user()->id;
         $input['segment_id'] = $package->segment->id;
         $input['package_id'] = $package->id;
         $input['slot_id'] = 0;
         $result = Transaction::create($input);

        }else{
        $slot = Slot::where('id',$slot_id)->firstOrFail();
        
        if($slot->month == 1)
            $month = 'January';
         
        if($slot->month == 2)
            $month = 'February';
         
        if($slot->month == 3)
            $month = 'March';
         
        if($slot->month == 4)
            $month = 'April';
         
        if($slot->month == 5)
            $month = 'May';
        
        if($slot->month == 6)
            $month = 'June';
        
        if($slot->month == 7)
            $month = 'July';
        
        if($slot->month == 8)
            $month = 'August';
         
        if($slot->month == 9)
            $month = 'September';
         
        if($slot->month == 10)
            $month = 'October';
         
        if($slot->month == 11)
            $month = 'November';
           
        if($slot->month == 12)
            $month = 'December';
        

         $name = $package->segment->name.' | '.$package->name. ' | '.$month. ' | '.$slot->year;
         $currency_code = 'USD';
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id);
         $input['gateway'] = 'Stripe';
         $input['name'] = $name;
         $input['currency_code'] = 'USD';
         $input['hash'] = $newhash;
         $input['amount'] = $price;
         $input['user_id'] = Auth::guard('web')->user()->id;
         $input['segment_id'] = $package->segment->id;
         $input['package_id'] = $package->id;
         $input['slot_id'] = $slot->id;
         $result = Transaction::create($input);

         $input5['enrolled'] = $slot->enrolled + 1;
         $result5 = $slot->update($input5);
        } 
         
         $curr_time = time();
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id.','.$result->id.','.$curr_time);

         $transaction = Transaction::findOrFail($result->id);
         $input2['hash'] = $newhash;
         $result2 = $transaction->update($input2);

            /*\Stripe\Charge::create ( array (
                    "amount" => $price * 100,
                    "currency" => "usd",
                    "source" => $request->input ( 'stripeToken' ), // obtained with Stripe.js
                    "description" => $name 
            ) );*/
            $transaction = Transaction::findOrFail($result->id);
            $input3['tx_id'] = $payment_data['id'];
            $input3['status'] = 'Completed';
            $result3 = $transaction->update($input3);

            
            $user = Auth::guard('web')->user();
            
            $pdf = PDF::loadView('elements/users/invoice',compact('package','slot','transaction','user','addtional_info'));
                
            $filename = rand(111,999).time().'-invoice';
            
            Storage::put(('public/invoice/'.$filename.'.pdf'), $pdf->output());
            // // $pdf->save();
            $filepath = ('storage/invoice/'.$filename.'.pdf');
            
            // Storage::put(('public/invoice/'.$filename.'.pdf'), $pdf->output());
            // $pdf->save();
            // $filepath = ('storage/invoice/'.$filename.'.pdf');
            
            $result5 = $transaction->update(['invoice' => $filename.'.pdf']);

            if($addtional_info == 1){

                 $package2 = Package::where([['name','Bronze'],['segment_id',2]])->first();
                 $input3['gateway'] = 'Stripe';
                 $input3['name'] = 'Segment 2 | Bronze';
                 $input3['currency_code'] = 'USD';
                 $input3['hash'] = $newhash;
                 $input3['amount'] = 50;
                 $input3['user_id'] = Auth::guard('web')->user()->id;
                 $input3['segment_id'] = $package2->segment->id;
                 $input3['package_id'] = $package2->id;
                 $input3['slot_id'] = 0;
                 $input3['invoice'] = $filename.'.pdf';
                 $input3['status'] = 'Completed';
                 $input3['tx_id'] = $payment_data['id'];
                 $result5 = Transaction::create($input3);

             }

            $input4['package'] = $package;
            $input4['segment'] = $package->segment->name;
            $input4['user'] = Auth::guard('web')->user();
            $input4['transaction'] = $transaction;
            $input4['slot'] = $slot;

            $result4 = Mail::send('email.invoice-email', ['request' => $input4], function ($message) use ($filepath){
                $message->to(Auth::guard('web')->user()->email, Auth::guard('web')->user()->name)->subject('A+ Driving School Course Invoice')->attach($filepath, ['as' => 'invoice.pdf', 'mime' => 'application/pdf']);
            });

            $to  = explode(",",$this->emails);
            $cc  = explode(",",$this->cc_emails);
            $bcc = explode(",",$this->bcc_emails);

            $email_arr = array('to' => $to, 'cc' => $cc, 'bcc' => $bcc);

            $result6 = Mail::send('email.confirm-booking-email', ['request' => $input4], function ($message) use ($email_arr) {
                if (count($email_arr['cc']) > 0 && $email_arr['cc']['0'] != '') {
                    $message->cc($email_arr['cc']);
            }
                if (count($email_arr['bcc']) > 0 && $email_arr['bcc']['0'] != '') {
                    $message->bcc($email_arr['bcc']);
            }
            $message->to($email_arr['to'] , 'A+ Driving School System Admin')->subject('New Payment Received');

            });

            Session::flash ( 'success', 'Payment done successfully !' );
            
        
    }


    public function stripe_custom(Request $request){

        $hash = $request->hash;
        $message = Crypt::decrypt($request->hash);
        $hash = explode(",",$message);
        $package_id = $hash[0];
        $slot_id = $hash[1];
        $card_option = $hash[2];
        $addtional_info = $hash[3];

        $package = Package::where('id',$package_id)->firstOrFail();
        $price = $request->amount;

        $description = $package->segment->name.' | '.$package->name;

        \Stripe\Stripe::setApiKey ( env('STRIPE_SECRET_KEY') );

        try {

        if(session()->has('xstrID')){
            session()->forget('xstrID');
        }

        $session = \Stripe\Checkout\Session::create([
          "success_url" => route('stripe.success'),
          "cancel_url" => route('stripe.cancel'),
          "metadata" => ['hash' => $request->hash],
          "payment_method_types" => ["card"],
          "line_items" => [[
            "name" => "Private Lessons Payment",
            "description" => $description,
            "amount" =>  $price * 100,//$total,
            "currency" => "USD",
            "quantity" => 1 //$quantity
          ]]
        ]);

        session()->put('xstrID',$session["id"]);

        return redirect()->route('stripe.checkout');

        } catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];
        
          //print('Status is:' . $e->getHttpStatus() . "\n");
          //print('Type is:' . $err['type'] . "\n");
          //print('Code is:' . $err['code'] . "\n");
          // param is '' in this case
          //print('Param is:' . $err['param'] . "\n");
          //print('Message is:' . $err['message'] . "\n");

          Session::flash ( 'danger', 'Since it\'s a decline' . 'Error : ' . $err['message'] . "\n" );
          return redirect()->back();
        } catch (\Stripe\Error\RateLimit $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Too many requests made to the API too quickly' );
            return redirect()->back();
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Invalid parameters were supplied to Stripe\'s API' );
            return redirect()->back();
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Authentication with Stripe\'s API failed' );
            return redirect()->back();
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Network communication with Stripe failed' );
            return redirect()->back();
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Display a very generic error to the user, and maybe send' );
            return redirect()->back();
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (\Exception $e) {
            Log::debug($e);
            // Session::flash ( 'danger', 'Something else happened, completely unrelated to Stripe' );
            Session::flash ( 'success', 'Payment Successfull' );
            return redirect()->back();
          // Something else happened, completely unrelated to Stripe
        }

    }

    public function stripe_checkout(){

        if(session()->has('xstrID')){

            return view('page_templates.stripe-checkout'); 

        }else{

            Session::flash ( 'danger', 'Something went wrong' );
            return redirect()->back();
        }

    }


    public function stripe_payment_custom($payment_data){


         $message = Crypt::decrypt($payment_data['metadata']->hash);
         $hash = explode(",",$message);
         $package_id = $hash[0];
         $slot_id = $hash[1];
         $card_option = $hash[2];
         $addtional_info = $hash[3];

         $package = Package::where('id',$package_id)->firstOrFail();
         
         $price = $payment_data['display_items'][0]->amount/100;
              

         $name = $package->segment->name.' | '.$package->name;
         $currency_code = 'USD';
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id);
         $input['gateway'] = 'Stripe';
         $input['name'] = $name;
         $input['currency_code'] = $currency_code;
         $input['hash'] = $newhash;
         $input['amount'] = $price;
         $input['user_id'] = Auth::guard('web')->user()->id;
         $input['segment_id'] = $package->segment->id;
         $input['package_id'] = $package->id;
         $input['slot_id'] = 0;
         $result = Transaction::create($input);

        
         $slot = NULL;
         $curr_time = time();
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id.','.$result->id.','.$curr_time);

         $transaction = Transaction::findOrFail($result->id);
         $input2['hash'] = $newhash;
         $result2 = $transaction->update($input2);
         

            /*\Stripe\Charge::create ( array (
                    "amount" => $price * 100,
                    "currency" => "usd",
                    "source" => $request->input ( 'stripeToken' ), // obtained with Stripe.js
                    "description" => $name 
            ) );
*/          
            $transaction = Transaction::findOrFail($result->id);
            $input3['tx_id'] = $payment_data['id'];
            $input3['status'] = 'Completed';
            $result3 = $transaction->update($input3);

            $user = Auth::guard('web')->user();
            $pdf = PDF::loadView('elements/users/invoice',compact('package','slot','transaction','user','addtional_info'));
            $filename = time().'-invoice';
            // $pdf->save(public_path('invoice/'.$filename.'.pdf'));
            // $filepath = public_path('invoice/'.$filename.'.pdf');

            Storage::put(('public/invoice/'.$filename.'.pdf'), $pdf->output());
            $filepath = ('storage/invoice/'.$filename.'.pdf');
            
            $result5 = $transaction->update(['invoice' => $filename.'.pdf']);

            $input4['package'] = $package;
            $input4['segment'] = $package->segment->name;
            $input4['user'] = Auth::guard('web')->user();
            $input4['transaction'] = $transaction;

            $result4 = Mail::send('email.invoice-email', ['request' => $input4], function ($message) use ($filepath){
                $message->to(Auth::guard('web')->user()->email, Auth::guard('web')->user()->name)->subject('A+ Driving School Course Invoice')->attach($filepath, ['as' => 'invoice.pdf', 'mime' => 'application/pdf']);
            });

            $to  = explode(",",$this->emails);
            $cc  = explode(",",$this->cc_emails);
            $bcc = explode(",",$this->bcc_emails);

            $email_arr = array('to' => $to, 'cc' => $cc, 'bcc' => $bcc);

            $result6 = Mail::send('email.confirm-booking-email', ['request' => $input4], function ($message) use ($email_arr) {
                if (count($email_arr['cc']) > 0 && $email_arr['cc']['0'] != '') {
                    $message->cc($email_arr['cc']);
            }
                if (count($email_arr['bcc']) > 0 && $email_arr['bcc']['0'] != '') {
                    $message->bcc($email_arr['bcc']);
            }
            $message->to($email_arr['to'] , 'A+ Driving School System Admin')->subject('New Payment Received');

            });

            Session::flash ( 'success', 'Payment done successfully !' );
            
        
    }

    public function stripe_success(){

        if(session()->has('xstrID')){

        try{

            \Stripe\Stripe::setApiKey ( env('STRIPE_SECRET_KEY') );
            $payment_data=\Stripe\Checkout\Session::retrieve(session()->get('xstrID'));
            session()->forget('xstrID');
            $this->stripe_payment_custom($payment_data);
            return redirect()->route('transactions');
            

        }catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];
        
          //print('Status is:' . $e->getHttpStatus() . "\n");
          //print('Type is:' . $err['type'] . "\n");
          //print('Code is:' . $err['code'] . "\n");
          // param is '' in this case
          //print('Param is:' . $err['param'] . "\n");
          //print('Message is:' . $err['message'] . "\n");

          Session::flash ( 'danger', 'Since it\'s a decline' . 'Error : ' . $err['message'] . "\n" );
          session()->forget('xstrID');
          return redirect()->route('home');
        } catch (\Stripe\Error\RateLimit $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Too many requests made to the API too quickly' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Invalid parameters were supplied to Stripe\'s API' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Authentication with Stripe\'s API failed' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Network communication with Stripe failed' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            Log::debug($e);
            Session::flash ( 'danger', 'Display a very generic error to the user, and maybe send' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (\Exception $e) {
            Log::debug($e);
            // Session::flash ( 'danger', 'Something else happened, completely unrelated to Stripe' );
            Session::flash ( 'success', 'Payment Successfull' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Something else happened, completely unrelated to Stripe
        }

        }else{

            Session::flash ( 'danger', 'Something went wrong' );
            return redirect()->route('home');
        }

         
         

    }


    public function stripe_segment_success(){

        if(session()->has('xstrID')){
            
        try{

            \Stripe\Stripe::setApiKey ( env('STRIPE_SECRET_KEY') );
            $payment_data=\Stripe\Checkout\Session::retrieve(session()->get('xstrID'));
            session()->forget('xstrID');
            $this->stripe_payment($payment_data);
            return redirect()->route('transactions');
            

        }catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];
        
          //print('Status is:' . $e->getHttpStatus() . "\n");
          //print('Type is:' . $err['type'] . "\n");
          //print('Code is:' . $err['code'] . "\n");
          // param is '' in this case
          //print('Param is:' . $err['param'] . "\n");
          //print('Message is:' . $err['message'] . "\n");
            Log::debug($e);
          Session::flash ( 'danger', 'Since it\'s a decline' . 'Error : ' . $err['message'] . "\n" );
          session()->forget('xstrID');
          return redirect()->route('home');
        } catch (\Stripe\Error\RateLimit $e) {
            
            Session::flash ( 'danger', 'Too many requests made to the API too quickly' );
            Log::debug($e);
            session()->forget('xstrID');
            return redirect()->route('home');
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            Session::flash ( 'danger', 'Invalid parameters were supplied to Stripe\'s API' );
            session()->forget('xstrID');
            Log::debug($e);
            return redirect()->route('home');
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            Session::flash ( 'danger', 'Authentication with Stripe\'s API failed' );
            Log::debug($e);
            session()->forget('xstrID');
            return redirect()->route('home');
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            Session::flash ( 'danger', 'Network communication with Stripe failed' );
            Log::debug($e);
            session()->forget('xstrID');
            return redirect()->route('home');
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            Session::flash ( 'danger', 'Display a very generic error to the user, and maybe send' );
            session()->forget('xstrID');
            Log::debug($e);
            return redirect()->route('home');
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (\Exception $e) {
            // Session::flash ( 'danger', 'Something else happened, completely unrelated to Stripe' );
            Session::flash ( 'success', 'Payment Successfull' );
            session()->forget('xstrID');
            Log::debug($e);
            return redirect()->route('home');
          // Something else happened, completely unrelated to Stripe
        }

        }else{

            Session::flash ( 'danger', 'Something went wrong' );
            return redirect()->route('home');
        }
    }

    public function stripe_canceled(){
        
        if(session()->has('xstrID')){

             Session::flash ( 'info', 'You have cancel the payment' );
             session()->forget('xstrID');

        }else{

            Session::flash ( 'danger', 'Something went wrong' );
            
        }

        return redirect()->route('home');
    }

    
}