@php ($id=session()->get('xstrID'))

<script src="https://js.stripe.com/v3/"></script>

<script>
// Create a Stripe client.
var stripe = Stripe('{{ env('STRIPE_PUBLISH_KEY') }}');
stripe.redirectToCheckout({
  // Make the id field from the Checkout Session creation API response
  // available to this file, so you can provide it as parameter here

  sessionId:'{{ $id }}'

}).then(function (result) {

  console.log(result);
  // If `redirectToCheckout` fails due to a browser or network
  // error, display the localized error message to your customer
  // using `result.error.message`.
});
</script>