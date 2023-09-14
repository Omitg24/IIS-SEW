class Consultor {
    constructor() {
        $("select").prop("selectedIndex",0);     
        this.url = "";   
        this.urlSave="https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroMunicipioProducto/4994/";
    }

    actualizarProvincia() {
        var provincia = $("select:first option:selected").val();        
        this.urlSave="https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroProvinciaProducto/" + provincia + "/";
        $("select:last").attr("disabled", true);
    }

    actualizarMunicipio() {
        var municipio = $("select:last option:selected").val();        
        this.urlSave="https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroMunicipioProducto/" + municipio + "/";
        $("select:first").attr("disabled", true);
    }

    seleccionarTipo(tipo) {        
        this.url= this.urlSave + tipo;
        this.mostrarDatos();
    }

    mostrarDatos() {
        $.ajax({
            dataType: "json",
            url: this.url,
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
                stringDatos+= "<th>Precio (€/L)</th>";
                stringDatos+= "<th>Rótulo</th>";
                stringDatos+= "</tr>";
                for(var i=0; i < ListaEESSPrecio.length;i++) {
                    stringDatos += "<tr>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].Localidad + "</td>";	
                    stringDatos += "<td>" + ListaEESSPrecio[i].Dirección + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].Latitud + ", " + ListaEESSPrecio[i]["Longitud (WGS84)"] + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].Margen + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].PrecioProducto + "</td>";
                    stringDatos += "<td>" + ListaEESSPrecio[i].Rótulo + "</td>";
                    stringDatos += "</tr>";
                }                
                stringDatos += "</table>";                
                $("input:last").after(stringDatos);
                $("select").attr("disabled", false);
            },
            error:function()
            {   
                $("h4").remove();
                $("table").remove(); 
                $("input:last").after("<h4>Ha ocurrido un error, no se han podido cargar los datos</h4>");
            }
        });
    }
}
var consultor = new Consultor();