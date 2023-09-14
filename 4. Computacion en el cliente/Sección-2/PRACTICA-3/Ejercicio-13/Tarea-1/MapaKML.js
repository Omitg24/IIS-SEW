class Mapa {
    initMap() {
        mapboxgl.accessToken = 'pk.eyJ1Ijoib21pdGciLCJhIjoiY2xiNWF4OWp4MDE2bDNub2FlbHp3dmZvcyJ9.mQp5NnAxt9CuOm7GuD1ODg';
        $("input").after("<article id=\"mapa\"></main>");
        this.map = new mapboxgl.Map({
            container: "mapa",
            style: "mapbox://styles/mapbox/streets-v9",
            center: [-3.70275, 40.41831], 
            zoom: 5
        });        
        this.map.addControl(new mapboxgl.NavigationControl());
        this.map.addControl(new mapboxgl.FullscreenControl());        
        this.map.addControl(
            new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },                
                trackUserLocation: true,                
                showUserHeading: true
            })
        );
    }

    ponerMarcador(lng, lat, color) {
        new mapboxgl.Marker({color: color}).setLngLat([lng, lat])
        .addTo(this.map);
    }
}

class Lector {
    constructor() {
        $("input").val(null);
    }

    compatibleConNavegador() {
        if (window.File && window.FileReader && window.FileList && window.Blob) 
        {              
            $("p:last").after("<p>Este navegador soporta el API File</p>");
        } else {
            $("p:last").after("<p>¡Este navegador NO soporta el API File y este programa puede no funcionar correctamente!</p>");
        }
    }

    cargarFicheros() {        
        var archivo = document.querySelector("input[type=file]").files[0];
        this.cargarContenido(archivo);
    }

    cargarContenido(archivo) {
        var lector = new FileReader();
        lector.onload = function (evento) {
            var kml = lector.result;
            var colors = $("style", kml);
            var coordinates = $("coordinates", kml);             
            var mapa = new Mapa();
            mapa.initMap();
            for (var i=0; i<coordinates.length; i++) {
                var coordenadas = coordinates[i].innerText.split(",");                
                var lng = coordenadas[0];                
                var lat = coordenadas[1];
                var color = "";
                if (colors[i].innerText.includes("red")) {
                    color = "red";
                } else {
                    color = "yellow";
                }
                mapa.ponerMarcador(lng, lat, color);
            }            
        };
        lector.readAsText(archivo);
    }
}
var lector = new Lector();
lector.compatibleConNavegador();