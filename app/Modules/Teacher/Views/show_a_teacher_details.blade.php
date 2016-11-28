@extends('master')

@section('css')
<link rel="stylesheet" href="plugins/tooltipster/tooltipster.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="../../plugins/datepicker/datepicker3.css">
@endsection

@section('scripts')
<script src="plugins/validation/dist/jquery.validate.js"></script>
<script src="plugins/tooltipster/tooltipster.js"></script>
<!-- bootstrap datepicker -->
<script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>

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
            "ajax": "{{URL::to('/get_teachers')}}",
            "columns": [
                    {"data": "id"},
                    {"data": "user.name"},
                    {"data": "user.email"},                    
                    {"data": "subject.name"},
                    {"data": "Link", name: 'link', orderable: false, searchable: false}
                ]
        });

        // Delete Batch
        $('#confirm_delete').on('show.bs.modal', function(e) {
           var $modal = $(this),
               user_id = e.relatedTarget.id;
               console.log(user_id);

           $('#delete_customer').click(function(e){    
               // event.preventDefault();
               $.ajax({
                   cache: false,
                   type: 'POST',
                   url: 'teacher/' + user_id + '/delete',
                   data: user_id,
                   success: function(data) {
                       console.log("Deleted Successfully");
                       table.ajax.reload(null, false);
                       $('#confirm_delete').modal('toggle');
                   }
               });
           });
        });







        // initialize tooltipster on text input elements
        $('form input,select,textarea').tooltipster({
            trigger: 'custom',
            onlyOne: false,
            position: 'right'
        });

        // initialize validate plugin on the form
        $('#add_member_form').validate({
            errorPlacement: function (error, element) {

                var lastError = $(element).data('lastError'),
                        newError = $(error).text();

                $(element).data('lastError', newError);

                if (newError !== '' && newError !== lastError) {
                    $(element).tooltipster('content', newError);
                    $(element).tooltipster('show');
                }
            },
            success: function (label, element) {
                $(element).tooltipster('hide');
            },
            rules: {
                fullname: {
                    required: true
                },
                date_of_birth: {
                    required: true
                },
                addrs: {
                    required: true
                },
                mob_num: {
                    required: true
                },
                off_num: {
                    required: true
                },
                email: {
                    required: true
                },
                member_type: {
                    valueNotEquals: "default"
                },
                password: {
                    required: true
                },
                password_confirmation: {
                    required: true
                },
                pic: {
                    required: true
                }
                
                
                
            },
            messages: {
                fullname: {
                    required: "provide fullname"
                },
                date_of_birth: {
                    required: "provide date of birth"
                },
                addrs: {
                    required: "provide address"
                },
                mob_num: {
                    required: "provide mobile number"
                },
                off_num: {
                    required: "provide office number"
                },
                email: {
                    required: "provide email"
                },
                member_type: {
                    valueNotEquals: "provide member type"
                },
                password: {
                    valueNotEquals: "provide password"
                },
                password_confirmation: {
                    valueNotEquals: "provide password again"
                },
                pic: {
                    required: "provide a photo"
                }
            }
        });

        //Date picker
        $('#datepicker').datepicker({
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

            <!-- /.box-footer -->
    </div>
    <!-- /.box -->

    <h3>
        Create a new Batch
    </h3>


    <!-- Horizontal Form -->
    <div class="box box-info">
        
        
            <div class="box-body">
            
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

            <!-- /.box-footer -->
    </div>
    <!-- /.box -->

    

    <h3>
        All Batches under this Teacher
    </h3>
    <!-- Horizontal Form -->
    <div class="box box-info">
        
        
            <div class="box-body">
            
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

            <!-- /.box-footer -->
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->

@endsection

