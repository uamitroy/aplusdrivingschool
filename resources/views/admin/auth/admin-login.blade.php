@extends('layouts.adminlogin')
@section('title') Admin Login @endsection
@section('content')

<div class="container">  
        
	   {!! Form::open([ 'route'=> ['admin.login.submit'],'role' => "form", 'class'=> 'form-signin', 'id'=>'login-form', 'method' => 'POST']) !!}
	   <div class="logo-img text-center"><img src="{!! asset('uploads/'.$setting->logo) !!}" /></div>
      
        <h2 class="form-signin-heading text-center">{{ $setting->title }} Admin Login </h2><hr />
        
        <div class="row">
			<div class="col-lg-12">
			  @component('elements.admin.components.flash') @endcomponent 	
			</div>
		</div>
        
        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
					{!! Form::label('email', 'Email',[ 'class' => 'control-label']) !!}
                           
                                
							{!! Form::input('email','email', null, [ 'class' => 'form-control','id' => 'email', 'placeholder' => 'Enter email here..'])!!} 

								@if ($errors->has('email'))
                                    <span class="help-block">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            
		</div>
        
        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
					{!! Form::label('password', 'Password',['class' => 'control-label']) !!}
                            
							{!! Form::input('password','password', null, [ 'class' => 'form-control','id' => 'password', 'placeholder' => 'Enter password here..'])!!} 

								@if ($errors->has('password'))
                                    <span class="help-block">
                                        {{ $errors->first('password') }}
                                    </span>
                                @endif
                            
		</div>
		
		<div class="form-group">
			<label class="checkbox-inline">
				<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
			</label>
			{{--<a class="btn btn-link pull-right" href="{{ route('admin.password.request') }}">Forgot Your Password?</a>--}}
        </div>
       
     	<hr />
        
        <div class="form-group">
			
				{!! Form::button('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In',['type' => 'submit', 'class' => 'btn btn-success']) !!}
			
       </div>  
      
      {!! Form::close() !!}
	    

</div>

@endsection
@push('custom-js')
{!!Html::script('admin-design/js/jquery.validate.js')!!}
<script>
$(function () {

  $.validator.addMethod("emailRegex", function(value, element) {
        return this.optional(element) || /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(value); 
      }, "Please enter a valid email");
   
    $("#login-form").validate({

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


             email: {
                
                	required: true,
					emailRegex: true

             },
      		 password: {
                      
                  	required: true

              }

          },
	  messages: {

             email: {
                
                	required: "Please enter email"

             },
      		 password: {
                      
                  required: "Please enter password"

              }

          }

    });

    

  });

</script>

@endpush

