class Lector {
    constructor() {
        $("input").val(null);   
    }

    compatibleConNavegador() {
        if (window.File && window.FileReader && window.FileList && window.Blob) 
        {              
            $("p").after("<p>Este navegador soporta el API File</p>");
        } else {
            $("p").after("<p>¡Este navegador NO soporta el API File y este programa puede no funcionar correctamente!</p>");
        }
    }

    calcularTamañoArchivos() {
        $("article").remove();
        $("input").after("<article><h2>Datos de los archivos cargados</h2></article>");
        this.archivos = document.querySelector("input[type=file]").files;
        var nBytes = 0;        
        var nArchivos = this.archivos.length;
        var nombresTiposTamaños="<ol>";
        for (var i = 0; i < nArchivos; i++) {
            nBytes += this.archivos[i].size;
            nombresTiposTamaños += "<li>Archivo: "+ this.archivos[i].name  + ", Tamaño: " + this.archivos[i].size +" bytes " +  ", Tipo: " + this.archivos[i].type+"</li>" ;
        }
        nombresTiposTamaños+="</ol>";
        
        $("article").append("<p>Número de archivos seleccionados: " + nArchivos + "</p>");
        $("article").append("<p>Tamaño de los archivos seleccionados: " + nBytes + " bytes</p>");
        $("article").append("<p>Lista de archivos:</p>" +  nombresTiposTamaños);
    }

    cargarFicheros() {
        $("section").remove();
        $("article").append("<section><h3>Contenido de los archivos cargados</h2></section>");
        for (var i=0; i<this.archivos.length; i++) {
            var archivo = this.archivos[i];
            var nombre = archivo.name;

            var tipoTexto = /text.*/;
            var tipoJson = /application.json/;

            $("section").append("<p>Contenido del fichero \"" + nombre + "\":</p>");
            $("section").append("<pre><code></code></pre>");
            if (archivo.type.match(tipoJson) || archivo.type.match(tipoTexto)) {
                this.cargarContenido(archivo, i);
            } else {
                $("section").append("<p>ERROR: El archivo cargado no es válido.</p>");
            }
        }
    }

    cargarContenido(archivo, i) {
        var lector = new FileReader();
        lector.onload = function (evento) {
            $("code").eq(i).text(lector.result);
        };
        lector.readAsText(archivo);
    }
    
}
var lector = new Lector();
lector.compatibleConNavegador();