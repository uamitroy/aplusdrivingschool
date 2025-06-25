<div class="form-body">
		
		<h3 class="card-title">Segment Info</h3>
		<hr>
		<div class="row p-t-20">
			<div class="col-md-12">
				<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
				{!! Form::label('title', 'Name',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','name', null, [ 'class' => 'form-control','id' => 'name', 'placeholder' => 'Segment 1'])!!} 
					<div class="error">
	                    @foreach ($errors->get('name') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
		</div>
</div>