;(function (map) {

    var object = {},
        markers = [],
        coords = [];

    object.createMarker = function(location, title) {
        if(markers.length < 2) {
            var marker = new google.maps.Marker({
                position: location,
                map: map,
                title: title,
                draggable: true
            });

            markers.push(marker);
            coords.push({lat: location.lat(), lng: location.lng()});

        }
    };

    object.clearLastMarker = function () {
        if(markers.length !== 0) {
            markers[markers.length - 1].setMap(null);
            markers.splice(markers.length - 1, 1);
            coords.splice(markers.length - 1, 1);
        }
    };

    object.clearMap = function () {
        while(markers.length !== 0)
            object.clearLastMarker();
    };

    object.getJSONCoords = function () {
        return JSON.stringify(coords);
    };

    window.markers = object;
})(window.map);