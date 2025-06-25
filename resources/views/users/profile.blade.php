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
			
		  
		  {!! Form::open([ 'route'=> ['user.profile'],'autocomplete'=>'off', 'id'=>'profile-form','method' => 'PATCH']) !!}
					
			<div class="form-group {{ $errors->has('fname') ? ' has-error' : '' }}">
				{!! Form::label('fname', 'First Name',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','fname', Auth::user()->fname , [ 'class' => 'form-control','id' => 'fname'])!!}
				@if ($errors->has('fname'))
					<span class="help-block">
						{{ $errors->first('fname') }}
					</span>
				@endif
			</div>
			<div class="form-group {{ $errors->has('lname') ? ' has-error' : '' }}">
				{!! Form::label('lname', 'Last Name',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','lname', Auth::user()->lname , [ 'class' => 'form-control','id' => 'lname'])!!}
				@if ($errors->has('lname'))
					<span class="help-block">
						{{ $errors->first('lname') }}
					</span>
				@endif
			</div>
			<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
				{!! Form::label('phone', 'Phone',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','phone', Auth::user()->phone , [ 'class' => 'form-control','id' => 'phone'])!!}
				@if ($errors->has('phone'))
					<span class="help-block">
						{{ $errors->first('phone') }}
					</span>
				@endif
			</div>
			<div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
				{!! Form::label('dob', 'D.O.B',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','dob', date("m/d/Y", strtotime(Auth::user()->dob)) , [ 'class' => 'form-control datepicker','id' => 'dob'])!!}
				@if ($errors->has('dob'))
					<span class="help-block">
						{{ $errors->first('dob') }}
					</span>
				@endif
			</div>
			<div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
				{!! Form::label('address', 'Address',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','address', Auth::user()->address , [ 'class' => 'form-control','id' => 'address'])!!}
				@if ($errors->has('address'))
					<span class="help-block">
						{{ $errors->first('address') }}
					</span>
				@endif
			</div>	
			<div class="form-group {{ $errors->has('guardian_name') ? ' has-error' : '' }}">
				{!! Form::label('guardian_name', 'Guardian Name',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','guardian_name', Auth::user()->guardian_name , [ 'class' => 'form-control','id' => 'guardian_name'])!!}
				@if ($errors->has('guardian_name'))
					<span class="help-block">
						{{ $errors->first('guardian_name') }}
					</span>
				@endif
			</div>
			<div class="form-group {{ $errors->has('guardian_phone') ? ' has-error' : '' }}">
				{!! Form::label('guardian_phone', 'Guardian Phone',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','guardian_phone', Auth::user()->guardian_phone , [ 'class' => 'form-control','id' => 'guardian_phone'])!!}
				@if ($errors->has('guardian_phone'))
					<span class="help-block">
						{{ $errors->first('guardian_phone') }}
					</span>
				@endif
			</div>	
			<div class="form-group {{ $errors->has('guardian_address') ? ' has-error' : '' }}">
				{!! Form::label('guardian_address', 'Guardian Address',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','guardian_address', Auth::user()->guardian_address , [ 'class' => 'form-control','id' => 'guardian_address'])!!}
				@if ($errors->has('guardian_address'))
					<span class="help-block">
						{{ $errors->first('guardian_address') }}
					</span>
				@endif
			</div>	
			
			 <div class="form-group">
				{!! Form::button('Confirm',['type' => 'submit', 'class' => 'gradiv editbtn']) !!}
			</div>
			
		{!! Form::close() !!}
		   
		  
		  
		</div>
	</div>
  </div>

</div>

</div>
</div>
<div class="gap"></div>
@endsection
@component('elements.users.components.flash') @endcomponent
@push('css')
{!!Html::style('admin-design/css/bootstrap-datepicker.css')!!}
@endpush
@push('js')
{!!Html::script('design/js/jquery.validate.js')!!}
<script>
$(function () {

	$.validator.addMethod("nameRegex", function (value, element) {
        return this.optional(element) || /^([a-zA-Z_-\s]{3,20})$/.test(value);
    }, "Enter valid name");

  
    $("#profile-form").validate({

          errorElement: 'span',
          errorClass: 'help-block',
          highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
          },
          errorPlacement: function (error, element) {
            error.insertAfter(element);
          },

      rules: {
	  
	   	    fname: {
                      
                 required: true,
      		     nameRegex: true

            },
			lname: {
                      
                 required: true,
      		     nameRegex: true

            },
			dob: {
                
                required: true
               

             },
			phone: {
                      
                 required: true,
      		     minlength:8,
				 maxlength:13,
				 digits:true

            },
			address: {
                      
                 required: true
				 
            },
/*			guardian_name : {
				required: true,
      		    nameRegex: true
			},
			guardian_phone: {
                      
                 required: true,
      		     minlength:8,
				 maxlength:13,
				 digits:true

            },
			guardian_address: {
                      
                 required: true
				 
            } */

          }

    });

    

  });

</script>
{!!Html::script('admin-design/js/bootstrap-datepicker.js')!!}
<script>
	$('.datepicker').datepicker({
		format: 'mm/dd/yyyy',
		clearBtn: true,
		todayHighlight: true
	});
</script>
@endpush

