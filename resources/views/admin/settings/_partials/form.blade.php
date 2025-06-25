<div class="form-body">
    <h3 class="card-title">Site Info</h3>
    <hr>
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
            	{!! Form::label('title', 'Site Title',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','title', null, [ 'class' => 'form-control','id' => 'title', 'placeholder' => 'e.g Matrix Media', 'required' => 'required' ])!!} 
					<div class="error">
						@foreach ($errors->get('title') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
                </div>
        </div>
		<div class="col-md-6">
            <div class="form-group {{ $errors->has('tagline') ? 'has-error' : ''}}">
            	{!! Form::label('tagline', 'Site Tagline',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','tagline', null, [ 'class' => 'form-control','id' => 'tagline', 'placeholder' => 'e.g step ahead' ])!!} 
					<div class="error">
						@foreach ($errors->get('tagline') as $error)
	                    	<p>{{ $error }}</p>
	                    @endforeach
	                </div>
                </div>
        </div>
       
    </div>
   
     <!--/row-->
	<h3 class="box-title m-t-40">Email Settings</h3>
    <hr>
     <div class="row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('emails') ? 'has-error' : ''}}">
            	{!! Form::label('emails', 'Email',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','emails', null, [ 'class' => 'form-control','id' => 'emails', 'placeholder' => 'can put multiple email address separated by commas' ])!!} 
						<div class="error">
							@foreach ($errors->get('emails') as $error)
		                    	<p>{{ $error }}</p>
		                    @endforeach	
	                	</div>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('cc_emails') ? 'has-error' : ''}}">
            	{!! Form::label('cc_emails', 'Cc Email',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','cc_emails', null, [ 'class' => 'form-control','id' => 'cc_emails', 'placeholder' => 'can put multiple email address separated by commas' ])!!} 
            </div>
        </div>
        <!--/span-->
		<div class="col-md-12">
            <div class="form-group {{ $errors->has('bcc_emails') ? 'has-error' : ''}}">
            	{!! Form::label('bcc_emails', 'Bcc Email',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','bcc_emails', null, [ 'class' => 'form-control','id' => 'bcc_emails', 'placeholder' => 'can put multiple email address separated by commas' ])!!} 
            </div>
        </div>
        <!--/span-->
		

    </div>
    <!--/row-->
	 
	 <h3 class="box-title m-t-40">Image</h3>
	 <hr>
	 <div class="row">
		<div class="col-md-4">
		<div class="form-group {{ $errors->has('logo') ? 'has-error' : ''}}">
			<label class="control-label">Logo</label>
			<div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 100px; height: 100px;"><img src="{!! asset('uploads/'.$setting->logo) !!}" alt="" /></div>
                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: auto; line-height: 20px;"></div>
                <div>
                    <span class="btn btn-file btn-primary btn-fill btn-sm"><span class="fileupload-new">Select new image</span>
                        <span class="fileupload-exists">Change</span>
                        {!! Form::input('file', 'logo', null) !!}
                    	</span>
                    <a href="#" class="btn btn-danger btn-fill btn-sm fileupload-exists" data-dismiss="fileupload">Remove</a>
                </div>
            </div>
		            <div class="error">
						@foreach ($errors->get('logo') as $error)
		                	<p>{{ $error }}</p>
		                @endforeach	
			        </div>	
       </div>
	   </div>
	    <div class="col-md-4">
		<div class="form-group {{ $errors->has('favicon') ? 'has-error' : ''}}">
		<label class="control-label">Fav Icon</label>
			<div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 50px; height: 50px;"><img src="{!! asset('uploads/'.$setting->favicon) !!}" alt="" /></div>
                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: auto; line-height: 20px;"></div>
                <div>
                    <span class="btn btn-file btn-primary btn-fill btn-sm"><span class="fileupload-new">Select new image</span>
                        <span class="fileupload-exists">Change</span>
                        {!! Form::input('file', 'favicon', null) !!} 
                    </span>
                    <a href="#" class="btn btn-danger btn-fill btn-sm fileupload-exists" data-dismiss="fileupload">Remove</a>
                </div>
            </div>
		            <div class="error">
						@foreach ($errors->get('favicon') as $error)
		                	<p>{{ $error }}</p>
		                @endforeach	
			        </div>
			
			
			
       </div>
	   </div>
	    <div class="col-md-4">
		<div class="form-group {{ $errors->has('loginbg') ? 'has-error' : ''}}">
		<label class="control-label">Admin Login Background</label>
			<div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 160px; height: 80px;"><img src="{!! asset('uploads/'.$setting->loginbg) !!}" alt="" /></div>
                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: auto; line-height: 20px;"></div>
                <div>
                    <span class="btn btn-file btn-primary btn-fill btn-sm"><span class="fileupload-new">Select new image</span>
                        <span class="fileupload-exists">Change</span>
                        <input type="file" class="" name="loginbg">
                        {!! Form::input('file', 'loginbg', null) !!}  
                    </span>
                    <a href="#" class="btn btn-danger btn-fill btn-sm fileupload-exists" data-dismiss="fileupload">Remove</a>
                </div>
            </div>
		            <div class="error">
						@foreach ($errors->get('loginbg') as $error)
		                	<p>{{ $error }}</p>
		                @endforeach	
			        </div>

			
       </div>
	   </div>
	  </div>
	  
	 <!--/row-->
	<h3 class="box-title m-t-40">reCAPTCHA Settings</h3>
    <hr>
     <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('site_key') ? 'has-error' : ''}}">
            	{!! Form::label('site_key', 'Site Key',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','site_key', null, [ 'class' => 'form-control','id' => 'site_key', 'placeholder' => 'Enter site key for reCAPTCHA' ])!!} 
						<div class="error">
							@foreach ($errors->get('site_key') as $error)
		                    	<p>{{ $error }}</p>
		                    @endforeach	
	                	</div>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('secret_key') ? 'has-error' : ''}}">
            	{!! Form::label('secret_key', 'Secret Key',[ 'class' => 'control-label']) !!}
				{!! Form::input('text','secret_key', null, [ 'class' => 'form-control','id' => 'secret_key', 'placeholder' => 'Enter secret key for reCAPTCHA' ])!!} 
            </div>
        </div>
    </div>
    <!--/row-->
	<!--/row-->
	<h3 class="box-title m-t-40">Google Analytics</h3>
    <hr>
     <div class="row">
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('google_analytics') ? 'has-error' : ''}}">
            	{!! Form::label('google_analytics', 'Google Analytics Code',[ 'class' => 'control-label']) !!}
				{!! Form::textarea('google_analytics', null, [ 'class' => 'form-control','id' => 'google_analytics', 'rows' => '3', 'placeholder' => 'Enter google analytics code here' ])!!} 
            </div>
			
        </div>
    </div>
    <!--/row-->
	 <hr>
</div>