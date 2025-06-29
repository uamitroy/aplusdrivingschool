<div class="form-body">
		
		<h3 class="card-title">Slot Info</h3>
		<hr>
		<div class="row p-t-20">
			<div class="col-md-12">
				<div class="form-group {{ $errors->has('segment_id') ? 'has-error' : ''}}">
				{!! Form::label('segment_id', 'Select Segment',[ 'class' => 'control-label']) !!}
				{!! Form::select('segment_id', ['' => 'Select Segment'] + $segments, $selected_segment, array('id' => 'segment_id', 'class' => 'form-control select2')) !!}
				<div class="error">
					@foreach ($errors->get('segment_id') as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
				{!! Form::label('type', 'Slot Type',[ 'class' => 'control-label']) !!}
				@php($type = ['Virtual' => 'Virtual', 'Offline' => 'Offline'])
				{!! Form::select('type', ['' => 'Select Type'] + $type, null, array('id' => 'type', 'class' => 'form-control')) !!}
					<div class="error">
						@foreach ($errors->get('type') as $error)
							<p>{{ $error }}</p>
						@endforeach
					</div>
				</div>
			</div>
			@php(  $curr_year = date("Y")) 
			@php( $year = [])
			@for($i = 0 ; $i < 3 ; $i++)
				@php ( $year[$curr_year + $i-1]= $curr_year + $i-1 )
			@endfor
			<div class="col-md-3">
				<div class="form-group {{ $errors->has('year') ? 'has-error' : ''}}">
				{!! Form::label('year', 'Year',[ 'class' => 'control-label']) !!}
				{!! Form::select('year', ['' => 'Select Year'] + $year, null, array('id' => 'year', 'class' => 'form-control select2')) !!}
					<div class="error">
						@foreach ($errors->get('year') as $error)
							<p>{{ $error }}</p>
						@endforeach
					</div>
				</div>
			</div>
			@php ($month = ['1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'])
			<div class="col-md-3">
				<div class="form-group {{ $errors->has('month') ? 'has-error' : ''}}">
				{!! Form::label('month', 'Month',[ 'class' => 'control-label']) !!}
				{!! Form::select('month', ['' => 'Select Month'] + $month, null, array('id' => 'month', 'class' => 'form-control select2')) !!}
					<div class="error">
						@foreach ($errors->get('month') as $error)
							<p>{{ $error }}</p>
						@endforeach
					</div>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group {{ $errors->has('start_time') ? 'has-error' : ''}}">
				{!! Form::label('start_time', 'Start Time',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','start_time', null, [ 'class' => 'form-control timepicker','id' => 'start_time', 'placeholder' => 'Select Start Time'])!!} 
					<div class="error">
						@foreach ($errors->get('start_time') as $error)
							<p>{{ $error }}</p>
						@endforeach
					</div>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group {{ $errors->has('end_time') ? 'has-error' : ''}}">
				{!! Form::label('end_time', 'End Time',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','end_time', null, [ 'class' => 'form-control timepicker','id' => 'end_time', 'placeholder' => 'Select End Time'])!!} 
					<div class="error">
						@foreach ($errors->get('end_time') as $error)
							<p>{{ $error }}</p>
						@endforeach
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group {{ $errors->has('dates') ? 'has-error' : ''}}">
				{!! Form::label('dates', 'Dates Selection',[ 'class' => 'control-label']) !!}
				@if(Route::currentRouteName() != 'admin.slot.edit')	
				{!! Form::input('text','dates', null, [ 'class' => 'form-control datepicker','id' => 'dates', 'placeholder' => 'Select Dates'])!!} 
				@else
				{!! Form::input('text','dates', $dates, [ 'class' => 'form-control datepicker','id' => 'dates', 'placeholder' => 'Select Dates'])!!} 
				@endif
				<div class="error">
					@foreach ($errors->get('dates') as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group {{ $errors->has('seat_allotted') ? 'has-error' : ''}}">
				{!! Form::label('seat_allotted', 'Allotted Seat',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','seat_allotted', null, [ 'class' => 'form-control','id' => 'seat_allotted', 'placeholder' => 'Total Seat'])!!} 
				<div class="error">
					@foreach ($errors->get('seat_allotted') as $error)
						<p>{{ $error }}</p>
					@endforeach
				</div>
				</div>
			</div>
		</div>
</div>

{{---Time Picker----}}

@push('custom-css')
{!!Html::style('admin-design/css/wickedpicker.min.css')!!}
{!!Html::style('admin-design/css/bootstrap-datepicker.css')!!}
@endpush
@push('custom-js')
{!!Html::script('admin-design/js/wickedpicker.min.js')!!}
{!!Html::script('admin-design/js/bootstrap-datepicker.js')!!}
<script>
	$('.timepicker').wickedpicker();
	$('.datepicker').datepicker({
	        format: 'mm/dd/yyyy',
		multidate: true,
		clearBtn: true,
		todayHighlight: true
	});
  </script>
@endpush

{{---End Time Picker----}}