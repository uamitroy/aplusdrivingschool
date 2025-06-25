@extends('layouts.main')
@section('title') Packages @endsection
@section('content')
<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Packages <small> Overview</small>
                        </h1>
                        <ol class="breadcrumb">{{ generateBreadcrumb() }}</ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div id="info">
                        </div>
                     @component('elements.admin.components.flash') @endcomponent 
                    </div>
                </div>

                
                
			<div class="parent-content-wrapper">
			 <div id="content-sortable">	
              
				<div class="row">
                    <div class="col-lg-12">
					  <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Packages <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a></h3>
                            </div>
                            <div class="panel-body">
			                     <div class="table-responsive">
                                    
                                        <table id="example" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                                         <thead>
                                            <tr>
                                                <th width="10%">Sl No.</th>
												<th width="10%">Segment</th>
                                                <th width="10%">Package</th>
												<th width="10%">Price</th>
												<th width="20%">Description</th>
												<th width="10%">Created At</th>
                                                <th width="10%">Action</th>
                
                                          </tr>
                                         </thead>
                                         <tbody>
                    @php ($i = 1)                   
                    @foreach($packages as $package)     
                <tr>
                
                <td width="10%">{{ $i++ }}</td>
                <td width="10%">{{ $package->segment->name }}</td>  
				<td width="10%">{{ $package->name }}</td>  
				<td width="10%">{{ $package->price }}</td>  
				<td width="10%">{!! $package->description !!}</td>
				<td width="10%">{{ date("m-d-Y", strtotime($package->created_at)) }}</td>              
                <td width="10%">
                <span class="action"><a href="{{ route('admin.package.edit',$package->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                <span class="action"><a onclick="return confirm('Are you sure you want delete ?');" href="{{ route('admin.package.delete',$package->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span>
                </td>
            </tr>  
                   @endforeach                 
                                        </tbody>
                                       </table>
                                       
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
