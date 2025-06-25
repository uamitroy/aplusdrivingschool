@extends('layouts.front-auth')
@section('meta') 
<title>Dashboard</title>
@endsection
@section('content')
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">Dashboard</h2>

</div>
<div class="gap"></div>
<div class="container">

  <div class="accoutab">

   <div class="row"> 

  <div class="col-md-4 col-sm-5">
    <div class="bgtab">
       @include('elements.users.nav')
    </div>
  </div>
@if($detail->segment->id == 1 || $detail->segment->id == 2)
@if($detail->slot->month == 1)
	@php( $month = 'January')
@endif 
@if($detail->slot->month == 2)
	@php( $month = 'February')
@endif 
@if($detail->slot->month == 3)
	@php( $month = 'March')
@endif 
@if($detail->slot->month == 4)
	@php( $month = 'April')
@endif 
@if($detail->slot->month == 5)
	@php( $month = 'May')
@endif 
@if($detail->slot->month == 6)
	@php( $month = 'June')
@endif 
@if($detail->slot->month == 7)
	@php( $month = 'July')
@endif 
@if($detail->slot->month == 8)
	@php( $month = 'August')
@endif 
@if($detail->slot->month == 9)
	@php( $month = 'September')
@endif 
@if($detail->slot->month == 10)
	@php( $month = 'October')
@endif 
@if($detail->slot->month == 11)
	@php( $month = 'November')
@endif   
@if($detail->slot->month == 12)
	@php( $month = 'December')
@endif 
@endif

@if($detail->segment->id == 2 && $detail->slot_id == 0)
	@php($isCombo = true)
@else
	@php($isCombo = false)
@endif
  
  <div class="col-md-8 col-sm-7">

    <div class="righttab">
    	@if($isCombo == false)
		<div class="tabdettext">
			<div class="text-center">
			<h3 class="heading">{{ $detail->segment->name }} class - <span class="yecolor">{{ $detail->package->name }}</span></h3>
			</div>
			
			@if($detail->segment->id == 1 || $detail->segment->id == 2)
			<div class="gaptab">
			
			<h3 class="smallhead">Classes in {{ $month }} {{ $detail->slot->year }}</h3>
			
			</div>
			@endif
			
			<div class="grayBg">
			
			<div class="row">
			
				<p>{!! $detail->package->description !!}</p>
				<br/>
				<p>Price : $ {{ $detail->amount }}</p>
				 <br/>
			@if($detail->segment->id == 1 || $detail->segment->id == 2)
			
			<div class="col-sm-4">
			
			<div class="segdetail clearfix">
			
				  <div class="segicon">
					<img src="{!! asset('design/images/gradi_1.png') !!}" alt="">
				  </div>
			
				  <div class="textseg">
			
					<h4>year:</h4>
			
					<ul>
					  <li>{{ $detail->slot->year }}</li>
					</ul>
			
				  </div>
			
				</div>
				
			</div>
			
			<div class="col-sm-4">
				
			<div class="segdetail clearfix">
			
				  <div class="segicon">
					<img src="{!! asset('design/images/gradi_2.png') !!}" alt="">
				  </div>
			
				  <div class="textseg">
			
					<h4>month:</h4>
				
					<ul>
					  <li>{{ $month }}</li>
					</ul>
			
				  </div>
			
				</div>
			
			</div>
			
			<div class="col-sm-4">
				
			<div class="segdetail clearfix">
			
				  <div class="segicon">
					<img src="{!! asset('design/images/gradi_1.png') !!}" alt="">
				  </div>
			
				  <div class="textseg">
			
					<h4>Time:</h4>
			
					<ul>
					  <li>{{ $detail->slot->start_time }} - {{ $detail->slot->end_time }}</li>
					</ul>
			
				  </div>
			
				</div>
			
			</div>
			
			<div class="col-sm-12">
			
			<div class="segdetail clearfix">
			
			  <div class="segicon">
				<img src="{!! asset('design/images/gradi_2.png') !!}" alt="">
			  </div>
			
			  <div class="textseg">
			
				<h4>Date:</h4>
			
				@php( $date_array = explode(",",$detail->slot->dates))
				<ul>
				  @for($dt = 0 ; $dt < count($date_array); $dt++)
					<li>{{ $date_array[$dt] }}</li>
				  @endfor
				</ul>
			
			  </div>
			
			</div>
			
			</div>
			
			@endif
			
			
			</div>
			
			</div>
		</div>
		@else

		<?php
			if(isset($_GET['year']) && $_GET['year'] != ''){
				$year = $_GET['year'];
				if($year == date('Y', strtotime('+1 year'))){
					$curr_yr = date('Y', strtotime('+1 year'));
					$prev_link = URL::current();
					$next_link = 'javascript:void(0)';
				}else{
					$url = route('booked.class.details',$detail->id);
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
			@php($isSlotAvailable = isThereAnySlot($detail->segment_id,$curr_yr,$i))
			@php($active_class = ($curr_month == $months[$i]) ? 'active ' : '')
                        @php($inactive_class = (($curr_yr == date('Y') && $curr_month_num > $i) || !$isSlotAvailable) ? 'tabdisabled' : '')
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
	    	  	
	    	  	@php($slots = getSlots($detail->segment_id,$curr_yr,$i))
		  		@forelse($slots as $slot)
	          	<div class="grayBg {{ $slot['type'] }}">

		            <div class="row">

		                <div class="col-sm-4">

			                <div class="segdetail clearfix">

			                  <div class="segicon">
			                    <img src="{!! asset('design/images/gradi_1.png') !!}" alt="">
			                  </div>

			                  <div class="textseg">

			                    <h4>Time:</h4>

			                    <ul>
			                      <li>{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</li>
			                    </ul>

			                  </div>

			                </div>

			                <div class="segdetail clearfix">

			                  <div class="segicon">
			                    <img src="{!! asset('design/images/gradi_3.png') !!}" alt="">
			                  </div>

			                  <div class="textseg">

			                    <h4>no. of student enrollment:</h4>

			                    <ul>
			                      <li><b>{{ $slot['enrolled'] }}</b></li>
			                    </ul>

			                  </div>

			                </div>

			            </div>

					<div class="col-sm-8">

					<div class="segdetail clearfix">

					  <div class="segicon">
					    <img src="{!! asset('design/images/gradi_2.png') !!}" alt="">
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

					<div class="segdetail clearfix">

					  <div class="textseg">

					    <h4>{{ $slot['seat_allotted'] - $slot['enrolled'] }} seat available</h4>

					  </div>

					</div>


					</div>

		            </div>


		            <div class="row">
					  <div class="col-md-6">
		                <div class="segprice text-center firstcolorbor">
		                  <div class="headprice">
		                    <h2>{{ $detail->package->name }}</h2>
		                  </div>
		                  <div class="bgtext">
		                    <h3>$50</h3>
		                  </div>
		                  <div class="lowar">
		                    <p>{!! $detail->package->description !!}</p>

							@if($slot['seat_allotted'] - $slot['enrolled'] > 0)

								{!! Form::open([ 'route'=> ['select.combo.pack'],'autocomplete'=>'off', 'method' => 'POST']) !!}
								{!! Form::hidden('detail',$detail->id) !!}
								{!! Form::hidden('slot',$slot['id']) !!} 
		                    	{!! Form::button('Select Now',['type' => 'submit', "class" => "bookbtn"]) !!}

		                    	{!! Form::close() !!}

							@else

								<a class="bookbtn" onclick="alert('seat is not available')" href="javascript:void(0)">Select Now</a>

							@endif

							
		                  </div>
		                </div>
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

			@endif
		</div>
  </div>

</div>

</div>
</div>
<div class="gap"></div>
@endsection
@component('elements.users.components.flash') @endcomponent
