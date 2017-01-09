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
<script type="text/JavaScript" src="{{asset('plugins/JQueryPrintJS/jQuery.print.js')}}" ></script>

<!-- DataTables Printing Operation -->
<script src="{{asset('plugins/DataTablePrint/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.flash.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/jszip.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/DataTablePrint/buttons.print.min.js')}}"></script>
<script>
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


	$('#teacher_user_id').select2({
        allowClear: true,
        placeholder: 'Select Teacher',
        ajax: {
            url: "/get_all_teacher_for_payment",
            dataType: 'json',
            delay: 250,
            tags: true,
            data: function (params) {
              return {
                term: params.term, // search term
                page: params.page
              };
            },
            processResults: function (data, params) {
              // parse the results into the format expected by Select2
              // since we are using custom formatting functions we do not need to
              // alter the remote JSON data, except to indicate that infinite
              // scrolling can be used
              params.page = params.page || 1;
              // console.log(data);
              return {
                results: data,
                pagination: {
                  more: (params.page * 30) < data.total_count
                }
              };
            },
            cache: true
        }
    });

    $("#all_batch_for_teacher_payment").click(function() {
        
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
                    'url': "{{URL::to('/get_all_batch_for_teacher_payment')}}",
                    'data': {
                       teacher_user_id: $('select[id=teacher_user_id]').val(),
                       ref_date: $('input[id=ref_date]').val()
                    },
                },
            "columns": [
                    {"data": "name"},
                    {"data": "teacher_payment_per_batch"},
                    {"data": "Link", name: 'link', orderable: false, searchable: false}
                ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
                    
                    var total_price = 0;
                    for ( var i=0 ; i<aaData.length ; i++ ) {
                        console.log(aaData[i]['teacher_payment_per_batch']);
                        total_price += aaData[i]['teacher_payment_per_batch'];
                    }

                    var nCells = nRow.getElementsByTagName('th');
                    nCells[1].innerHTML = total_price;
                },
            dom: 'Bfrtip',
            buttons: [
                    'copy',
                    {
                        extend: 'csvHtml5',
                        title: 'Payment for '+$('select[id=teacher_user_id]').val(),
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
                        title: 'Payment for '+$('#teacher_user_id').text()+"\n"+" Date: "+ $('input[id=ref_date]').val(),
                        "footer": true
                    }
                ]
            });

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
        Payment Dashboard
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Payment</a></li>
        <li class="active">Student Payment Page</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    
    <!-- Horizontal Form -->
    <div class="box box-danger">
        
        <div class="box-body">
        
            <div class="box-header with-border">
              <h3 class="box-title">Teacher Payment for all Batches</h3>
            </div>
            <div class="box-body">
                <div class="row">
	                <div class="col-xs-4">
	                    <div class="form-group">
	                        <label for="start_date">Refference Date</label>
	                        <div class="input-group date">
	                            <div class="input-group-addon">
	                                <i class="fa fa-calendar"></i>
	                            </div>
	                            <input id="ref_date" type="text" class="form-control ref_date" name="ref_date" value="{{ $refDate }}" autocomplete="off">
	                        </div>
	                    </div>
	                </div>
	                
                    
	                <div class="col-xs-4">
	                    <label for="batch_id" >Student*</label>
	                    <select class="form-control select2" name="teacher_user_id" id="teacher_user_id"></select>
                	</div>
	                <div class="col-xs-4">
	                    <label for="" ></label>
	                    <button type="submit" id="all_batch_for_teacher_payment" class="btn btn-block btn-success">Show</button>
	                </div>
                    
                    
                </div>
            </div>
        </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box-body -->




        <!-- Horizontal Form -->
    <div class="box box-warning">
            <div class="box-header">
                <h4>
                    All Batches under 
                </h4>            
            </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="all_user_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Batch Name</th>
                                <th>Price Tk/=</th>
                                <th>Action</th>                            
                            </tr>
                        </thead>
                        <tfoot>
                          <tr>
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

