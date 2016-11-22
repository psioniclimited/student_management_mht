@extends('master')

@section('css')
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('plugins/datepicker/datepicker3.css')}}">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- bootstrap time picker -->
<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>

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
                time: {
                    required: true
                },
                venue: {
                    required: true
                },
                banner: {
                    required: false
                }
                
                
                
            },
            messages: {
                name: {
                    required: "provide event name"
                },
                start_date: {
                    required: "provide start date"
                },
                time: {
                    required: "provide time"
                },
                venue: {
                    required: "provide venue"
                },
                banner: {
                    required: "provide a banner"
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
        <!-- /.box-header -->
        <!-- form starts here -->        
        {!! Form::open(array('url' => '/update/'.$EventsDetail->id.'/', 'id' => 'add_event_form', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}

        {!! csrf_field() !!}
		{{ method_field('PATCH') }}

            <div class="box-body">
                <div class="col-md-6">
                    <!-- Event name -->
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Event Name*</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="{{$EventsDetail->name}}" size="35">
                        </div>
                    </div>
                    <!-- Start date -->
                    <div class="form-group">
                        <label for="start_date" class="col-sm-3 control-label">Start Date*</label>
                        <div class="col-sm-9">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="datepicker1" name="start_date" value="{{$EventsDetail->start_date}}">
                            </div>
                        </div>
                    </div>
                    <!-- End date -->
                     <div class="form-group">
                        <label for="end_date" class="col-sm-3 control-label">End Date</label>
                        <div class="col-sm-9">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" id="datepicker2" name="end_date" value="{{$EventsDetail->end_date}}">
                            </div>
                        </div>
                    </div>
                    <!-- Event time -->
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <label for="time" class="col-sm-3 control-label">Event Time*</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" name="event_Time" value="{{$EventsDetail->time}}">
                                    <div class="input-group-addon">
                                      <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.form group -->
                    </div>
                    <div class="form-group">
                        <label for="venue" class="col-sm-3 control-label">Venue*</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" id="venue" name="venue">{{$EventsDetail->venue}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Event Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" id="description" name="description">{{$EventsDetail->description}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="banner" class="col-sm-3 control-label">Upload Banner*</label>
                        <div class="col-sm-9">
                            <!-- {{Form::file('pic')}} -->
                            <input type="file" name="banner" id="banner" value="$EventsDetail->banner">
                            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                        </div>
                    </div>
                </div>
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

