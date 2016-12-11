@extends('master')

@section('css')
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('../../plugins/datepicker/datepicker3.css')}}">
@endsection

@section('scripts')
<script src="{{asset('plugins/validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('plugins/jQuery/jquery.form.min.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('../../plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/momentjs/moment.min.js')}}"></script>
<script>
    // add the rule here
    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg != value;
    }, "Value must not equal arg.");

    $(document).ready(function () {

    $('#data_table').html("<table id='all_user_list' class='table table-bordered table-striped'>"+
        "<thead>"+
            "<tr>"+
                "<th>Student Id</th>"+
                "<th>Student Name</th>"+
                "<th>Student Phone Number</th>"+
                "<th>Payment Date</th>"+
                "<th>Total Paid Amount/-</th>"+
            "</tr>"+
        "</thead>"+
        "<tbody>  "+                          
        "</tbody></table>");

    

	//Date picker for Start Date
    $('.ref_date').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
    });


    $("#daily_payment_reporting").click(function() {
        $('#data_table').html('');
        $('#data_table').html("<table id='all_user_list' class='table table-bordered table-striped'>"+
        "<thead>"+
            "<tr>"+
                "<th>Student Id</th>"+
                "<th>Student Name</th>"+
                "<th>Student Phone Number</th>"+
                "<th>Payment Date</th>"+
                "<th>Total Paid Amount/-</th>"+
            "</tr>"+
        "</thead>"+
        "<tbody>  "+                          
        "</tbody></table>");
        var table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_daily_reporting')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "student.name"},
                    {"data": "student.phone_home"},                    
                    {"data": "payment_date"},
                    {"data": "total"},
                ]
            });

    });
    $("#monthly_payment_reporting").click(function() {
        $('#data_table').html('');
        $('#data_table').html("<table id='all_user_list' class='table table-bordered table-striped'>"+
        "<thead>"+
            "<tr>"+
                "<th>Student Id</th>"+
                "<th>Student Name</th>"+
                "<th>Student Phone Number</th>"+
                "<th>Payment Date</th>"+
                "<th>Total Paid Amount/-</th>"+
            "</tr>"+
        "</thead>"+
        "<tbody>  "+                          
        "</tbody></table>");
        var table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_daily_reporting')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "student.name"},
                    {"data": "student.phone_home"},                    
                    {"data": "payment_date"},
                    {"data": "total"},
                ]
            });

    });
    $("#all_payment_reporting").click(function() {
        $('#data_table').html('');
        $('#data_table').html("<table id='all_user_list' class='table table-bordered table-striped'>"+
        "<thead>"+
            "<tr>"+
                "<th>Student Id</th>"+
                "<th>Student Name</th>"+
                "<th>Student Phone Number</th>"+
                "<th>Payment Date</th>"+
                "<th>Total Paid Amount/-</th>"+
            "</tr>"+
        "</thead>"+
        "<tbody>  "+                          
        "</tbody></table>");
        var table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_all_reporting')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "student.name"},
                    {"data": "student.phone_home"},                    
                    {"data": "payment_date"},
                    {"data": "total"},
                ]
            });

    });

    





        // initialize tooltipster on text input elements
        // $('form input,select,textarea').tooltipster({
        //     trigger: 'custom',
        //     onlyOne: false,
        //     position: 'right'
        // });

        // initialize validate plugin on the form
        // $('#add_member_form').validate({
        //     errorPlacement: function (error, element) {

        //         var lastError = $(element).data('lastError'),
        //                 newError = $(error).text();

        //         $(element).data('lastError', newError);

        //         if (newError !== '' && newError !== lastError) {
        //             $(element).tooltipster('content', newError);
        //             $(element).tooltipster('show');
        //         }
        //     },
        //     success: function (label, element) {
        //         $(element).tooltipster('hide');
        //     },
        //     rules: {
        //         fullname: {
        //             required: true
        //         },
        //         date_of_birth: {
        //             required: true
        //         },
        //         addrs: {
        //             required: true
        //         },
        //         mob_num: {
        //             required: true
        //         },
        //         off_num: {
        //             required: true
        //         },
        //         email: {
        //             required: true
        //         },
        //         member_type: {
        //             valueNotEquals: "default"
        //         },
        //         password: {
        //             required: true
        //         },
        //         password_confirmation: {
        //             required: true
        //         },
        //         pic: {
        //             required: true
        //         }
                
                
                
        //     },
        //     messages: {
        //         fullname: {
        //             required: "provide fullname"
        //         },
        //         date_of_birth: {
        //             required: "provide date of birth"
        //         },
        //         addrs: {
        //             required: "provide address"
        //         },
        //         mob_num: {
        //             required: "provide mobile number"
        //         },
        //         off_num: {
        //             required: "provide office number"
        //         },
        //         email: {
        //             required: "provide email"
        //         },
        //         member_type: {
        //             valueNotEquals: "provide member type"
        //         },
        //         password: {
        //             valueNotEquals: "provide password"
        //         },
        //         password_confirmation: {
        //             valueNotEquals: "provide password again"
        //         },
        //         pic: {
        //             required: "provide a photo"
        //         }
        //     }
        // });

    });
</script>


@endsection

@section('side_menu')

@endsection

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Payment Reporting Dashboard
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reporting > </a></li>
        <li class="active">Payment Reporting Page</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    
    <!-- Horizontal Form -->
    <div class="box box-primary">
        
        <div class="box-body">
        
            <div class="box-header with-border">
              <h3 class="box-title">Choose Payment Reporting</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="start_date">From</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control ref_date" name="start_date" >
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="daily_payment_reporting" class="btn btn-block btn-success">Daily Reporting</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="end_date">To</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control ref_date" name="end_date" >
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="monthly_payment_reporting" class="btn btn-block btn-success">Monthly Reporting</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="student_info_for_payment" class="btn btn-block btn-primary">Show</button>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="all_payment_reporting" class="btn btn-block btn-success">All Reporting</button>
                    </div>
                </div>


            </div>

        </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box-body -->







        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Daily Payment Reporting</h3>
            </div>
            <!-- /.box-header -->
            <div id="data_table" class="box-body">
                
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->


</section>
<!-- /.content -->

@endsection

