@extends('layouts.main')

@section('title') Edit Package @endsection

@section('content')

<div id="page-wrapper">



            <div class="container-fluid">



                <!-- Page Heading -->

                <div class="row">

                    <div class="col-lg-12">

                        <h1 class="page-header">

                            Edit <small> Package</small>

                        </h1>

                       <ol class="breadcrumb">{{ generateBreadcrumb() }}</ol>

                    </div>

                </div>

                <!-- /.row -->



                <div class="row">

                    <div class="col-lg-12">

                         @component('elements.admin.components.flash') @endcomponent 

                    </div>

                </div>

                

            <div class="parent-content-wrapper">

             <div id="content-sortable">    

                

               <div class="row">

                    <div class="col-lg-12">

                      <div class="panel panel-default">

                            <div class="panel-heading">

                                <h3 class="panel-title"><i class="fa fa-user"></i>

                                Edit Package

                                <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a></h3>

                            </div>

                            <div class="panel-body">

                            {!! Form::Model($package,['autocomplete'=>'off', 'id'=>'package', 'method' => 'PATCH','route' => ['admin.package.update',$package->id]]) !!}

                                    @include('admin.packages._partials.form') 

                                    <div class="form-actions pull-right">

                                        {!! Form::button('<i class="fa fa-pencil"></i> Update',['name' => 'Submit', 'type' => 'submit', 'value' => 'Edit', 'class' => 'btn btn-info btn-rounded']) !!}

                                    </div>

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

@push('custom-js')

{!!Html::script('admin-design/js/ckeditor/ckeditor.js')!!}
{!!Html::script('admin-design/js/ckeditor/adapters/jquery.js')!!}
@endpush