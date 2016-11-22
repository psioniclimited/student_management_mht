@extends('master')

@section('css')
<link rel="stylesheet" href="{{asset('plugins/tooltipster/tooltipster.css')}}">
@endsection

@section('scripts')
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
        position: 'right'
    });

    // initialize validate plugin on the form
    $('#create_coa_form').validate({
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
            typeofacc: {
                valueNotEquals: "default"
            },
            accgroup: {
                valueNotEquals: "default"
            },
            accsubgroup: {
                valueNotEquals: "default"
            },
            accheadgroup: {
                valueNotEquals: "default"
            },
            nameofacc: {
                required: true
            },
            depreciation: {
                valueNotEquals: "default"
            },
            reportacc: {
                valueNotEquals: "default"
            },
            accsource: {
                valueNotEquals: "default"
            },
            accstat: {
                valueNotEquals: "default"
            }

        },
        messages: {
            typeofacc: {
                valueNotEquals: "select types of account"
            },
            accgroup: {
                valueNotEquals: "select accounts group"
            },
            accsubgroup: {
                valueNotEquals: "select accounts sub group"
            },
            accheadgroup: {
                valueNotEquals: "select accounts head group"
            },
            nameofacc: {
                required: "enter name of account"
            },
            depreciation: {
                valueNotEquals: "select depreciation"
            },
            reportacc: {
                valueNotEquals: "select reporting account"
            },
            accsource: {
                valueNotEquals: "select account source"
            },
            accstat: {
                valueNotEquals: "select account status"
            }
        }
    });

    // initialize validate plugin on the form for Modal-1
    $('#accgroup_modal_form').validate({
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
            typeofacc: {
                valueNotEquals: "default"
            },
            accgroupname: {
                required: true
            }
        },
        messages: {
            typeofacc: {
                valueNotEquals: "select types of account"
            },
            accgroupname: {
                required: "enter name of account"
            }
        }
    });

    // initialize validate plugin on the form for Modal-2
    $('#accsubgroup_modal_form').validate({
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
            typeofacc: {
                valueNotEquals: "default"
            },
            accgroup: {
                valueNotEquals: "default"
            },
            accsubgroupname: {
                required: true
            }
        },
        messages: {
            typeofacc: {
                valueNotEquals: "select types of account"
            },
            accgroup: {
                valueNotEquals: "select accounts group"
            },
            accsubgroupname: {
                required: "enter accounts sub group name"
            }
        }
    });

    // initialize validate plugin on the form for Modal-3
    $('#accheadgroup_modal_form').validate({
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
            typeofacc: {
                valueNotEquals: "default"
            },
            accgroup: {
                valueNotEquals: "default"
            },
            accsubgroup: {
                valueNotEquals: "default"
            },
            accsubgroupname: {
                required: true
            }
        },
        messages: {
            typeofacc: {
                valueNotEquals: "select types of account"
            },
            accgroup: {
                valueNotEquals: "select accounts group"
            },
            accsubgroup: {
                valueNotEquals: "select accounts sub group"
            },
            accsubgroupname: {
                required: "enter accounts sub group name"
            }
        }
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
    <!-- Horizontal Form -->
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Create New Chart of Accounts</h3>
        </div>
        <!-- /.box-header -->
        <!-- form starts here -->
        <form class="form-horizontal" id="create_coa_form">
            <div class="box-body">
                <div class="col-md-6">
<!--                    <div class="form-group">
                        <label for="typeofacc" class="col-md-4 control-label">Types of Accounts</label>
                        <div class="col-md-8">
                            <select class="form-control" name="typeofacc">
                                <option value="default">Choose...</option>
                                <option value="1">CAPITAL & LIABILITY</option>
                                <option value="2">PROPERTY & ASSETS</option>
                                <option value="3">INCOME</option>
                                <option value="4">EXPENDITURES</option>
                            </select>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label for="accgroup" class="col-md-4 control-label">Accounts Group</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control" name="accgroup">
                                    <option value="default">Choose...</option>
                                    <option value="1">option 1</option>
                                    <option value="2">option 2</option>
                                    <option value="3">option 3</option>
                                    <option value="4">option 4</option>
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#accgroup_modal">...</button>
                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="accsubgroup" class="col-md-4 control-label">Accounts Sub Group</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control" name="accsubgroup">
                                    <option value="default">Choose...</option>
                                    <option value="1">option 1</option>
                                    <option value="2">option 2</option>
                                    <option value="3">option 3</option>
                                    <option value="4">option 4</option>
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#accsubgroup_modal">...</button>
                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="accheadgroup" class="col-md-4 control-label">Accounts Head Group</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control" name="accheadgroup">
                                    <option value="default">Choose...</option>
                                    <option value="1">option 1</option>
                                    <option value="2">option 2</option>
                                    <option value="3">option 3</option>
                                    <option value="4">option 4</option>
                                </select>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-block btn-info btn-flat" data-toggle="modal" data-target="#accheadgroup_modal">...</button>
                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="nameofacc" class="col-md-4 control-label">Name of Account</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nameofacc" name="nameofacc" placeholder="Enter name of account">
                        </div>
                    </div>
<!--                    <div class="form-group">
                        <label for="depreciation" class="col-md-4 control-label">Depreciation</label>
                        <div class="col-md-8">
                            <select class="form-control" name="depreciation">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label for="reportacc" class="col-md-4 control-label">Reporting Account</label>
                        <div class="col-md-8">
                            <select class="form-control" name="reportacc">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="accsource" class="col-md-4 control-label">Account Source</label>
                        <div class="col-md-8">
                            <select class="form-control" name="accsource">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="accstat" class="col-md-4 control-label">Accounts Status</label>
                        <div class="col-md-8">
                            <select class="form-control" name="accstat">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
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

    <!-- All Modals -->
    <!-- Form for Modal-1  -->
    <form id="accgroup_modal_form">
        <!-- Modal 1 -->
        <div class="modal fade" id="accgroup_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Accounts Group</h4>
                    </div>
                    <div class="modal-body">
                        <label for="btypeid" class="control-label">Select Types of Accounts</label>
                        <select class="form-control" name="typeofacc">
                            <option value="default">Choose...</option>
                            <option value="1">CAPITAL & LIABILITY</option>
                            <option value="2">PROPERTY & ASSETS</option>
                            <option value="3">INCOME</option>
                            <option value="4">EXPENDITURES</option>
                        </select>
                        <label for="accgroupname" class="control-label">Accounts Group Name</label>
                        <input type="text" class="form-control" id="accgroupname" name="accgroupname" placeholder="Enter accounts group name">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Modal 1 ends here -->
    </form>
    <!-- /.  Form for Modal-1 ends here -->
    <!-- Form for Modal-2  -->
    <form id="accsubgroup_modal_form">
        <!-- Modal 2 -->
        <div class="modal fade" id="accsubgroup_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Accounts Sub Group</h4>
                    </div>
                    <div class="modal-body">
                        <label for="typeofacc" class="control-label">Select Types of Accounts</label>
                        <select class="form-control" name="typeofacc">
                            <option value="default">Choose...</option>
                            <option value="1">CAPITAL & LIABILITY</option>
                            <option value="2">PROPERTY & ASSETS</option>
                            <option value="3">INCOME</option>
                            <option value="4">EXPENDITURES</option>
                        </select>
                        <label for="accgroup" class="control-label">Select Accounts Group</label>
                        <select class="form-control" name="accgroup">
                            <option value="default">Choose...</option>
                            <option value="1">option 1</option>
                            <option value="2">option 2</option>
                            <option value="3">option 3</option>
                            <option value="4">option 4</option>
                        </select>
                        <label for="accsubgroupname" class="control-label">Accounts Sub Group Name</label>
                        <input type="text" class="form-control" id="accsubgroupname" name="accsubgroupname" placeholder="Enter accounts sub group name">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Modal 2 ends here -->
    </form>
    <!-- /.  Form for Modal-2 ends here -->
    <!-- Form for Modal-3  -->
    <form id="accheadgroup_modal_form">
        <!-- Modal 3 -->
        <div class="modal fade" id="accheadgroup_modal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Accounts Sub Group</h4>
                    </div>
                    <div class="modal-body">
                        <label for="typeofacc" class="control-label">Select Types of Accounts</label>
                        <select class="form-control" name="typeofacc">
                            <option value="default">Choose...</option>
                            <option value="1">CAPITAL & LIABILITY</option>
                            <option value="2">PROPERTY & ASSETS</option>
                            <option value="3">INCOME</option>
                            <option value="4">EXPENDITURES</option>
                        </select>
                        <label for="accgroup" class="control-label">Select Accounts Group</label>
                        <select class="form-control" name="accgroup">
                            <option value="default">Choose...</option>
                            <option value="1">option 1</option>
                            <option value="2">option 2</option>
                            <option value="3">option 3</option>
                            <option value="4">option 4</option>
                        </select>
                        <label for="accsubgroup" class="control-label">Select Accounts Sub Group</label>
                        <select class="form-control" name="accsubgroup">
                            <option value="default">Choose...</option>
                            <option value="1">option 1</option>
                            <option value="2">option 2</option>
                            <option value="3">option 3</option>
                            <option value="4">option 4</option>
                        </select>
                        <label for="accsubgroupname" class="control-label">Accounts Head Group Name</label>
                        <input type="text" class="form-control" id="accsubgroupname" name="accsubgroupname" placeholder="Enter accounts head group name">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /. Modal content ends here -->
            </div>
        </div>
        <!--  Modal 3 ends here -->
    </form>
    <!-- /.  Form for Modal-3 ends here -->

    <!-- All Modals end here -->
</section>
<!-- /.content -->

@endsection

