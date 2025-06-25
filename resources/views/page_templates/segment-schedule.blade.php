@extends('layouts.front')
@section('content')
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">{{ $page->title}}</h2>

  <div class="breadcr">
    <ul>
      <li><a href="{{ route('site') }}">Home</a></li>
      <li><i class="fas fa-caret-right"></i></li>
      <li>{{ $page->title}}</li>
    </ul>
  </div>

</div>

<div class="gap"></div>
<div class="container">
<?php
if(isset($_GET['year']) && $_GET['year'] != ''){
	$year = $_GET['year'];
	if($year == date('Y', strtotime('+1 year'))){
		$curr_yr = date('Y', strtotime('+1 year'));
		$prev_link = URL::current();
		$next_link = 'javascript:void(0)';
	}else{
		$url = Request::segment(1);
		$curr_yr = date('Y');
		$prev_link = 'javascript:void(0)';
		$next_link = '?year='.date('Y', strtotime('+1 year'));
	?>
		<script type="text/javascript">window.location = "{{ url($url) }}"</script>
<?php	}
}else{
	$curr_yr = date('Y');
	$prev_link = 'javascript:void(0)';
	$next_link = '?year='.date('Y', strtotime('+1 year'));
}

?>
<div class="text-center">
    <h3 class="heading"> {!!  get_post_meta($page->id,'title') !!}</h3>
</div>	
    <div class="smallarea normalpara">
      <p>{!!  get_post_meta($page->id,'description') !!}</p>
    </div>
  
<div class="gap"></div>

<div class="segmenttab">

		@if($prev_link != 'javascript:void(0)')
        	<a class="btntableft" href="{{ $prev_link }}"><img src="{!! asset('design/images/tableft.png') !!}" alt="prev"/></a>
		@endif
			<div class="yearsec gradiv text-center"><h4>{{ $curr_yr }}</h4></div>
		@if($next_link != 'javascript:void(0)')
			<a class="btntabright" href="{{ $next_link }}"><img src="{!! asset('design/images/tabright.png') !!}" alt="next"/></a>
		@endif

		@php ($months = ['1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'])
		@php ($month_names = ['1' => "January", '2' => "February", '3' => "March",'4' => "April",'5' => "May",'6' => "June",'7' => "July",'8' => "August",'9' => "September",'10' => "October",'11' => "November",'12' => "December"])
      <ul class="nav nav-pills">
		 @for($i = 1; $i <= count($months); $i++)
		  	<?php $curr_month = date('M')?>
			<?php $curr_month_num = date('m')?>
			@php($isSlotAvailable = isThereAnySlot($segment->id,$curr_yr,$i))
			@php($active_class = ($curr_month == $months[$i]) ? 'active ' : '')
      @php($inactive_class = (!$isSlotAvailable) ? 'tabdisabled' : '')
			@if($curr_month == $months[$i])
				@php($inactive_class = '')
			@endif
			@php($anchor_tab = (!$inactive_class) ? 'month-'.$i : '')
       		 <li class="{{ $active_class }}{{ $inactive_class }}"><a data-toggle="pill" href="#{{ $anchor_tab }}">{{ $months[$i] }}</a></li>
		 @endfor

      </ul>

      <div class="tab-content">
        @for($i = 1; $i <= count($months); $i++)
		@php($active_class = ($curr_month == $months[$i]) ? 'active ' : '')
        <div id="month-{{ $i }}" class="tab-pane fade in {{ $active_class }}">

          <div class="gaptab">

            <h3 class="smallhead">Classes in {{ $month_names[$i] }} {{ $curr_yr }}</h3>

          </div>
    	  <?php $slots = getSlotsWithCurrentMonth($segment->id,$curr_yr,$i)?>
		  @forelse($slots as $slot)

          		<div class="grayBg {{ $slot['type'] }}">

            <div class="row">

              <div class="col-sm-4">

                <div class="segdetail clearfix">

                  <div class="segicon">
                    <img src="{!! asset('design/images/gradi_1.png') !!}" alt=""/>
                  </div>

                  <div class="textseg">

                    <h4>Time:</h4>

                    <ul>
                      <li>{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</li>
                    </ul>

                  </div>

                </div>

                <div class="segdetail clearfix">

                  {{--<div class="segicon">
                    <img src="{!! asset('design/images/gradi_3.png') !!}" alt=""/>
                  </div>

                  <div class="textseg">

                    <h4>no. of student enrollment:</h4>

                    <ul>
                      <li><b>{{ $slot['enrolled'] }}</b></li>
                    </ul>

                  </div>--}}

                </div>

              </div>

              <div class="col-sm-8">

                <div class="segdetail clearfix">

                  <div class="segicon">
                    <img src="{!! asset('design/images/gradi_2.png') !!}" alt=""/>
                  </div>

                  <div class="textseg">

                    <h4>Date:</h4>
    				@php( $date_array = explode(",",$slot['dates']))
                    <ul>
					  @for($dt = 0 ; $dt < count($date_array); $dt++)
                      	<li>{{ $date_array[$dt] }}</li>
					  @endfor
                    </ul>

                  </div>

                </div>

                {{--<div class="segdetail clearfix">

                  <div class="textseg">

                    <h4>{{ $slot['seat_allotted'] - $slot['enrolled'] }} seat available</h4>

                  </div>

                </div>--}}


              </div>

            </div>

		 		</div>

		  @empty

		  	<p>No Slot Available</p>

		  @endforelse

        </div>

        @endfor
      </div>

    </div>


</div>
@endsection