@extends('master')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script>
    $(document).ready(function () {        
        var table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_due_reporting')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "student.name"},
                    {"data": "student.phone_home"},                    
                    {"data": "payment_date"},
                    {"data": "total"},
                ]
        });

        // Delete Customer
       $('#confirm_delete').on('show.bs.modal', function(e) {
           var $modal = $(this),
               user_id = e.relatedTarget.id;
               console.log(user_id);

           $('#delete_customer').click(function(e){    
               // event.preventDefault();
               $.ajax({
                   cache: false,
                   type: 'POST',
                   url: 'student/' + user_id + '/delete',
                   data: user_id,
                   success: function(data) {
                       console.log("Deleted Successfully");
                       table.ajax.reload(null, false);
                       $('#confirm_delete').modal('toggle');
                   }
               });
           });
        })
    });
</script>

@endsection



@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Payment of Students
        <small>today's payment list</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reporting</a></li>
        <li class="active">Payment List</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">            

            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Due Payment Reporting</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="all_user_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student Id</th>
                                <th>Student Name</th>
                                <th>Student Phone Number</th>
                                <th>Payment Date</th>
                                <th>Paid Amount /=</th>
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
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
<!-- /.content -->
@endsection

