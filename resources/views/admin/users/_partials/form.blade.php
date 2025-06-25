<div class="form-body">
		@if(Route::currentRouteName() != 'admin.user.change_password')	
		<h3 class="card-title">Person Info</h3>
		<hr>
		<div class="row p-t-20">
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('fname') ? 'has-error' : ''}}">
				{!! Form::label('fname', 'First Name',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','fname', null, [ 'class' => 'form-control','id' => 'fname', 'placeholder' => 'e.g John'])!!} 
					<div class="error">
	                    @foreach ($errors->get('fname') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('lname') ? 'has-error' : ''}}">
				{!! Form::label('lname', 'Last Name',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','lname', null, [ 'class' => 'form-control','id' => 'lname', 'placeholder' => 'e.g Doe'])!!} 
					<div class="error">
	                    @foreach ($errors->get('lname') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
				{!! Form::label('email', 'Email',[ 'class' => 'control-label']) !!}
				{!! Form::input('email','email', null, [ 'class' => 'form-control','id' => 'email', 'placeholder' => 'e.g demo@gmail.com'])!!} 
					<div class="error">
	                    @foreach ($errors->get('email') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
				{!! Form::label('phone', 'Phone',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','phone', null, [ 'class' => 'form-control','id' => 'phone', 'placeholder' => 'e.g 8981237966'])!!} 
					<div class="error">
	                    @foreach ($errors->get('phone') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('dob') ? 'has-error' : ''}}">
				{!! Form::label('dob', 'D.O.B',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','dob', null, [ 'class' => 'form-control datepicker','id' => 'dob', 'placeholder' => '2012/12/31'])!!} 
					<div class="error">
	                    @foreach ($errors->get('dob') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
				{!! Form::label('address', 'Address',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','address', null, [ 'class' => 'form-control','id' => 'address', 'placeholder' => 'e.g lorem ipsum'])!!} 
					<div class="error">
	                    @foreach ($errors->get('address') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('guardian_name') ? 'has-error' : ''}}">
				{!! Form::label('guardian_name', 'Guardian Name',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','guardian_name', null, [ 'class' => 'form-control','id' => 'guardian_name', 'placeholder' => 'e.g lorem ipsum'])!!} 
					<div class="error">
	                    @foreach ($errors->get('guardian_name') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('guardian_phone') ? 'has-error' : ''}}">
				{!! Form::label('guardian_phone', 'Guardian Phone',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','guardian_phone', null, [ 'class' => 'form-control','id' => 'guardian_phone', 'placeholder' => 'e.g lorem ipsum'])!!} 
					<div class="error">
	                    @foreach ($errors->get('guardian_phone') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group {{ $errors->has('guardian_address') ? 'has-error' : ''}}">
				{!! Form::label('guardian_address', 'Guardian Address',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','guardian_address', null, [ 'class' => 'form-control','id' => 'guardian_address', 'placeholder' => 'e.g lorem ipsum'])!!} 
					<div class="error">
	                    @foreach ($errors->get('guardian_address') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
		</div>
		@if(Route::currentRouteName() != 'admin.user.create')
		<h3 class="box-title m-t-40">Image</h3>
	 <hr>
	 <div class="row">
		<div class="col-md-12">
		<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
			<label class="control-label">Profile Picture</label>
			<div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 100px; height: 100px;"><img src="{!! asset('uploads/'.$user->image) !!}" alt="" /></div>
                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: auto; line-height: 20px;"></div>
                <div>
                    <span class="btn btn-file btn-primary btn-fill btn-sm"><span class="fileupload-new">Select new image</span>
                        <span class="fileupload-exists">Change</span>
                        {!! Form::input('file', 'image', null) !!}
                    	</span>
                    <a href="#" class="btn btn-danger btn-fill btn-sm fileupload-exists" data-dismiss="fileupload">Remove</a>
                </div>
            </div>
		            <div class="error">
						@foreach ($errors->get('image') as $error)
		                	<p>{{ $error }}</p>
		                @endforeach	
			        </div>	
       	</div>
	   	</div>
		</div>
		@endif
		@endif
		@if(Route::currentRouteName() != 'admin.user.edit')	
		<h3 class="card-title">Password</h3>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
 			    {!! Form::label('password', 'Password',[ 'class' => 'control-label']) !!}
			    {!! Form::input('password','password', null, [ 'class' => 'form-control','id' => 'password', 'placeholder' => ''])!!}
				    <div class="error">
	                    @foreach ($errors->get('password') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group {{ $errors->has('conf_password') ? 'has-error' : ''}}">
				{!! Form::label('conf_password', 'Confirm Password',[ 'class' => 'control-label']) !!}
			    {!! Form::input('password','conf_password', null, [ 'class' => 'form-control','id' => 'conf_password', 'placeholder' => ''])!!}
				    <div class="error">
	                    @foreach ($errors->get('conf_password') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
				</div>
			</div>
		</div>
		@endif
</div>
@push('custom-css')
{!!Html::style('admin-design/css/bootstrap-datepicker.css')!!}
@endpush
@push('custom-js')
{!!Html::script('admin-design/js/bootstrap-datepicker.js')!!}
<script>
	$('.datepicker').datepicker({
		format: 'mm/dd/yyyy',
		clearBtn: true,
		todayHighlight: true
	});
</script>
@endpush