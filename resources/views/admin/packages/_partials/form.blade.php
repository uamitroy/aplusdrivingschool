<div class="form-body">

		

		<h3 class="card-title">Package Info</h3>

		<hr>

		<div class="row p-t-20">

			<div class="col-md-12">

				<div class="form-group {{ $errors->has('segment_id') ? 'has-error' : ''}}">

				{!! Form::label('segment_id', 'Select Segment',[ 'class' => 'control-label']) !!}

				{!! Form::select('segment_id', ['' => 'Select Segment'] + $segments, $selected_segment, array('id' => 'segment_id', 'class' => 'form-control')) !!}

				<div class="error">

					@foreach ($errors->get('segment_id') as $error)

						<p>{{ $error }}</p>

					@endforeach

				</div>

				</div>

			</div>

			<div class="col-md-12">

				<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">

				{!! Form::label('title', 'Name',[ 'class' => 'control-label']) !!}

				{!! Form::input('text','name', null, [ 'class' => 'form-control','id' => 'name', 'placeholder' => 'Enter Package name'])!!} 

					<div class="error">

	                    @foreach ($errors->get('name') as $error)

	                    	<p>{{ $error }}</p>

	                    @endforeach

	                </div>

				</div>

			</div>

			<div class="col-md-12">

				<div class="form-group {{ $errors->has('price') ? 'has-error' : ''}}">

				{!! Form::label('price', 'Price',[ 'class' => 'control-label']) !!}

				{!! Form::input('text','price', null, [ 'class' => 'form-control','id' => 'price', 'placeholder' => 'Enetr Price'])!!} 

					<div class="error">

	                    @foreach ($errors->get('price') as $error)

	                    	<p>{{ $error }}</p>

	                    @endforeach

	                </div>

				</div>

			</div>

			<div class="col-md-12">

				<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">

				{!! Form::label('description', 'Package Description',[ 'class' => 'control-label']) !!}

				{!! Form::textarea('description', null, ['id' => 'description', 'class' => 'ckeditor']) !!} 

				</div>

			</div>

		</div>

</div>