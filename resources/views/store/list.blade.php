<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Finder</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 mb-5"><h3 class="text-danger" id="location-error"> </h3> </div>
        <!-- Store List Section -->
        <div class="col-md-6">
            <h2>Store List</h2>
            @if(!empty($stores))
            @php
            $storeLocations = [];
            @endphp
            @foreach($stores as $store)
            @php
            $storeLocations[] = [
                'title' => $store->title,
                'position' => [
                    'lat' => $store->latitude,
                    'lng' => $store->longitude
                ],
                'address' => $store->address
            ];
            @endphp            
            <div class="card pt-2 mt-2" >
                <div class="card-body">
                    <h5 class="card-title">{{@$store->title}}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{number_format(@$store->distance,2)}} km</h6>
                    <p class="card-text">{{@$store->address}}</p>
                </div>
            </div>
            @endforeach
            @endif
            <!-- Store Section End-->
        </div>
        <!-- Map Section -->
        <div class="col-md-6">        
            <h2>Map</h2>
            <div id="map" style="height: 400px;"></div>
        </div>
         <!-- Map Section End-->
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<!-- Google Maps JavaScript API script here -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCG39EpX8oGAXWTHK-CPU_uZgtyFRkERRU"></script>
<!--  JavaScript for interacting with the map and list -->
<script>
    navigator.permissions.query({ name: 'geolocation' }).then(permissionStatus => {
        if (permissionStatus.state === 'granted') {
            // Geolocation permission is granted
            // You can proceed to get the user's location
            if (permissionStatus.state === 'granted') {
                // Geolocation permission is granted
                // You can proceed to get the user's location
                navigator.geolocation.getCurrentPosition(initMap);
            //     console.log("Latitude: " + position.coords.latitude +
            // " Longitude: " + position.coords.longitude);

        }else if(permissionStatus.state === 'denied' || permissionStatus.state === 'prompt'){
            $("#location-error").text("Geolocation permission is denied/prompt.");
        }
        } else {
            // Geolocation permission is not granted
            $("#location-error").text("Geolocation permission is not granted.Please allow and refresh the page.");
           // console.log("Geolocation permission is not granted.");
        }
    });
   
    function initMap(position) {
        // Center map on a default location (e.g., your store's location)
        var user_lat = position.coords.latitude;
        var user_long = position.coords.longitude;
        var mapCenter = { lat: user_lat  , lng: user_long};
        // Create map object
        var map = new google.maps.Map(document.getElementById('map'), {
            center: mapCenter,
            zoom: 12 // Adjust the initial zoom level as needed
        });

        // Array to store store markers      
        var markers = [];
        var storeLocations = {!! json_encode($stores->map(function($store) {
            return [
                'title' => $store->title,
                'position' => [
                    'lat' =>  floatval( $store->latitude),
                    'lng' =>  floatval( $store->longitude)
                ],
                'address' => $store->address
            ];
        })) !!};

        // Add markers and info windows for each store location
        storeLocations.forEach(function(store) {
            var marker = new google.maps.Marker({
                position: store.position,
                map: map,
                title: store.title
            });

            var infoWindow = new google.maps.InfoWindow({
                content: '<h3>' + store.title + '</h3><p>' + store.address + '</p>'
            });
            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
            markers.push(marker);
        });
    } 
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCG39EpX8oGAXWTHK-CPU_uZgtyFRkERRU&callback=initMap"></script>
</body>
</html>
