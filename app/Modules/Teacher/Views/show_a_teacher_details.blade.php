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
        var table = $('#all_user_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/get_batches/'.$getTeacher->id)}}",
            "columns": [
                    {"data": "Schedule"},
                    {"data": "name"},
                    {"data": "price"},
                    {"data": "batch_type.name"},                    
                    {"data": "grade.name"},
                    {"data": "start_date"},
                    {"data": "end_date"},
                    {"data": "Link", name: 'link', orderable: false, searchable: false}
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
                   url: 'batch/' + user_id + '/delete',
                   data: user_id,
                   success: function(data) {
                       console.log("Deleted Successfully");
                       table.ajax.reload(null, false);
                       $('#confirm_delete').modal('toggle');
                   }
               });
           });
        });

       $('#batch_id').select2({
            allowClear: true,
            placeholder: 'Set Day and Time',
            ajax: {
                url: "/getallbatch",
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

        //Date picker
        $('#datepicker').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
        });

        $("#add_batch_form").ajaxForm({
            url: '/create_new_batch_process', 
            type: 'post',
            clearForm: true,
            success:  function(e) { 
                console.log(e); 
            } 
        });

        //Date picker for Start Date
        $('#start_date').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
        });

        //Date picker End Date
        $('#end_date').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true
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
        Teacher Information
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Teacher</a></li>
        <li class="active">Teacher Detail Page</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Horizontal Form -->
    <div class="box box-info">
            <div class="box-body">
            <div class="box-header with-border">
              <h3 class="box-title">Teacher's Information</h3>
            </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name" >Teacher Name</label>
                        <p>{{ $getTeacher->user->name }}</p>
                    </div>
                    <div class="form-group">
                        <label for="email" >Email</label>
                        <p>{{ $getTeacher->user->email }}</p>
                    </div>
                    
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="addrs" >Description</label>
                        <p>{{ $getTeacher->description }}</p>  
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <p>{{ $getTeacher->subject->name }}</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                </div>
            </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box -->






    <!-- Horizontal Form -->
    <div class="box box-success">
        
        <div class="box-body">
        
            <div class="box-header with-border">
              <h3 class="box-title">Create a New Batch</h3>
            </div>
            <div class="box-body">
                <div class="row">
                {!! Form::open(array('id' => 'add_batch_form')) !!}
                <div class="col-xs-1">
                    <label for="name" >Batch name*</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Batch name">
                </div>
                <div class="col-xs-1">
                    <label for="price" >Price*</label>
                    <input type="text" class="form-control" name="price" id="price" placeholder="Price">
                </div>
                <div class="col-xs-1">
                    <div class="form-group">
                        <label for="batch_types_id" >Batch Type*</label>
                        <select class="form-control" name="batch_types_id">
                                <option value="default">Choose...</option>
                                @foreach ($batchType as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-1">
                    <div class="form-group">
                        <label for="grades_id" >Grade*</label>
                        <select class="form-control" name="grades_id">
                            <option value="default">Choose...</option>
                            @foreach ($getGrades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="batch_id" >Schedule*</label>
                        <select class="form-control select2" name="batch_day_time[]" id="batch_id" multiple></select>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="start_date" >Start Date</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Select Start Date">
                        </div>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for="end_date" >End Date</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Select end date">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="teacher_details_id" value="{{ $getTeacher->id }}">
                <input type="hidden" name="teacher_details_users_id" value="{{ $getTeacher->user->id }}">
                <div class="col-xs-2">
                    <label for="" ></label>
                    <button type="submit" class="btn btn-block btn-success">Add Batch</button>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <!-- Horizontal Form -->
    <div class="box box-warning">
            <div class="box-header">
                <h4>
                    All Batches under <b>{{ $getTeacher->user->name }}</b>
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

