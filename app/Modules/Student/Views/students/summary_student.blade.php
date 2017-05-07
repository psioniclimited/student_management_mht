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
<!-- <script src="http://www.position-absolute.com/creation/print/jquery.printPage.js" ></script> -->
<!-- <script src="{{asset('plugins/jqueryPrintArea/jquery.PrintArea.js')}}" ></script> -->
<script type="text/JavaScript" src="{{asset('plugins/JQueryPrintJS/jQuery.print.js')}}" ></script>
<script>
    // add the rule here
    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg != value;
    }, "Value must not equal arg.");

    $(document).ready(function () {

        //Date picker for Start Date
        $('.summary_month').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
        });


		var table = $('#all_user_list').DataTable({
            "paging": true,
            "pageLength": 50,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_all_batches_and_students')}}",
            "columns": [
                    {"data": "name"},
                    // {"data": "teacher_name"},
                    {"data": "total_number_of_students"},
                ]
        });

        $("#summary_month_submit").click(function() {
            if ( $('input[id=summary_month]').val() !== '' )  {
                console.log('Inside IF');
                console.log($('input[id=summary_month]').val());
                $.get("/monthly_paryment_summary", { 
                        summary_month: $('input[id=summary_month]').val(),
                })
                .done(function( data ) {
                   console.log(data);
                   $('#total_students').text(data.total_students);
                   $('#total_expected_amount').text(data.total_expected_amount);
                   $('#total_paid_amount').text(data.total_paid_amount);
                   $('#total_unpaid_amount').text(data.total_unpaid_amount);
                });     
            }
            else {
                console.log('Outside IF');
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
        Summary
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Student</a></li>
        <li class="active">Student Summary</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    

    <!-- Horizontal Form -->
    <div class="box box-primary">
        
        <div class="box-body">
        
            <div class="box-header with-border">
              <h3 class="box-title">Choose a Month and Year with any Date to Show Summary</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    
                    <div class="col-xs-6">
                        <label for="schools_id" > Month</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input id="summary_month" type="text" class="form-control summary_month" name="summary_month" autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <label for="" ></label>
                        <button type="submit" id="summary_month_submit" class="btn btn-block btn-success">Show</button>
                    </div>
                    
                    
                </div>
            </div>
        </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box-body -->

    <!-- Horizontal Form -->
    <div class="box box-info">
        
        <div class="box-body">
        
            <div class="box-header">
                <div class="row">
                    <div class="col-xs-6">
                        <h2>Total Students: <strong id="total_students"></strong></h2> 
                        <h2>Total Expected Amount: <strong id="total_expected_amount"></strong></h2>
                    </div>
                    <div class="col-xs-6">
                        <h2>Total Paid Amount: <strong id="total_paid_amount"></strong></h2>
                        <h2>Total Unpaid Amount: <strong id="total_unpaid_amount"></strong></h2> 
                    </div>
                </div>
            </div>
        </div>
            <!-- /.box-body -->
    </div>
    <!-- Horizontal Form -->




	<div class="box box-warning">
        <div class="box-header">
            <h3 class="box-title">Batch list</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="all_user_list" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Batch Name</th>
                        <!-- <th>Teacher Name</th> -->
                        <th>Total number of students</th>
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

