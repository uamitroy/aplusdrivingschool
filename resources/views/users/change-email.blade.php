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
			
		  
		  <h5>Current Email : {{ Auth::user()->email }}</h5>
		   {!! Form::open(['autocomplete'=>'off', 'class' => 'form-horizontal pro', 'id'=>'email-form', 'method' => 'PATCH', 'route' => ['user.email']]) !!}
		   <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
				<div class="col-md-3 col-sm-4">
					<label for="email" class=" control-label">Enter Your Email :</label>
				</div>
				<div class="col-md-5 col-sm-8">
					<input type="text" name="email" id="email" class="form-control" placeholder="Enter Email"  value="{{  Auth::user()->email }}" />
				</div>
				<div class="error-php">
					@foreach ($errors->get('email') as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
			</div>
			@if( Auth::user()->email_verify_status == 0 )
				<div class="form-group">
				<div class="col-md-3 col-md-offset-2 col-sm-4">
					<label for="pass" class=" control-label"></label>
				</div>
				<div class="col-md-3 col-sm-3">
					<input type="text" name="security_code" id="security_code" class="form-control" placeholder="Enter Security Code"  value="" />
				</div>
				<div class="col-md-4 col-sm-5">
					<a class="btn confirm_btn" id="email-otp-resend">Resend</a>
					<a class="btn confirm_btn" id="verify-email">Verify</a>									
				</div>

			</div>
				<div class="form-group">
				<div class="col-md-5 col-md-offset-5">
					<div class="error-php" id="info"></div>
				</div>
				<div class="col-md-5 col-md-offset-5">
					<span class="otp-resend"><i class="fa fa-info-circle"></i> Paste the OTP sent to {{  Auth::user()->update_email }}</span>
				</div>
			</div>
			@endif
			<div class="form-group">
					<div class="col-md-3 col-md-offset-2 col-sm-4">
					</div>
					<div class="col-md-5 col-sm-8">
						<button type="submit" class="gradiv editbtn">Confirm</button>
					</div>
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

	  $.validator.addMethod("emailRegex", function(value, element) {
        return this.optional(element) || /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(value); 
      }, "Please enter a valid email");

  
    $("#email-form").validate({

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
	
					email : {
					 
						 required: true,
						 emailRegex : true
						
					},
	
			  }

    });

    

  });

</script>

<script>
  $(document).ready(function(){
  	$('#verify-email').click(function(){
			
			var token = $('input[name="_token"]').val();
			var security_code = $('#security_code').val();

 			$.ajax({

				type: 'POST',
				url:  '{{ route('user.verify.email') }}',
				data : {'_token': token,'security_code' : security_code},
				success :  function(response){
					if(response.status === true) {
						$('#security_code').closest('.form-group').removeClass("has-error");
						$("#info").html('<span class="green"><i class="fa fa-info-circle"></i> ' + response.success +'</span>');
						setTimeout('window.location = "{{ route('user.email') }}"',2000);
					} else if(response.danger.security_code){
						$('#security_code').closest('.form-group').addClass("has-error");
						if(response.danger.security_code.length == 1){
							$("#info").html('<p>'+ response.danger.security_code[0] +'</p>');
						}else{
							$("#info").html('<p>'+ response.danger.security_code[0] +'</p><p>'+ response.danger.security_code[1] +'</p>');
						}
					}else {
						$('#security_code').closest('.form-group').addClass("has-error");
						$("#info").html('<p>'+ response.danger +'</p>');
					}
				},
				error: function(xhr){
				
					  $('#security_code').closest('.form-group').addClass("has-error");
					  $("#info").html('<span>' + xhr.status + ' ' + xhr.statusText +'</span>');

				}


  			});	
	
	});
  });

</script>

<script>
  $(document).ready(function(){
  	$('#email-otp-resend').click(function(){
			
			var token = $('input[name="_token"]').val();

 			$.ajax({

				type: 'POST',
				url:  '{{ route('user.resend.otp') }}',
				data : {'_token': token},
				async:false,
				success :  function(response){
					if(response.status === true) {
					
						$("#info").html('<span class="green"><i class="fa fa-info-circle"></i> ' + response.success +'</span>');
						setTimeout('window.location = self.location.href',2000);
						
					} else {
					
						$("#info").html('<span>' + response.danger +'</span>');
						setTimeout('window.location = self.location.href',2000);
						
					}
				},
				error: function(xhr){
				
					  $("#info").html('<span>' + xhr.status + ' ' + xhr.statusText +'</span>');
					  setTimeout('window.location = self.location.href',2000);

				}


  			});	
	
	});
  });

</script>

@endpush