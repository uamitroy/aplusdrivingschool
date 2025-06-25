@extends('layouts.main')
@section('title') View User @endsection
@section('content')
<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            View <small> User</small>
                        </h1>
                       <ol class="breadcrumb"></ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-12">
					
					
                    </div>
                </div>
                
			<div class="parent-content-wrapper">
			 <div id="content-sortable">	
                
               <div class="row">
                    <div class="col-lg-12">
					  <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user"></i> View User <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a></h3>
                            </div>
                            <div class="panel-body">
							<div class="form-body">
                                        <h3 class="box-title">User Info</h3>
                                        <hr class="m-t-0 m-b-40">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Name :</label>
                                                    <div class="col-md-9">
                                                        <p class="form-control-static">{{ $user->fname }} {{ $user->lname }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Email :</label>
                                                    <div class="col-md-9">
                                                        <p class="form-control-static"> {{ $user->email }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <h3 class="box-title">Date</h3>
                                        <hr class="m-t-0 m-b-40">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3">Created At :</label>
                                                    <div class="col-md-9">
                                                        <p class="form-control-static"> {{ $user->created_at }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label text-right col-md-3"> Updated At :</label>
                                                    <div class="col-md-9">
                                                        <p class="form-control-static"> {{ $user->updated_at }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                    </div>
                </div>	
				
					
            </div>
		    </div>
            </div>
            <!-- /.container-fluid -->

        </div>
        
@endsection