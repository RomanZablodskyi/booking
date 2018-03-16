;(function (map) {

    var waypoints = [],
        object = {},
        options = {
            origin: {lat: 0.0, lng: 0.0},
            destination: {lat: 0.0, lng: 0.0},
            travelMode: 'DRIVING',
            waypoints: waypoints
        },
        directionsDisplay = new google.maps.DirectionsRenderer(),
        directionsService = new google.maps.DirectionsService();

    function createWaypointsRoute(event) {

        event = event || window.event;

        var point = {
            lat: event.latLng.lat(),
            lng: event.latLng.lng()
        };

        if(options.origin.lat === 0.0 && options.origin.lng === 0.0){
            options.origin = point;
        }else{
            if(options.destination.lat() === 0.0 && options.destination.lng() === 0.0) {
                options.destination = point;
            }
            else{
                var lastPoint = options.destination;
                options.destination = point;
                waypoints.push({location: lastPoint});
            }
        }

        directionsService.route(options, function(result, status) {
            if (status == 'OK') {
                directionsDisplay.setDirections(result);
            }
        });
    }

    function clearLastPoints() {
        options.origin = {lat: 0.0, lng: 0.0};
        options.destination = {lat: 0.0, lng: 0.0};

        directionsDisplay.setMap(null);
        directionsDisplay = new google.maps.DirectionsRenderer();
        directionsDisplay.setMap(map);
    }

    object.createRoute = function() {
        directionsDisplay.setMap(map);

        map.addListener("click", function (event) {
            createWaypointsRoute(event, options)
        });
    };

    object.disableRoute = function () {
        directionsDisplay.setMap(null);
    };

    object.cancelLastPoint = function() {

        if(waypoints.length !== 0){
            options.destination = waypoints[waypoints.length - 1];
            waypoints.splice(waypoints.length - 1, 1);

            directionsService.route(options, function(result, status) {
                if (status == 'OK') {
                    directionsDisplay.setDirections(result);
                }
            });
        }else{
            clearLastPoints();
        }
    };

    object.clearMap = function() {
        waypoints.splice(0, waypoints.length);
        clearLastPoints();
    };

    object.getJSONCoords = function () {
        var array = [];

        array.push({lat: options.origin.lat(), lng: options.origin.lng()});
        waypoints.forEach(function (item) {
            array.push(item.location);
        });
        array.push({lat: options.destination.lat(), lng: options.destination.lng()});
        return JSON.stringify(array);
    };

    window.route = object;
})(window.map);
