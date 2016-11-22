@extends('master')

@section('css')
<link rel="stylesheet" href="plugins/tooltipster/tooltipster.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="../../plugins/datepicker/datepicker3.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">
@endsection

@section('scripts')
<script src="plugins/validation/dist/jquery.validate.js"></script>
<script src="plugins/tooltipster/tooltipster.js"></script>
<!-- bootstrap datepicker -->
<script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap time picker -->
<script src="../../plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script>
    // add the rule here
    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg != value;
    }, "Value must not equal arg.");

    $(document).ready(function () {
        // initialize tooltipster on text input elements
        $('form input,select,textarea').tooltipster({
            trigger: 'custom',
            onlyOne: false,
            position: 'right'
        });

        // initialize validate plugin on the form
        $('#add_event_form').validate({
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
                name: {
                    required: true
                },
                start_date: {
                    required: true
                },
                end_date: {
                    required: true
                },
                time: {
                    required: true
                },
                venue: {
                    required: true
                },
                description: {
                    required: true
                },
                banner: {
                    required: true
                }
                
                
                
            },
            messages: {
                name: {
                    required: "provide event name"
                },
                start_date: {
                    required: "provide start date"
                },
                end_date: {
                    required: "provide end date"
                },
                time: {
                    required: "provide time"
                },
                venue: {
                    required: "provide venue"
                },
                description: {
                    required: "provide description"
                },
                banner: {
                    required: "provide a banner",
                    max: "Upload Limited Image file",
                    mimes: "Upload an image(.jpg,.jpeg)"
                }
            }
        });

        //Date picker 1
        $('#datepicker1').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
        });

        //Date picker 2 
        $('#datepicker2').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
        });

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
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
        Events
        <small>add event page</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Add an Event</h3>
        </div>
        @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <!-- /.box-header -->
        <!-- form starts here -->
        {!! Form::open(array('url' => 'create_event_process', 'id' => 'add_event_form', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
        {!! csrf_field() !!}
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-4">
                    <!-- Event name -->
                    <div class="form-group">
                        <label for="name" >Event Name*</label>
                        
                            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" size="35" placeholder="Enter event name">
                        
                    </div>
                    <!-- Start date -->
                    <div class="form-group">
                        <label for="start_date" >Start Date*</label>
                        
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="datepicker1" name="start_date" value="{{old('start_date')}}" placeholder="Select start date">
                            </div>
                        
                    </div>
                    <!-- End date -->
                     <div class="form-group">
                        <label for="end_date" >End Date</label>
                       
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="datepicker2" name="end_date" value="{{old('end_date')}}" placeholder="Select end date">
                            </div>
                        
                    </div>
                    <!-- Event time -->
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <label for="time" >Event Time*</label>
                            
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" name="event_Time">
                                    <div class="input-group-addon">
                                      <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            
                        </div>
                        <!-- /.form group -->
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="venue" >Venue*</label>
                        
                            <textarea class="form-control" rows="3" id="venue" name="venue" value="{{old('venue')}}" placeholder="Enter venue..."></textarea>
                        
                    </div>
                    <div class="form-group">
                        <label for="description" >Event Description</label>
                        
                            <textarea class="form-control" rows="3" id="description" name="description" value="{{old('description')}}" placeholder="Enter event description..."></textarea>
                        
                    </div>
                    <div class="form-group">
                        <label for="banner" >Upload Banner*</label>
                        
                            <!-- {{Form::file('pic')}} -->
                            <input type="file" name="banner" id="banner">
                            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                        
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
            </div>
            <!-- /.box-footer -->
        </form>
        <!-- /.form ends here -->
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->

@endsection

