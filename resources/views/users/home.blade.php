@extends('layouts.front-auth')
@section('meta') 
<title>Dashboard</title>
@endsection
@section('content')
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">Dashboard</h2>

</div>
<div class="gap"></div>
<div class="container">

  <div class="accoutab">

   <div class="row"> 

  <div class="col-md-4 col-sm-5">
    <div class="bgtab">
       @include('elements.users.nav')
    </div>
  </div>
  
  <div class="col-md-8 col-sm-7">
    <div class="righttab">
		<div class="tabdettext">
			
		   <?php 
				$from = new DateTime(Auth::user()->dob);
				$to   = new DateTime('today');
		   ?>
		  <h5>Name: {{ Auth::user()->fname }} {{ Auth::user()->lname }}</h5>
		  <h5>Date of Birth: {{ date("m-d-Y", strtotime(Auth::user()->dob)) }}</h5>
		  <h5>Age : {{ $from->diff($to)->y }}</h5>
		  <h5>Phone No: {{ Auth::user()->phone }}</h5>
		  <h5>Email: {{ Auth::user()->email }}</h5>
		  <h5>Address: {{ Auth::user()->address }}</h5>
		  
		  <h5>Guardian Name: {{ Auth::user()->guardian_name }}</h5>
		  <h5>Guardian Phone No: {{ Auth::user()->guardian_phone }}</h5>
		  <h5>Guardian Address: {{ Auth::user()->guardian_address }}</h5>

		  <div class="text-right">
			<a class="gradiv editbtn" href="{{ route('user.profile') }}">Edit</a>
		  </div>
		  
		</div>
	</div>
  </div>

</div>

</div>
</div>
<div class="gap"></div>
@endsection
@component('elements.users.components.flash') @endcomponent