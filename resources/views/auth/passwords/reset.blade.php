@extends('layouts.front-auth')

@section('meta') 

<title>Reset Password</title>

@endsection

@section('content')

<div class="innerbanner">



  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>



  <h2 class="upptext">Reset Password</h2>



</div>





<div class="gap"></div>

<div class="container">



  <div class="text-center">

    <h3 class="heading">Reset Password</h3>

  </div>



  <div class="col-sm-8 col-sm-offset-2">



    <div class="bglog">

	  @component('elements.flash') @endcomponent 

     <form class="form-horizontal" role="form" method="POST" id="reset-password-form" action="{{ route('password.request') }}">

		{{ csrf_field() }}

		<input type="hidden" name="token" value="{{ $token }}">



        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

          <label for="email"><span><img src="{!! asset('design/images/formmail.png') !!}" alt=""/></span>Email:</label>

		  

		  <input id="email" type="email" class="form-control editform" name="email" value="{{ $email or old('email') }}" required>

			@if ($errors->has('email'))

				<span class="help-block">

					{{ $errors->first('email') }}

				</span>

			@endif        

        </div>



        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

          <label for="password"><span><img src="{!! asset('design/images/formlock.png') !!}" alt=""/></span>Password:</label>

		  <input id="password" type="password" class="form-control editform" name="password" required>

			@if ($errors->has('password'))

				<span class="help-block">

					{{ $errors->first('password') }}

				</span>

			@endif

        </div>



		<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

				  <label for="password"><span><img src="{!! asset('design/images/formlock.png') !!}" alt=""/></span>Rewrite Password:</label>

				   <input id="password-confirm" type="password" class="form-control editform" name="password_confirmation" required>

					 @if ($errors->has('password_confirmation'))

						<span class="help-block">

							{{ $errors->first('password_confirmation') }}

						</span>

					@endif

				</div>



        <div class="form-group">

          

          <input class="gradiv mainlog" type="submit" value="Update">



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

	  

   $.validator.addMethod("passwordRegex", function (value, element) {

        return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z\d=!@#$%^&*()_*]{8,12}$/.test(value);

    }, "Must contain at least one number and one uppercase and lowercase letter and one special character, and at least 8 or maximum 12");

  

    $("#reset-password-form").validate({



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



             },

			 password : {

			 

				 required: true,

				 passwordRegex : true

				

			 },

			 password_confirmation : {

			

				required: true,

				equalTo: '#password-confirm'

				

			}



          },

	    messages: {



            email: {

                

                required: "Please enter an email"



             },

      		 password: {

                      

                  required: "Please enter a password"



              },

			 password_confirmation: {

                      

                  required: "Please enter a confirmation password"



              }



          }



    });



    



  });



</script>



@endpush('js')