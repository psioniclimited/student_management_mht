@extends('master')

@section('css')
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
@endsection

@section('scripts')
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<script>

$(document).ready(function () {

    // initialize tooltipster on form input elements
    $('form input, select').tooltipster({// <-  USE THE PROPER SELECTOR FOR YOUR INPUTs
        trigger: 'custom', // default is 'hover' which is no good here
        onlyOne: false, // allow multiple tips to be open at a time
        position: 'right'  // display the tips to the right of the element
    });

    // initialize validate plugin on the form
    $('#add_user_form').validate({
        errorPlacement: function (error, element) {

            var lastError = $(element).data('lastError'),
                    newError = $(error).text();

            $(element).data('lastError', newError);

            if (newError !== '' && newError !== lastError) {
                $(element).tooltipster('content', newError);
                $(element).tooltipster('show');
            }
        },
        success: function (label, element) {
            $(element).tooltipster('hide');
        },
        rules: {
            name: {required: true},
            description: {required: true},
        },
        messages: {
            name: {required: "Please provide Subject or Class name"},
            description: {required: "Please provide Subject Description"},
        }
    });


});



</script>

@endsection

@section('side_menu')

@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Subject Module
        <small>it all starts here</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Subject</a></li>
        <li class="active">Edit Subject</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- <div class="col-md-6"> -->
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Subject Create Page</h3>
        </div>
        <!-- /.box-header -->
        <!-- form starts here -->
        {!! Form::open(array('url' => '/subject_update_process/'.$getSubject->id.'/', 'id' => 'add_user_form', 'class' => 'form-horizontal')) !!}

        {!! csrf_field() !!}
		{{ method_field('PATCH') }}
        
        <div class="box-body">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="name">Subject Name*</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $getSubject->name }}">
                </div>
                <div class="form-group">
                    <label for="name">Description Name*</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ $getSubject->description }}">
                </div>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">

            </div>
            <!-- /.col -->
            <div class="col-md-1"></div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Submit</button>
        </div>
        <!-- /.box-footer -->
        {!! Form::close() !!}
        <!-- /.form ends here -->


        @if (count($errors) > 0)
        <div class="alert alert-danger alert-login col-sm-4">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <!-- /.box -->
    <!-- </div> -->
</section>
<!-- /.content -->

@endsection

