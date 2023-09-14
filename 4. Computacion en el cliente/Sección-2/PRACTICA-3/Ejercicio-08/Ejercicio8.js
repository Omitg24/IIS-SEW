class Meteo {
    constructor(){
        $("select").prop("selectedIndex",0);
        this.apikey = "177ebb638514363c550937092c3c6bc7";
        this.ciudad = $("select option:selected").val();
        this.codigoPais = "ES";
        this.unidades = "&units=metric";
        this.idioma = "&lang=es";
        this.url = "https://api.openweathermap.org/data/2.5/weather?q=" + this.ciudad + "," + this.codigoPais + this.unidades + this.idioma + "&APPID=" + this.apikey;        
    }

    actualizarCiudad() {
        this.ciudad = $("select option:selected").val();
        this.url = "https://api.openweathermap.org/data/2.5/weather?q=" + this.ciudad + "," + this.codigoPais + this.unidades + this.idioma + "&APPID=" + this.apikey;
        $("input[type=\"button\"]").attr("disabled", false);
    }

    mostrarTiempo(){
        $.ajax({
            dataType: "json",
            url: this.url,
            method: 'GET',
            success: function(datos) 
            {  
                $("h3").remove();
                $("article").remove();
                var icono = datos.weather[0].icon;
                var imagen = "https://openweathermap.org/img/w/" + icono + ".png";
                var stringDatos = "<article>";
                stringDatos += "<h2>Datos</h2>";                    
                stringDatos += "<img src=\"" + imagen + "\" alt=\"Icono del tiempo\"/>";
                stringDatos += "<p>Ciudad: " + datos.name + "</p>";
                stringDatos += "<p>País: " + datos.sys.country + "</p>";
                stringDatos += "<p>Latitud: " + datos.coord.lat + " grados</p>";
                stringDatos += "<p>Longitud: " + datos.coord.lon + " grados</p>";
                stringDatos += "<p>Temperatura: " + datos.main.temp + " grados Celsius</p>";
                stringDatos += "<p>Temperatura máxima: " + datos.main.temp_max + " grados Celsius</p>";
                stringDatos += "<p>Temperatura mínima: " + datos.main.temp_min + " grados Celsius</p>";
                stringDatos += "<p>Presión: " + datos.main.pressure + " milímetros</p>";
                stringDatos += "<p>Humedad: " + datos.main.humidity + "%</p>"; 
                stringDatos += "<p>Amanece a las: " + new Date(datos.sys.sunrise *1000).toLocaleTimeString() + "</p>"; 
                stringDatos += "<p>Oscurece a las: " + new Date(datos.sys.sunset *1000).toLocaleTimeString() + "</p>"; 
                stringDatos += "<p>Dirección del viento: " + datos.wind.deg + "  grados</p>";
                stringDatos += "<p>Velocidad del viento: " + datos.wind.speed + " metros/segundo</p>";
                stringDatos += "<p>Hora de la medida: " + new Date(datos.dt *1000).toLocaleTimeString() + "</p>";
                stringDatos += "<p>Fecha de la medida: " + new Date(datos.dt *1000).toLocaleDateString() + "</p>";
                stringDatos += "<p>Descripción: " + datos.weather[0].description + "</p>";
                stringDatos += "<p>Visibilidad: " + datos.visibility + " metros</p>";
                stringDatos += "<p>Nubosidad: " + datos.clouds.all + " %</p>";                    
                stringDatos += "</article>";
                $("input").after(stringDatos);
                $("input[type=\"button\"]").attr("disabled", true);
            },
            error:function()
            {   
                $("h3").remove();
                $("article").remove();
                $("input:last").after("<h3>Ha ocurrido un error, no se han podido cargar los datos</h3>");
            }
        });
    }
}
var meteo = new Meteo();