@extends('master')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
@endsection

@section('scripts')
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('plugins/validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/tooltipster/tooltipster.js')}}"></script>

<!-- Page Script -->
<script>
// add the rule here
$.validator.addMethod("valueNotEquals", function (value, element, arg) {
    return arg != value;
}, "Value must not equal arg.");

$(document).ready(function () {
        // initialize tooltipster on text input elements
        $('form input,select').tooltipster({
            trigger: 'custom',
            onlyOne: false,
            position: 'left'
        });

        // initialize validate plugin on the form
        $('#company_branch').validate({
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
                bname: {
                    required: true
                },
                baddrs: {
                    required: true
                },
                cnum: {
                    required: true
                },
                btypeid: {
                    valueNotEquals: "default"
                },
                cinfoid: {
                    valueNotEquals: "default"
                }
            },
            messages: {
                bname: {
                    required: "provide branch name"
                },
                baddrs: {
                    required: "provide branch address"
                },
                cnum: {
                    required: "provide contact number"
                },
                btypeid: {
                    valueNotEquals: "provide branch type id"
                },
                cinfoid: {
                    valueNotEquals: "provide company information id"
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#branch_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/brancheslist')}}",
            "columns": [
            {"data": "id"},
            {"data": "branch_name"},
            {"data": "branch_address"},
            {"data": "contact_number"},
            {"data": "branch_type_id"},
            {"data": "companie_information_id"},
            {"data": "Link", name: 'link', orderable: false, searchable: false}
            ],
            "order": [[1, 'asc']]
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
        Blank page
        <small>it all starts here</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-6">
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Branch Information</h3>
                </div>
                <!-- /.box-header -->
                @if (count($errors) > 0)
                <div class="alert alert-danger alert-login col-sm-4">
                    <ul class="list-unstyled">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(isset($getBranchInfo))

                @foreach($getBranchInfo as $b_info)

                <!-- form starts here -->                
                {!! Form::open(array('url' => 'update_branch_process', 'class' => 'form-horizontal', 'id' => 'company_branch')) !!}    
                <div class="box-body">
                    <div class="form-group">
                        <label for="bname" class="col-sm-3 control-label">Branch Name*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="bname" name="bname" placeholder="Enter branch name" value="{{$b_info['branch_name']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="baddrs" class="col-sm-3 control-label">Branch Address*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="baddrs" name="baddrs" placeholder="Enter branch address" value="{{$b_info['branch_address']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cnum" class="col-sm-3 control-label">Contact Number*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cnum" name="cnum" placeholder="Enter contact number" value="{{$b_info['contact_number']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="btypeid" class="col-sm-3 control-label">Branch Type*</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="btypeid">
                                <option value="default">Choose...</option>
                                @foreach($branchType as $bType)
                                <option value="{{ $bType->id }}">{{ $bType->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cinfoid" class="col-sm-3 control-label">Company Information*</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="cinfoid">
                                <option value="default">Choose...</option>
                                @foreach($companyInfo as $cInfo)
                                <option value="{{ $cInfo->id}}">{{ $cInfo->name_of_company}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-default">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
                <!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
                @endforeach

                @else
                <!-- form starts here -->                
                {!! Form::open(array('url' => 'create_branch_process', 'class' => 'form-horizontal', 'id' => 'company_branch')) !!}    
                <div class="box-body">
                    <div class="form-group">
                        <label for="bname" class="col-sm-3 control-label">Branch Name*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="bname" name="bname" placeholder="Enter branch name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="baddrs" class="col-sm-3 control-label">Branch Address*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="baddrs" name="baddrs" placeholder="Enter branch address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cnum" class="col-sm-3 control-label">Contact Number*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cnum" name="cnum" placeholder="Enter contact number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="btypeid" class="col-sm-3 control-label">Branch Type*</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="btypeid">
                                <option value="default">Choose...</option>
                                @foreach($branchType as $bType)
                                <option value="{{ $bType->id }}">{{ $bType->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cinfoid" class="col-sm-3 control-label">Company Information*</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="cinfoid">
                                <option value="default">Choose...</option>
                                @foreach($companyInfo as $cInfo)
                                <option value="{{ $cInfo->id}}">{{ $cInfo->name_of_company}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-default">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
                <!-- /.box-footer -->
                {!! Form::close() !!}
                <!-- /.form ends here -->
                @endif
            </div>
            <!-- /.box -->
        </div>
        <!-- /. col -->
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Hover Data Table</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="branch_list" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Company</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!--  /. col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

@endsection

