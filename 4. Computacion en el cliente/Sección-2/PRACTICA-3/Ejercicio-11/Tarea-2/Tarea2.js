class Geolocalizador {
    constructor() {
        navigator.geolocation.getCurrentPosition(this.getPosicion.bind(this), this.verErrores.bind(this));
    }

    getPosicion(posicion){
        this.mensaje = "Se ha realizado correctamente la peticion de geolocalización.";
        this.longitud = posicion.coords.longitude; 
        this.latitud = posicion.coords.latitude;  
        this.precision = posicion.coords.accuracy;
        this.altitud = posicion.coords.altitude;
        this.precisionAltitud = posicion.coords.altitudeAccuracy;
        this.rumbo = posicion.coords.heading;
        this.velocidad = posicion.coords.speed;       
    }

    verErrores(error){
        switch(error.code) {
        case error.PERMISSION_DENIED:
            this.mensaje = "El usuario no permite la petición de geolocalización.";
            break;
        case error.POSITION_UNAVAILABLE:
            this.mensaje = "Información de geolocalización no disponible.";
            break;
        case error.TIMEOUT:
            this.mensaje = "La petición de geolocalización ha caducado.";
            break;
        case error.UNKNOWN_ERROR:
            this.mensaje = "Se ha producido un error desconocido.";
            break;
        }
    }

    getLongitud(){
        return this.longitud;
    }

    getLatitud(){
        return this.latitud;
    }

    getAltitud(){
        return this.altitud;
    }
    
    mostrarDatos(){
        $("article").remove();
        var stringDatos="<article>"; 
        stringDatos+="<h2>Datos</h2>";
        stringDatos+="<p>" + this.mensaje + "</p>";
        stringDatos+="<p>Longitud: "+this.longitud +" grados</p>"; 
        stringDatos+="<p>Latitud: "+this.latitud +" grados</p>";
        stringDatos+="<p>Precisión de la latitud y longitud: "+ this.precision +" metros</p>";
        stringDatos+="<p>Altitud: "+ this.altitude +" metros</p>";
        stringDatos+="<p>Precisión de la altitud: "+ this.precisionAltitud +" metros</p>"; 
        stringDatos+="<p>Rumbo: "+ this.rumbo +" grados</p>"; 
        stringDatos+="<p>Velocidad: "+ this.velocidad +" metros/segundo</p>";
        stringDatos+="</article>";
        $("input").after(stringDatos);
    }
}
var geo = new Geolocalizador();