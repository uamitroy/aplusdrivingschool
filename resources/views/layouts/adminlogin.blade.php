<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{!! asset('uploads/'.$setting->favicon) !!}" type="image/png">

    <title>{{ $setting->title }} | @yield('title')</title>


    {!!Html::style('admin-design/css/bootstrap.min.css')!!}
    {!!Html::style('admin-design/style.css')!!}
	{!!Html::style('admin-design/css/sb-admin.css')!!}
	{!!Html::style('admin-design/font-awesome/css/font-awesome.min.css')!!}
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style="background: url({!! asset('uploads/'.$setting->loginbg) !!}) no-repeat fixed center;">

   <div class="signin-form">

      @yield('content')
	  
	</div> 

    {!!Html::script('admin-design/js/jquery.js')!!}
    {!!Html::script('admin-design/js/bootstrap.min.js')!!}  

    @stack('custom-js')
	
{{-- ---Alert msg slideup--- --}}

<script>

$(function(){

 $('#information').delay(5000).slideUp();

});

</script>

{{-- ---End Alert msg slideup--- --}}


</body>

</html>
