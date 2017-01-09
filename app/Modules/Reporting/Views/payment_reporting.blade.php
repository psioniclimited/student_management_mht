@extends('master')

@section('css')
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('../../plugins/datepicker/datepicker3.css')}}">

<!-- DataTables Printing Operation -->
<link rel="stylesheet" href="{{asset('plugins/DataTablePrint/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/DataTablePrint/buttons.dataTables.min.css')}}">
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

<!-- DataTables Printing Operation -->
<script src="{{asset('plugins/DataTablePrint/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.flash.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/jszip.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.print.min.js')}}"></script>


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

    var daily_payment_reporting_table = "";
    $("#daily_payment_reporting").click(function() {
        $("#box_color").attr("class","box box-warning");
        $("#payment_title").html("<p><b>Daily</b> Payment Reporting</p>");
        $("#alternate_data").text("Payment Date");
        $("#total_amount").text("Total Paid Amount/-");
        var daily_payment_reporting_table = $('#all_user_list').DataTable({
            "paging": false,
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
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    var total_price = 0;
                    for ( var i=0 ; i<aaData.length ; i++ ) {
                        console.log(aaData[i]['total']);
                        total_price += aaData[i]['total'];
                    }

                    var nCells = nRow.getElementsByTagName('th');
                    nCells[nCells.length-1].innerHTML = total_price;
                    var nCells = $('#total_money').text(total_price);
                    nCells = total_price;
                },
            dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'DailyPaymentReporting',
                        "footer": true
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'DailyPaymentReporting',
                        "footer": true
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'DailyPaymentReporting',
                        "footer": true
                    },
                    {
                        extend: 'print',
                        title: 'Daily Payment Report',
                        "footer": true
                    }
                ]
            });
    });


    
    $("#monthly_payment_reporting").click(function() {
        $("#box_color").attr("class","box box-success");
        $("#payment_title").html("<b>Monthly</b> Payment Reporting");
        $("#alternate_data").text("Payment Date");
        $("#total_amount").text("Total Paid Amount/-");
        var table = $('#all_user_list').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "destroy": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_monthly_reporting')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "student.name"},
                    {"data": "student.phone_home"},                    
                    {"data": "payment_date"},
                    {"data": "total"},
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    var total_price = 0;
                    for ( var i=0 ; i<aaData.length ; i++ ) {
                        console.log(aaData[i]['total']);
                        total_price += aaData[i]['total'];
                    }

                    var nCells = nRow.getElementsByTagName('th');
                    nCells[nCells.length-1].innerHTML = total_price;
                },
            dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'MonthlyPaymentReporting',
                        "footer": true
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'MonthlyPaymentReporting',
                        "footer": true
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Monthly Payment Report',
                        "footer": true
                    },
                    {
                        extend: 'print',
                        title: 'Monthly Payment Report',
                        "footer": true
                    }
                ]
            });
    });

    $("#due_payment_reporting").click(function() {
        $("#box_color").attr("class","box box-danger");
        $("#payment_title").html("<p><b>Due</b> Payment Reporting</p>");
        $("#alternate_data").text("Additional Phone Number");
        $("#total_amount").text("Total Due Amount/-");
        var table = $('#all_user_list').DataTable({
            "paging": false,
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
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    var TotalDuePrice = 0;
                    for ( var i=0 ; i<aaData.length ; i++ ) {
                        console.log(aaData[i]['TotalDuePrice']);
                        TotalDuePrice += aaData[i]['TotalDuePrice'];
                    }

                    var nCells = nRow.getElementsByTagName('th');
                    nCells[nCells.length-1].innerHTML = TotalDuePrice;
                },
            dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'DuePaymentReporting',
                        "footer": true
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'DuePaymentReporting',
                        "footer": true
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Due Payment Report',
                        "footer": true
                    },
                    {
                        extend: 'print',
                        title: 'Due Payment Report',
                        "footer": true
                    }
                ]

            });

    });
    
    $("#range_payment_reporting").click(function() {
        if ($('input[id=start_date]').val() && $('input[id=end_date]').val()) {
            $("#box_color").attr("class","box box-primary");
            $("#payment_title").html("<p>Payment Reporting from <b>"+$('input[id=start_date]').val()+"</b> to <b>"+$('input[id=end_date]').val()+"</b></p>");
            $("#alternate_data").text("Payment Date");
            $("#total_amount").text("Total Paid Amount/-");
            var table = $('#all_user_list').DataTable({
                "paging": false,
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
                    ],
                "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    var TotalDuePrice = 0;
                    for ( var i=0 ; i<aaData.length ; i++ ) {
                        console.log(aaData[i]['total']);
                        TotalDuePrice += aaData[i]['total'];
                    }

                    var nCells = nRow.getElementsByTagName('th');
                    nCells[nCells.length-1].innerHTML = TotalDuePrice;
                },
                dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'Payment Report from '+$('input[id=start_date]').val()+' to '+ $('input[id=end_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Payment Report from '+$('input[id=start_date]').val()+' to '+ $('input[id=end_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Payment Report from '+$('input[id=start_date]').val()+' to '+ $('input[id=end_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'print',
                        title: 'Payment Report from '+$('input[id=start_date]').val()+' to '+ $('input[id=end_date]').val(),
                        "footer": true
                    }
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
                        <button type="submit" id="daily_payment_reporting" class="btn btn-block btn-warning"><strong>Daily</strong> Reporting</button>
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
                        <button type="submit" id="monthly_payment_reporting" class="btn btn-block btn-success"><strong>Monthly Payment</strong> Reporting</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="range_payment_reporting" class="btn btn-block btn-info"><strong>Show</strong></button>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="due_payment_reporting" class="btn btn-block btn-danger"><strong>Due</strong> Reporting</button>
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
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total:</th>
                        <th></th> 
                    </tr>
                </tfoot>                
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

