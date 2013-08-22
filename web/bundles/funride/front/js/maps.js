function initialize() {
    var mapOptions = {
        center: new google.maps.LatLng( 52.119845, 19.310657 ),
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    try {
        var map = new google.maps.Map( document.getElementById("funride_map"),
            mapOptions );
    } catch (e) {}
}


    google.maps.event.addDomListener(window, 'load', initialize);

