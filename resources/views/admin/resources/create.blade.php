@extends('layouts.main')
@section('title') Resources @endsection
@section('content')
<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Resources <small> upload</small>
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
                                <h3 class="panel-title"><i class="fa fa-fw fa-edit"></i> Multiple Image Upload <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a></h3>
                            </div>
                            <div class="panel-body">
                                <h3 class="card-title">Upload Images </h3>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-12">
                                        {!! Form::open([ 'route' => ['admin.resources.store'],'autocomplete'=>'off', 'files'=> true,'id'=>'post_files', 'method' => 'POST']) !!}
                                        <div class="form-group">
                                            {!! Form::label('id-input-file-3', 'Upload Images',[ 'class' => 'control-label']) !!}
                                            {!! Form::input('file', 'images[]', null, ['id' => 'id-input-file-3','multiple' => 'multiple']) !!} 
                                            <div class="error">
                                                @if (count($errors) > 0)
                                                    @foreach ($errors->all() as $error)
                                                        <p>{{ $error }}</p>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-actions pull-right">
                                            {!! Form::button('<i class="fa fa-check"></i> Upload',['type' => 'submit', 'value' => 'Add', 'class' => 'btn btn-rounded btn-success']) !!}
                                        </div>
                                    {!! Form::close() !!} 
                                    </div>
                                    <hr>  
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
@push('custom-css')
{!!Html::style('admin-design/css/ace.min.css')!!}
@endpush
@push('custom-js')
{!!Html::script('admin-design/js/ace-elements.min.js')!!}
{!!Html::script('admin-design/js/ace.min.js')!!}
<script>
            $('#id-input-file-3').ace_file_input({
                style: 'well',
                btn_choose: 'Drop images here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-picture-o',
                droppable: true,
                thumbnail: 'small',
                whitelist_ext: ["jpeg", "jpg", "png", "gif", "bmp"],
                whitelist_mime: ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"],
                preview_error: function (filename, error_code) {
                }

            }).on('change', function () {
            });
</script>
@endpush
