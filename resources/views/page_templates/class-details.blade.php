@extends('layouts.front-auth')
@section('meta') 
<title>Segment Details</title>
@endsection
@section('content') 
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">{{ $package->segment->name }}</h2>

  <div class="breadcr">
    <ul>
      <li><a href="{{ route('site') }}">Home</a></li>
      <li><i class="fas fa-caret-right"></i></li>
      <li>{{ $package->segment->name }}</li>
    </ul>
  </div>

</div>

@if($package->segment->id == 1 || $package->segment->id == 2)
@if($slot->month == 1)
	@php( $month = 'January')
@endif 
@if($slot->month == 2)
	@php( $month = 'February')
@endif 
@if($slot->month == 3)
	@php( $month = 'March')
@endif 
@if($slot->month == 4)
	@php( $month = 'April')
@endif 
@if($slot->month == 5)
	@php( $month = 'May')
@endif 
@if($slot->month == 6)
	@php( $month = 'June')
@endif 
@if($slot->month == 7)
	@php( $month = 'July')
@endif 
@if($slot->month == 8)
	@php( $month = 'August')
@endif 
@if($slot->month == 9)
	@php( $month = 'September')
@endif 
@if($slot->month == 10)
	@php( $month = 'October')
@endif 
@if($slot->month == 11)
	@php( $month = 'November')
@endif   
@if($slot->month == 12)
	@php( $month = 'December')
@endif 
@endif
<div class="gap"></div>
<div class="container">

  <div class="text-center">
    <h3 class="heading">{{ $package->segment->name }} class - <span class="yecolor">{{ $package->name }}</span></h3>
  </div>
  @if($package->segment->id == 1 || $package->segment->id == 2)
    <div class="gaptab">
    
    	<h3 class="smallhead">Classes in {{ $month }} {{ $slot->year }}</h3>
    
    </div>
  @endif
 
      <div class="grayBg">
	  
	  @if($package->segment->id == 1 || $package->segment->id == 2)
      
      	<div class="row">
        
            <div class="col-sm-4">
                    
               <div class="segdetail clearfix">
        
                      <div class="segicon">
                        <img src="{!! asset('design/images/gradi_1.png') !!}" alt="">
                      </div>
        
                      <div class="textseg">
        
                        <h4>year:</h4>
        
                        <ul>
                          <li>{{ $slot->year }}</li>
                        </ul>
        
                      </div>
        
                    </div>
        
            </div>
            
            <div class="col-sm-4">
                    
               <div class="segdetail clearfix">
        
                      <div class="segicon">
                        <img src="{!! asset('design/images/gradi_2.png') !!}" alt="">
                      </div>
        
                      <div class="textseg">
        
                        <h4>month:</h4>
        			
                        <ul>
                          <li>{{ $month }}</li>
                        </ul>
        
                      </div>
        
                    </div>
        
            </div>
            
            <div class="col-sm-4">
                    
               <div class="segdetail clearfix">
        
                      <div class="segicon">
                        <img src="{!! asset('design/images/gradi_1.png') !!}" alt="">
                      </div>
        
                      <div class="textseg">
        
                        <h4>Time:</h4>
        
                        <ul>
                          <li>{{ $slot->start_time }} - {{ $slot->end_time }}</li>
                        </ul>
        
                      </div>
        
                    </div>
        
            </div>
            
            <div class="col-sm-12">
            
            	<div class="segdetail clearfix">
    
                  <div class="segicon">
                    <img src="{!! asset('design/images/gradi_2.png') !!}" alt="">
                  </div>
    
                  <div class="textseg">
    
                    <h4>Date:</h4>
    
                    @php( $date_array = explode(",",$slot->dates))
                    <ul>
					  @for($dt = 0 ; $dt < count($date_array); $dt++)
                      	<li>{{ $date_array[$dt] }}</li>
					  @endfor
                    </ul>
    
                  </div>
    
                </div>
            
            </div>
            
        
        </div>
		
	 @endif	
		
		 {!! Form::open([ 'route'=> ['booking.confirmation'],'autocomplete'=>'off', 'id'=>'booking','method' => 'POST']) !!}
		 
		  <input type="hidden" name="package" value="{{ $package->id }}" />
		  
		  @if($package->segment->id == 1 || $package->segment->id == 2)
		  <input type="hidden" name="slot" value="{{ $slot->id }}" />
          @else
		  <input type="hidden" name="slot" value="0" />
		  <input type="hidden" name="addtional_info" value="0" />
		  <input type="hidden" name="card_option" value="0" />
		  @endif
        <div class="costarea">
        
        	<h3>total cost : <strong>${{ $package->price }}</strong></h3>
            
            <div class="radioarea">
            	<input name="card_option" class="radiobtn" type="radio" id="Creditpay" value="1" checked/><span>Pay via Debit/Credit Card</span>
            </div>
            
            <div class="radioarea">
            	<input name="card_option" class="radiobtn" type="radio" value="2" id="DontCreditpay"/><span>I do not have a Debit/Credit Card</span>
            </div>
            
			
            
            <div class="paranor normalpara">
            
            	<p>All services or programs must be paid for at the time of registration. If you do not have a debit or credit card, please call 1(866) 611-3552
to make other arrangements.</p>

            </div>
            
            <div class="readpara normalpara">
            
            	<p>Note: If you do not have a debit or credit card, please call our office at 1(866)611-3552 to make other arrangements.</p>

            </div>
			
			@if($package->segment->id == 1 || $package->segment->id == 2)
			
			@if($package->segment->id == 1)
            
            {{--<b class="boldgap">For an additional $50, would you like to add Segment Two to your order and save $$?</b>
            
            <div class="radioarea inlinegapra">
            	<input name="addtional_info" class="radiobtn" type="radio" value="1" id="cheyes" checked/><span>Yes</span> 
                <input name="addtional_info" class="radiobtn" id="cheno" value="2" type="radio"/><span>No</span>
            </div>--}}
            <input type="hidden" name="addtional_info" value="2" />
			
			@else
				<input type="hidden" name="addtional_info" value="0" />
			@endif
            
            <div class="lastpara normalpara">
            
            	<p>Segment 2 cannot be taken until the student has held their Level One License for 3 continuous months and has completed 30 hours of practice driving.</p>

            </div>
			
			@endif
            
            <button class="bookbtn nonetopgap" type="submit">continue</button>
        
        </div>
        
		 {!! Form::close() !!}
      	
      
      </div>
    
     
</div>
<div class="gap"></div>
@endsection


