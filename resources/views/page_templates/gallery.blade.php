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
	
		{!! $page->content !!}
	
	  </div>
	<div class="gap"></div>
	
	
	<div class="mosaicflow" data-item-height-calculation="attribute">
	@php($delay = 0.5)
	@foreach($photos as $photo)
	
		<div class="mosaicflow__item wow animated slideInUp" data-wow-duration="1s" data-wow-delay="{{ $delay += 0.1 }}s">
		  <img class="jbox-img" src="{!! asset('uploads/'.$photo->image) !!}"  alt="{{ $photo->title }}">
		</div>
	
	 @endforeach
		
	  </div>
			 <div class="jbox-container">
			<div class="img-alt-text"></div>
			<img src="" />
			<svg version="1.1" class="jbox-prev" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			viewBox="0 0 306 306" xml:space="preserve">
				<g>
					<g id="chevron-right">
						<polygon points="211.7,306 247.4,270.3 130.1,153 247.4,35.7 211.7,0 58.7,153" />
					</g>
				</g>
			</svg>
			<svg version="1.1" class="jbox-next" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			viewBox="0 0 306 306" xml:space="preserve">
				<g>
					<g id="chevron-right">
						<polygon points="94.35,0 58.65,35.7 175.95,153 58.65,270.3 94.35,306 247.35,153" />
					</g>
				</g>
			</svg>
			<svg version="1.1" class="jbox-close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		   viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
				<path d="M512,51.75L460.25,0L256,204.25L51.75,0L0,51.75L204.25,256L0,460.25L51.75,512L256,307.75L460.25,512L512,460.25
	  L307.75,256L512,51.75z" />
			</svg>
	
		</div>
	
	
	
	
	
	  </div>

<div class="gap"></div>

@endsection


