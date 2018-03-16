function initMap() {
    window.map = new google.maps.Map($('#map').get(0), {
            center: {lat: 50.26680229, lng: 28.6494872},
            zoom: 8,
            minZoom: 2,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

}

