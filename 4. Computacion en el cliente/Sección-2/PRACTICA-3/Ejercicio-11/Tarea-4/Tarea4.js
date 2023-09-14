class Mapa {
    initMap() {       
        mapboxgl.accessToken = 'pk.eyJ1Ijoib21pdGciLCJhIjoiY2xiNWF4OWp4MDE2bDNub2FlbHp3dmZvcyJ9.mQp5NnAxt9CuOm7GuD1ODg';
        $("header").after("<main id=\"mapa\"></main>");
        var map = new mapboxgl.Map({
            container: "mapa",
            style: "mapbox://styles/mapbox/streets-v9",
            center: [-5.693909,43.311065], 
            zoom: 8 
        });
        
        const marker1 = new mapboxgl.Marker()
        .setLngLat([-5.693909,43.311065])
        .addTo(map)
    }
}
new Mapa().initMap();