@extends('master')

@section('css')
<link rel="stylesheet" href="plugins/tooltipster/tooltipster.css">
@endsection

@section('scripts')
<script src="plugins/validation/dist/jquery.validate.js"></script>
<script src="plugins/tooltipster/tooltipster.js"></script>
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
        $('#add_product_form').validate({
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
                pcode: {
                    required: true
                },
                pname: {
                    required: true
                },
                uprice: {
                    required: true
                },                
                item_type: {
                    valueNotEquals: "default"
                },
                pg_id: {
                    valueNotEquals: "default"
                },
                psg_id: {
                    valueNotEquals: "default"
                },
                pu_id: {
                    valueNotEquals: "default"
                },
                coa_id: {
                    valueNotEquals: "default"
                }
            },
            messages: {
                pcode: {
                    required: "provide code"
                },
                pname: {
                    required: "provide a product name"
                },
                uprice: {
                    required: "provide unit price"
                },                
                item_type: {
                    valueNotEquals: "provide item type"
                },
                pg_id: {
                    valueNotEquals: "provide product group id"
                },
                psg_id: {
                    valueNotEquals: "provide product sub group id"
                },
                pu_id: {
                    valueNotEquals: "provide product unit id"
                },
                coa_id: {
                    valueNotEquals: "provide chart of account id"
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
        Products
        <small>add product page</small>
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
            <h3 class="box-title">Add a Product</h3>
        </div>
        <!-- /.box-header -->
        <!-- form starts here -->        
        {!! Form::open(array('url' => 'create_product_process', 'id' => 'add_product_form', 'class' => 'form-horizontal')) !!}
            <div class="box-body">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code" class="col-sm-3 control-label">Code*</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="pcode" name="pcode" size="35" placeholder="Enter code">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pname" class="col-sm-3 control-label">Product Name*</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="pname" name="pname" placeholder="Enter product name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pdescription" class="col-sm-3 control-label">Product Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" id="pdescription" name="pdescription" placeholder="Enter product description..."></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uprice" class="col-sm-3 control-label">Unit Price*</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">BDT</span>
                                <input type="text" class="form-control" id="uprice" name="uprice" placeholder="Enter unit price">
                                <span class="input-group-addon">.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vat" class="col-sm-3 control-label">VAT*</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">BDT</span>
                                <input type="text" class="form-control" id="vat" name="vat" placeholder="Enter vat amount">
                                <span class="input-group-addon">.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dprice" class="col-sm-3 control-label">Discount Price</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-addon">BDT</span>
                                <input type="text" class="form-control" id="dprice" name="dprice" placeholder="Enter discount price">
                                <span class="input-group-addon">.00</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dtype" class="col-sm-3 control-label">Discount Type</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="discount_type">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="itype" class="col-sm-3 control-label">Item Type*</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="item_type">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pgroupid" class="col-sm-3 control-label">Product Group ID*</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="pg_id">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="psubgroupid" class="col-sm-3 control-label">Product Sub Group ID*</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="psg_id">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="punitid" class="col-sm-3 control-label">Product Unit ID*</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="pu_id">
                                <option value="default">Choose...</option>
                                <option value="1">option 2</option>
                                <option value="2">option 3</option>
                                <option value="3">option 4</option>
                                <option value="4">option 5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="coaid" class="col-sm-3 control-label">Chart of Account ID*</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="coa_id">
                                <option value="default">Choose...</option>
                                <option value="1">option 1</option>
                                <option value="2">option 2</option>
                                <option value="3">option 3</option>
                                <option value="4">option 4</option>
                            </select>
                        </div>
                    </div>
                </div>
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

