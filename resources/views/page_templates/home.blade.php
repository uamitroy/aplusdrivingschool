@extends('layouts.front')
@section('content')

<div class="banner">
<!--@if($page->image)-->
<!--<img class="fullimage slide-image" src="{!! asset('uploads/'.$page->image) !!}" alt="{{ $page->title }}"/>-->
<!--@else-->
<!--<img class="fullimage slide-image" src="design/images/banner_1.jpg" alt="home-banner"/>-->
<!--@endif-->


@if($sliders)
     <div class="banner-slider owl-carousel owl-theme">
    @foreach($sliders as $slider)
    <div class="item"><img class="fullimage slide-image" src="{!! asset('uploads/'.$slider->image) !!}" alt="home-banner"/></div>
    @endforeach
    </div>
@endif
</div>
{{-- <div class="banner">
    <div class="banner-slider owl-carousel owl-theme">
    <div class="item"><img class="fullimage slide-image" src="design/images/banner_1.jpg" alt="home-banner"/></div>
       <div class="item"><img class="fullimage slide-image" src="design/images/banner_1.jpg" alt="home-banner"/></div>
  </div>
    
    </div> --}}
    


<div class="carton wow animated zoomIn" data-wow-duration="1s" data-wow-delay="0.5s">
  <a href="{{ get_slug('2') }}"><img src="design/images/cartoon.png" alt="cart"/></a>
</div>

<div class="gap"></div>

<div class="container">

  <div class="text-center">

    {!! get_post_meta($page->id,'about-heading') !!}

  </div>

<div class="gap"></div>

<div class="row">

   {!! $page->content !!}

  </div>
<div class="gap"></div>

<div class="smallarea normalpara text-center botgap60">

      {!! get_post_meta($page->id,'misc') !!}
	  
</div>

</div>

<div class="fullwvideo">
<video id="myVideo" controls>
  <source src="{!! asset('design/video/video.mp4') !!}" type="video/mp4">
</video>
</div>
<div class="gap"></div>

<div class="container">

<div class="text-center wow animated fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s">

    <h2 class="heading"><span class="gradiv padh">Our</span>  classes</h2>

</div>


@if($segments)
<div class="vk-slidedoor en-US wow animated fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s">

    <div class="slidedoor-wrap">
	
	@foreach($segments as $segment)
    
          <dl class="current">
          <dt><span><img class="fullimage" src="{!! asset('uploads/'.$segment->image) !!}" alt="{{ $segment->title }}"/></span></dt>
            <dd>
            <div class="partgray">
            <h3 class="smallhead">{{ $segment->title }}</h3>
            <div class="normalpara">
            {!! $segment->content !!}
         </div>
		<a class="gradiv readmorebtn" href="{!! get_post_meta($segment->id,'link') !!}">read more</a>
        </div>
        </dd>
      </dl>
    
      @endforeach
	      
        </div>
  </div>
@endif          

        
</div>
<div class="gap"></div>

<div class="photogallery clearfix"> 
<div class="gap"></div>

<div class="text-center">

    <h2 class="heading whitecolor">photo <span class="gradiv padh"> gallery</span></h2>

</div>

@if($photos)

<div class="owl-carousel photogall owl-theme wow animated fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s" id="photogall">

	@foreach($photos as $photo)

 	   <div class="item">
      		<img src="{!! asset('uploads/'.$photo->image) !!}" alt="{{ $photo->title }}"/>
       </div>
	
    @endforeach
    
</div>



<div class="text-center">
<a class="viewmore" href="{{ get_slug('3') }}">view more</a>
</div>
@endif 

<div class="gap"></div>
</div>

<div class="gap"></div>

@if($testimonials)

<div class="container">

  <div class="text-center">

    <h2 class="heading"><span class="gradiv padh">client</span> says</h2>

</div>

  <div class="owl-carousel testimo owl-theme wow animated fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s">

	@foreach($testimonials as $testimonial)
	
  	  <div class="item">

        <div class="testibg">

          <div class="maintesti">

            <div class="roundedimage">
              <div class="mainimage"><img src="{!! asset('uploads/'.$testimonial->image) !!}" alt="{{ $segment->title }}"></div>
            </div>

            <div class=" text-center textonly">
            <div class="normalpara">
              {!! $testimonial->content !!}
            </div>

            <h5>{{ $testimonial->title }}</h5>
            <h6>{!! get_post_meta($testimonial->id,'designation') !!}</h6>

            </div>

          </div>

        </div>

    </div>
	 
	@endforeach
    
</div>

</div>

@endif
<div class="gap"></div>

@endsection


