@extends('layouts.front-auth')
@section('meta') 
<title>Booking Confimation</title>
@endsection
@section('content') 
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">Booking Confimation</h2>

  <div class="breadcr">
    <ul>
      <li><a href="{{ route('site') }}">Home</a></li>
      <li><i class="fas fa-caret-right"></i></li>
      <li>{{ $package->segment->name }}</li>
    </ul>
  </div>

</div>
<div class="gap"></div>
<div class="container">

 @component('elements.flash') @endcomponent

  <div class="text-center">
    <h3 class="heading">confirm your information</h3>
  </div>
  
   
 
      <div class="grayBg">
      
        <div class="row">
        
        <div class="col-md-11">
        <div class="normalpara botgap30">
        
      	<p>Please review the following information. When you&apos;re finished, press the submit button at the bottom of the page to continue. If yor are paying by credit card you will be asked to enter that information.</p>
      </div>
      
        </div>
        
        	<div class="col-md-5">
            	
                <div class="paypage">
            	<h3 class="headpay">Applicant Information</h3>
                
                	<p>Name: {{ Auth::user()->fname }} {{ Auth::user()->lname }}</p>
                    <p>Address: {{ Auth::user()->address }}</p>
                    <p>Student Mobile Phone : {{ Auth::user()->phone }}</p>
                    <p>Student Email Address : {{ Auth::user()->email }}</p>
					<p>Guardian Name: {{ Auth::user()->guardian_name }}</p>
		 			<p>Guardian Phone No: {{ Auth::user()->guardian_phone }}</p>
		  			<p>Guardian Address: {{ Auth::user()->guardian_address }}</p>
                
                </div>
                
            
            </div>
            
            <div class="col-md-6">
            	
                <div class="paypage">
				
				<h3 class="headpay">Class Information</h3>
				
                
                	<p>Class : {{ $package->segment->name }}</p>
                    <p>Package : {{ $package->name }}</p>
					
                    
                </div>
                
                <div class="paypage">

                    <p>You want to deposit : <b>${{ $amount }}</b></p>
                    
                </div>
                
            
            </div>
            
        </div>

		{!! Form::open([ 'route'=> ['booking.paypal.custom'], 'autocomplete'=>'off', 'id'=>'paypal','method' => 'POST']) !!}
		    {!! Form::hidden('hash', $slug) !!}
			{!! Form::hidden('amount', $amount) !!}
			{!! Form::button('Pay with paypal',['name' => 'Submit', 'type' => 'submit', 'value' => 'Submit', 'class' => 'bookbtn nonetopgap']) !!}
		{!! Form::close() !!}
		{!! Form::open([ 'route'=> ['booking.stripe.custom'], 'autocomplete'=>'off', 'id'=>'stripe','method' => 'POST']) !!}
		    {!! Form::hidden('hash', $slug) !!}
			{!! Form::hidden('amount', $amount) !!}
			{!! Form::button('Pay via Credit/Debit card',['name' => 'Submit', 'type' => 'submit', 'value' => 'Submit', 'class' => 'bookbtn nonetopgap']) !!}
		{!! Form::close() !!}
		
      
      </div>
    
     
</div>
<div class="gap"></div>
@endsection


