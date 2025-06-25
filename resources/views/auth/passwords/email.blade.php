@extends('layouts.front-auth')
@section('meta') 
<title>Send Password Reset Link</title>
@endsection
@section('content')
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">Forgot Password</h2>

</div>


<div class="gap"></div>
<div class="container">

  <div class="text-center">
    <h3 class="heading">Forgot Password</h3>
  </div>

  <div class="col-sm-8 col-sm-offset-2">

    <div class="bglog">
	  @component('elements.flash') @endcomponent 
      <form class="form-horizontal" role="form" method="POST" id="send-password-link-form" action="{{ route('password.email') }}">
      {{ csrf_field() }}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
          <label for="email"><span><img src="{!! asset('design/images/formmail.png') !!}" alt=""/></span>Email:</label>
          <input type="email" class="form-control editform" id="email" name="email" value="{{ old('email') }}" required>
		  @if ($errors->has('email'))
			<span class="help-block">
				{{ $errors->first('email') }}
			</span>
		 @endif
        </div>

        <div class="form-group">
          
          <input class="gradiv mainlog" type="submit" value="Send">

        </div>

    </form>

    </div>

  </div>

</div>
<div class="gap"></div>
@endsection
@push('js')
{!!Html::script('admin-design/js/jquery.validate.js')!!}
<script>
$(function () {

   $.validator.addMethod("emailRegex", function(value, element) {
        return this.optional(element) || /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(value); 
      }, "Please enter a valid email");
	    
    $("#send-password-link-form").validate({

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
                emailRegex:true

             }
			

          },
	    messages: {

            email: {
                
                required: "Please enter an email"

             }      		

          }

    });

    

  });

</script>

@endpush('js')