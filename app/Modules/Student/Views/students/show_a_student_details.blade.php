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
                        <h3 class="profile-username text-center">Student Name : {{ $getStudent->name }}</h3>
                    </div>
                    <div class="form-group">
                        <p class="text-muted text-center">Joining Year : {{ $getStudent->joining_year }}</p>
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
                      <label for="phone_home" >Phone Home</label>
                      <p>{{ $getStudent->phone_home }}</p>
                    </div>
                    <div class="form-group">
                        <label for="phone_away" >Additional Phone Number</label>
                        <p>{{ $getStudent->phone_away }}</p>  
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email" >Email</label>
                        <p>{{ $getStudent->student_email }}</p>
                    </div>
                    <div class="form-group">
                        <label for="school" >School</label>
                        <p>{{ $getStudent->school->name }}</p>  
                    </div>
                    <div class="form-group">
                        <label for="batch_type">Education Board</label>
                        <p>{{ $getStudent->batch_type->name }}</p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
        </form>
        <!-- /.form ends here -->
    </div>
    <!-- /.box -->


    <!-- Teacher payment Datatable -->
    <div class="box box-warning">
        <div class="box-header">
                <h4><strong>Payment History</strong></h4>
        </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="student_payment_history" class="table table-bordered table-striped">
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
            </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box -->




</section>
<!-- /.content -->

@endsection

