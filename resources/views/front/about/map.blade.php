{{ null, $location = setting()->ask('location')->gain() }}
@if($location and is_array($location) and (count($location) == 2))
    <div class="row mb30">
        <div id="map" class="col-xs-12" style="height: 400px"></div>
    </div>
@section('endOfBody')
    {{ null, $title = setting()->ask('site_title')->gain() }}
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrN-fzR0qjq_9VE7HyKdFHEfMAlkHR8Z4&callback=initMap"
            type="text/javascript"></script>
    <script type="text/javascript">
        // Multiple Markers
        var markers = [
            [
                "{{ $title }}",
                {{ $location[0] }},
                {{ $location[1] }}
            ],
        ];

        // Info Window Content
        var infoWindowContent = [
            ['<div class="info_content">' +
            '<h3>{{ $title }}</h3>' +
            '<p>{{ setting()->ask('address')->gain() }}</p>' +
            '</div>'
            ]
        ];
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            initialize();
        });
        function initialize() {
            var map;
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                scrollwheel: false,
                styles: [{
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
                }, {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
                }, {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [{"color": "#ffffff"}, {"lightness": 17}]
                }, {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
                }, {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [{"color": "#ffffff"}, {"lightness": 18}]
                }, {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [{"color": "#ffffff"}, {"lightness": 16}]
                }, {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
                }, {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [{"color": "#dedede"}, {"lightness": 21}]
                }, {
                    "elementType": "labels.text.stroke",
                    "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
                }, {
                    "elementType": "labels.text.fill",
                    "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
                }, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]
                }, {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [{"color": "#fefefe"}, {"lightness": 20}]
                }, {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
                }]
            };

            // Display a map on the page
            map = new google.maps.Map(document.getElementById("map"), mapOptions);
            map.setTilt(45);


            // Display multiple markers on a map
            var infoWindow = new google.maps.InfoWindow(),
                marker, i;

            // Loop through our array of markers & place each one on the map
            for (i = 0; i < markers.length; i++) {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: markers[i][0]
                });

                // Allow each marker to have an info window
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infoWindow.setContent(infoWindowContent[i][0]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));

                // Automatically center the map fitting all markers on the screen
                map.fitBounds(bounds);
            }

            // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
                this.setZoom(16);
                google.maps.event.removeListener(boundsListener);
            });

        }
    </script>
@endsection
@endif