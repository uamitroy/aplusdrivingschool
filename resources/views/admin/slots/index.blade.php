@extends('layouts.main')
@section('title') Slots @endsection
@section('content')
<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Slots <small> Overview</small>
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
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Slots <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a></h3>
                            </div>
                            <div class="panel-body">
			                     <div class="table-responsive">
                                    
                                        <table id="example" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                                         <thead>
                                            <tr>
                                                <th width="5%">Sl No.</th>
												<th width="10%">Segment</th>
                                                <th width="10%">Type</th>
												<th width="10%">Year</th>
												<th width="10%">Month</th>
                                                <th width="10%">St. Time</th>
												<th width="10%">En. Time</th>
												<th width="20%">Dates</th>
												<th width="10%">Created At</th>
                                                <th width="5%">Action</th>
                
                                          </tr>
                                         </thead>
                                         <tbody>
                    @php ($i = 1)                   
                    @foreach($slots as $slot)  

                    @if($slot->month == 1)
                        @php( $month = 'Jan')
                    @endif 
                    @if($slot->month == 2)
                        @php( $month = 'Feb')
                    @endif 
                    @if($slot->month == 3)
                        @php( $month = 'Mar')
                    @endif 
                    @if($slot->month == 4)
                        @php( $month = 'Apr')
                    @endif 
                    @if($slot->month == 5)
                        @php( $month = 'May')
                    @endif 
                    @if($slot->month == 6)
                        @php( $month = 'Jun')
                    @endif 
                    @if($slot->month == 7)
                        @php( $month = 'Jul')
                    @endif 
                    @if($slot->month == 8)
                        @php( $month = 'Aug')
                    @endif 
                    @if($slot->month == 9)
                        @php( $month = 'Sep')
                    @endif 
                    @if($slot->month == 10)
                        @php( $month = 'Oct')
                    @endif 
                    @if($slot->month == 11)
                        @php( $month = 'Nov')
                    @endif   
                    @if($slot->month == 12)
                        @php( $month = 'Dec')
                    @endif 

                <tr>
                
                <td width="5%">{{ $i++ }}</td>
                <td width="10%">{{ $slot->segment->name }}</td>
                <td width="10%">{{ $slot->type }}</td>  
				<td width="10%">{{ $slot->year }}</td>  
				<td width="10%">{{ $month }}</td>  
				<td width="10%">{{ $slot->start_time }}</td>
				<td width="10%">{{ $slot->end_time }}</td>
				<td width="20%">{{ $slot->dates }}</td>
				<td width="10%">{{ date("m-d-Y", strtotime($slot->created_at)) }}</td>              
                <td width="5%">
                <span class="action"><a href="{{ route('admin.slot.edit',$slot->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                <span class="action"><a onclick="return confirm('Are you sure you want delete ?');" href="{{ route('admin.slot.delete',$slot->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span>
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
