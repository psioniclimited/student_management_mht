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
<script type="text/JavaScript" src="{{asset('plugins/JQueryPrintJS/jQuery.print.js')}}" ></script>
<script>

    var monthNames = ["January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];    

    // add the rule here
    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg != value;
    }, "Value must not equal arg.");

    $(document).ready(function () {

    
    
    //Date picker for Start Date
    $('.ref_date').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
    });


    $("#daily_payment_reporting").click(function() {
        $("#box_color").attr("class","box box-warning");
        $("#payment_title").html("<p><b>Daily</b> Payment Reporting</p>");
        $("#alternate_data").text("Payment Date");
        $("#total_amount").text("Total Paid Amount/-");
        var table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "destroy": true,
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
    $("#print_daily_payment_reporting").click(function() {
        $.get('/get_daily_reporting',function(daily_data) {
            var data = daily_data.data;
            console.log(data);
            var top = "<div><b>Daily Reporting</b><div/><br>";
            var due_payment_output = "<table class='table table-bordered table-striped'>"+
                            "<thead>"+
                                "<tr>"+
                                    "<th>Student Name</th>"+
                                    "<th>Phone Number</th>"+
                                    "<th>Additional Phone Number</th>"+
                                    "<th>Payment Date</th>"+
                                    "<th>Total Paid Amount /- </th>"+
                                "</tr>"+
                            "</thead>"+
                        "<tbody>";
            var total_paid = 0;
            for (var i = 0; i < data.length; i++) {
                total_paid += data[i].total;
                due_payment_output += "<tr role='row' class='even'>"+
                                "<td>"+data[i].student.name+"</td>"+
                                "<td>"+data[i].student.phone_home+"</td>"+
                                "<td>"+data[i].student.phone_away+"</td>"+
                                "<td>"+data[i].payment_date+"</td>"+
                                "<td>"+data[i].total+"</td>"+
                                "</tr>";
            }
            
            due_payment_output += "<tr role='row' class='even'>"+
                                "<td></td>"+
                                "<td></td>"+
                                "<td></td>"+
                                "<td>Total Price</td>"+
                                "<td>"+total_paid+"</td>"+
                                "</tr>";
            
            var final_output = top + due_payment_output;
            $(final_output).print({
            globalStyles: true,
            mediaPrint: false,
            stylesheet : "http://fonts.googleapis.com/css?family=Inconsolata",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
            });
        });
    });
    
    $("#all_payment_reporting").click(function() {
        $("#box_color").attr("class","box box-success");
        $("#payment_title").html("<b>All</b> Payment Reporting");
        $("#alternate_data").text("Payment Date");
        $("#total_amount").text("Total Paid Amount/-");
        var table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "destroy": true,
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

    $("#due_payment_reporting").click(function() {
        $("#box_color").attr("class","box box-danger");
        $("#payment_title").html("<p><b>Due</b> Payment Reporting</p>");
        $("#alternate_data").text("Additional Phone Number");
        $("#total_amount").text("Total Due Amount/-");
        var table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "destroy": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_due_reporting')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "phone_home"},                    
                    {"data": "phone_away"},
                    {"data": "TotalDuePrice"},
                ]
            });

    });
    $("#print_due_payment_reporting").click(function() {
        $.get('/get_due_reporting',function(due_data) {
            var data = due_data.data;
            var top = "<div>Due Reporting For: <b>"+monthNames[moment().month()]+"</b><div/>"+
                                "<br>";
            var due_payment_output = "<table class='table table-bordered table-striped'>"+
                            "<thead>"+
                                "<tr>"+
                                    "<th>Student Name</th>"+
                                    "<th>Phone Number</th>"+
                                    "<th>Additional Phone Number</th>"+
                                    "<th>Total Due Amount /- </th>"+
                                "</tr>"+
                            "</thead>"+
                        "<tbody>";  
            for (var i = 0; i < data.length; i++) {
                due_payment_output += "<tr role='row' class='even'>"+
                                "<td>"+data[i].name+"</td>"+
                                "<td>"+data[i].phone_home+"</td>"+
                                "<td>"+data[i].phone_away+"</td>"+
                                "<td>"+data[i].TotalDuePrice+"</td>"+
                                "</tr>";
            }
            
            due_payment_output += "</tbody ></table>";
            
            var final_output = top + due_payment_output;
            $(final_output).print({
            globalStyles: true,
            mediaPrint: false,
            stylesheet : "http://fonts.googleapis.com/css?family=Inconsolata",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
            });
        });
    });
    
    $("#range_payment_reporting").click(function() {
        if ($('input[id=start_date]').val() && $('input[id=end_date]').val()) {
            $("#box_color").attr("class","box box-primary");
            $("#payment_title").html("<p>Payment Reporting from <b>"+$('input[id=start_date]').val()+"</b> to <b>"+$('input[id=end_date]').val()+"</b></p>");
            $("#alternate_data").text("Payment Date");
            $("#total_amount").text("Total Paid Amount/-");
            var table = $('#all_user_list').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "destroy": true,
                "info": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true, 
                "ajax": {
                    'url': "{{URL::to('/payment_date_range')}}",
                    'data': {
                       start_date: $('input[id=start_date]').val(),
                       end_date: $('input[id=end_date]').val() 
                    },
                },
                "columns": [
                        {"data": "id"},
                        {"data": "student.name"},
                        {"data": "student.phone_home"},                    
                        {"data": "payment_date"},
                        {"data": "total"},
                    ]
            });
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
                                <input id="start_date" type="text" class="form-control ref_date" name="start_date" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <div class="col-xs-6">
                            <button type="submit" id="daily_payment_reporting" class="btn btn-block btn-warning"><strong>Daily</strong> Reporting</button>
                        </div>
                        <div class="col-xs-6">
                            <button type="submit" id="print_daily_payment_reporting" class="btn btn-block btn-warning"><strong>Print </strong>Daily Reporting</button>
                        </div>
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
                                <input id="end_date" type="text" class="form-control ref_date" name="end_date" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="all_payment_reporting" class="btn btn-block btn-success"><strong>All Payment</strong> Reporting</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="range_payment_reporting" class="btn btn-block btn-info"><strong>Show</strong></button>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <div class="col-xs-6">
                            <button type="submit" id="due_payment_reporting" class="btn btn-block btn-danger"><strong>Due</strong> Reporting</button>
                        </div>
                        <div class="col-xs-6">
                            <button type="submit" id="print_due_payment_reporting" class="btn btn-block btn-danger"><strong>Print</strong> Due Reporting</button>
                        </div>
                    </div>
                </div>


            </div>

        </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box-body -->
        
    <div id="box_color" class="box box-primary">
            <div class="box-header">
                <h3 class="box-title" id="payment_title">Payment Reporting</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id='all_user_list' class='table table-bordered table-striped'>
                <thead>
                    <tr>
                        <th>Student Id</th>
                        <th>Student Name</th>
                        <th>Phone Number</th>
                        <th id="alternate_data">Payment Date</th>
                        <th id="total_amount">Total Amount/-</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- user list -->
                </tbody>
                </table>   
            </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box -->


</section>
<!-- /.content -->

@endsection

