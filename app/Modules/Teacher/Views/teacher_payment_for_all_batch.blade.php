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

    var month = ["January","February","March", "April",
                "May", "June","July", "August",
                "September","October","November","December"];

    var batch_length = 0;

	//Date picker for Start Date
    $('.ref_date').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
    });


	$('#student_id').select2({
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
    <div class="box box-danger">
        
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
	                            <input type="text" class="form-control ref_date" name="ref_date" value="{{ $refDate }}">
	                        </div>
	                    </div>
	                </div>
	                
                    
	                <div class="col-xs-4">
	                    <label for="batch_id" >Student*</label>
	                    <select class="form-control select2" name="student_id" id="student_id"></select>
                	</div>
	                <div class="col-xs-4">
	                    <label for="" ></label>
	                    <button type="submit" id="student_info_for_payment" class="btn btn-block btn-success">Show</button>
	                </div>
                    
                    
                </div>
            </div>
        </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box-body -->





</section>
<!-- /.content -->

@endsection
