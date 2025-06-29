@extends('layouts.main')
@section('title') Transactions @endsection
@section('content')
<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Transactions <small> Overview</small>
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
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Transactions <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a></h3>
                            </div>
                            <div class="panel-body">
			                     <div class="table-responsive auto-table" style="overflow-x: auto !important">
                                        {!! Form::open([ 'route'=> ['admin.transactions'],'autocomplete'=>'off','id'=>'search','method' => 'POST']) !!}
                                    <div class="row">
                                        
                                        <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('segment', 'Select Segment',[ 'class' => 'control-label']) !!}
                                            {!! Form::select('segment', ['' => 'Select Segment'] + $segments, null, array('id' => 'segment', 'class' => 'form-control', 'onchange' => 'getSlotBySegment(this.value)')) !!}
                                        </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                          {!! Form::label('year', 'Slot Year',[ 'class' => 'control-label']) !!}
                                          {!! Form::select('year', ['' => 'Select Year'] + $years, null, array('id' => 'year', 'class' => 'form-control', 'onchange' => 'getSlotByYear(this.value)')) !!}
                                        </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                          <div class="form-group">
                                          @php ($month = ['1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'])
                                          {!! Form::label('month', 'Slot Month',[ 'class' => 'control-label']) !!}
                                          {!! Form::select('month', ['' => 'Select Month'] + $month, null, array('id' => 'month', 'class' => 'form-control', 'onchange' => 'getSlotByMonth(this.value)')) !!}
                                         </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('slot', 'Slots',[ 'class' => 'control-label']) !!}
                                            <select class="form-control select2" name="slot[]" multiple="multiple" id="slot">
                                                <option value="">Select Slots</option>
                                                @foreach($slots as $slot)
                                                    <option value="{{ $slot->id }}">{{ $slot->segment->name . ' - ' .$slot->year .' - '. $month[$slot->month] . ' - ' . $slot->start_time . ' - ' . $slot->end_time }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                          <div class="form-group">
                                          @php ($status = ['1' => 'Complete', '0' => 'Incomplete'])
                                          {!! Form::label('status', 'Tx Status',[ 'class' => 'control-label']) !!}
                                          {!! Form::select('status', ['' => 'Select'] + $status, null, array('id' => 'month', 'class' => 'form-control')) !!}
                                         </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                          <div class="form-group">
                                          @php($type = ['Virtual' => 'Virtual', 'Offline' => 'Offline'])
                                          {!! Form::label('type', 'Slot Type',[ 'class' => 'control-label']) !!}
                                          {!! Form::select('type', ['' => 'Select'] + $type, null, array('id' => 'type', 'class' => 'form-control')) !!}
                                         </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-md-offest-3">
                                            <div class="form-group"> 
                                                {!! Form::button('Search',['type' => 'submit', 'class' => 'btn btn-rounded btn-success pull-right']) !!}
                                            </div>
                                        </div>    
                                        </div>
                                        {!! Form::close() !!}
                                        <hr/>
                                        <table id="example-tx" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                                         <thead>
                                            <tr>
                                                <th width="5%">Sl No.</th>
                                                <th width="10%">Slot Type</th>
                                                <th width="10%">Gateway</th>
												<th width="10%">Item Name</th>
                                                <th width="20%">Dates</th>  
                                                <th width="10%">Start Time</th>
                                                <th width="10%">End Time</th>                                              
												<th width="10%">User Name</th>
                                                <th width="10%">User Email</th>
                                                <th width="10%">User Phone</th>
                                                <th width="10%">Guardian Phone</th>
												<th width="5%">Amount</th>
												<th width="5%">Currency</th>
												<th width="10%">Status</th>
										        <th width="10%">Invoice</th>
                                                <th width="10%">Transaction ID</th>
												{{--<th width="10%">Created At</th>
                                                <th width="10%">Completed At</th>--}}
                
                                          </tr>
                                         </thead>
                                         <tbody>
                    @php ($i = 1)                   
                    @foreach($transactions as $transaction) 
                    @php($status = $transaction->status == 'Completed' ? 1 : 0)
                <tr>
                
                <td width="5%">{{ $i++ }}</td>
                <td width="10%">{{ $transaction->slot->type ?: 'N.A' }}</td>
                <td width="10%">{{ $transaction->gateway }}</td>  
				<td width="10%">{{ $transaction->name }}</td> 
                <td width="20%">{{ $transaction->slot->dates }}</td>
                <td width="10%">{{ $transaction->slot->start_time ?: 'N.A' }}</td> 
                <td width="10%">{{ $transaction->slot->end_time ?: 'N.A' }}</td>  
                <td width="10%">{{ $transaction->user ? $transaction->user->fname : 'N/A' }} {{ $transaction->user ? $transaction->user->lname : 'N/A' }}</td>  
                <td width="10%">{{ $transaction->user ? $transaction->user->email : 'N/A' }}</td>
                <td width="10%">{{ $transaction->user ? $transaction->user->phone : 'N/A' }}</td>
                <td width="10%">{{ $transaction->user ? $transaction->user->guardian_phone : 'N/A' }}</td>
				 
				<td width="5%">{{ $transaction->amount }}</td> 
				<td width="5%">{{ $transaction->currency_code }}</td> 
				<td width="10%">{!! Form::select('status', ['0' => 'Not Completed','1' => 'Completed'],$status,['class' => 'form-control', 'onchange' => 'changeTxStatus(this.value,'. $transaction->id .')']) !!}</td>  
		  <td width="10%">@if($transaction->invoice)<a href="{!! asset('storage/invoice/'.$transaction->invoice) !!}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>@endif</td>
                <td width="10%">{{ $transaction->tx_id }}</td> 
				{{--<td width="10%">{{ date("m-d-Y h:i:s", strtotime($transaction->created_at)) }}</td> 
				<td width="10%">{{ date("m-d-Y h:i:s", strtotime($transaction->updated_at)) }}</td>--}}              
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
@push('custom-js')

<script>
function changeTxStatus(status,txID){

  var token = $('meta[name="csrf-token"]').attr('content');

  $.ajax({

            type: 'POST',
            url:  '{{ route('admin.tx.change.status') }}',
            data : {'_token': token,'status' : status,'id' : txID},
            success :  function(response){
                if(response.status === true) {
                    $("#info").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check-circle" aria-hidden="true"></i> <strong>Success : </strong> &nbsp; '+ response.success +'</div>');
                    alert( response.success );
                } else {
                    $("#info").html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Warning : </strong> &nbsp;'+ response.danger +'</div>');
                    alert( response.danger );
                }
                setTimeout( function(){
                    $('#info').load(location.href +  ' #info');
                },3000);
            },
            error: function(xhr){
                  alert( "An error occured: " + xhr.status + " " + xhr.statusText );
                  console.log("An error occured: " + xhr.status + " " + xhr.statusText);
            }


  });

}
</script>
<script>

var token = $('meta[name="csrf-token"]').attr('content');

function getSlotBySegment(segmentId){

  var selectedSegment = segmentId;
  var selectedYear = $("#year").children("option:selected").val();
  var selectedMonth = $("#month").children("option:selected").val();

  $.ajax({

    type: 'POST',
    url:  '{{ route('admin.slots.search') }}',
    data : {'_token': token, 'selectedSegment' : selectedSegment, 'selectedYear' : selectedYear, 'selectedMonth' : selectedMonth},
    success :  function(response){
        $('#slot').empty();
        if(response){
            $('#slot').append(response);
        }
        
    },
    error: function(xhr){
          console.log("An error occured: " + xhr.status + " " + xhr.statusText);
    }

  });

}

function getSlotByYear(year){
    
    var selectedSegment = $("#segment").children("option:selected").val();
    var selectedYear = year;
    var selectedMonth = $("#month").children("option:selected").val();

    $.ajax({

    type: 'POST',
    url:  '{{ route('admin.slots.search') }}',
    data : {'_token': token, 'selectedSegment' : selectedSegment, 'selectedYear' : selectedYear, 'selectedMonth' : selectedMonth},
    success :  function(response){
        $('#slot').empty();
        if(response){
            $('#slot').append(response);
        }
        
    },
    error: function(xhr){
          console.log("An error occured: " + xhr.status + " " + xhr.statusText);
    }

  });

}

function getSlotByMonth(month){
    
    var selectedSegment = $("#segment").children("option:selected").val();
    var selectedYear = $("#year").children("option:selected").val();
    var selectedMonth = month;

    $.ajax({

    type: 'POST',
    url:  '{{ route('admin.slots.search') }}',
    data : {'_token': token, 'selectedSegment' : selectedSegment, 'selectedYear' : selectedYear, 'selectedMonth' : selectedMonth},
    success :  function(response){
        $('#slot').empty();
        if(response){
            $('#slot').append(response);
        }
        
    },
    error: function(xhr){
          console.log("An error occured: " + xhr.status + " " + xhr.statusText);
    }

  });

}
</script>

<script>

$(function(){

  var buttonCommon = {
      exportOptions: {
          format: {
              body: function (data, column, row, node) {
                  // if it is select
                  if (row == 13) {
                    return $(data).find("option:selected").text()
                  }else if(row == 14){
                    return $(data).attr('href')
                  }else {
                    return data
                  }
              }
          }
      }
  };

   $('#example-tx').DataTable({

      "pageLength": 10,

      dom: 'Bfrtip',

        buttons: [
            $.extend(true, {}, buttonCommon, {
                extend: "csv"
            }), $.extend(true, {}, buttonCommon, {
                extend: "excel"
            })
        ],
      "columnDefs": [ { "targets": 0, "orderable": false} 
    ]

  });
      
});

</script>
@endpush

@push('custom-css')
<style>
  #example-tx_filter label,#example-tx_paginate{
  float:right !important;
  }
</style>
@endpush