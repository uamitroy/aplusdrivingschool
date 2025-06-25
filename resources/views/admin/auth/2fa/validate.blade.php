@extends('layouts.adminlogin')
@section('title') 2FA @endsection
@section('content')
<div class="container">  
        
	   {!! Form::open([ 'route'=> ['admin.2fa.post.validate'],'role' => "form", 'class'=> 'form-signin', 'id'=>'login-2fa-form', 'method' => 'POST']) !!}
	   <div class="logo-img text-center"><img src="{!! asset('uploads/'.$setting->logo) !!}" /></div>
      
        <h2 class="form-signin-heading text-center">2 Factor Authentication</h2><hr />
        
        <div class="row">
			<div class="col-lg-12">
			  @component('elements.admin.components.flash') @endcomponent 	
			</div>
		</div>
        
        <div class="form-group {{ $errors->has('totp') ? ' has-error' : '' }}">
			{!! Form::label('totp', 'One Time Password',[ 'class' => 'control-label']) !!}
                           
			{!! Form::input('text','totp', null, [ 'class' => 'form-control','id' => 'totp', 'placeholder' => 'Enter otp here..'])!!} 

				@if ($errors->has('totp'))
					<span class="help-block">
						{{ $errors->first('totp') }}
					</span>
				@endif
                            
		</div>
        
     	<hr />
        
        <div class="form-group">
			
				{!! Form::button('<i class="fa fa-btn fa-mobile"></i> &nbsp; Validate',['type' => 'submit', 'class' => 'btn btn-success']) !!}
			
       </div>  
      
      {!! Form::close() !!}
	    

</div>
@endsection
@push('custom-js')
{!!Html::script('admin-design/js/jquery.validate.js')!!}
<script>
$(function () {
   
    $("#login-2fa-form").validate({

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


             totp: {
					required: true,
					digits: true,
					maxlength: 6,
					minlength: 6
             }
      		 
          },
	  messages: {

             totp: {
                
                	required: "Please enter otp in your google authenticator mobile app",
					digits: "Please enter digits only",
					maxlength : "Please enter 6 digits only",
					minlength : "Please enter 6 digits only"

             }

          }

    });

    

  });

</script>

@endpush
