<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, inital-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="csrf-token" content="{{ csrf_token() }}">
@yield('meta')
<link rel="shortcut icon" type="image/x-icon" href="{!! asset('uploads/'.$setting->favicon) !!}">
{!!Html::style('design/css/bootstrap.min.css')!!}
{!!Html::style('design/css/animate.css')!!}
{!!Html::style('design/css/owl.carousel.css')!!}
{!!Html::style('design/css/owl.carousel.min.css')!!}
{!!Html::style('design/css/owl.theme.default.css')!!}
{!!Html::style('design/css/jBox.css')!!}
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
{!!Html::style('design/style.css')!!}
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
@stack('css')
</head>
<body data-spy="scroll" data-target=".navbar" data-offset="10">
	@include('elements.header')
	@yield('content')  
	@include('elements.footer')
{!!Html::script('design/js/jquery.min.js')!!}
{!!Html::script('design/js/bootstrap.min.js')!!}
{!!Html::script('design/js/jquery.easeScroll.js')!!}
{!!Html::script('design/js/owl.carousel.min.js')!!}
{!!Html::script('design/js/owl.carousel.js')!!}
{!!Html::script('design/js/banner.js')!!}
{!!Html::script('design/js/vk-slidedoor.js')!!}
{!!Html::script('design/js/jquery.mosaicflow.js')!!}
{!!Html::script('design/js/jBox-min.js')!!}
{!!Html::script('design/js/wow.min.js')!!}
{!!Html::script('design/js/custome.js')!!}
{!!Html::script('design/js/chartist.min.js')!!}
{!!Html::script('design/js/bootstrap-notify.js')!!}
{!!Html::script('design/js/light-bootstrap-dashboard.js')!!}
{!!Html::script('design/js/demo.js')!!}
@stack('js')
<script>
$(function () {
    var url = window.location;
    var home_url = "{{ url('/') }}";
    if( url == (home_url+'/')) {
    $('#myNavbar').find('li:first').addClass('active');  
    } else {
    $('#myNavbar a[href="' + url + '"]').parent('li').addClass('active');
    }
});
</script>
</body>
</html>
