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
<script>
    // add the rule here
    $.validator.addMethod("valueNotEquals", function (value, element, arg) {
        return arg != value;
    }, "Value must not equal arg.");

    $(document).ready(function () {
        
    var table = "";

	//Date picker for Start Date
    $('#ref_date').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
    });


	$('#student_id').select2({
        allowClear: true,
        placeholder: 'Select Student',
        ajax: {
            url: "/get_all_student_for_payment",
            dataType: 'json',
            delay: 250,
            tags: true,
            data: function (params) {
              return {
                q: params.term, // search term
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


    $("#student_info_for_payment").ajaxForm({
        url: '/get_student_info_for_payment', 
        type: 'post',
        clearForm: true,
        success: function(data) {
           $('p#student_name').text(data.name);
           $('p#student_email').text(data.email);
           $('p#fathers_name').text(data.fathers_name);
           $('p#mothers_name').text(data.mothers_name);
           $('p#phone_home').text(data.phone_home);
           $('p#phone_away').text(data.phone_away);

           	table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_batch_info_for_payment/'. data.id)}}",
            "columns": [
                    {"data": "name"},
                    {"data": "price"},
                    {"data": "price"},
                    {"data": "batch_type.name"},                    
                    {"data": "grade.name"},
                    {"data": "start_date"},
                    {"data": "end_date"},
                    {"data": "Link", name: 'link', orderable: false, searchable: false}
                ]
        	});
        } 
    });





        // initialize tooltipster on text input elements
        // $('form input,select,textarea').tooltipster({
        //     trigger: 'custom',
        //     onlyOne: false,
        //     position: 'right'
        // });

        // initialize validate plugin on the form
        // $('#add_member_form').validate({
        //     errorPlacement: function (error, element) {

        //         var lastError = $(element).data('lastError'),
        //                 newError = $(error).text();

        //         $(element).data('lastError', newError);

        //         if (newError !== '' && newError !== lastError) {
        //             $(element).tooltipster('content', newError);
        //             $(element).tooltipster('show');
        //         }
        //     },
        //     success: function (label, element) {
        //         $(element).tooltipster('hide');
        //     },
        //     rules: {
        //         fullname: {
        //             required: true
        //         },
        //         date_of_birth: {
        //             required: true
        //         },
        //         addrs: {
        //             required: true
        //         },
        //         mob_num: {
        //             required: true
        //         },
        //         off_num: {
        //             required: true
        //         },
        //         email: {
        //             required: true
        //         },
        //         member_type: {
        //             valueNotEquals: "default"
        //         },
        //         password: {
        //             required: true
        //         },
        //         password_confirmation: {
        //             required: true
        //         },
        //         pic: {
        //             required: true
        //         }
                
                
                
        //     },
        //     messages: {
        //         fullname: {
        //             required: "provide fullname"
        //         },
        //         date_of_birth: {
        //             required: "provide date of birth"
        //         },
        //         addrs: {
        //             required: "provide address"
        //         },
        //         mob_num: {
        //             required: "provide mobile number"
        //         },
        //         off_num: {
        //             required: "provide office number"
        //         },
        //         email: {
        //             required: "provide email"
        //         },
        //         member_type: {
        //             valueNotEquals: "provide member type"
        //         },
        //         password: {
        //             valueNotEquals: "provide password"
        //         },
        //         password_confirmation: {
        //             valueNotEquals: "provide password again"
        //         },
        //         pic: {
        //             required: "provide a photo"
        //         }
        //     }
        // });

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
    <div class="box box-success">
        
        <div class="box-body">
        
            <div class="box-header with-border">
              <h3 class="box-title">Search for a Student</h3>
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
	                            <input type="text" class="form-control" id="ref_date" name="ref_date" value="{{ $refDate }}">
	                        </div>
	                    </div>
	                </div>
	                {!! Form::open(array('id' => 'student_info_for_payment')) !!}
	                <div class="col-xs-4">
	                    <label for="batch_id" >Student*</label>
	                    <select class="form-control select2" name="student_id" id="student_id"></select>
                	</div>
	                <div class="col-xs-4">
	                    <label for="" ></label>
	                    <button type="submit" class="btn btn-block btn-success">Show</button>
	                {!! Form::close() !!}
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
            <div class="box-header with-border">
              <h3 class="box-title">Student's Information</h3>
            </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name" >Student Name : </label>
                        <p id="student_name"></p>
                    </div>
                    <div class="form-group">
                        <label for="student_email" >Email : </label>
                        <p id="student_email"></p>
                    </div>
                    <div class="form-group">
                        <label for="fathers_name" >Father's Name : </label>
                        <p id="fathers_name"></p>
                    </div>
                    <div class="form-group">
                        <label for="mothers_name" >Mother's Name : </label>
                        <p id="mothers_name"></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phone_home" >Phone(Home) : </label>
                        <p id="phone_home"></p>
                    </div>
                    <div class="form-group">
                        <label for="phone_away" >Phone(Additional) : </label>
                        <p id="phone_away"></p>
                    </div>
                </div>
                
                <div class="col-md-4">
                </div>
            </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box -->


	<!-- Horizontal Form -->
    <div class="box box-warning">
            <div class="box-header">
                <h4>
                    All Batches under <b></b>
                </h4>            
            </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="all_user_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Schedule</th>
                                <th>Batch Name</th>
                                <th>Price Tk/=</th>
                                <th>Batch Type</th>
                                <th>Grade</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>                            
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

