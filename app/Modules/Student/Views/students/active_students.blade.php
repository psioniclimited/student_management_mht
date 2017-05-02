@extends('master')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<!-- DataTables Printing Operation -->
<link rel="stylesheet" href="{{asset('plugins/DataTablePrint/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/DataTablePrint/buttons.dataTables.min.css')}}">
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<!-- DataTables Printing Operation -->
<script src="{{asset('plugins/DataTablePrint/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.flash.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/jszip.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.print.min.js')}}"></script>

<script>
    $(document).ready(function () {        
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
            "ajax": "{{URL::to('/get_active_students')}}",
            "columns": [
                    {"data": "student_permanent_id"},
                    {"data": "name"},
                    {"data": "school.name"},
                    {"data": "batch", "name": "batch.name"},                    
                    {"data": "phone_home"},
                    {"data": "payable"},
                    {"data": "Link", name: 'link', orderable: false, searchable: false}
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    let total_price = 0;
                    for ( var i=0 ; i<aaData.length ; i++ ) {
                        console.log(aaData[i]['payable']);
                        total_price += aaData[i]['payable'];
                    }

                    let nCells = nRow.getElementsByTagName('th');
                    total_money = total_price;
                    nCells[4].innerHTML = total_price;
            },
            dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'List of Active Students',
                        "lengthChange": true,
                        "footer": true,
                        exportOptions: {
                            columns: [ 0, 1,2,3,4 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'List of Active Students',
                        "lengthChange": true,
                        "footer": true,
                        exportOptions: {
                            columns: [ 0, 1,2,3,4 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'List of Active Students',
                        "lengthChange": true,
                        "footer": true,
                         exportOptions: {
                             columns: [ 0, 1,2,3,4 ]
                         }
                    },
                    {
                        extend: 'print',
                        title: 'List of Active Students',
                        "lengthChange": true,
                        "footer": true,
                        exportOptions: {
                            columns: [ 0, 1,2,3,4 ]
                        }
                    },
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
        Students
        <small>all student list</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Students</a></li>
        <li class="active">All Students</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">            

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Student list</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="all_user_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Student Permanent ID</th>
                                <th>Student Name</th>
                                <th>School Name</th>
                                <th>Batch</th>
                                <th>Phone Home</th>
                                <th>Total Payable Amount /-</th>
                                <th>Action</th>                            
                            </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Total:</th> 
                            <th></th>
                            <th></th>
                          </tr>
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

     <!-- Delete Customer Modal -->
   <div class="modal fade" id="confirm_delete" role="dialog">
       <div class="modal-dialog">
           <!-- Modal content-->
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4 class="modal-title">Remove Parmanently</h4>
               </div>
               <div class="modal-body">
                   <p>Are you sure about this ?</p>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-danger" id="delete_customer">Delete</button>
                   <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
               </div>
           </div>
           <!-- /. Modal content ends here -->
       </div>
   </div>
   <!--  Delete Customer Modal ends here -->    

</section>
<!-- /.content -->
@endsection
