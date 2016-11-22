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
        Member Information
        
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Horizontal Form -->
    <div class="box box-info">
        
        <!-- /.box-header -->
        <!-- form starts here -->        
        {!! Form::open(array('url' => 'create_member_process', 'id' => 'add_member_form', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
            <div class="box-body">
            
            
                <div class="col-md-4">
                    <div class="form-group">
                        
                      <!-- Profile Image -->
                      
                        <div class="box-body box-profile">
                          <img class="profile-user-img img-responsive img-circle" src="http://4.bp.blogspot.com/-dqWQXNlSC7Y/VXb5yU9zHjI/AAAAAAAAruc/mp42uTS_vc8/s1600/Obama%2BA.png" alt="User profile picture">

                          <h3 class="profile-username text-center">{{ $getMemberDetail->user->name }}</h3>

                          <p class="text-muted text-center">Member Type : {{ $memberType->name }}</p>

                        </div>
                        <!-- /.box-body -->
                      
                      <!-- /.box -->
                        
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email" >Email</label>
                        <p>{{ $getMemberDetail->user->email }}</p>
                    </div>
                    <!-- Date -->
                    <div class="form-group">
                      <label for="date_of_birth" >Date of Birth</label>
                      <p>{{ $getMemberDetail->dob }}</p>
                        
                    </div>
                    <div class="form-group">
                        <label for="addrs" >Address</label>
                        <p>{{ $getMemberDetail->address }}</p>  
                            
                        
                    </div>
                    
                    <div class="form-group">
                        <label for="member_type" >Member Type Description</label>
                        <p>{{ $memberType->description }}</p>  
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mob_num">Mobile number</label>
                        <p>{{ $getMemberDetail->mobile_number }}</p>
                            
                        
                    </div>
                    <div class="form-group">
                        <label for="off_num" >Office number</label>
                        <p>{{ $getMemberDetail->office_number }}</p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
        </form>
        <!-- /.form ends here -->
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->

@endsection

