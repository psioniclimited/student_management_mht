@extends('master')

@section('css')
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
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
            name: {required: true, minlength: 4},
            fathers_name: {required: true, minlength: 4},
            mothers_name: {required: true, minlength: 4},
            phone_home: {required: true},
            phone_away: {required: true},
            schools_id: {valueNotEquals: "default"},
            batch_id: {valueNotEquals: "default"},
            subjects_id: {required: true},

        },
        messages: {
            name: {required: "Enter Student Name"},
            fathers_name: {required: "Enter Student's Father Name"},
            mothers_name: {required: "Enter Student's Mother's Name"},
            phone_home: {required: "Enter Home Phone Number"},
            phone_away: {required: "Enter Additional Phone Number"},
            schools_id: {valueNotEquals: "Select a School"},
            batch_id: {valueNotEquals: "Select a Batch"},
            subjects_id: {required: "Choose Subjects"},
        }
    });

    $('#batch_id').select2({
        allowClear: true,
        placeholder: 'Select batch',
        ajax: {
            url: "/getallbatch",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                q: params.term, // search term
                page: params.page
              };
            },
            processResults: function (data, params) {
              // parse the results into the format expected by Select2
              // since we are using custom formatting functions we do not need to
              // alter the remote JSON data, except to indicate that infinite
              // scrolling can be used
              params.page = params.page || 1;
              console.log(data);
              return {
                results: data,
                pagination: {
                  more: (params.page * 30) < data.total_count
                }

              };
            },
            cache: true
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
        Student Module
        <small>it all starts here</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Student</a></li>
        <li class="active">Create Student</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- <div class="col-md-6"> -->
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Student Create Page</h3>
        </div>
        <!-- /.box-header -->
        <!-- form starts here -->
        {!! Form::open(array('url' => 'create_student_process', 'id' => 'add_user_form', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}

        <div class="box-body">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="name">Fullname*</label>
                    
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Student name">
                    
                </div>
                <div class="form-group">
                    <label for="fathers_name">Father's name*</label>
                    
                        <input type="text" class="form-control" id="fathers_name" name="fathers_name" placeholder="Enter Father's name">
                    
                </div>
                <div class="form-group">
                    <label for="mothers_name">Mother's name*</label>
                    
                        <input type="text" class="form-control" id="mothers_name" name="mothers_name" placeholder="Enter Mother's name">
                    
                </div>
                <div class="form-group">
                    <label for="phone_home">Phone Number*</label>
                    
                        <input type="text" class="form-control" id="phone_home" name="phone_home" placeholder="Enter Phone number">
                    
                </div>
                <div class="form-group">
                    <label for="phone_away">Additional Phone Number*</label>
                    
                        <input type="text" class="form-control" id="phone_away" name="phone_away" placeholder="Enter additinal Phone number">
                    
                </div>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="schools_id" >School*</label>
                    
                        <select class="form-control" name="schools_id">
                                <option value="default">Choose...</option>
                                @foreach ($Schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        
                </div>
                <div class="form-group">
                    <label for="batch_id" >Batch*</label>
                    <select class="form-control select2" name="batch_id" id="batch_id"></select>
                </div>
                <!-- checkbox -->
                <div class="form-group">
                <label for="subjects_id" >Choose Subject*</label>
                @foreach ($Subjects as $subject)
                <div class="checkbox">
                    <label>
                      <input type="checkbox" name="subject[]" value="{{ $subject->id }}" class="flat-red">
                      {{ $subject->name }}
                    </label>
                </div>
                @endforeach
                </div>
                <!-- <div class="form-group"> -->
                        <!-- <label for="pic" >Upload Photo*</label> -->
                        
                            <!-- {{Form::file('pic')}} -->
                            <!-- <input type="file" name="pic" id="pic"> -->
                            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                        
                <!-- </div> -->
            </div>
            <!-- /.col -->
            <div class="col-md-1"></div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-default">Cancel</button>
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

