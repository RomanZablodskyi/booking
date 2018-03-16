$(function () {
    var href = location.toString(),
        result = href.slice(href.search(/id=/) + 3);


    $.ajax({
       url: '/passage/get_coords',
       type: "POST",
       data: "id=" + result,
       success: function (jsonSting) {
            var coodsObj = JSON.parse(jsonSting);
            if(coodsObj.length > 2) {
                var directionsDisplay = new google.maps.DirectionsRenderer(),
                    directionsService = new google.maps.DirectionsService(),
                    origin = coodsObj.shift(),
                    destination = coodsObj.pop(),
                    waypoints = [];

                coodsObj.forEach(function (item) {
                    waypoints.push({location: item});
                });

                var options = {
                        origin: origin,
                        destination: destination,
                        travelMode: 'DRIVING',
                        waypoints: waypoints
                };

                directionsService.route(options, function(result, status) {
                    if (status == 'OK') {
                        directionsDisplay.setDirections(result);
                    }
                });

                directionsDisplay.setMap(map);

            }else{
                coodsObj.forEach(function (item, i, arr) {
                    var marker = new google.maps.Marker({
                        position: item,
                        map: map,
                        title: ''
                    });

                    marker.setMap(map);
                })
            }
       }
    })
});