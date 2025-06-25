@extends('layouts.front-auth')
@section('meta') 
<title>Payment By Stripe</title>
@endsection
@section('content') 
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">Booking Confimation</h2>

  <div class="breadcr">
    <ul>
      <li><a href="{{ route('site') }}">Home</a></li>
      <li><i class="fas fa-caret-right"></i></li>
      <li>Payment By Stripe</li>
    </ul>
  </div>

</div>
<div class="gap"></div>
	<div class="container">
	
	 @component('elements.flash') @endcomponent
	
	  <div class="text-center">
		<h3 class="heading">Payment By Stripe</h3>
	  </div>
	
			<div class='col-md-4'></div>
			<div class='col-md-4'>
				<form accept-charset="UTF-8" action="{{ route('stripe.payment.custom') }}" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_PUBLISH_KEY') }}" id="payment-form" method="post">
					{{ csrf_field() }}
					{!! Form::hidden('hash', $hash) !!}
					{!! Form::hidden('price', $price) !!}
					<div class='form-row'>
						<div class='col-xs-12 form-group required'>
							<label class='control-label'>Name on Card</label> <input
								class='form-control card-name' size='4' type='text'>
						</div>
					</div>
					<div class='form-row'>
						<div class='col-xs-12 form-group card required'>
							<label class='control-label'>Card Number</label> <input
								autocomplete='off' class='form-control card-number' size='20'
								type='text'>
						</div>
					</div>
					<div class='form-row'>
						<div class='col-xs-4 form-group cvc required'>
							<label class='control-label'>CVC</label> <input
								autocomplete='off' class='form-control card-cvc'
								placeholder='ex. 311' size='4' type='text'>
						</div>
						<div class='col-xs-4 form-group expiration required'>
							<label class='control-label'>Expiration</label> <input
								class='form-control card-expiry-month' placeholder='MM' size='2'
								type='text'>
						</div>
						<div class='col-xs-4 form-group expiration required'>
							<label class='control-label'> </label> <input
								class='form-control card-expiry-year' placeholder='YYYY'
								size='4' type='text'>
						</div>
					</div>
					<div class='form-row'>
						<div class='col-md-12'>
							<div class='form-control total btn btn-info'>
								Total: <span class='amount'>${{ $price }}</span>
							</div>
						</div>
					</div>
					<div class='form-row'>
						<div class='col-md-12 form-group'>
							<button class='form-control btn btn-primary submit-button'
								type='submit' style="margin-top: 10px;">Pay »</button>
						</div>
					</div>
					<div class='form-row'>
						<div class='col-md-12 error form-group hide'>
							<div class='alert-danger alert'>Please correct the errors and try
								again.</div>
						</div>
					</div>
				</form>
			</div>
			<div class='col-md-4'></div>
		  </div>
		
		 
	
<div class="gap"></div>
@endsection

@push('js')
<script src='https://js.stripe.com/v2/' type='text/javascript'></script>
<script>
$(function() {
	  $('form.require-validation').bind('submit', function(e) {
		var $form         = $(e.target).closest('form'),
			inputSelector = ['input[type=email]', 'input[type=password]',
							 'input[type=text]', 'input[type=file]',
							 'textarea'].join(', '),
			$inputs       = $form.find('.required').find(inputSelector),
			$errorMessage = $form.find('div.error'),
			valid         = true;

		$errorMessage.addClass('hide');
		$('.has-error').removeClass('has-error');
		$inputs.each(function(i, el) {
		  var $input = $(el);
		  if ($input.val() === '') {
			$input.parent().addClass('has-error');
			$errorMessage.removeClass('hide');
			e.preventDefault(); // cancel on first error
		  }
		});
	  });
	});

	$(function() {
	  var $form = $("#payment-form");

	  $form.on('submit', function(e) {
		if (!$form.data('cc-on-file')) {
		  e.preventDefault();
		  Stripe.setPublishableKey($form.data('stripe-publishable-key'));
		  Stripe.createToken({
                        name : $('.card-name').val(),
			number: $('.card-number').val(),
			cvc: $('.card-cvc').val(),
			exp_month: $('.card-expiry-month').val(),
			exp_year: $('.card-expiry-year').val()
		  }, stripeResponseHandler);
		}
	  });

	  function stripeResponseHandler(status, response) {
	    console.log(response);
		console.log(status);
		if (response.error) {
		  $('.error')
			.removeClass('hide')
			.find('.alert')
			.text(response.error.message);
		} else {
		  // token contains id, last4, and card type
		  var token = response['id'];
		  // insert the token into the form so it gets submitted to the server
		  $form.find('input[type=text]').empty();
		  $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
		  $form.get(0).submit();
		}
	  }
	})
</script>
@endpush




