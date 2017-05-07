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
        $("#box_color").attr("class","box box-success");
        $("#payment_title").html("<p><b>Daily</b> Payment Reporting</p>");
        $("#alternate_data").text("Payment Date");
        $("#batch_info").text("Batches(Batch name, Price, Payment for)");
        $("#invoice_info").text("Invoice ID");
        $("#phone_num").text("Phone Number");
        $("#total_amount").text("Total Paid Amount/-");
        var daily_payment_reporting_table = $('#all_user_list').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "destroy": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_daily_reporting')}}",
            "columns": [
                    {"data": "serial_number"},
                    {"data": "student.student_permanent_id"},
                    {"data": "student.name"},
                    {"data": "student.student_phone_number"},                    
                    {"data": "payment_date"},
                    {"data": "paid_batches"},
                    {"data": "total"},
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    let total_price = parseFloat(0);;
                    for ( let i=0 ; i<aaData.length ; i++ ) {
                        total_price += parseFloat(aaData[i]['total']);
                    }

                    // let nCells = nRow.getElementsByTagName('th');
                    // nCells[nCells.length-1].innerHTML = total_price;
                    $('#total_taka').text(total_price);
                    // nCells = total_price;
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

    $("#refund_reporting").click(function() {
        $("#box_color").attr("class","box box-primary");
        $("#payment_title").html("<p><b>Daily</b> Payment Reporting</p>");
        $("#alternate_data").text("Batch Name");
        $("#batch_info").text("Payment For");
        $("#invoice_info").text("Invoice ID");
        $("#phone_num").text("Payment Date");
        $("#total_amount").text("Total Amount/-");
        var daily_payment_reporting_table = $('#all_user_list').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "destroy": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/refund_reporting')}}",
            "columns": [
                    {"data": "invoice_detail.invoice_master.serial_number"},
                    {"data": "invoice_detail.invoice_master.student.student_permanent_id"},
                    {"data": "invoice_detail.invoice_master.student.name"},
                    {"data": "invoice_detail.invoice_master.payment_date"},                    
                    {"data": "invoice_detail.batch.name"},
                    {"data": "invoice_detail.payment_from"},
                    {"data": "amount"},
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    let total_price = parseFloat(0);;
                    for ( let i=0 ; i<aaData.length ; i++ ) {
                        total_price += parseFloat(aaData[i]['amount']);
                    }

                    // let nCells = nRow.getElementsByTagName('th');
                    // nCells[nCells.length-1].innerHTML = total_price;
                    $('#total_taka').text(total_price);
                    // nCells = total_price;
                },
            dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'Refund Reporting',
                        "footer": true
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Refund Reporting',
                        "footer": true
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Refund Reporting',
                        "footer": true
                    },
                    {
                        extend: 'print',
                        title: 'Refund Reporting',
                        "footer": true
                    }
                ]
            });
    });
    
    $("#monthly_payment_reporting").click(function() {
        $("#box_color").attr("class","box box-success");
        $("#payment_title").html("<b>Current Month</b> Payment Reporting");
        $("#alternate_data").text("Payment Date");
        $("#invoice_info").text("Invoice ID");
        $("#batch_info").text("Batches(Batch name, Price, Payment for)");
        $("#phone_num").text("Phone Number");
        $("#total_amount").text("Total Paid Amount/-");
        var table = $('#all_user_list').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "destroy": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_monthly_reporting')}}",
            "columns": [
                    {"data": "serial_number"},
                    {"data": "student.student_permanent_id"},
                    {"data": "student.name"},
                    {"data": "student.phone_home"},                    
                    {"data": "payment_date"},
                    {"data": "paid_batches"},
                    {"data": "total"},
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    let total_price = 0.0;
                    for ( let i=0 ; i<aaData.length ; i++ ) {
                        total_price += parseFloat(aaData[i]['total']);
                    }
                    // let nCells = nRow.getElementsByTagName('th');
                    // nCells[nCells.length-1].innerHTML = total_price;
                    $('#total_taka').text(total_price);
                    // nCells = total_price;
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

    $("#range_payment_reporting").click(function() {
        if ($('input[id=start_date]').val() && $('input[id=end_date]').val()) {
            $("#box_color").attr("class","box box-info");
            $("#payment_title").html("<p>Payment Reporting from <b>"+$('input[id=start_date]').val()+"</b> to <b>"+$('input[id=end_date]').val()+"</b></p>");
            $("#alternate_data").text("Payment Date");
            $("#batch_info").text("Batches(Batch name, Price, Payment for)");
            $("#invoice_info").text("Invoice ID");
            $("#phone_num").text("Phone Number");
            $("#total_amount").text("Total Paid Amount/-");
            var table = $('#all_user_list').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
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
                        {"data": "serial_number"},
                        {"data": "student.student_permanent_id"},
                        {"data": "student.name"},
                        {"data": "student.student_phone_number"},
                        {"data": "payment_date"},
                        {"data": "paid_batches"},
                        {"data": "total"},
                    ],
                "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    let TotalRangePrice = parseFloat(0);
                    for ( let i=0 ; i<aaData.length ; i++ ) {
                        TotalRangePrice += parseFloat(aaData[i]['total']);
                    }

                    // var nCells = nRow.getElementsByTagName('th');
                    // nCells[nCells.length-1].innerHTML = TotalRangePrice;
                    $('#total_taka').text(TotalRangePrice);
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

    $("#monthly_statement").click(function() {
        if ($('input[id=statement_date]').val()) {
            $("#box_color").attr("class","box box-warning");
            $("#payment_title").html("<p>Monthly Statement for <b>"+$('input[id=statement_date]').val()+"</b></p>");
            $("#alternate_data").text("Payment For");
            $("#batch_info").text("Batches(Batch name)");
            $("#invoice_info").text("Invoice ID");
            $("#phone_num").text("Payment Date");
            $("#total_amount").text("Total Paid Amount/-");
            var table = $('#all_user_list').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "destroy": true,
                "info": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true, 
                "ajax": {
                    'url': "{{URL::to('/monthly_statement')}}",
                    'data': {
                       statement_date: $('input[id=statement_date]').val() 
                    },
                },
                "columns": [
                        {"data": "invoice_master.serial_number"},
                        {"data": "invoice_master.student.student_permanent_id"},
                        {"data": "invoice_master.student.name"},
                        {"data": "invoice_master.payment_date"}, 
                        {"data": "payment_from"},
                        {"data": "batch.name"},
                        {"data": "price"},
                    ],
                "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    let TotalRangePrice = parseFloat(0);
                    for ( let i=0 ; i<aaData.length ; i++ ) {
                        TotalRangePrice += parseFloat(aaData[i]['price']);
                    }

                    // var nCells = nRow.getElementsByTagName('th');
                    // nCells[nCells.length-1].innerHTML = TotalRangePrice;
                    $('#total_taka').text(TotalRangePrice);
                },
                dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'Monthly Payment Statement for '+$('input[id=statement_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Monthly Payment Statement for '+$('input[id=statement_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Monthly Payment Statement for '+$('input[id=statement_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'print',
                        title: 'Monthly Payment Statement for '+$('input[id=start_date]').val()+' to '+ $('input[id=end_date]').val(),
                        "footer": true
                    }
                ]
            });
        }

    });

    $("#due_payment_reporting").click(function() {
        $("#box_color").attr("class","box box-danger");
        $("#payment_title").html("<p><b>Due</b> Payment Reporting</p>");
        $("#alternate_data").text("Guardian's Phone Number");
        $("#batch_info").text("Batches(Batch name, Price, Last Paid Date)");
        $("#invoice_info").text("Student ID");
        $("#phone_num").text("Student's Phone Number");
        $("#total_amount").text("Total Due Amount/-");
        var table = $('#all_user_list').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "destroy": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_due_reporting')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "student_permanent_id"},
                    {"data": "name"},
                    {"data": "student_phone_number"},                    
                    {"data": "guardian_phone_number"},
                    {"data": "due_batches"},
                    {"data": "TotalDuePrice"},
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    let TotalDuePrice = parseFloat(0);
                    for ( let i=0 ; i<aaData.length ; i++ ) {
                        TotalDuePrice += parseFloat(aaData[i]['TotalDuePrice']);
                    }

                    let nCells = nRow.getElementsByTagName('th');
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

    $("#due_statement").click(function() {
        if ($('input[id=due_statement_date]').val()) {
            $("#box_color").attr("class","box box-danger");
            $("#payment_title").html("<p>Due Payment Statement for <b>"+$('input[id=due_statement_date]').val()+"</b></p>");
            $("#alternate_data").text("Payment Date");
            $("#phone_num").text("Phone Number");
            $("#total_amount").text("Total Amount/-");
            var table = $('#all_user_list').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "destroy": true,
                "info": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true, 
                "ajax": {
                    'url': "{{URL::to('/due_statement')}}",
                    'data': {
                       due_statement_date: $('input[id=due_statement_date]').val() 
                    },
                },
                "columns": [
                        {"data": "student.name"},
                        {"data": "student.phone_home"},
                        {"data": "due_batches"},                    
                        {"data": "payment_date"},
                        {"data": "total"},
                    ],
                "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    let TotalRangePrice = parseFloat(0);
                    for ( let i=0 ; i<aaData.length ; i++ ) {
                        TotalRangePrice += parseFloat(aaData[i]['total']);
                    }

                    // var nCells = nRow.getElementsByTagName('th');
                    // nCells[nCells.length-1].innerHTML = TotalRangePrice;
                    $('#total_taka').text(TotalRangePrice);
                },
                dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'Due Statement for '+$('input[id=statement_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Due Statement for '+$('input[id=statement_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Due Statement for '+$('input[id=statement_date]').val(),
                        "footer": true
                    },
                    {
                        extend: 'print',
                        title: 'Due Statement for '+$('input[id=statement_date]').val(),
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
    
    <!-- Test content -->
    <!-- <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Choose Reporting Option</h3>
        </div>
        
        <div class="box-body">
            <div class="row">
                <div class="col-xs-2">
                    <button type="submit" id="daily_payment_reporting" class="btn btn-block btn-success"><strong>Daily</strong> Collection</button>
                </div>
                <div class="col-xs-2">
                    <button type="submit" id="due_payment_reporting" class="btn btn-block btn-danger"><strong>Due</strong> Reporting</button>
                </div>
           </div>  
        </div>
        
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Choose Reporting Option</h3>
        </div>
        
        <div class="box-body">
            <div class="row">
                asdfasdf
           </div>  
        </div>
        
    </div> -->
    <!-- Test content -->


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
                        <button type="submit" id="daily_payment_reporting" class="btn btn-block btn-success"><strong>Daily</strong> Collection</button>
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
                    <!-- <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="monthly_payment_reporting" class="btn btn-block btn-success"><strong>Current Month</strong> Collection</button>
                    </div> -->
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="refund_reporting" class="btn btn-block btn-primary"><strong>Refund</strong> Reporting</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="range_payment_reporting" class="btn btn-block btn-info"><strong>Show Date Range</strong>  Collection</button>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="due_payment_reporting" class="btn btn-block btn-danger"><strong>Due</strong> Reporting</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="statement_date">Month (For Monthly Payment Statement)</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input id="statement_date" type="text" class="form-control ref_date" name="statement_date" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="monthly_statement" class="btn btn-block btn-warning"><strong>Monthly Payment </strong> Statement</button>
                    </div>
                </div>
                <hr>
                <!-- <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="due_statement_date">Month (For Monthly Due Statement)</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input id="due_statement_date" type="text" class="form-control ref_date" name="due_statement_date" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="due_statement" class="btn btn-block btn-danger"><strong>Monthly Due</strong> Statement</button>
                    </div>
                </div> -->


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
                        <th id="invoice_info"></th>
                        <th>Student Permanent ID</th>
                        <th>Student Name</th>
                        <th id="phone_num">Phone Number</th>
                        <th id="alternate_data">Payment Date</th>
                        <th id="batch_info"></th>
                        <th id="total_amount">Total Amount/-</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Total:</th>
                        <th id="total_taka"></th> 
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

