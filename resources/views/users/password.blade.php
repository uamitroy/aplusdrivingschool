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
			
		  
		  {!! Form::open([ 'route'=> ['user.change_password'],'autocomplete'=>'off', 'id'=>'password-form','method' => 'PATCH']) !!}
                    
                        <div class="form-group gapform passw {{ $errors->has('old_password') ? 'has-error' : ''}}">
							{!! Form::label('old_password', 'Old Password',[ 'class' => 'control-label']) !!}
                        	{!! Form::input('password','old_password', null, [ 'class' => 'form-control commeonfor','id' => 'old_password', 'placeholder' => 'Enter Current Pasword'])!!}
							 @if ($errors->has('email'))
								<span class="help-block">
									{{ $errors->first('old_password') }}
								</span>
							@endif
                        </div>
                        
                        <div class="form-group gapform passw {{ $errors->has('password') ? 'has-error' : ''}}">
							{!! Form::label('password', 'New Password',[ 'class' => 'control-label']) !!}
                        	{!! Form::input('password','password', null, [ 'class' => 'form-control commeonfor','id' => 'password', 'placeholder' => 'Enter New Pasword'])!!}
							 @if ($errors->has('email'))
								<span class="help-block">
									{{ $errors->first('password') }}
								</span>
							@endif
                        </div>
                        
                        <div class="form-group gapform passw {{ $errors->has('conf_password') ? 'has-error' : ''}}">
							{!! Form::label('conf_password', 'Confirm Password',[ 'class' => 'control-label']) !!}
                        	{!! Form::input('password','conf_password', null, [ 'class' => 'form-control commeonfor','id' => 'conf_password', 'placeholder' => 'Enter Confirm Pasword'])!!}
							 @if ($errors->has('email'))
								<span class="help-block">
									{{ $errors->first('conf_password') }}
								</span>
							@endif
                        </div>

                         <div class="form-group gapform">
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
@push('js')
{!!Html::script('design/js/jquery.validate.js')!!}
<script>
$(function () {

	$.validator.addMethod("passwordRegex", function (value, element) {
        return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z\d=!@#$%^&*()_*]{8,12}$/.test(value);
    }, "Must contain at least one number and one uppercase and lowercase letter and one special character, and at least 8 or maximum 12");

  
    $("#password-form").validate({

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
	  
	   			old_password : {
      			 
      				 required: true,

      			},

			    password : {
      			 
      				 required: true,
      				 passwordRegex : true
      				
      			},
      			conf_password : {
      			
      				required: true,
      				equalTo: '#password'
      				
      			},

          }

    });

    

  });

</script>

@endpush