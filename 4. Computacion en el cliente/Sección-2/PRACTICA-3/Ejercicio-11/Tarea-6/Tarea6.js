class Mapa {
    constructor(){
        this.provincia = "";
    }

    initMap() {
        mapboxgl.accessToken = 'pk.eyJ1Ijoib21pdGciLCJhIjoiY2xiNWF4OWp4MDE2bDNub2FlbHp3dmZvcyJ9.mQp5NnAxt9CuOm7GuD1ODg';
        $("article").after("<main id=\"mapa\"></main>");
        var map = new mapboxgl.Map({
            container: "mapa",
            style: "mapbox://styles/mapbox/streets-v9",
            center: [-3.70275, 40.41831], 
            zoom: 5
        });        
        map.addControl(new mapboxgl.NavigationControl());
        map.addControl(new mapboxgl.FullscreenControl());        
        map.addControl(
            new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },                
                trackUserLocation: true,                
                showUserHeading: true
            })
        );
        const marker1 = new mapboxgl.Marker();
        map.on('load', () => {
            map.on('click', (e) => {                
                marker1.remove();
                var lng = e.lngLat.lng;
                var lat = e.lngLat.lat;                
                marker1.setLngLat([lng, lat])
                .addTo(map); 
                this.obtenerProvincia(lng, lat);
                var codigo = this.obtenerCodigoProvincia(this.provincia);
                this.mostrarDatos(codigo);
            });
        });
    }

    obtenerProvincia(lng, lat) {
        var url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" + lng + "," + lat + ".json?country=es&limit=1&types=region&access_token=pk.eyJ1Ijoib21pdGciLCJhIjoiY2xiNWF4OWp4MDE2bDNub2FlbHp3dmZvcyJ9.mQp5NnAxt9CuOm7GuD1ODg";
        $.ajax({
            dataType: "json",
            url: url,
            method: 'GET',
            context:this,
            async:false,
            success: function(datos) 
            {
                $("h3").remove();
                $("h4").remove();
                var features = datos.features
                if (features.length > 0) {
                    this.provincia = features[0].text;
                }
                $("main").after("<h3>Gasolineras de " + this.provincia + "</h3>");
            },
            error:function()
            {
                $("h3").remove();
                $("h4").remove();
                $("main").after("<h4>Ha ocurrido un error, no se ha podido cargar la provincia</h4>");
            }            
        });
    }

    obtenerCodigoProvincia() {
        switch (this.provincia) {
            case "Álava":
                return "01";
            case "Albacete":
                return "02";
            case "Alicante":
                return "03";
            case "Almería":
                return "04";
            case "Asturias":
                return "33";
            case "Ávila":
                return "05";
            case "Badajoz":
                return "06";
            case "Balearic Islands":
                return "07";
            case "Barcelona":
                return "08";
            case "Burgos":
                return "09";
            case "Cáceres":
                return "10";
            case "Cádiz":
                return "11";
            case "Cantabria":
                return "39";
            case "Castellón":
                return "12";
            case "Ceuta":
                return "51";
            case "Ciudad Real":
                return "13";
            case "Córdoba":
                return "14";
            case "Cuenca":
                return "16";
            case "Girona":
                return "17";
            case "Granada":
                return "18";
            case "Guadalajara":
                return "19";
            case "Gipuzkoa":
                return "20";
            case "Huelva":
                return "21";
            case "Huesca":
                return "22";
            case "Jaén":
                return "23";
            case "A Coruña":
                return "15";
            case "La Rioja":
                return "26";
            case "Las Palmas":
                return "35";
            case "León":
                return "24";
            case "Lleida":
                return "25";
            case "Lugo":
                return "27";
            case "Madrid":
                return "28";
            case "Málaga":
                return "29";
            case "Melilla":
                return "52";
            case "Region of Murcia":
                return "30";
            case "Navarre":
                return "31";
            case "Ourense":
                return "32";
            case "Palencia":
                return "34";
            case "Pontevedra":
                return "36";
            case "Salamanca":
                return "37";
            case "Segovia":
                return "40";
            case "Seville":
                return "41";
            case "Soria":
                return "42";
            case "Tarragona":
                return "43";
            case "Santa Cruz de Tenerife":
                return "38";
            case "Teruel":
                return "44";
            case "Toledo":
                return "45";
            case "Valencia":
                return "46";
            case "Valladolid":
                return "47";
            case "Biscay":
                return "48";
            case "Zamora":
                return "49";
            case "Zaragoza":
                return "50";
            default:
                return "";          
        }
    }

    mostrarDatos(codigo) {
        var url = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroProvincia/" + codigo;
        $.ajax({
            dataType: "json",
            url: url,
            method: 'GET',
            success: function(datos) 
            {   
                $("h4").remove();
                $("table").remove(); 
                var ListaEESSPrecio = datos.ListaEESSPrecio;
                var stringDatos = "<table>";
                stringDatos+= "<tr>";
                stringDatos+= "<th>Localidad</th>";
                stringDatos+= "<th>Dirección</th>";
                stringDatos+= "<th>Latitud y Longitud</th>";
                stringDatos+= "<th>Margen</th>";
                stringDatos+= "<th>Precio E5 (€/L)</th>";
                stringDatos+= "<th>Precio E10 (€/L)</th>";
                stringDatos+= "<th>Precio E5 Premium (€/L)</th>";
                stringDatos+= "<th>Rótulo</th>";
                stringDatos+= "</tr>";
                for(var i=0; i < ListaEESSPrecio.length;i++) {
                    stringDatos += "<tr>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].Localidad + "</td>";	
                    stringDatos += "<td>" + ListaEESSPrecio[i].Dirección + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].Latitud + ", " + ListaEESSPrecio[i]["Longitud (WGS84)"] + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].Margen + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i]["Precio Gasolina 95 E5"] + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i]["Precio Gasolina 95 E10"] + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i]["Precio Gasolina 95 E5 Premium"] + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].Rótulo + "</td>";
                    stringDatos += "</tr>";
                }                
                stringDatos += "</table>";                
                $("h3").after(stringDatos);
            },
            error:function()
            {   
                $("h4").remove();
                $("table").remove(); 
                $("h3").after("<h4>Ha ocurrido un error, no se han podido cargar los datos</h4>");
            }
        });
    }
}
var mapa = new Mapa();
mapa.initMap();