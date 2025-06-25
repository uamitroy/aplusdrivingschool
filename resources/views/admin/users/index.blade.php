@extends('layouts.main')
@section('title') Users @endsection
@section('content')
<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Users <small> Overview</small>{{--<a onclick="return confirm('Are you sure you want empty the data table?');" class="btn btn-inverse pull-right" href="{{ route('admin.users.truncate') }}">Empty Table</a>--}}
                        </h1>
                        <ol class="breadcrumb">{{ generateBreadcrumb() }}</ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div id="info"></div>
                     @component('elements.admin.components.flash') @endcomponent 
                    </div>
                </div>

                
                
			<div class="parent-content-wrapper">
			 <div id="content-sortable">	
              
				<div class="row">
                    <div class="col-lg-12">
					  <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Users <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a></h3>
                            </div>
                            <div class="panel-body">
			                     <div class="table-responsive">
                                    {!! Form::open([ 'route'=> ['admin.users.delete'], 'method' => 'POST','onsubmit' => 'return datatable_validation()']) !!}
                                  <p><button type="submit" name="bulk_delete" class="btn btn-danger btn-rounded"><i class="fa fa-trash"></i></button><br /></p>
                                        <table id="example" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                                         <thead>
                                            <tr>
                                                <th width="2%">{!! Form::input('checkbox','chkHeader',null,['id' => 'chkHeader', 'onclick' => 'checkUncheckAll(this)'])!!}</th>
                                                <th width="3%">Sl No.</th>
                                                <th width="10%">Name</th>
                                                <th width="10%">Email</th>
                                                <th width="10%">Phone</th>
                                                <th width="10%">Guardian Phone</th>
                                                <th width="10%">Created At</th>
                                                <th width="10%">Status</th>
                                                <th width="10%">Action</th>
                
                                          </tr>
                                         </thead>
                                         <tbody>
                    @php ($i = 1)                   
                    @foreach($users as $user)     
                <tr>
                <td width="2%">{!! Form::input('checkbox','item[]',$user->id,['class' => 'chkItems', 'onclick' => 'checkUnCheckParent()'])!!}</td>
                <td width="3%">{{ $i++ }}</td>
                <td width="10%">{{ $user->fname }} {{ $user->lname }}</td>
                <td width="10%">{{ $user->email }}</td>
                <td width="10%">{{ $user->phone }}</td>
                <td width="10%">{{ $user->guardian_phone }}</td>
                <td width="10%">{{ date("m-d-Y", strtotime($user->created_at)) }}</td>
                <td width="10%">{!! Form::select('status', ['0' => 'Inactive','1' => 'Active'],$user->status,['class' => 'form-control', 'onchange' => 'changeStatus(this.value,'. $user->id .')']) !!} 
                </td>
                <td width="10%">
                <span class="action"><a href="{{ route('admin.user.edit',$user->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                <span class="action"><a href="{{ route('admin.user.change_password',$user->id) }}"><i class="fa fa-unlock-alt" aria-hidden="true"></i></a></span>
                <span class="action"><a href="{{ route('admin.user.show',$user->id) }}"><i class="fa fa-list-ul" aria-hidden="true"></i></a></span>
                {{--<span class="action"><a onclick="return confirm('Are you sure you want delete ?');" href="{{ route('admin.user.delete',$user->id) }}"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span>--}}
                </td>
            </tr>  
                   @endforeach                 
                                        </tbody>
                                       </table>
                                       {!! Form::close() !!}
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
function changeStatus(status,userID){

  var token = $('input[name="_token"]').val();

  $.ajax({

            type: 'POST',
            url:  '{{ route('admin.user.change_status') }}',
            data : {'_token': token,'status' : status,'id' : userID},
            success :  function(response){
                if(response.status === true) {
                    $("#info").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check-circle" aria-hidden="true"></i> <strong>Success : </strong> &nbsp; '+ response.success +'</div>');
                } else {
                    $("#info").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span class="glyphicon glyphicon-remove-sign"></span> <strong>Success : </strong> &nbsp;'+ response.danger +'</div>');
                }
                setTimeout( function(){
                    $('#info').load(location.href +  ' #info');
                },3000);
            },
            error: function(xhr){
                  console.log("An error occured: " + xhr.status + " " + xhr.statusText);
            }


  });

}
</script>
@endpush