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
use PDF;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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

    public function booking_details($slug){

         $message = Crypt::decrypt($slug);
         $hash = explode(",",$message);
         $package_id = $hash[0];
         $slot_id = $hash[1];
         $card_option = $hash[2];
         $addtional_info = $hash[3];

         $package = Package::where('id',$package_id)->firstOrFail();

         if($slot_id == 0){

            return view('page_templates.booking-confirmation',compact('package','card_option','addtional_info','slug')); 

         }else{

             $slot = Slot::where('id',$slot_id)->firstOrFail();

             if($addtional_info == 0){
                /*$transactions = Transaction::where([['user_id',Auth::guard('web')->user()->id],['segment_id','1']])->get();
                if(count($transactions) > 0){
                    return view('page_templates.booking-confirmation',compact('package','slot','card_option','addtional_info','slug')); 
                }else{
                    Session::flash('danger', 'Sorry !!! you have to completed segemnt one first'); 
                    return redirect()->route('home');
                }*/
                return view('page_templates.booking-confirmation',compact('package','slot','card_option','addtional_info','slug'));

             }else{
                
                $from = new DateTime(Auth::guard('web')->user()->dob);
                $to   = new DateTime('today');
                if($from->diff($to)->y+(0.1 * $from->diff($to)->m) > 14.8){
                 return view('page_templates.booking-confirmation',compact('package','slot','card_option','addtional_info','slug')); 
                }else{
                  Session::flash('danger', 'Sorry !!! your age should be greter than 14.8'); 
                  return redirect()->route('home');
                }
             }
         } 
         
         
    }

    public function private_booking_details($slug){
        $message = Crypt::decrypt($slug);
        $hash = explode(",",$message);
        $package_id = $hash[0];
        $amount = $hash[1];
        $slot_id = 0;
        $card_option = 0;
        $addtional_info = 0;
        $package = Package::where('id',$package_id)->firstOrFail();
        $slug = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info);
        return view('page_templates.private-booking-confirmation',compact('package','amount','slug')); 
    }

     public function booking_enquiry(Request $request){

         $messgae = Crypt::decrypt($request->hash);
         $hash = explode(",",$messgae);
         $package_id = $hash[0];
         $slot_id = $hash[1];
         $card_option = $hash[2];
         $addtional_info = $hash[3];
         $package = Package::where('id',$package_id)->firstOrFail();
         if($slot_id == 0){
            $input['slot'] = 0;
            $input['addtional_info'] = 0;

         }else{
           $slot = Slot::where('id',$slot_id)->firstOrFail();
           $input['slot'] = $slot;
           $input['addtional_info'] = $addtional_info;
         } 
         

         $to  = explode(",",$this->emails);
         $cc  = explode(",",$this->cc_emails);
         $bcc = explode(",",$this->bcc_emails);

         $email_arr = array('to' => $to, 'cc' => $cc, 'bcc' => $bcc);

         $input['package'] = $package;
         $input['segment'] = $package->segment->name;
         $input['card_option'] = $card_option;
         $input['user'] = Auth::guard('web')->user();

         $result = Mail::send('email.booking-email', ['request' => $input], function ($message) use ($email_arr) {
            if (count($email_arr['cc']) > 0 && $email_arr['cc']['0'] != '') {
                $message->cc($email_arr['cc']);
             }
             if (count($email_arr['bcc']) > 0 && $email_arr['bcc']['0'] != '') {
                $message->bcc($email_arr['bcc']);
             }
            $message->to($email_arr['to'] , 'A+ Driving School System Admin')->subject('New Booking Request Received');

         });

         if(count(Mail::failures()) > 0){
                Session::flash('danger', 'Failed to send booking enquiry email, please try again.'); 
                 return redirect()->back();
         }else{
             Session::flash('success', 'Email has sent successfully'); 
              return redirect()->route('home');
         }

     }

     public function paypal(Request $request){

         $message = Crypt::decrypt($request->hash);
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

         $name = $package->segment->name.' | '.$package->name;
         $currency_code = 'USD';
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id);
         $input['gateway'] = 'Paypal';
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
         $input['gateway'] = 'Paypal';
         $input['name'] = $name;
         $input['currency_code'] = $currency_code;
         $input['hash'] = $newhash;
         $input['amount'] = $price;
         $input['user_id'] = Auth::guard('web')->user()->id;
         $input['segment_id'] = $package->segment->id;
         $input['package_id'] = $package->id;
         $input['slot_id'] = $slot->id;
         $result = Transaction::create($input);
          }  

         $curr_time = time();
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id.','.$result->id.','.$curr_time);

         $transaction = Transaction::findOrFail($result->id);
         $input2['hash'] = $newhash;
         $result2 = $transaction->update($input2);

         return view('page_templates.paypal',compact('newhash','name','price','currency_code'));
     }

    public function paypal_success(Request $request){
        
        if($request->has(['tx','item_name','amt','cc','st','cm','item_number'])){
            
            $message = Crypt::decrypt($request->cm);
            $hash = explode(",",$message);

            $package_id = $hash[0];
            $slot_id = $hash[1];
            $addtional_info = $hash[3];
            $package = Package::where('id',$package_id)->firstOrFail();
            if($slot_id == 0){
                $slot = NULL;
            }else{
                $slot = Slot::where('id',$slot_id)->firstOrFail();
            }

            

            $user = Auth::guard('web')->user();

            $transaction_id = $hash[5];
            $transaction = Transaction::where([['hash',$request->cm],['id',$transaction_id]])->firstOrFail();
            $input['status'] = $request->st;
            $input['tx_id'] = $request->tx;
            $result = $transaction->update($input);

            $pdf = PDF::loadView('elements/users/invoice',compact('package','slot','transaction','user','addtional_info'));
            $filename = time().'-invoice';
            // $pdf->save(public_path('invoice/'.$filename.'.pdf'));
            // $filepath = public_path('invoice/'.$filename.'.pdf');
            
            Storage::put(('public/invoice/'.$filename.'.pdf'), $pdf->output());
            $filepath = ('storage/invoice/'.$filename.'.pdf');
            

            $result5 = $transaction->update(['invoice' => $filename.'.pdf']);

             if($addtional_info == 1){

                 $package2 = Package::where([['name','Bronze'],['segment_id',2]])->first();
                 $input3['gateway'] = 'Paypal';
                 $input3['name'] = 'Segment 2 | Bronze';
                 $input3['currency_code'] = 'USD';
                 $input3['hash'] = $request->cm;
                 $input3['amount'] = 50;
                 $input3['user_id'] = Auth::guard('web')->user()->id;
                 $input3['segment_id'] = $package2->segment->id;
                 $input3['package_id'] = $package2->id;
                 $input3['slot_id'] = 0;
                 $input3['invoice'] = $filename.'.pdf';
                 $input3['status'] = $request->st;
                 $input3['tx_id'] = $request->tx;
                 $result5 = Transaction::create($input3);

             }


         $input2['package'] = $package;
         $input2['segment'] = $package->segment->name;
         $input2['user'] = Auth::guard('web')->user();
         $input2['transaction'] = $transaction;
         $input2['slot'] = $slot;

         if($slot != NULL){
            $input4['enrolled'] = $slot->enrolled + 1;
            $result4 = $slot->update($input4);
         }

         $result = Mail::send('email.invoice-email', ['request' => $input2], function ($message) use ($filepath){
            $message->to(Auth::guard('web')->user()->email, Auth::guard('web')->user()->name)->subject('A+ Driving School Course Invoice')->attach($filepath, ['as' => 'invoice.pdf', 'mime' => 'application/pdf']);
         });

         $to  = explode(",",$this->emails);
         $cc  = explode(",",$this->cc_emails);
         $bcc = explode(",",$this->bcc_emails);

         $email_arr = array('to' => $to, 'cc' => $cc, 'bcc' => $bcc);

        $result2 = Mail::send('email.confirm-booking-email', ['request' => $input2], function ($message) use ($email_arr) {
            if (count($email_arr['cc']) > 0 && $email_arr['cc']['0'] != '') {
                $message->cc($email_arr['cc']);
             }
             if (count($email_arr['bcc']) > 0 && $email_arr['bcc']['0'] != '') {
                $message->bcc($email_arr['bcc']);
             }
            $message->to($email_arr['to'] , 'A+ Driving School System Admin')->subject('New Payment Received');

         });


            if( count(Mail::failures()) > 0 ) {

                Session::flash('danger', 'Error encounterd');
                
            } else {
                
                Session::flash('success', 'Payment has been completed successfully.');

            }
            return redirect()->route('transactions');
        }else{
            abort(404);
        }
    }

    public function paypal_cancel(Request $request){

        Session::flash('danger', 'You have cancel the payment');
        return redirect()->route('home');
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
            
            Session::flash ( 'danger', 'Too many requests made to the API too quickly' );
            return redirect()->back();
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            Session::flash ( 'danger', 'Invalid parameters were supplied to Stripe\'s API' );
            return redirect()->back();
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            Session::flash ( 'danger', 'Authentication with Stripe\'s API failed' );
            return redirect()->back();
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            Session::flash ( 'danger', 'Network communication with Stripe failed' );
            return redirect()->back();
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            Session::flash ( 'danger', 'Display a very generic error to the user, and maybe send' );
            return redirect()->back();
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (Exception $e) {
            Session::flash ( 'danger', 'Something else happened, completely unrelated to Stripe' );
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


    public function update_email(Request $request){

            if ($request->isMethod('PATCH')) {

            $this->validate($request, [
                 'email' => 'required|email|unique:users,email,'.$request->id
            ]);

                $user = User::findOrFail(Auth::guard('web')->user()->id);

             if($request->email == Auth::guard('web')->user()->email){

                Session::flash('info', 'You have requested updatation with the same email'); 

             }else{  

            $input['update_email'] = $request->email;
            $input['security_code'] = rand(111111,999999);
            $input['email_verify_status'] = 0;

            $req['update_email'] = $request->email;
            $req['security_code'] = $input['security_code'];
            $req['name'] = Auth::guard('web')->user()->fname;

            $result = $user->update($input);

            $result2 = Mail::send('email.email-confirmation2', ['user' => $req], function ($message) use ($request) {
                $message->to( $request->email, 'A+ Driving School System Admin')->subject('Change of Email address verification ');
            });

            $result3 = Mail::send('email.email-confirmation', ['user' => $req], function ($message) {
                $message->to( Auth::guard('web')->user()->email, 'A+ Driving School System Admin')->subject('Change of Email address verification ');
            });

               if($result){
                   Session::flash('success', 'Confirmation email is sent to '.$request->email); 
                } else {
                   Session::flash('danger', 'Error encounterd');
                }
            }

                return redirect()->back();

            }else {
                return view('users.change-email');
            }

    }

    public function verfy_email(Request $request) {

        if ($request->ajax()) {

        $validator = Validator::make($request->all(), [
             'security_code' => 'required|numeric|digits:6',
        ]);

        if ($validator->passes()) {

            $user = User::findOrFail(Auth::guard('web')->user()->id);
            $security_code = $request->input('security_code');
                
            if(Auth::guard('web')->user()->security_code == $security_code){

            $input['email'] = Auth::guard('web')->user()->update_email;
            $input['update_email'] = NULL;
            $input['security_code'] = NULL;
            $input['email_verify_status'] = 1;
            $result = $user->update($input);

            $req['name'] = Auth::guard('web')->user()->fname;
            $req['title'] = 'Change of email address confirmation';
            $req['message'] = 'Your email address has been successfully changed in your A+ Driving School account. From now on, all communications will be sent to this email address.';

                if($result) {

                $result2 = Mail::send('email.common', ['user' => $req], function ($message) {
                $message->to( Auth::guard('web')->user()->update_email, 'A+ Driving School System Admin')->subject('Change of Email address confirmation ');
                });

                    Session::flash('success', 'Your email has been successfully changed');
                    
                    return response()->json(['status' => true, 'success' => 'Your email has been successfully changed']);

                } else {
                        
                     return response()->json(['status' => false, 'danger' => 'Error Encounterd']);
                }

            }else{

                 return response()->json(['status' => false, 'danger' => 'Security code is not matched']);
            } 

        }else{

            return response()->json(['status' => false, 'danger' => $validator->errors()]);
        }


        }
    }


    public function resend(Request $request){

        if ($request->ajax()) {

            $user = User::findOrFail(Auth::guard('web')->user()->id);

            $sender_email = Auth::guard('web')->user()->update_email;
            $input['security_code'] = rand(111111,999999);

            $req['email'] = Auth::guard('web')->user()->email;
            $req['security_code'] = $input['security_code'];
            $req['name'] = Auth::guard('web')->user()->fname;

            $result = $user->update($input);

            $result2 = Mail::send('email.email-confirmation', ['user' => $req], function ($message) use ($sender_email) {
                $message->to( $sender_email, 'A+ Driving School System Admin')->subject('Resend OTP For Email Verification');
            });

               if($result){

                   return response()->json(['status' => true, 'success' => 'Confirmation email is sent to '.$sender_email]);

                } else {
                   
                   return response()->json(['status' => false, 'danger' => 'Error Encounterd']);
                }

        }
    }

     public function profile(Request $request)
    {
        if ($request->isMethod('PATCH')) {

            $this->validate($request, [
                 'fname' => 'required|regex:/^[\pL\s\-]+$/u',
                 'lname' => 'required|regex:/^[\pL\s\-]+$/u',
                 'dob' => 'required',
                 'phone' => 'required|min:8|max:13',
                 'address' => 'required|string'
            ]);
       
            $input = $request->except('email');
            $input['dob'] = date("Y-m-d", strtotime(str_replace('-', '/', $request->dob)));
            $profile = User::findOrFail(Auth::guard('web')->user()->id);
            $result = $profile->update($input);
            if($result){
               Session::flash('success', 'User profile updated successfully'); 
            } else {
               Session::flash('danger', 'Error encounterd');
            }
               return redirect()->route('user.profile');
            } else {

            return view('users.profile');
       }
        
    }

    public function change_password(Request $request)
    {

       if ($request->isMethod('PATCH')) {

            $this->validate($request, [
                'password' => 'required|string|regex:/(?=^.{8,12}$)(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_])^.*/|same:conf_password',
                'conf_password' => 'required'
            ]);

            $user = User::findOrFail(Auth::guard('web')->user()->id);
            $check = Hash::check($request->old_password, $user->password);
            if($check){
                $user->password = Hash::make($request->input('password'));
                $result = $user->update();
                if($result){
                   Session::flash('success', 'User password updated Successfully'); 
                } else {
                   Session::flash('danger', 'Error Encounterd');
                }
             } else{
                Session::flash('warning', 'Your current password is not mathching'); 
             }
             return redirect()->route('user.change_password');
        }else {
            return view('users.password');
        }
        
    }

    public function bookings() {
        $transactions = Transaction::where('user_id',Auth::guard('web')->user()->id)->orderBy('created_at','desc')->get();
        return view('users.transactions', compact('transactions'));
    }

    public function class_details($id) {
        $detail = Transaction::with('segment','package','slot')->where('user_id',Auth::guard('web')->user()->id)->where('id',$id)->firstOrFail();
        return view('users.class-details', compact('detail'));
    }

    public function select_combo_pack(Request $request){

        $this->validate($request, [
            'detail' => 'required|integer',
            'slot' => 'required|integer'
        ]);
        $id = $request->detail;
        $slot = $request->slot;

        $transaction = Transaction::with('segment','package','slot')->where('user_id',Auth::guard('web')->user()->id)->where('id',$id)->firstOrFail();

        $slot = Slot::where([['id',$slot],['segment_id',$transaction->segment_id]])->firstOrFail();

        $input3['enrolled'] = $slot->enrolled + 1;
        $result3 = $slot->update($input3);

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


         $card_option = 1;
         $addtional_info = 1;
         $name = $transaction->segment->name.' | '.$transaction->package->name. ' | '.$month. ' | '.$slot->year;
         $newhash = Crypt::encrypt($transaction->package->id.','.$slot->id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id);

        $transaction->name = $name;
        $transaction->hash = $newhash;
        $transaction->slot_id = $slot->id;
        $result = $transaction->update();
        if($result){

            
            $input4['package'] = $transaction->package->name;
            $input4['segment'] = $transaction->segment->name;
            $input4['year'] = $slot->year; 
            $input4['month'] = $month;
            $input4['start_time'] = $slot->start_time;
            $input4['end_time'] = $slot->end_time;
            $input4['dates'] = $slot->dates;
            $input4['user'] = Auth::guard('web')->user()->fname;

            $sender_email = Auth::guard('web')->user()->email;
            try{
                $result2 = Mail::send('email.combo-pack-select-email', ['req' => $input4], function ($message) use ($sender_email) {
                $message->to( $sender_email, 'A+ Driving School System Admin')->subject('Segment 2 slot has been selected Successfully');
            });
                Session::flash('success', 'Segment 2 slot has been selected Successfully'); 
            }catch(\Exception $e){
                Session::flash('danger', $e->getMessage());
            }
        
        } else {
           Session::flash('danger', 'Error Encounterd');
        }

        return redirect()->back();
        
    }


    public function paypal_custom(Request $request){

         $message = Crypt::decrypt($request->hash);
         $hash = explode(",",$message);
         $package_id = $hash[0];
         $slot_id = $hash[1];
         $card_option = $hash[2];
         $addtional_info = $hash[3];
         $package = Package::where('id',$package_id)->firstOrFail();
         $price = $request->amount;
        

         $name = $package->segment->name.' | '.$package->name;
         $currency_code = 'USD';
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id);
         $input['gateway'] = 'Paypal';
         $input['name'] = $name;
         $input['currency_code'] = $currency_code;
         $input['hash'] = $newhash;
         $input['amount'] = $price;
         $input['user_id'] = Auth::guard('web')->user()->id;
         $input['segment_id'] = $package->segment->id;
         $input['package_id'] = $package->id;
         $input['slot_id'] = 0;
         $result = Transaction::create($input); 

         $curr_time = time();
         $newhash = Crypt::encrypt($package_id.','.$slot_id.','.$card_option.','.$addtional_info.','.Auth::guard('web')->user()->id.','.$result->id.','.$curr_time);

         $transaction = Transaction::findOrFail($result->id);
         $input2['hash'] = $newhash;
         $result2 = $transaction->update($input2);

         return view('page_templates.paypal',compact('newhash','name','price','currency_code'));
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
            
            Session::flash ( 'danger', 'Too many requests made to the API too quickly' );
            return redirect()->back();
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            Session::flash ( 'danger', 'Invalid parameters were supplied to Stripe\'s API' );
            return redirect()->back();
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            Session::flash ( 'danger', 'Authentication with Stripe\'s API failed' );
            return redirect()->back();
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            Session::flash ( 'danger', 'Network communication with Stripe failed' );
            return redirect()->back();
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            Session::flash ( 'danger', 'Display a very generic error to the user, and maybe send' );
            return redirect()->back();
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (Exception $e) {
            Session::flash ( 'danger', 'Something else happened, completely unrelated to Stripe' );
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
            $pdf->save(public_path('invoice/'.$filename.'.pdf'));
            $filepath = public_path('invoice/'.$filename.'.pdf');

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
            
            Session::flash ( 'danger', 'Too many requests made to the API too quickly' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            Session::flash ( 'danger', 'Invalid parameters were supplied to Stripe\'s API' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            Session::flash ( 'danger', 'Authentication with Stripe\'s API failed' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            Session::flash ( 'danger', 'Network communication with Stripe failed' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            Session::flash ( 'danger', 'Display a very generic error to the user, and maybe send' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (Exception $e) {
            Session::flash ( 'danger', 'Something else happened, completely unrelated to Stripe' );
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

          Session::flash ( 'danger', 'Since it\'s a decline' . 'Error : ' . $err['message'] . "\n" );
          session()->forget('xstrID');
          return redirect()->route('home');
        } catch (\Stripe\Error\RateLimit $e) {
            
            Session::flash ( 'danger', 'Too many requests made to the API too quickly' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            Session::flash ( 'danger', 'Invalid parameters were supplied to Stripe\'s API' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            Session::flash ( 'danger', 'Authentication with Stripe\'s API failed' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            Session::flash ( 'danger', 'Network communication with Stripe failed' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            Session::flash ( 'danger', 'Display a very generic error to the user, and maybe send' );
            session()->forget('xstrID');
            return redirect()->route('home');
          // Display a very generic error to the user, and maybe send
          // yourself an email
        } catch (Exception $e) {
            Session::flash ( 'danger', 'Something else happened, completely unrelated to Stripe' );
            session()->forget('xstrID');
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
