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
    $(document).ready(function () {});
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
        
            <div class="box-body">
            
            <div id="map" style="width:100%;height:500px"></div>
            <div class="col-md-1"></div>
                <div class="col-md-2"></div>

                
                <div class="col-md-1"></div>
            </div>
            <!-- /.box-body -->
            
            <!-- /.box-footer -->
        
        <!-- /.form ends here -->
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
<script>
        function myMap() {
          var stavanger = new google.maps.LatLng(23.901721, 90.391389);
          var amsterdam = new google.maps.LatLng(23.902749, 90.391128);
          var london = new google.maps.LatLng(23.902373, 90.388338);

          var mapCanvas = document.getElementById("map");
          var mapOptions = {center: amsterdam, zoom: 25,mapTypeId: google.maps.MapTypeId.HYBRID};
          var map = new google.maps.Map(mapCanvas,mapOptions);

          // var flightPath = new google.maps.Polyline({
          //   path: [stavanger, amsterdam, london],
          //   strokeColor: "#0000FF",
          //   strokeOpacity: 0.8,
          //   strokeWeight: 2
          // });

          // flightPath.setMap(map);
          
            var custLat = [23.901721, 23.902749, 23.902373];
            var custLong = [90.391389, 90.391128, 90.388338];
            // var a = [  [custLat[0],custLong[0]],[custLat[1],custLong[1]], [custLat[2],custLong[2]]  ];
            var markers = [];
            var infowindows = [];
            var linePoints = [];


            for(let i=0; i<custLat.length; i++) {

              markers[i] = new google.maps.Marker({position:new google.maps.LatLng(custLat[i], custLong[i]),map:map});
              linePoints.push(new google.maps.LatLng(custLat[i], custLong[i]));
              infowindows[i] = new google.maps.InfoWindow({
                  content: "<b>Hello World!</b>"
              });

              google.maps.event.addListener(markers[i], 'click', function() {
                infowindows[i].open(map, markers[i]);
              });
            }

            var flightPath = new google.maps.Polyline({
                path: linePoints,
                strokeColor: "#0000FF",
                strokeOpacity: 0.8,
                strokeWeight: 2
            });

            flightPath.setMap(map);
            console.log(linePoints);
            
          }
</script>
<script src="https://maps.googleapis.com/maps/api/js?callback=myMap"></script>

@endsection

