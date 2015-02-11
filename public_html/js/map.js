
var map;
var rendererOptions;
var directionsDisplay;
var directionsService;
var stepDisplay;
var position;
var markersArray = [];
var marker_start;
var marker_end;
var polyline = null;
var poly2 = null;
var speed = 0.000005, wait = 1;
var infowindow = null;
var geocoder;


/* var address = 'Philippines,Manila';
 var country_cod='ph';//ph-philiphin*/
var address = 'Belarus';
var country_cod = 'BY';

var myPano;
var panoClient;
var nextPanoId;
var timerHandle = null;

var step = 50; // 5; // metres
var tick = 100; // milliseconds
var eol;
var k = 0;
var stepnum = 0;
var lastVertex = 1;
var start_icon = "https://maps.gstatic.com/mapfiles/markers2/marker_greenA.png";
var end_icon = "https://maps.gstatic.com/mapfiles/markers2/markerB.png";

$(document).ready(function (e)
{
    google.maps.event.addDomListener(window, 'load', initialize);

});

function createMarkerRound(label, html, icon) {
    var contentString = '<b>' + label + '</b><br>' + html;
    var marker = new google.maps.Marker({
        map: map,
        title: label,
        animation: google.maps.Animation.DROP,
        icon: icon
                //icon:"http://google-maps-icons.googlecode.com/files/car.png"
    });
    markersArray.push(marker);

    google.maps.event.addListener(marker, 'dragend', function () {
        infowindow.setContent(contentString);
        infowindow.open(map, marker);
    });

    return marker;
}

function initialize() {
    var steps = []
    infowindow = new google.maps.InfoWindow(
            {
                size: new google.maps.Size(150, 50)
            });
    // Instantiate a directions service.
    directionsService = new google.maps.DirectionsService();
    // Create a map
    var myOptions = {
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    geocoder = new google.maps.Geocoder();

    if (!marker_end)
        marker_end = createMarkerRound("start", "green");
    if ($('#end').val())
    {
        address = $('#end').val();
        geocoder.geocode({
            'address': address
        }, function (results, status) {
            if (results[0])
            {
                map.setCenter(results[0].geometry.location);
                marker_end.setPosition(results[0].geometry.location);
                marker_end.setVisible(true);
            }
        });

    }
    else{
         geocoder.geocode({
     'address': address
     }, function (results, status) {
     map.setCenter(results[0].geometry.location);
     });
    }

    // Create a renderer for directions and bind it to the map.
    rendererOptions = {
        map: map,
        draggable: true
    }
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    // Instantiate an info window to hold step text.
    stepDisplay = new google.maps.InfoWindow();
    var input_end = document.getElementById('end');
    var options_end = {
        types: [],
        componentRestrictions: {
            country: country_cod
        }
    };
    autocomplete_end = new google.maps.places.Autocomplete(input_end, options_end);
    /**
     *  autocomplete_end
     *
     */
    autocomplete_end.bindTo('bounds', map);
    marker_end = createMarkerRound("end", "red", end_icon);
    google.maps.event.addListener(autocomplete_end, 'place_changed', function () {
        if ($('#end').val())
        {
            marker_end.setVisible(false);
            infowindow.close();
            var place = autocomplete_end.getPlace();
            if (!place.geometry) {
                return;
            }
            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker_end.setPosition(place.geometry.location);
            marker_end.setVisible(true);
            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }
            infowindow.open(map, marker_end);

        }
    });

    /*setTimeout(function () {
     showShop();
     }, 2000);*/




}

function showShop()
{
    if (!marker_end)
        marker_end = createMarkerRound("end", "red");
    if ($('#end').val())
    {
        geocoder.geocode({
            'address': $('#end').val()
        }, function (results, status) {
            alert();
            map.setCenter(results[0].geometry.location);
            marker_end.setPosition(results[0].geometry.location);
            marker_end.setVisible(true);
        });

    }
}


function changeRouteEnd(lat, lng)
{
    var newLatLng = new google.maps.LatLng(lat, lng);
    if (!marker_end)
        marker_end = createMarkerRound("end", "red");
    marker_end.setPosition(newLatLng);
    geocodePosition(marker_end.getPosition(), 'end', marker_end);
}

function geocodePosition(pos, road, marker) {
    geocoder.geocode({
        latLng: pos
    }, function (responses) {
        if (responses.length > 0) {
            $('#' + road).val(responses[0].formatted_address);
            if (!changeRoute())
                marker.setVisible(true);
        }
        else
            return false;
    });
}

function clearMarker(val)
{
    var end = $('#end').val();
    directionsDisplay.setMap(null);
    if (!(val))
    {
        var end = $('#end').val();
        var show_start = true;
        var show_end = true;

        if (!(end))
        {
            if (marker_end)
                marker_end.setVisible(false);
            show_end = false;
        }

        if (show_end)
        {
            if (marker_end)
                marker_end.setVisible(true);
        }
    }
}










