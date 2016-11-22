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

<script>
    $(document).ready(function () {
        // initialize tooltipster on text input elements
        $('form input,select').tooltipster({
            trigger: 'custom',
            onlyOne: false,
            position: 'left'
        });

        // initialize validate plugin on the form
        $('#company_info').validate({
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
                cname: {
                    required: true
                },
                addrs: {
                    required: true
                },
                cnum: {
                    required: true
                }
            },
            messages: {
                cname: {
                    required: "provide company name"
                },
                addrs: {
                    required: "provide address"
                },
                cnum: {
                    required: "provide contact number"
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#company_info_list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": "{{URL::to('/companydata')}}",
            "columns": [
            {"data": "id"},
            {"data": "name_of_company"},
            {"data": "address"},
            {"data": "contact_number"},
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
        Companies
        <small>all company info.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Settings</a></li>
        <li class="active">Companies</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-6">
            <!-- Horizontal Form -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Company Information Page</h3>
                </div>
                <!-- /.box-header -->
                @if(isset($getCompanyInfo))

                @foreach($getCompanyInfo as $c_info)
                
                <!-- form starts here --> 
                {!! Form::open(array('url' => 'update_company_process', 'class' => 'form-horizontal', 'id' => 'company_info')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label for="cname" class="col-sm-3 control-label">Company Name*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cname" name="cname" placeholder="Enter company name" value="{{$c_info['name_of_company']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addrs" class="col-sm-3 control-label">Address*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="addrs" name="addrs" placeholder="Enter address" value="{{$c_info['address']}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cnum" class="col-sm-3 control-label">Contact Number*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cnum" name="cnum" placeholder="Enter contact number" value="{{$c_info['contact_number']}}">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="companyID" value="{{$c_info['id']}}">
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
                {!! Form::open(array('url' => 'create_company_process', 'class' => 'form-horizontal', 'id' => 'company_info')) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label for="cname" class="col-sm-3 control-label">Company Name*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cname" name="cname" placeholder="Enter company name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addrs" class="col-sm-3 control-label">Address*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="addrs" name="addrs" placeholder="Enter address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cnum" class="col-sm-3 control-label">Contact Number*</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cnum" name="cnum" placeholder="Enter contact number">
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
        <!-- col -->
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Hover Data Table</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="company_info_list" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
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
        <!-- /. col -->
    </div>
    <!-- /. row -->
</section>
<!-- /.content -->

@endsection

