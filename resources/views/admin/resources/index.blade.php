@extends('layouts.main')
@section('title') Resources @endsection
@section('content')
<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Resources <small> Overview</small>
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
                                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> Resources <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a></h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    {!! Form::open([ 'route'=> ['admin.resources.delete'], 'method' => 'DELETE','onsubmit' => 'return datatable_validation()']) !!}
                                    <div class="col-md-9"><button type="submit" name="bulk_delete" class="btn btn-danger btn-rounded"><i class="fa fa-trash"></i></button><br /></div>
                                    <div class="col-md-3 pull-right">{!! Form::input('checkbox','chkHeader',null,['id' => 'chkHeader', 'onclick' => 'checkUncheckAll(this)'])!!}<label class="control-label resource-select-txt">Select / Unselect All</label></div>
                                </div>
<?php 
$file_display = array('jpg','jpeg','png','gif','ico');
$destinationPath = 'uploads/metas/';
$dir = base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$destinationPath;
if (file_exists($dir) == false) {
   echo '<div class="text-center no-records"><p>Directory \''. $dir. '\' not found!</p></div>';
} else {
    $dir_contents = scandir($dir);
    $i=1;
	if(count($dir_contents) > 2){
    foreach ($dir_contents as $file) {
        $file_type = explode('.', $file);
        if ($file !== '.' && $file !== '..' && in_array($file_type[1], $file_display) == true) {?>
         <div class="col-md-3">
            <div class="resource-img">
                <a class="image-popup-vertical-fit" href="{!! asset('uploads/metas/'.$file_type[0].'.'.$file_type[1]) !!}" title="{{ $file_type[0] }}">
                    <img width="140px" height="140px" id="file{{ $i }}" src="{!! asset('uploads/metas/'.$file_type[0].'.'.$file_type[1]) !!}" />
                </a>
                <h2>{{ $file_type[0] }}</h2>
                <div class="row">
                <div class="col-md-10">
                    <a class="clipboadrd-btn" onclick="copyToClipboard('#file{{ $i }}')">Copy Link</a>
                </div>
                <div class="col-md-2">
                    {!! Form::input('checkbox','item[]',$file_type[0].'.'.$file_type[1],['class' => 'form-control chkItems resource-checkbox', 'onclick' => 'checkUnCheckParent()'])!!}
                </div>
                </div>
            </div>
        </div>  
     <?php  $i++;}
    }
	} else { ?>
	<div class="text-center no-records"><p>No Resources Found !!!</p></div>
	<?php } 
}?>
{!! Form::close() !!}
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
@push('custom-css')
{!!Html::style('admin-design/css/magnificPopup.css')!!}
@endpush
@push('custom-js')
{!!Html::script('admin-design/js/jquery.magnific-popup.min.js')!!}
<script>
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).attr('src')).select();
  document.execCommand("copy");
  $temp.remove();
  $("#info").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span class="glyphicon glyphicon-remove-sign"></span> <strong>Error : </strong><span>&nbsp; Link copied to clipboadrd </span></div>');
setTimeout( function(){
$('#info').load(location.href +  ' #info');
},3000);
}
</script>
 <script type="text/javascript">
      $(document).ready(function() {

        $('.image-popup-vertical-fit').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          mainClass: 'mfp-img-mobile',
          image: {
            verticalFit: true
          }
          
        });

      });
    </script>
@endpush
