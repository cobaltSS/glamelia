
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
var address = 'Belarus,Minsk';
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

    /*if there is a start and end point of the route*/
    setTimeout(function() {
     changeRoute();
     },200);

    /*show menu*/
    $('#map_canvas').mousedown(function (e) {
        if (e.button == 2 && (!$('#findTaxi').is(':disabled')))
        {
            var x = e.pageX - 60;
            var y = e.pageY + 10;
            $('#menuLocation').css('left', x + 'px');
            $('#menuLocation').css('top', y + 'px');
            $('#menuLocation').show();
        }
    });


    /*Pick up Location*/
    $('#ChangeStart').click(function () {
        var lat = parseFloat($('#markerLat').val());
        var lng = parseFloat($('#markerLng').val());
        $('#location').val(lat + ',' + lng);
        changeRouteStart(lat, lng);
        $('#menuLocation').hide();
        changeRoute();

    });

    /*Drop off Location*/
    $('#ChangeEnd').click(function () {
        var lat = parseFloat($('#markerLat').val());
        var lng = parseFloat($('#markerLng').val());
        changeRouteEnd(lat, lng);
        $('#menuLocation').hide();
        changeRoute();
    });


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
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    //this test version
    // address = 'Philippines,Manila'
    //address = 'Belarus,Minsk'
    geocoder = new google.maps.Geocoder();

    geocoder.geocode({
        'address': address
    }, function (results, status) {
        map.setCenter(results[0].geometry.location);
    });

    // Create a renderer for directions and bind it to the map.
    rendererOptions = {
        map: map,
        draggable: true
    }
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    // Instantiate an info window to hold step text.
    stepDisplay = new google.maps.InfoWindow();

    polyline = new google.maps.Polyline({
        path: [],
        strokeColor: '#FF0000',
        strokeWeight: 3
    });
    poly2 = new google.maps.Polyline({
        path: [],
        strokeColor: '#FF0000',
        strokeWeight: 3
    });

    google.maps.event.addListener(map, "rightclick", function (event) {
        $('#markerLat').val(event.latLng.lat());
        $('#markerLng').val(event.latLng.lng());
    });

    /**
     *  inizializate autocomplete (start,end)
     *
     */
    var input_start = document.getElementById('start');
    var options = {
        types: [],
        componentRestrictions: {
            country: country_cod
        }
    };
    autocomplete_start = new google.maps.places.Autocomplete(input_start, options);

    var input_end = document.getElementById('end');
    var options_end = {
        types: [],
        componentRestrictions: {
            country: country_cod
        }
    };
    autocomplete_end = new google.maps.places.Autocomplete(input_end, options_end);


    /**
     *  autocomplete_start
     *
     */

    autocomplete_start.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();
    marker_start = createMarkerRound("start", "green", start_icon);
    google.maps.event.addListener(autocomplete_start, 'place_changed', function () {
        if (($('#start').val()))
        {
            marker_start.setVisible(false);
            infowindow.close();

            var place = autocomplete_start.getPlace();
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
            marker_start.setPosition(place.geometry.location);
            $('#location').val(place.geometry.location.toString().replace('(', '').replace(')', ''));
            marker_start.setVisible(true);
            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }
            infowindow.open(map, marker_start);
            if (($('#end').val()))
                calcRoute();

        }
    });

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
            if ($('#start').val())
                calcRoute();
        }
    });


}

function changeRouteStart(lat, lng)
{
    var newLatLng = new google.maps.LatLng(lat, lng);
    if (!marker_start)
        marker_start = createMarkerRound("start", "green");
    marker_start.setPosition(newLatLng);
    geocodePosition(marker_start.getPosition(), 'start', marker_start);
}

function changeRouteEnd(lat, lng)
{
    var newLatLng = new google.maps.LatLng(lat, lng);
    if (!marker_end)
        marker_end = createMarkerRound("end", "red");
    marker_end.setPosition(newLatLng);
    geocodePosition(marker_end.getPosition(), 'end', marker_end);
}

function changeRoute()
{
    if ($('#start').val() && $('#end').val())
    {
        geocoder.geocode({
            'address': $('#start').val()
        }, function (results) {
            if (results[0])
                $('#location').val(results[0].geometry.location.toString().replace('(', '').replace(')', ''));
        })

        calcRoute();
    }
    else
    {
       /* $("#time").val('');
        $("#distance").val('');
        $("#cost").val('');*/
    }
    return true;
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

function RecomendateLocations(addr, locat)
{
    geocoder = new google.maps.Geocoder();
    if (!marker_start)
        marker_start = createMarkerRound("start", "green");
    if (!marker_end)
        marker_end = createMarkerRound("start", "red");
    if (marker_start)
        marker_start.setVisible(false);
    if (marker_end)
        marker_end.setVisible(false);
    if (locat == 'end')
    {
        geocoder.geocode({
            'address': addr
        }, function (results, status) {
            map.setCenter(results[0].geometry.location);
            marker_end.setPosition(results[0].geometry.location);
            marker_end.setVisible(true);
        })
    }
    else {
        geocoder.geocode({
            'address': addr
        }, function (results, status) {
            map.setCenter(results[0].geometry.location);
            $('#location').val(results[0].geometry.location.toString().replace('(', '').replace(')', ''));
            marker_start.setPosition(results[0].geometry.location);
            marker_start.setVisible(true);
        })
    }

}

function calcRoute() {
    if (timerHandle) {
        clearTimeout(timerHandle);
    }
    polyline.setMap(null);
    poly2.setMap(null);
    if (marker_start)
        marker_start.setVisible(false);
    if (marker_end)
        marker_end.setVisible(false);
    if (directionsDisplay != null) {
        directionsDisplay.setMap(null);
        directionsDisplay = null;
    }

    polyline = new google.maps.Polyline({
        path: [],
        strokeColor: '#FF0000',
        strokeWeight: 3
    });
    poly2 = new google.maps.Polyline({
        path: [],
        strokeColor: '#FF0000',
        strokeWeight: 3
    });
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    var start = document.getElementById("start").value;
    var end = document.getElementById("end").value;
    var travelMode = google.maps.DirectionsTravelMode.DRIVING;
    var request = {
        origin: start,
        destination: end,
        travelMode: travelMode
    };

    // ie placeholders for input with  id  "start" and "end"
      /*        if ($.browser.msie) {
        $.each([$('#start'), $('#end')], function () {
            var val = $(this).attr('placeholder');
            if ($(this).val() == val || $(this).val() == '') {
                $(this).attr('value', val).addClass('placeholder-style').attr('data-empty', 1);
            } else {
                $(this).removeClass('placeholder-style').attr('data-empty', 0);
            }
        })

    }    ;*/

    // Route the directions and pass the response to a
    // function to create markers for each step.
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
            polyline.setMap(map);
            computeTotalDistance(directionsDisplay.directions);
            map.setZoom(15);

            $('#start').val(directionsDisplay.directions.routes[0].legs[0].start_address);
            $('#end').val(directionsDisplay.directions.routes[0].legs[0].end_address);
            marker_start.setPosition(directionsDisplay.directions.routes[0].legs[0].start_location);
            marker_end.setPosition(directionsDisplay.directions.routes[0].legs[0].end_location);



            // change marker
            google.maps.event.addListener(directionsDisplay, 'directions_changed', function () {
                polyline.setMap(map);
                computeTotalDistance(directionsDisplay.directions);
                marker_start.setPosition(directionsDisplay.directions.routes[0].legs[0].start_location);
                marker_end.setPosition(directionsDisplay.directions.routes[0].legs[0].end_location);
                $('#start').val(directionsDisplay.directions.routes[0].legs[0].start_address);
                $('#end').val(directionsDisplay.directions.routes[0].legs[0].end_address);
                $('#location').val(directionsDisplay.directions.routes[0].legs[0].start_location.toString().replace('(', '').replace(')', ''));

            });
        }

    });
}

function computeTotalDistance(result) {
    var total = 0;
    var total_time = 0;

    var myroute = result.routes[0];
    for (i = 0; i < myroute.legs.length; i++) {
        total += myroute.legs[i].distance.value;
    }

    for (i = 0; i < myroute.legs.length; i++) {
        total_time += myroute.legs[i].duration.value;
    }
    // total=total/1609.344;//mile
    total = total / 1000.0;//km
    total_time = total_time / 60;//min

    var cost = 40 + parseFloat(total_time.toFixed(0)) / 2 * 3.50;

    /*$("#time").val(parseFloat(total_time.toFixed(0)));
    $("#distance").val(parseFloat(total.toFixed(2)));
    $("#cost").val(parseFloat(cost.toFixed(2)));*/
}


function clearMarker(val)
{
    var start = $('#start').val();
    var end = $('#end').val();
    if (!$('#findTaxi').is(':disabled'))
    {
        polyline.setMap(null);
        poly2.setMap(null);
        directionsDisplay.setMap(null);
        if (!(val))
        {
            var start = $('#start').val();
            var end = $('#end').val();
            var show_start = true;
            var show_end = true;

            if (!(start))
            {
                if (marker_start)
                    marker_start.setVisible(false);
                show_start = false;
            }

            if (!(end))
            {
                if (marker_end)
                    marker_end.setVisible(false);
                show_end = false;
            }

            if (show_start)
            {
                if (marker_start)
                    marker_start.setVisible(true);
            }

            if (show_end)
            {
                if (marker_end)
                    marker_end.setVisible(true);
            }
            $("#time").val('');
            $("#distance").val('');
            $("#cost").val('');

        }
    }
    /*  if (start && end)
     calcRoute();*/
}










