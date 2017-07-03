@extends('master')

@section('css')

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
    $(document).ready(function () {
        var table = $('#student_payment_history').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "destroy": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                    'url': "{{URL::to('/get_student_payment_history')}}",
                    'data': {
                       student_id: "{{ $getStudent->id }}",
                    },
                },
            "columns": [
                    {"data": "name"},
                    {"data": "price"},
                    {"data": "pivot.last_paid_date"},
                ]
        });

        var transaction_table = $('#student_transaction_history').DataTable({
            "paging": false,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "destroy": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                    'url': "{{URL::to('/get_student_transaction_history')}}",
                    'data': {
                       student_id: "{{ $getStudent->id }}",
                    },
                },
            "columns": [
                    {"data": "invoice_master.serial_number"},
                    {"data": "invoice_master.payment_date"},
                    {"data": "batch.name"},
                    {"data": "payment_from"},
                    {"data": "price"},
                ],
            "fnCreatedRow": function ( row, data, index ) {
                // if (data.refund) {
                //     $(row).css("color", "red");
                // }
            
        },
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
        Student Detail Information
        
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Student</a></li>
        <li class="active">Detail</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Horizontal Form -->
    <div class="box box-info">
        
        
            <div class="box-body">
            
            
                <div class="col-md-4">
                    
                    <div class="form-group">
                        <img src="{{ URL::to('/') }}/{{ $getStudent->students_image }}" class='profile-user-img img-responsive' height='200' width='200' alt='Student profile picture'>
                    </div>
                    <div class="form-group">
                        <h2 class="profile-username text-center">Student Name : {{ $getStudent->name }}</h2>
                    </div>
                    <div class="form-group">
                        <h3 class="text-muted text-center">Permanent ID : {{ $getStudent->student_permanent_id }}</h3>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fathers_name" >Fathers Name</label>
                        <p>{{ $getStudent->fathers_name }}</p>
                    </div>
                    <div class="form-group">
                        <label for="mothers_name" >Mothers Name</label>
                        <p>{{ $getStudent->mothers_name }}</p>
                    </div>
                    <div class="form-group">
                      <label for="phone_home" >Student's Phone Number</label>
                      <p>{{ $getStudent->student_phone_number }}</p>
                    </div>
                    <div class="form-group">
                        <label for="phone_away" >Guardian's Phone Number</label>
                        <p>{{ $getStudent->guardian_phone_number }}</p>  
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email" >Email</label>
                        <p>{{ $getStudent->student_email }}</p>
                    </div>
                    <div class="form-group">
                        <label for="school" >School</label>
                        @if ($getStudent->school)
                            <p>{{ $getStudent->school->name }}</p>
                        @else
                            <p></p>
                        @endif  
                    </div>
                    <div class="form-group">
                        <label for="batch_type">Education Board</label>
                        @if ($getStudent->batch_type)
                            <p>{{ $getStudent->batch_type->name }}</p>
                        @else
                            <p></p>
                        @endif 
                    </div>
                    <div class="form-group">
                        <label for="driving_license_number">Driving License Number</label>
                        <p>{{ $getStudent->driving_license_number }}</p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
        </form>
        <!-- /.form ends here -->
    </div>
    <!-- /.box -->


    
<!--     <div class="box box-widget widget-user-2">
        
        <div class="widget-user-header bg-yellow">
            <div class="widget-user-image">
                <img src="{{ URL::to('/') }}/{{ $getStudent->students_image }}" class='img-circle' alt='Student profile picture'>
            </div>
            
            <h1 class="widget-user-username">{{ $getStudent->name }}</h1>
            <h3 class="widget-user-desc">Permanent ID : {{ $getStudent->student_permanent_id }}</h3>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Fathers Name <span class="pull-right badge bg-blue">{{ $getStudent->fathers_name }}</span></a></li>
                    <li><a href="#">Mothers Name <span class="pull-right badge bg-aqua">{{ $getStudent->mothers_name }}</span></a></li>
                    <li><a href="#">Student's Phone Number <span class="pull-right badge bg-green">{{ $getStudent->student_phone_number }}</span></a></li>
                    <li><a href="#">Guardian's Phone Number <span class="pull-right badge bg-red">{{ $getStudent->guardian_phone_number }}</span></a></li>
                  </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Projects <span class="pull-right badge bg-blue">31</span></a></li>
                    <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>
                    <li><a href="#">Completed Projects <span class="pull-right badge bg-green">12</span></a></li>
                    <li><a href="#">Followers <span class="pull-right badge bg-red">842</span></a></li>
                  </ul>
                </div>
            </div>
        </div>
    </div> -->
    

    <!-- Stydent Payment History -->
    <div class="box box-warning">
        <div class="box-header animated fadeInUp">
                <h4><strong>Payment Status</strong></h4>
        </div><!-- /.box-header -->
            
            <div class="box-body">
                <table id="student_payment_history" class="table table-bordered table-striped animated fadeInUp">
                    <thead>
                        <tr>
                            <th>Batch Name</th>
                            <th>Unit Price</th>
                            <th>Last paid Date</th>
                        </tr>
                    </thead>
                    <tbody>                            
                        <!-- user list -->
                    </tbody>                        
                </table>
            </div><!-- /.box-body -->
    </div>
    <!-- /.box -->


    <!-- Stydent Transaction History -->
    <div class="box box-warning">
        <div class="box-header animated fadeInUp">
                <h4>All transactions of <strong>{{ $getStudent->name }}</strong></h4>
        </div><!-- /.box-header -->
            
            <div class="box-body">
                <table id="student_transaction_history" class="table table-bordered table-striped animated fadeInUp">
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Payment Date</th>
                            <th>Batch Name</th>
                            <th>Payment For</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>                            
                        <!-- user list -->
                    </tbody>                        
                </table>
            </div><!-- /.box-body -->
    </div>
    <!-- /.box -->




</section>
<!-- /.content -->

@endsection

