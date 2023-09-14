/**
 * Titulo: Clase Calculadora
 * Descripción: Clase que realiza la funcionalidad de una calculadora
 * 
 * @author Omar Teixeira González, UO281847
 * @version 14/11/2022
 */
class Calculadora {
    /**
     * Constructor de la calculadora
     */
    constructor() {
        this.estadoEncendido = false;
        this.estadoOperacion = false;
        this.pantalla = "OFF";
        this.memoria = "";
        this.operando1 = "";        
        this.operando2 = "";
        this.operadorActual = "";
        this.operadorAnterior = "";
        this.recargarPantalla();
    }

    /**
     * Método digitos
     * @param digito 
     */
    digitos(digito) {
        if (this.estadoEncendido) {
            if (this.estadoOperacion 
                || this.pantalla.includes("M")) {
                this.pantalla = "0";    
                this.estadoOperacion = false;
            }
            if (this.pantalla.length < 8) {     
                if (this.pantalla != "0") {
                    this.pantalla += digito;
                } else {
                    this.pantalla = digito.toString();
                }
                this.operando2 = this.pantalla;                
                this.recargarPantalla();
                if ((this.pantalla === this.operando1 
                    || (this.pantalla === "M " + this.operando1)) 
                    && this.estadoOperacion) {
                    this.pantalla = "0";
                    this.operando1 = "";
                    this.operando2 = "";
                    this.operadorActual = "";
                }              
            }            
        }        
    }
    
    /**
     * Método punto
     */
    punto() {
        if (this.estadoEncendido) {
            if (!this.pantalla.includes(".")) {
                this.pantalla += ".";
            }            
            this.recargarPantalla();
        }
    }

    /**
     * Método suma
     */
    suma() {
        this.calcularOperador("+");
    }

    /**
     * Método resta
     */
    resta() {
        this.calcularOperador("-");
    }

    /**
    * Método multiplicacion
    */
    multiplicacion() {
        this.calcularOperador("*");
    }

    /**
     * Método division
     */
    division() {
        this.calcularOperador("/");
    }

    /**
     * Método mrc
     */
    mrc() {
        if (this.estadoEncendido) {
            if (this.memoria != "") {
                if (this.pantalla.includes("M")) {
                    this.pantalla = this.pantalla.slice(2, this.pantalla.length);
                } else {
                    this.pantalla = "M " + this.memoria;    
                }
                this.operando2 = this.pantalla.slice(2,this.pantalla.length);
                this.recargarPantalla();
            }            
        }       
    }

    /**
     * Método mMenos
     */
    mMenos() {
        if (this.estadoEncendido) {
            if (this.pantalla.includes("M") || this.pantalla.includes("E")){
                this.pantalla = this.pantalla.slice(2, this.pantalla.length);
            }
            this.memoria = Number(this.memoria) - Number(this.pantalla);
        }
    }

    /**
     * Método mMas
     */
    mMas() {
        if (this.estadoEncendido) {
            if (this.pantalla.includes("M") || this.pantalla.includes("E")){
                this.pantalla = this.pantalla.slice(2, this.pantalla.length);
            }
            this.memoria = Number(this.memoria) + Number(this.pantalla);
        }
    }

    /**
     * Método borrar
     */
     borrar() {
        if (this.estadoEncendido) {            
            this.pantalla = "0";
            this.recargarPantalla();
        }       
    }

    /**
     * Método igual
     */
    igual() {
        if (this.estadoEncendido) {
            if (this.operando1.includes("E")){
                this.operando1 = Number(this.pantalla.slice(2, this.pantalla.length));
            }
            if (this.operando2.includes("E")){
                this.operando2 = Number(this.pantalla.slice(2, this.pantalla.length));
            }
            if (this.operando1 == "" && this.operadorActual == "") {
                varValorCalculado = Number(this.operando2);
                this.calcular(valorCalculado);
            }
            if (this.operadorActual != "=") {
                this.operadorAnterior = this.operadorActual;
                var valorCalculado = Number(this.operando1) + this.operadorActual + Number(this.operando2);
                this.calcular(valorCalculado);
                this.operadorActual = "=";
                this.estadoOperacion = true;
            } else {
                var valorCalculado = Number(this.operando1) + this.operadorAnterior + Number(this.operando2);
                this.calcular(valorCalculado);
                this.estadoOperacion = true;
            }
        }
    }

    /**
     * Método porcentaje
     */
    porcentaje() {
        if (this.estadoEncendido) {
            if (this.pantalla != "") {
                if (this.pantalla.includes("M")) {
                    this.pantalla =  this.pantalla.slice(2, this.pantalla.length);
                }
                if (this.operadorActual == "+" || this.operadorActual == "-") {
                    this.operando2 = (Number(this.operando1) * Number(this.operando2))/100;            
                    var valorCalculado = Number(this.operando1) + this.operadorActual + Number(this.operando2);
                    this.estadoOperacion = true;
                } else {
                    this.operando2 = 0;
                    var valorCalculado = Number(this.operando2);
                    if (this.operando1 != "" && this.operadorActual != "" && this.operadorActual != "=") {
                        this.operando2 = (Number(this.pantalla)/100);
                        var valorCalculado = Number(this.operando1) + this.operadorActual + Number(this.operando2);
                    }
                    this.estadoOperacion = true;
                }
                this.calcular(valorCalculado);    
            }
        }
    }

    /**
     * Método raizCuadrada
     */
    raizCuadrada() {
        if (this.estadoEncendido) {
            if (this.pantalla != "" && this.operando2 != "") {
                if (this.pantalla.includes("M") || this.pantalla.includes("E")) {
                    this.pantalla =  this.pantalla.slice(2, this.pantalla.length);
                }
                this.operando2 = this.pantalla;
                var valorObtenido = Number(this.operando2);            
                var valorCalculado = Math.sqrt(valorObtenido);
                this.estadoOperacion = true;
                this.calcular(valorCalculado);
            }
        }
    }

    /**
     * Método cambioDeSigno
     */
    cambioDeSigno() {
        if (this.estadoEncendido){
            var valorObtenido = Number(this.pantalla);
            if (Number.isNaN(valorObtenido) === false) {                
                if (valorObtenido >= 0) {
                    var valorCalculado = -valorObtenido;
                } else if (valorObtenido < 0) {
                    var valorCalculado = Math.abs(valorObtenido);
                }    
            }            
            this.pantalla = valorCalculado.toString();
            this.recargarPantalla();
        }        
    }

    /**
     * Método onC
     */
    onC() {
        this.estadoEncendido = !this.estadoEncendido;
        this.pantalla = "0";
        this.recargarPantalla();
        if (!this.estadoEncendido) {
            this.pantalla = "OFF";
            this.memoria = "";
            this.operando1 = "";
            this.operando2 = "";
            this.operadorActual = "";
            this.operadorAnterior = "";
            this.recargarPantalla();
        }
    }

    /**
     * Método recargarPantalla
     */
    recargarPantalla() {
        document.getElementsByTagName("input")[0].value = this.pantalla;
    }

    /**
     * Método calcularOperador
     * @param operador 
     */
    calcularOperador(operador) {
        this.calcularOperacion();
        if (this.estadoEncendido) {
            this.operando1 = this.pantalla;
            if (this.operando1.includes("M")) {
                this.operando1 = this.operando1.slice(2,this.operando1.length);
            }
            this.pantalla = "0";
            this.operadorActual = operador;
            this.estadoOperacion = true;
        }
    }

    /**
     * Método calcular
     * @param  valor 
     */
    calcular(valor) {
        try {
            this.pantalla = eval(valor).toString();
            if (this.pantalla.length > 8) {
                this.pantalla = "E "+ this.pantalla.slice(0,8);
            }
            this.operando1 = this.pantalla;
            this.recargarPantalla();
        }catch(err) {
            this.pantalla = "ERROR";
            this.recargarPantalla();
        }
    }   

    /**
     * Método calcularOperacion
     */
    calcularOperacion() {
        if (this.operando1 != "" && this.operadorActual != "" && this.operadorActual != "=" && this.operando2 != "") {
            var valorCalculado = Number(this.operando1) + this.operadorActual + Number(this.operando2);
            this.calcular(valorCalculado);
        }
    }
}

/**
 * Atributo calculadora
 */
var calculadora = new Calculadora();

/**
 * Gestión de eventos keydown
 */
document.addEventListener('keydown', function(e) {
    switch (e.key) {
        case "o":
            calculadora.onC();
            break;
        case "e":
            calculadora.borrar();
            break;
        case "c":
            calculadora.cambioDeSigno();
            break;
        case "r":
            calculadora.raiz();
            break;
        case "p":
            calculadora.porcentaje();
            break;
        case "x":
            calculadora.multiplicacion();
            break;
        case "/":
            calculadora.division();
            break;
        case "-":
            calculadora.resta();
            break;
        case "m":
            calculadora.mrc();
            break;
        case "+":
            calculadora.suma();
            break;
        case "e":
            calculadora.mMenos();
            break;
        case "a":
            calculadora.mMas();
            break;
        case ".":
            calculadora.punto();
            break;
        case "=":
            calculadora.igual();
            break;
        case "Enter":
            calculadora.igual();
            e.preventDefault();
            break;
        case "Delete":
            calculadora.borrar();
            break;
        default:
            if (e.key >= 0 && e.key <= 9){
                calculadora.digitos(Number(e.key));
            }            
            break;
    }
});