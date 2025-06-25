@if($setting->tagline)
<style>
  .header-top
  {
    background: #91160c;
    padding: 7px 0;
    color: #fff;
  }
  .green {
    color: #63F700;
    font-weight: 600;
    text-transform: uppercase;
  }
  .blue {
    color: #F7F700;
    text-transform: uppercase;
  }
</style>
<div class="header-top">
 <!-- <div class="container"> -->
    <marquee direction="left" style="font-size: 16px;">{!! $setting->tagline !!}</marquee>
  <!--</div>-->
</div>
@endif
<header>
    
	<div class="container">
      
    	<div class="row">
        
        <div class="col-sm-8">
          
          <div class="headleft">

            {!! $header_contact_info->content or '' !!}

          </div>

        </div>

        <div class="col-sm-4">

            <div class="text-right headbtn">
				
			  @guest
              <a class="loginbtn gradiv" href="{{ route('login') }}"><span><img src="{!! asset('design/images/login.png') !!}" alt=""/></span>LogIn</a>
              <a class="registerbtn" href="{{ route('register') }}"><span><img src="{!! asset('design/images/reg.png') !!}" alt=""/></span>Register</a>
			  @else
			  <a class="loginbtn gradiv" href="{{ url('home') }}"><span><img src="{!! asset('design/images/login.png') !!}" alt=""/></span>Hi {{ Auth::user()->fname }}</a>
              <a class="registerbtn" href="{{ route('users.logout') }}">Logout</a>
			  @endguest

            </div>

        </div>
    
    </div>

  </div>

</header>
<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="{{ route('site') }}"><img src="{!! asset('uploads/'.$setting->logo) !!}" alt="{{ $setting->title }}"/></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
	@php ($header_menu =  header_menu())
	@foreach ($header_menu as $tree_view)
        {!! $tree_view !!}
    @endforeach      
    </div>
  </div>
</nav>