<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, inital-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Payment By Paypal</title>
<style>
    .loaderGif
    {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 9999;
        background-color: rgba(225, 225, 225,0.3);
        background-repeat: no-repeat;
        background-position: center center;
        background-image: url({!! asset("design/images/loader.gif") !!});
    }
</style>
</head>
<body>
<h4>This page is redirect to the paypal. Do not back or refresh this page....</h4>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="myPaypalform" id="myPaypalform">
    <!-- Identify your business so that you can collect the payments. -->
    <input type="hidden" name="business" value="kwmaxnimer@gmail.com">
    <!-- Specify a Buy Now button. -->
    <input type="hidden" name="cmd" value="_xclick">
    <!-- Specify details about the item that buyers will purchase. -->
    <input type="hidden" name="item_name" value="{{ $name }}">
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" name="amount" value="{{ $price }}">
    <input type="hidden" name="currency_code" value="{{ $currency_code }}">
    <input type="hidden" name="custom" value="{{ $newhash }}">
    <!-- Specify URLs -->
    <input type='hidden' name='return' value="{{ route('paypal.success') }}">
    <input type='hidden' name='cancel_return' value="{{ route('paypal.cancel') }}">
</form>
<div class="loaderGif" id="payment_loder_img"></div>
<script type="text/javascript">
    document.getElementById("myPaypalform").submit();
</script>
</body>
</html>




