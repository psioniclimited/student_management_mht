@extends('master')
@section('css')
<!-- jvectormap -->
<link rel="stylesheet" href="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
@endsection

@section('scripts')
<!-- Sparkline -->
<script src="{{asset('plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- ChartJS 1.0.1 -->
<script src="{{asset('plugins/chartjs/Chart.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{asset('dist/js/pages/dashboard2.js')}}"></script> -->
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script>
$(document).ready(function () {
   $('.count').each(function () {
      $(this).prop('Counter',0).animate({
          Counter: $(this).text()
      }, {
          duration: 1000,
          easing: 'swing',
          step: function (now) {
              $(this).text(Math.ceil(now));
          }
      });
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

});
</script>
@endsection


@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-navy color-palette"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Number of Students</span>
              <span class="info-box-number count">{{ $total_students }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-purple color-palette "><i class="ion ion-cash"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Expected Amount</span>
              <span class="info-box-number count" style="float: left">{{ $total_expected_amount }}</span>
              <strong> &nbsp; /-</strong>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa ion-social-usd-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Paid Amount</span>
              <span class="info-box-number count" style="float: left">{{ $total_paid_amount }}</span>
              <strong> &nbsp; /-</strong>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-social-usd"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Due Amount</span>
              <span class="info-box-number count" style="float: left">{{ $total_unpaid_amount }}</span>
              <strong> &nbsp; /-</strong>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
      </div>
      <!-- /.row -->

    <div class="box box-primary">
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
    </div><!-- /.box -->
      

    </section>
    <!-- /.content -->
@endsection