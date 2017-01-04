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
        placeholder: 'Select Student',
        ajax: {
            url: "/get_all_student_for_payment",
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

    function getBatches(id) {
        $.get( "/get_batch_info_for_payment", { id: id } )
          .done(function( batches ) {
            // console.log("/get_batch_info_for_payment");
            // console.log(batches);
            var output='';
            $('#batch_table').html(''); 
            var c = 0;
            batch_length = batches.length;

            for (var i = 0; i < batches.length; i++) {
                var current = moment();
                var last_paid = moment(batches[i].pivot.last_paid_date);
                var month_diffrence = current.diff(last_paid, 'months');
                
                if (month_diffrence < 0) {
                    month_diffrence = 0;
                }

                var human_readable_last_paid_date = moment(batches[i].pivot.last_paid_date);
                human_readable_last_paid_date = month[human_readable_last_paid_date.month()] + " - " + human_readable_last_paid_date.year();
                var payment_for_each_batch = month_diffrence * batches[i].price;
                             
                output += "<tr role='row' class='even'>"+
                                "<input type='hidden' name=batch_id[] value='"+batches[i].id+"'>"+
                                "<input type='hidden' name=subjects_id[] value='"+batches[i].subjects_id+"'>"+
                                "<input type='hidden' name=last_paid_date[] value='"+batches[i].pivot.last_paid_date+"' readonly>"+
                                "<input type='hidden' id='unit_price_"+i+"' name=batch_unit_price[] value='"+batches[i].price+"'>"+
                                "<input type='hidden' name=batch_name[] value='"+batches[i].name+"' readonly>"+
                                
                                
                                "<td>"+batches[i].name+"</td>"+
                                "<td>"+human_readable_last_paid_date+"</td>"+
                                "<td>"+batches[i].price+"</td>"+
                                "<td>"+
                                    "<select class='form-control' id='month_" + i + "' name='month[]' >"+
                                            "<option value='"+month_diffrence+"'>"+month_diffrence+"</option>"+
                                            "<option value=0>0</option>"+
                                            "<option value=1>1</option>"+
                                            "<option value=2>2</option>"+
                                            "<option value=3>3</option>"+
                                            "<option value=4>4</option>"+
                                            "<option value=5>5</option>"+
                                    "</select>"+
                                "</td>"+                       
                                "<td>"+"<input id='total_price_"+i+"' class='totalprice' name=total_price[] value='"+payment_for_each_batch+"' readonly></td>"+
                            "</tr>";
            }

            output += "<tr role='row' class='even'>"+
                                "<td></td>"+
                                "<td></td>"+
                                "<td></td>"+
                                "<td>Total Price</td>"+
                                "<td>"+"<input id='totalpriceAmount' name=total value='' readonly></td>"+
                            "</tr>";


            $('#batch_table').append(output);

            var sum = 0;
            $('.totalprice').each(function(){
                sum += parseFloat(this.value);
            });

            $('input#totalpriceAmount').val(sum);
            // console.log(sum);

            $('[id^=month_]').change(function(event)  {
                var no_of_month = this.value;
                var month_id = this.id;
                var unit_price_id = "#unit_price_" + month_id.substring(month_id.length-1);
                // console.log(unit_price_id);
                var total_price_id = "#total_price_"+month_id.substring(month_id.length-1);
                var unit_price_amount = $(unit_price_id).val();
                var total_price_amount = $(total_price_id).val(unit_price_amount * no_of_month);
                // console.log(total_price_amount);
                var sum = 0;
                $('.totalprice').each(function(){
                    sum += parseFloat(this.value);
                    $('input#totalpriceAmount').val(sum);
                });
                $('input#totalpriceAmount').val(sum);
                // console.log(sum);
            });
        });
    }

    var invoice_serial_number = 0;
    $.get('/get_invoice_id',function(serial_number){
        invoice_serial_number = serial_number;
        $('#serial_number').val(serial_number);
        console.log(invoice_serial_number);
    });
    

    // $( "#student_payment" ).submit(function( event ) {
    $('#payment_print').click(function() {
        // var mode = 'iframe';
        // var close = mode == 'popup';
        // var options = { mode : mode, popClass : close };
        // $('#printPaymentArea').printArea(options);
        // $.print("#printPaymentArea" /*, options*/);
        var top = "<div>Money Receipt no: INV/"+moment().year()+"/"+(moment().month()+1)+"/"+invoice_serial_number+"<div/>"+
                    "<div>Date: "+$('#ref_date').val()+"<div/>"+
                    "<div>Student Name: "+$('p#student_name').text()+"<div/>"+
                    "<div>Father's Name: "+$('p#fathers_name').text()+"<div/>"+
                    "<div>Phone Number: "+$('p#phone_home').text()+"<div/>"+
                    "<br>";
        

        var header =    "<table class='table table-bordered table-striped'>"+
                            "<thead>"+
                                "<tr>"+
                                    "<th>Batch Name</th>"+
                                    "<th>Last Paid</th>"+
                                    "<th>Unit Price /=</th>"+
                                    "<th>no of month</th>"+
                                    "<th>Total Price Per Course /= </th>"+
                                "</tr>"+
                            "</thead>"+
                        "<tbody>";                         
                        

        console.log("Batch Length : " + batch_length);
        
        var date_of_payment = $('.ref_date').attr('value');

        var payment_data = $('#student_payment').serializeArray();
        console.log(payment_data);
        var payment_data_count = 0;
        var payment_output = "";

        for (var count = 0; count < batch_length; count++) {
            // var human_readable_last_paid_date = moment(payment_data[5+payment_data_count].value);
            //     human_readable_last_paid_date = month[human_readable_last_paid_date.month()] + " - " + human_readable_last_paid_date.year();
            // payment_output += "<tr role='row' class='even'>"+
            //                     "<td>"+payment_data[7+payment_data_count].value+"</td>"+
            //                     "<td>"+human_readable_last_paid_date+"</td>"+
            //                     "<td>"+payment_data[6+payment_data_count].value+"</td>"+
            //                     "<td>"+payment_data[8+payment_data_count].value+"</td>"+
            //                     "<td>"+payment_data[9+payment_data_count].value+"</td>"+
            //                 "</tr>";

            var human_readable_last_paid_date = moment(payment_data[6+payment_data_count].value);
                human_readable_last_paid_date = month[human_readable_last_paid_date.month()] + " - " + human_readable_last_paid_date.year();
            payment_output += "<tr role='row' class='even'>"+
                                "<td>"+payment_data[8+payment_data_count].value+"</td>"+
                                "<td>"+human_readable_last_paid_date+"</td>"+
                                "<td>"+payment_data[7+payment_data_count].value+"</td>"+
                                "<td>"+payment_data[9+payment_data_count].value+"</td>"+
                                "<td>"+payment_data[10+payment_data_count].value+"</td>"+
                            "</tr>";                


            

            payment_data_count += 7;
        }

            payment_output += "<tr role='row' class='even'>"+
                                "<td></td>"+
                                "<td></td>"+
                                "<td></td>"+
                                "<td>Total Price</td>"+
                                "<td>"+payment_data[ payment_data.length - 1 ].value+"</td>"+
                            "</tr></tbody ></table>";                
        var final_output = top + header + payment_output;
        // $('#printFormat').html(final_output);

        // $.print("#student_payment" /*, options*/);
    $(final_output).print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet : "http://fonts.googleapis.com/css?family=Inconsolata",
        noPrintSelector: ".no-print",
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 750,
        title: null,
        doctype: '<!doctype html>'
    });
        console.log("Total : "+payment_data[ payment_data.length - 1 ].value);
        console.log(payment_data);
    });
    


    $("#student_info_for_payment").click(function() {
        console.log($('select[id=student_id]').val());
        $.get("/get_student_info_for_payment", { 
                student_id: $('select[id=student_id]').val() 
        })
        .done(function( data ) {
            if (($('select[id=student_id]').val() != null) && ($('input[id=ref_date]').val() != null)) {
               $("#student_payment_div").css({ display: "block" });
               $('p#student_name').text(data.name);
               $('p#student_email').text(data.email);
               $('p#fathers_name').text(data.fathers_name);
               $('p#mothers_name').text(data.mothers_name);
               $('p#phone_home').text(data.phone_home);
               $('p#phone_away').text(data.phone_away);
               $('input#students_id').val(data.id);
               getBatches(data.id);
            }
           
        });

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
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div id="search_student_div" class="box-body">
                <div class="row">
	                <div class="col-xs-4">
	                    <div class="form-group">
	                        <label for="start_date">Refference Date</label>
	                        <div class="input-group date">
	                            <div class="input-group-addon">
	                                <i class="fa fa-calendar"></i>
	                            </div>
	                            <input id="ref_date" type="text" class="form-control ref_date" name="ref_date" value="{{ $refDate }}" autocomplete="off">
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
                    Payment 
                </h4>            
            </div>
            
            <div id="student_payment_div" class="box-body" style="display: none;">
                {!! Form::open(array('url' => 'student_payment', 'id' => 'student_payment', 'class' => 'form-horizontal')) !!}
                <input type='hidden' class="form-control ref_date" name="payment_date" value="{{ $refDate }}">
                <input type='hidden' id="students_id" name="students_id">
                <input type='hidden' id="serial_number" name="serial_number">
                <table id="all_user_list" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Batch Name</th>
                            <th>Last Paid</th>
                            <th>Unit Price /=</th>
                            <th>no of month</th>
                            <th>Total Price Per Course /= </th>
                        </tr>
                    </thead>
                    <tbody id="batch_table">                            
                    </tbody >
                </table>
                <div class="footer">
                    <label for="" ></label>
                    <div class="row">
                    <div class="col-md-6">
                        <button id="payment_print" type="button" class="btn btn-block btn-primary">Print</button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-block btn-success">Payment</button>
                    </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.box-body -->
    </div>
    <!-- /.box -->


</section>
<!-- /.content -->

@endsection

