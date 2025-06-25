@extends('layouts.adminlogin')
@section('title') Disable 2FA @endsection
@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">2FA Secret Key</div>

                <div class="panel-body">
                    2FA has been removed
                    <br /><br />
                    <a href="{{ route('admin.dashboard') }}">Go To Dashboard</a>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection