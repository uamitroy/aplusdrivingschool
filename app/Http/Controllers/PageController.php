<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Post;
use App\PostMeta;
use App\Setting;
use App\Category;
use App\ContactForm;
use App\Subscriber;
use App\Menu;
use App\Segment;
use App\Package;
use App\Slot;
use Session;
use Crypt;
use Auth;
use Mail;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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

      $page = Post::where([['post_type','page'],['is_front_page',1]])->firstOrFail();

      $seg_id = 2;
      $gal_id = 3;
      $slider_cat_id = 5;
      $testimonial_cat_id = 4;

      $segments = Post::whereHas('categories', function($query) use($seg_id) {
            $query->where('category_id',$seg_id);
        })->where('post_type','=','post')->orderBy('created_at', 'asc')->get(['id','title','image','content']);

      $photos = Post::whereHas('categories', function($query) use($gal_id) {
            $query->where('category_id',$gal_id);
        })->where('post_type','=','post')->orderBy('created_at', 'desc')->get(['id','title','image']);

      $testimonials = Post::whereHas('categories', function($query) use($testimonial_cat_id) {
            $query->where('category_id',$testimonial_cat_id);
        })->where('post_type','=','post')->orderBy('created_at', 'desc')->get(['id','title','content','image']);
     $sliders = Post::whereHas('categories', function($query) use($slider_cat_id) {
            $query->where('category_id',$slider_cat_id);
        })->where('post_type','=','post')->orderBy('created_at', 'asc')->get(['id','title','image','content']);

      $meta_html = $this->get_meta_information($page->post_meta_element);
      return view('page_templates.home',compact('page','meta_html','segments','photos','testimonials','sliders')); 
        
    }
    
    public function other($slug)
    {        
        $page = Post::where([['post_type','page'],['slug',$slug],['status','1']])->firstOrFail();
        $meta_html = $this->get_meta_information($page->post_meta_element);
        $slots = '';
        if($page->template == 3){

        $gal_id = 3;
        $photos = Post::whereHas('categories', function($query) use($gal_id) {
            $query->where('category_id',$gal_id);
        })->where('post_type','=','post')->orderBy('created_at', 'desc')->get(['id','title','image']);

          return view('page_templates.gallery',compact('page','meta_html','photos')); 

        }elseif($page->template == 4){

          return view('page_templates.contact',compact('page','meta_html')); 

        }elseif($page->template == 5){

          if($page->id == 5){
            $segment = Segment::where('id',1)->first();
            $packages = Package::where('segment_id',$segment->id)->get();
            $slots = Slot::where('segment_id',$segment->id)->orderBy('id','desc')->get();
          }
          if($page->id == 6){
            $segment = Segment::where('id',2)->first();
            $packages = Package::where('segment_id',$segment->id)->get();
            $slots = Slot::where('segment_id',$segment->id)->orderBy('id','desc')->get();
          }
          if($page->id == 7){
            $segment = Segment::where('id',3)->first();
            $packages = Package::where('segment_id',$segment->id)->get();
            $slot = '';
          }

          return view('page_templates.segment',compact('page','segment','packages','slots','meta_html')); 

        }elseif($page->template == 6){

          if(get_post_meta($page->id,'segment_slug') == 'teen-segment-1'){
            $segment = Segment::where('id',1)->first();
            $packages = Package::where('segment_id',$segment->id)->get();
            $slots = Slot::where('segment_id',$segment->id)->orderBy('id','desc')->get();
          }else{
            $segment = Segment::where('id',2)->first();
            $packages = Package::where('segment_id',$segment->id)->get();
            $slots = Slot::where('segment_id',$segment->id)->orderBy('id','desc')->get();
          }

          return view('page_templates.segment-schedule',compact('page','segment','packages','slots','meta_html')); 

        }else{

         return view('page_templates.default',compact('page','meta_html')); 

        }
        
			       
    }

  public function class_details($slug){

     $messgae = Crypt::decrypt($slug);
     $hash = explode(",",$messgae);
     $package_id = $hash[0];
     $slot_id = $hash[1];
     $package = Package::where('id',$package_id)->firstOrFail();
     if($slot_id == 0){
        return view('page_templates.class-details',compact('package'));
     }else{
        $slot = Slot::where('id',$slot_id)->firstOrFail();
        if($slot->seat_allotted - $slot->enrolled > 0){
          return view('page_templates.class-details',compact('package','slot'));
       }else{
          return redirect()->back();
       }   
     }
     
     
      
  }

  public function booking_confirmation(Request $request){

     $package_id = $request->package;
     $slot_id = $request->slot;
     $card_option = $request->card_option;
     $addtional_info = $request->addtional_info;
     $package = Package::where('id',$package_id)->firstOrFail();
     if($slot_id == 0){
        $newhash= Crypt::encrypt($package->id.','.$slot_id.','.$card_option.','.$addtional_info);
     }else{
        $slot = Slot::where('id',$slot_id)->firstOrFail();
        $newhash= Crypt::encrypt($package->id.','.$slot->id.','.$card_option.','.$addtional_info);
     } 
     
     if(Auth::guard('web')->check()){
         return redirect()->route('booking.details',$newhash);
      }else{
        return redirect()->intended('/booking-details/'.$newhash);
      }
  }

  public function private_booking_confirmation(Request $request){

          $this->validate($request, [
                'amount' => 'required|numeric',
                'package' => 'required|integer'
            ]);

       $package_id = $request->package;
       $amount = $request->amount;
       $newhash= Crypt::encrypt($package_id.','.$amount);

       if(Auth::guard('web')->check()){
          return redirect()->route('private.booking.details',$newhash);
        }else{
          return redirect()->intended('/private-booking-details/'.$newhash);
        }

    }

  public function contact_form(Request $request){
        
     if ($request->ajax()) {
      $to  = explode(",",$this->emails);
      $cc  = explode(",",$this->cc_emails);
      $bcc = explode(",",$this->bcc_emails);

      $email_arr = array('to' => $to, 'cc' => $cc, 'bcc' => $bcc);

       $input['name'] =  $request->fname.' '.$request->lname;
       $input['email'] =  $request->email;
       $input['phone'] =  $request->phone;
       $input['message'] =  $request->message;

        $result = Mail::send('email.contact-email', ['request' => $input], function ($message) use ($email_arr) {
          if (count($email_arr['cc']) > 0 && $email_arr['cc']['0'] != '') {
            $message->cc($email_arr['cc']);
         }
         if (count($email_arr['bcc']) > 0 && $email_arr['bcc']['0'] != '') {
            $message->bcc($email_arr['bcc']);
         }
            $message->to($email_arr['to'] , 'A+ Driving School India System Admin')->subject('New Contact Request Received');

        });

        $result2 = ContactForm::create($input);
        
        if($result2){
           Session::flash('success', 'Your information has been submitted successfully');
           return response()->json(['status' => true, 'success' => 'Email has sent successfully']); 
          } else {
           Session::flash('danger', 'Your information has not been submitted successfully');
           return response()->json(['status' => false, 'danger' => 'Email has not sent successfully']);
          }

      }

    }

  
  public function thankyou(){
	
	  if(Session::has('success') || Session::has('danger')) {

      return view('page_templates.thankyou');
	  
	  }else{
	  
	  return redirect()->action('PageController@index');	
	  }

  } 

  public function get_meta_information($meta){
      
      if($meta->title){

          $meta_info = '<title>'. $meta->title . '</title>'.PHP_EOL;
      }
      if($meta->description){
        
          $meta_info .= '<meta name="description" content="'. $meta->description .'" />'.PHP_EOL;
      }
      if($meta->keywords){
          
          $meta_info .= '<meta name="keywords" content="'. $meta->keywords .'" />'.PHP_EOL;

      }
      if($meta->robots){
          
          $meta_info .= '<meta name="robots" content="'. $meta->robots .'" />'.PHP_EOL;

      }
      if($meta->revisit_after){
          
          $meta_info .= '<meta name="revisit-after" content="'. $meta->revisit_after .'" />'.PHP_EOL;

      }
      if($meta->og_locale){
          
          $meta_info .= '<meta property="og:locale" content="'. $meta->og_locale .'" />'.PHP_EOL;

      }
      if($meta->og_type){
          
          $meta_info .= '<meta property="og:type" content="'. $meta->og_type .'" />'.PHP_EOL;

      }
      if($meta->og_image){
          
          $meta_info .= '<meta property="og:image" content="'. $meta->og_image .'" />'.PHP_EOL;
      }
      if($meta->og_title){
          
          $meta_info .= '<meta property="og:title" content="'. $meta->og_title .'" />'.PHP_EOL;

      }
      if($meta->og_url == NULL){

        $uri = $_SERVER['REQUEST_URI'];
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
          
        $meta_info .= '<meta property="og:url" content="'. $url .'" />'.PHP_EOL;
      }
      if($meta->og_description){
          
          $meta_info .= '<meta property="og:description" content="'. $meta->og_description .'" />'.PHP_EOL;
      }
      if($meta->og_site_name){
          
          $meta_info .= '<meta property="og:site_name" content="'. $meta->og_site_name .'" />'.PHP_EOL;

      }
      if($meta->author){
          
          $meta_info .= '<meta name="author" content="'. $meta->author .'" />'.PHP_EOL;
      }
      if($meta->canonical == NULL){

        $uri = $_SERVER['REQUEST_URI'];
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $meta_info .= '<link rel="canonical" href="'. $url .'" />'.PHP_EOL;
      }
      if($meta->geo_region){
          
          $meta_info .= '<meta name="geo.region" content="'. $meta->geo_region .'" />'.PHP_EOL;
      }
      if($meta->geo_placename){
          
          $meta_info .= '<meta name="geo.placename" content="'. $meta->geo_placename .'" />'.PHP_EOL;
      }
      if($meta->geo_position){
          
          $meta_info .= '<meta name="geo.position" content="'. $meta->geo_position .'" />'.PHP_EOL;
      }
      if($meta->ICBM){
          
          $meta_info .= '<meta name="ICBM" content="'. $meta->ICBM .'" />';
      }

      return $meta_info;
  }


    
}
