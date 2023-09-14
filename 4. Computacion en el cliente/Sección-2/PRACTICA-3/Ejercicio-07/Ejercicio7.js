class Funcionalidades {
    constructor() {}

    ocultar() {
        $("h1").hide();
        $("h2").hide();
        $("img").hide();
    }

    mostrar() {
        $("h1").show();
        $("h2").show();
        $("img").show();        
    }

    modificar() {
        $("p").text("Texto de los párrafos modificados para la realización de la tarea 2 del ejercicio 7 de computación en la nube.");
    }

    añadir() {
        $("p").after("<p>Texto añadido para la realización de la tarea 3 del ejercicio 7 de computación en la nube.</p>");
        $("table").after("<img alt=\"Logos de los framework a comparar\" src=\"multimedia/comparation.png\"/>");
    }

    eliminar() {
        $("p").remove();
        $("img").remove();
    }

    recorrer() {
        $("article").eq(2).before("<article><h3>Recorrido del arbol DOM:</h3></article>");
        $("*", document.body).each(function() {
            var etiquetaPadre = $(this).parent().get(0).tagName;
            $("article").eq(3).before("<p>Etiqueta padre: "  + etiquetaPadre + " elemento: " + $(this).get(0).tagName +"</p>");
        });
    }

    calcular() {
        var filas = ["tipo", "respaldado", "fecha", 
                    "rendimiento", "enlace", "usado", 
                    "curva", "modelos", "velocidad", 
                    "popularidad", "compañias", "sumacolumnas"];
        var columnas = ["react", "angular", "ember", 
                    "vue", "sumafilas"];
        var indexFilas = 0;
        var indexColumnas = 0;
        var valorFila = 0;
        $("tr").each(function() {
            $(this).find("td").each(function() {
                var valorCelda = $(this).text();
                valorFila += Number(valorCelda);
            });
            if ($(this).find("td").length == 0) {
                $(this).append("<th scope=\"col\" id=\"sumafilas\">ΣFilas</th>");
            } else {
                $(this).append("<td headers=\"" + filas[indexFilas] + " sumafilas\">" + valorFila + "</td>");
                indexFilas++;
            }
            valorFila = 0;            
        });

        var valorColumna=0;
        var contador = 0;
        var nFilas = 0;
        while(contador < $("tr:first-child > th ").length - 1) {
            $("tr").each(function() {
                var valorCelda = $(this).find("td").eq(contador).text();
                valorColumna += Number(valorCelda);
                nFilas++;
                if (nFilas == $("tr").length) {
                    if (contador == 0) {
                        $("tr").last().after("<tr><th scope=\"row\" id=\"sumacolumnas\" headers=\"atributos\">ΣColumnas</th></tr>");
                    }
                    $("tr").last().append("<td headers=\"sumacolumnas " + columnas[indexColumnas] + "\">" + valorColumna + "</td>");
                    contador++;
                    indexColumnas++;
                    nFilas = 0;
                    valorColumna = 0;
                }
            })
        }        
    }
}
var funcionalidades = new Funcionalidades();