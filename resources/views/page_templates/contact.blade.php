@extends('layouts.front')
@section('content')

<div class="innerbanner">

@if($page->image)
  <img class="fullimage" src="{!! asset('uploads/'.$page->image) !!}" alt="{{ $page->title }}"/>
@else
  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt="inner-banner"/>
@endif
  <h2 class="upptext">{{ $page->title }}</h2>


  <div class="breadcr">
    <ul>
      <li><a href="{{ route('site') }}">Home</a></li>
      <li><i class="fas fa-caret-right"></i></li>
      <li>{{ $page->title }}</li>
    </ul>
  </div>

</div>
<div class="gap"></div>
<div class="container">

  <div class="text-center">
    {!! get_post_meta($page->id,'contact-heading') !!}
  </div>

  <div class="gap"></div>

  <div class="threesec">

<div class="row">
	{!! $page->content !!}
</div>
  </div>
<div class="gap"></div>

<div class="text-center wow animated slideInUp" data-wow-duration="1" data-wow-delay="0.4s">
    <h3 class="heading">How can we help you?</h3>
  </div>

  <div class="col-md-8 col-md-offset-2 wow animated slideInUp" data-wow-duration="1.5s" data-wow-delay="0.4s">
	  <div id="contact-flash"></div>
      <form id="contact-form">
	   {{ csrf_field() }}

        <div class="row">

          <div class="col-sm-6">
          <div class="form-group">
            <input type="text" class="form-control formdit" name="fname" id="fname" placeholder="First Name">
          </div>
          </div>

          <div class="col-sm-6">
          <div class="form-group">
            <input type="text" class="form-control formdit" name="lname" id="lname" placeholder="Last Name">
          </div>
          </div>

          <div class="col-sm-6">
          <div class="form-group">
            <input type="email" class="form-control formdit" name="email" id="email" placeholder="Email">
          </div>
          </div>
          <div class="col-sm-6">
          <div class="form-group">
            <input type="text" class="form-control formdit" name="phone" id="phone" placeholder="Phone">
          </div>
          </div>

          <div class="col-sm-12">
          <div class="form-group">
            <textarea rows="6" class="form-control commedit" name="message" id="message" placeholder="Comment:"></textarea>
          </div>
          </div>

          <div class="col-sm-12">
            <div class="text-center">
              <input type="submit" class="gradiv subbtn" id="btn-submit" value="submit">
            </div>
          </div>


        </div>
      </form>

  </div>

</div>
<div class="gap"></div>

@endsection

@push('js')
{!!Html::script('design/js/jquery.validate.js')!!}
<script>
$(document).ready(function() {
$.validator.addMethod("emailRegex",function(value, element) {
        if(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value ))
        { return true;} else{ return false;}    
  },"Please enter a valid Email.");

$.validator.addMethod("nameRegex", function (value, element) {
        return this.optional(element) || /^([a-zA-Z_-\s]{3,20})$/.test(value);
    }, "Enter valid name");

        $("#contact-form").validate({
          errorElement: 'span',
          errorClass: 'help-block',
          highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
          },
          errorPlacement: function (error, element) {
             //error.insertAfter(element);
          },

            rules: {

                fname: {
					
					required: true,
					nameRegex : true
	
				 },
				 lname: {
					
					required: true,
					nameRegex : true
	
				 },
				 email: {
					
					required: true,
					emailRegex:true
	
				 },
                                 phone: {
                    
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    digits: true
    
                 },

				 message: {
					
					required: true
	
				 }

            },


             messages: {

                

            },

            submitHandler: submitForm

        });





    });


</script> 
<script>
function submitForm(){ 

var data = $("#contact-form").serialize();

$('#fname').val('');
$('#lname').val('');
$('#email').val('');
$('#phone').val('');
$('#message').val('');
$('#btn-submit').attr('disabled',true);

      $.ajax({

				type: 'post',
		
				url: "{{ route('contact.form') }}",
				
				async: false,
		
				data : data,
		
			    success :  function(response){
				
				 if(response.status === true) {

                		$("#contact-flash").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <span class="glyphicon glyphicon-ok"></span> &nbsp; ' + response.success +'</div>');
                		 setTimeout('window.location.href = self.location.href',5000);
						
					}else{
						
						$("#contact-flash").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <i class="fa fa-times" aria-hidden="true"></i> &nbsp; '+response.danger+'</div>');

						 setTimeout('window.location.href = self.location.href',5000);
					
					}
					 
            	},

		        error: function(xhr, statusText, thrownError) {
	
					$("#contact-flash").fadeIn(1000, function(){                      
						$("#contact-flash").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <i class="fa fa-times" aria-hidden="true"></i> &nbsp; '+xhr.statusText+'</div>');
					});

					 setTimeout('window.location.href = self.location.href',5000);
					
					
				},
		
				timeout : 8000  
		
		  });
	 return false;	
} 
  
</script>
@endpush
