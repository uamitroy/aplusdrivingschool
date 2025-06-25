@extends('layouts.front-auth')

@section('meta') 

<title>Login</title>

@endsection

@section('content')

<div class="innerbanner">



  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>



  <h2 class="upptext">login</h2>



</div>





<div class="gap"></div>

<div class="container">



  <div class="text-center">

    <h3 class="heading">already have an account?</h3>

  </div>



  <div class="col-sm-8 col-sm-offset-2">



    <div class="bglog">

	  @component('elements.flash') @endcomponent 

      <form class="form-horizontal" role="form" method="POST" id="login-form" action="{{ route('login') }}">

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



        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

          <label for="password"><span><img src="{!! asset('design/images/formlock.png') !!}" alt=""/></span>Password:</label>

          <input type="password" class="form-control editform" id="password" name="password" required>

		  @if ($errors->has('password'))

			<span class="help-block">

				{{ $errors->first('password') }}

			</span>

		 @endif

        </div>



        <div class="form-group whiteform">



          <div class="pull-left">

          <input class="checkdesi" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}><p>Remember me</p>

          </div>



          <div class="text-right">

            <a class="forgot" href="{{ route('password.request') }}"><h5 class="forgotpw">Forgot password? Reset</h5></a>

          </div>



        </div>



        <div class="form-group">

          

          <input class="gradiv mainlog" type="submit" value="LogIn">



        </div>

         <div class="text-right">
            <a class="forgot" href="{{ route('register') }}"><h5 class="forgotpw">Don&apos;t have account ? Register Now</h5></a>
        </div>



    </form>



    </div>



  </div>



</div>

<div class="gap"></div>

@endsection

@push('js')

{!!Html::script('design/js/jquery.validate.js')!!}

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

