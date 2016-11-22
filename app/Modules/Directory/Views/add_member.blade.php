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
                    required: true, minlength: 6
                },
                password_confirmation: {
                    required: true, equalTo: "#password"
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
        Members
        <small>add member page</small>
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
        <div class="box-header with-border">
            <h3 class="box-title">Add a Member</h3>
        </div>
        <!-- /.box-header -->
        <!-- form starts here -->        
        {!! Form::open(array('url' => 'create_member_process', 'id' => 'add_member_form', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
            <div class="box-body">
            
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="col-md-1"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fullname" >Fullname*</label>
                        
                            <input type="text" class="form-control" id="fullname" name="fullname" value="{{old('fullname')}}" size="35" placeholder="Enter fullname">
                        
                    </div>
                    <!-- Date -->
                    <div class="form-group">
                      <label for="date_of_birth" >Date of Birth*</label>
                      
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control" id="datepicker" name="date_of_birth" value="{{old('date_of_birth')}}" placeholder="Select date of birth">
                        
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="addrs" >Address*</label>
                        
                            <textarea class="form-control" rows="3" id="addrs" name="addrs" value="{{old('addrs')}}" placeholder="Enter address..."></textarea>
                        
                    </div>
                    <div class="form-group">
                        <label for="mob_num">Mobile number*</label>
                        
                            <input type="text" class="form-control" id="mob_num" name="mob_num" size="35" value="{{old('mob_num')}}" placeholder="Enter mobile number">
                        
                    </div>
                    <div class="form-group">
                        <label for="off_num" >Office number*</label>
                        
                            <input type="text" class="form-control" id="off_num" name="off_num" size="35" value="{{old('off_num')}}" placeholder="Enter office number">
                        
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email" >Email*</label>
                        
                            <input type="email" class="form-control" id="email" name="email" size="35" value="{{old('email')}}" placeholder="Enter email">
                        
                    </div>
                    <div class="form-group">
                        <label for="password" >Password*</label>
                        
                            <input type="password" class="form-control" id="password" name="password" size="35" placeholder="Enter password">
                        
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" >Confirm Password*</label>
                        
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" size="35" placeholder="Enter password again">
                        
                    </div>
                    <div class="form-group">
                        <label for="member_type" >Member Type*</label>
                          
                            <select class="form-control" name="member_type">
                                <option value="default">Choose...</option>
                                @foreach ($memberType as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        
                    </div>
                    <div class="form-group">
                        <label for="pic" >Upload Photo*</label>
                        
                            <!-- {{Form::file('pic')}} -->
                            <input type="file" name="pic" id="pic">
                            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                        
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-primary pull-right">Submit</button>
            </div>
            <!-- /.box-footer -->
        </form>
        <!-- /.form ends here -->
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->

@endsection

