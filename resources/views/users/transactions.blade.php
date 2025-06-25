@extends('layouts.front-auth')
@section('meta') 
<title>Dashboard</title>
@endsection
@section('content')
<div class="innerbanner">

  <img class="fullimage" src="{!! asset('design/images/logbanner.jpg') !!}" alt=""/>

  <h2 class="upptext">Dashboard</h2>

</div>
<div class="gap"></div>
<div class="container">

  <div class="accoutab">

   <div class="row"> 

  <div class="col-md-4 col-sm-5">
    <div class="bgtab">
       @include('elements.users.nav')
    </div>
  </div>
  
  <div class="col-md-8 col-sm-7">
    <div class="righttab">
		<div class="tabdettext">
			<div class="table-responsive">
                                    
                                        <table class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                                         <thead>
                                            <tr>
                                                <th width="5%">Sl No.</th>
                                                <th width="10%">Slot type</th>
                                                <th width="10%">Gateway</th>
												<th width="10%">Item Name</th>
												<th width="15%">Transaction ID</th>
												<th width="5%">Amount</th>
												<th width="5%">Currency</th>
												<th width="10%">Status</th>
										   {{-- <th width="10%">Invoice</th> --}}
												<th width="10%">Created At</th>
                                                <th width="10%">Completed At</th>
												<th width="10%">Details</th>
                
                                          </tr>
                                         </thead>
                                         <tbody>
                    @php ($i = 1)                   
                    @foreach($transactions as $transaction)  
					@php($folder = 'invoice')
					@php($filename = $transaction->invoice)  
					@php($path = storage_path().'\\app\\'.$folder.'/'.$filename ) 
                <tr>
                
                <td width="5%">{{ $i++ }}</td>
                <td width="10%">{{ $transaction->slot->type ?: 'N.A' }}</td>
                <td width="10%">{{ $transaction->gateway }}</td>  
				<td width="10%">{{ $transaction->name }}</td>  
				<td width="15%">{{ $transaction->tx_id }}</td>  
				<td width="5%">{{ $transaction->amount }}</td> 
				<td width="5%">{{ $transaction->currency_code }}</td> 
				<td width="10%">{{ $transaction->status }}</td>  
			{{--<td width="10%">
							@if($transaction->invoice)
									<a href="{{ $path }}" download target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
							@else
									{{ 'N.A' }}
							@endif
				</td>  --}}
				<td width="10%">{{ date("m-d-Y h:i:s", strtotime($transaction->created_at)) }}</td> 
				<td width="10%">{{ date("m-d-Y h:i:s", strtotime($transaction->updated_at)) }}</td> 
				<td width="10%">
                <span class="action"><a href="{{ route('booked.class.details',$transaction->id) }}"><i class="fa fa-list-ul" aria-hidden="true"></i></a></span>
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
<div class="gap"></div>
@endsection
@component('elements.users.components.flash') @endcomponent
