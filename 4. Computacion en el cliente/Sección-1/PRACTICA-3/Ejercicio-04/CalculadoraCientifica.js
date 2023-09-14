/**
 * Titulo: Clase CalculadoraBasica
 * Descripción: Clase que realiza la funcionalidad de una calculadora
 * 
 * @author Omar Teixeira González, UO281847
 * @version 14/11/2022
 */
class CalculadoraBasica {
    /**
     * Constructor de la calculadora
     */
    constructor() {
        this.estadoEncendido = false;
        this.estadoOperacion = false;
        this.pantalla = "0";
        this.memoria = "";
        this.operando1 = "";        
        this.operando2 = "";
        this.operadorActual = "";
        this.operadorAnterior = "";
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
     * Método raiz
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
            this.pantalla = "APAGADO";
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
 * Titulo: Clase CalculadoraCientifica
 * Descripción: Clase que realiza la funcionalidad de una calculadora cientifica
 * 
 * @author Omar Teixeira González, UO281847
 * @version 16/11/2022
 */
class CalculadoraCientifica extends CalculadoraBasica {
    /**
     * Constructor de la calculadora cientifica
     */
    constructor() {
        super();
        this.estadoEncendido = true;
        this.estadoTrigonometria = false;
        this.estadoHiperbolico = false;
        this.estadoShift = false;
        this.estadoFE = false;
        this.pantallaMemoria = "";     
        this.operandoFE = "";
        this.unidad="DEG";       
        this.recargarPantalla();
        this.deshabilitar(true);
    }

    /**
     * Método digitos
     * @param digito 
     */
    digitos(digito) {
        if (this.estadoOperacion || this.estadoFE) {            
            this.pantalla = "0";            
            this.estadoOperacion = false;
            this.estadoFE = false;
        } 
        if (this.estadoTrigonometria) {
            this.pantalla="0";
            this.pantallaMemoria = "";
            this.estadoTrigonometria = false;
        } 
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
            this.pantalla = "";
            this.operando1 = "";
            this.operando2 = "";
        } 
        if (this.pantalla.includes(",e+")) {
            if (this.pantalla.charAt(this.pantalla.length-1) == "0") {
                this.pantalla = this.pantalla.slice(0, -1);
            }
            this.pantalla+= digito;
        } else if (this.pantalla != "0") {
            this.pantalla += digito;
        } else {
            this.pantalla = digito.toString();
        }
        this.operando2 = this.pantalla;                
        this.recargarPantalla();
        if (this.pantalla === this.operando1) {
            this.pantalla = "0";
            this.operando1 = "";
            this.operando2 = "";
            this.operadorActual = "";
        }
    }   

    /**
     * Método igual
     */
    igual() {
        if (this.pantalla == "0" ) {
            this.pantallaMemoria = "0";
        }
        if (this.operadorActual == "**2" 
            || this.operadorActual == "10**"
            || this.operadorActual == "fact"
            || this.operadorActual == "log") {
            this.pantallaMemoria = this.pantalla;
            this.operadorActual = "="; 
        } else if (this.operadorActual == "Mod") {
            this.pantallaMemoria+= this.operando2;            
            this.calcular(this.pantallaMemoria.replace("Mod", "%"));
            this.operadorAnterior = this.operadorActual;
            this.operadorActual = "=";  
        } else if (this.operadorActual == "Exp") {
            var posicion = this.pantalla.indexOf(",");
            this.operando2 = this.pantalla.slice(posicion, this.pantalla.length).replace(",e+","");
            this.calcular(this.operando1 + "*10**" + this.operando2);
            this.pantallaMemoria=this.pantalla;
            this.operadorActual="=";
        } else if (this.estadoTrigonometria) {     
            this.operando2 = Number(this.pantalla);
            this.calcular(Number(this.operando1) + Number(this.pantalla));
            this.estadoTrigonometria = false;
            this.operadorAnterior = this.operadorActual;
            this.operadorActual = "=";        
        } else if (this.estadoFE) {
            this.pantallaMemoria += this.operando2;
            this.calcular(this.operandoFE + this.operadorActual + this.operando2);
            this.operadorAnterior = this.operadorActual;
            this.operadorActual = "=";
        } else if (this.operadorActual != "=") {
            if (this.pantalla=="" && this.estadoOperacion) {                                
                this.pantallaMemoria += this.operando1;
                this.estadoOperacion = false;
            } else {
                this.operadorAnterior = this.operadorActual;
                this.pantallaMemoria += this.operando2;                          
            }   
            this.calcular(this.pantallaMemoria.replace("^", "**"));
            this.operadorActual = "=";         
        } else {
            if (this.pantallaMemoria == "" 
                || this.pantallaMemoria.slice(0, this.pantallaMemoria.length-1) == this.pantalla) {                
                this.pantallaMemoria = this.pantalla;
            } else {                
                this.pantallaMemoria = this.operando1 + this.operadorAnterior.replace("**", "^").replace("%", "Mod") + this.operando2;
            }
            this.calcular(this.pantallaMemoria.replace("^", "**").replace("Mod", "%"));
        }
        if (this.pantallaMemoria[this.pantallaMemoria.length-1]!="=") {
            this.pantallaMemoria += "=";
        }
        this.estadoOperacion = true;
        this.recargarPantalla();        
    }

    /**
     * Método cuadrado
     */
    cuadrado() {
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
        }
        if (this.pantallaMemoria="") {
            this.pantallaMemoria = "sqr(" + this.pantallaMemoria + ")";            
        } else {
            this.pantallaMemoria += "sqr(" + this.pantalla + ")";              
        }
        this.estadoOperacion = true;
        this.operadorActual = "**2";
        this.calcular(this.pantalla+"**2");
        this.recargarPantalla();
        this.pantallaMemoria = "";
    }

    /**
     * Método potencia
     */
    potencia() {
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
        }
        this.pantallaMemoria=this.pantalla + "^";
        this.estadoOperacion = true;
        this.operadorActual = "**";
        this.recargarPantalla();
    }

    /**
     * Método raizCuadrada
     */
    raizCuadrada() {
        this.pantallaMemoria = "√(" + this.pantalla + ")";        
        super.raizCuadrada();
        this.recargarPantalla();
        this.pantallaMemoria = "";
    }

    /**
     * Método potenciaDeDiez
     */
    potenciaDeDiez() {
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
        }
        if (this.pantallaMemoria="") {
            this.pantallaMemoria = "10^(" + this.pantallaMemoria + ")";            
        } else {
            this.pantallaMemoria += "10^(" + this.pantalla + ")";                        
        }       
        this.estadoOperacion = true;
        this.operadorActual = "10**"
        this.calcular(this.pantallaMemoria.replace("^", "**"));        
        this.recargarPantalla();
        this.pantallaMemoria = "";
    }

    /**
     * Método log
     */
    log () {
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
        }
        if (this.pantallaMemoria="") {
            this.pantallaMemoria = "log(" + this.pantallaMemoria + ")";            
        } else {
            this.pantallaMemoria += "log(" + this.pantalla + ")";                        
        }
        this.estadoOperacion = true;
        this.operadorActual = "log"
        this.calcular("Math.log10(" + this.pantalla + ")");        
        this.recargarPantalla();
        this.pantallaMemoria = "";
    }

    /**
     * Método exp
     */
    exp() {
        this.operando1 = this.pantalla;
        this.pantalla += ",e+0";
        this.operadorActual = "Exp";
        this.recargarPantalla();
    }

    /**
     * Método mod
     */
    mod() {
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
        }
        this.operando1 = this.pantalla;
        this.pantallaMemoria += this.pantalla + "Mod";        
        this.estadoOperacion = true;
        this.operadorActual = "Mod";
        this.recargarPantalla();
    }

    /**
     * Método sin
     */
    sin() {
        if (this.estadoHiperbolico) {
            if (this.estadoShift) {
                this.calcularTrigonometrica("asinh");
            } else {
                this.calcularTrigonometrica("sinh");
            }            
        } else {
            if (this.estadoShift) {
                this.calcularTrigonometrica("asin");
            } else {
                this.calcularTrigonometrica("sin");
            }
        }     
    }

    /**
     * Método cos
     */
    cos() {
        if (this.estadoHiperbolico) {
            if (this.estadoShift) {
                this.calcularTrigonometrica("acosh");
            } else {
                this.calcularTrigonometrica("cosh");
            }            
        } else {
            if (this.estadoShift) {
                this.calcularTrigonometrica("acos");
            } else {
                this.calcularTrigonometrica("cos");
            }
        }
    }

    /**
     * Método tan
     */
    tan() {
        if (this.estadoHiperbolico) {
            if (this.estadoShift) {
                this.calcularTrigonometrica("atanh");
            } else {
                this.calcularTrigonometrica("tanh");
            }            
        } else {
            if (this.estadoShift) {
                this.calcularTrigonometrica("atan");
            } else {
                this.calcularTrigonometrica("tan");
            }            
        }  
    }

    /**
     * Método shift
     */
    shift() {
        this.estadoShift = !this.estadoShift;        
        if (this.estadoShift) {            
            if (this.estadoHiperbolico) {
                document.querySelector("input[type=\"button\"][value=\"sinh\"]").value="asinh";
                document.querySelector("input[type=\"button\"][value=\"cosh\"]").value="acosh";
                document.querySelector("input[type=\"button\"][value=\"tanh\"]").value="atanh";
            } else {
                document.querySelector("input[type=\"button\"][value=\"sin\"]").value="asin";
                document.querySelector("input[type=\"button\"][value=\"cos\"]").value="acos";
                document.querySelector("input[type=\"button\"][value=\"tan\"]").value="atan";
            }
        } else {
            if (this.estadoHiperbolico) {
                document.querySelector("input[type=\"button\"][value=\"asinh\"]").value="sinh";
                document.querySelector("input[type=\"button\"][value=\"acosh\"]").value="cosh";
                document.querySelector("input[type=\"button\"][value=\"atanh\"]").value="tanh";
            } else {
                document.querySelector("input[type=\"button\"][value=\"asin\"]").value="sin";
                document.querySelector("input[type=\"button\"][value=\"acos\"]").value="cos";
                document.querySelector("input[type=\"button\"][value=\"atan\"]").value="tan";
            }
        }
    }

    /**
     * Método pi
     */
    pi() {
        this.pantalla = Math.PI;
        this.recargarPantalla();
    }

    /**
     * Método factorial
     */
    factorial() {
        if (this.pantallaMemoria.includes("fact")) {
            this.pantallaMemoria = "fact(" + this.pantallaMemoria + ")";            
        } else {
            this.pantallaMemoria += "fact(" + this.pantalla + ")";                        
        }
        var fact = 1;
        var valor = Number(this.pantalla);        
        while (valor > 1) {
            fact*=valor--;
        }
        this.pantalla = fact.toString();
        this.estadoOperacion = true;
        this.operadorActual = "fact";
        this.recargarPantalla();
    }

    /**
     * Método borrarTodo
     */
    borrarTodo() {
        this.pantallaMemoria="";
        this.memoria="";
        this.operando1="";
        this.operando2="";
        this.operadorActual="";
        this.operadorAnterior="";
        super.borrar();
    }

    /**
     * Método borrarIzquierda
     */
    borrarIzquierda() {
        if (this.pantalla != "0" && !this.estadoOperacion) {
            var copia = this.pantalla;
            this.pantalla = copia.slice(0, -1);
            if (this.pantalla.length==0){
                this.pantalla = "0";
            }
            this.recargarPantalla();
        }
    }

    /**
     * Método abreParentesis
     */
    abreParentesis() {
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
        }        
        this.recargarPantalla();
        this.pantallaMemoria+="(";
        this.recargarPantalla();
        this.estadoOperacion = true;
    }

    /**
     * Método cierraParentesis
     */
    cierraParentesis() {        
        if (this.pantallaMemoria.includes("(")) {            
            this.pantallaMemoria+=this.pantalla + ")";
            this.recargarPantalla();
            this.pantalla = "";
            this.estadoOperacion = true;
        }        
    }

    /**
     * Método deg
     */
    deg() {
        switch(this.unidad) {
            case "DEG":
                document.querySelector("input[type=\"button\"][value=\"DEG\"]").value="RAD";
                this.unidad ="RAD";
                break;
            case "RAD":
                document.querySelector("input[type=\"button\"][value=\"RAD\"]").value="GRAD";
                this.unidad = "GRAD";
                break;
            case "GRAD":
                document.querySelector("input[type=\"button\"][value=\"GRAD\"]").value="DEG";
                this.unidad = "DEG";
                break;
        }
    }

    /**
     * Método hyp
     */
    hyp() {
        this.estadoHiperbolico = !this.estadoHiperbolico;
        if (this.estadoHiperbolico) {
            if (this.estadoShift) {
                document.querySelector("input[type=\"button\"][value=\"asin\"]").value="asinh";
                document.querySelector("input[type=\"button\"][value=\"acos\"]").value="acosh";
                document.querySelector("input[type=\"button\"][value=\"atan\"]").value="atanh";    
            } else {
                document.querySelector("input[type=\"button\"][value=\"sin\"]").value="sinh";
                document.querySelector("input[type=\"button\"][value=\"cos\"]").value="cosh";
                document.querySelector("input[type=\"button\"][value=\"tan\"]").value="tanh";
            }
            
        } else {
            if (this.estadoShift){
                document.querySelector("input[type=\"button\"][value=\"asinh\"]").value="asin";
                document.querySelector("input[type=\"button\"][value=\"acosh\"]").value="acos";
                document.querySelector("input[type=\"button\"][value=\"atanh\"]").value="atan";
            } else {
                document.querySelector("input[type=\"button\"][value=\"sinh\"]").value="sin";
                document.querySelector("input[type=\"button\"][value=\"cosh\"]").value="cos";
                document.querySelector("input[type=\"button\"][value=\"tanh\"]").value="tan";
            }            
        }
    }

    /**
     * Método fe
     */
    fe() {
        this.estadoFE = !this.estadoFE;
        if (this.pantalla.indexOf("e") == -1) {
            this.operandoFE = this.pantalla;
            this.pantalla = eval(this.pantalla).toString();
            var posicion = this.pantalla.indexOf(".");
            var tamanio = this.pantalla.length -1;
            if (posicion != -1) {
                tamanio -= this.pantalla.length - posicion;
                this.pantalla = this.pantalla.replace(".", "");
            }
            this.pantalla = this.pantalla.charAt(0) + "." + this.pantalla.substring(1) + "e+" + tamanio;
        } else {
            this.pantalla = eval(this.pantalla).toString();
        }
        this.recargarPantalla();
    }

    /**
     * Método mc
     */
    mc() {
        this.memoria = "";
        this.pantallaMemoria = "";
        this.deshabilitar(true);
        this.recargarPantalla();
    }

    /**
     * Método mr
     */
    mr() {
        if (!this.estadoOperacion) {
            this.pantallaMemoria = "";
        }
        this.pantalla = this.memoria;        
        this.deshabilitar(false);
        this.recargarPantalla();
    }

    /**
     * Método mMas
     */
    mMas() {
        this.memoria= Number(this.memoria) + Number(this.pantalla);        
        this.deshabilitar(false);
    }

    /**
     * Método mMenos
     */
    mMenos() {
        this.memoria= Number(this.memoria) - Number(this.pantalla);        
        this.deshabilitar(false);
    }

    /**
     * Método ms
     */
    ms() {
        this.memoria = this.pantalla;
        this.deshabilitar(false);
        this.recargarPantalla();
    }

    /**
     * Método calcular
     * @param valor 
     */
    calcular(valor) {
        try {
            this.pantalla = eval(valor).toString();
            this.operando1 = this.pantalla;                       
        }catch(err) {
            this.pantalla = "ERROR";            
        }
    }

    /**
     * Método calcularOperador
     * @param operador 
     */
    calcularOperador(operador) {
        if (this.operadorActual == "Exp") {
            var posicion = this.pantalla.indexOf(",");
            this.operando2 = this.pantalla.slice(posicion, this.pantalla.length).replace(",e+","");
            this.calcular(this.operando1 + "*10**" + this.operando2);
        } else if (this.estadoTrigonometria) {     
            this.calcular(Number(this.operando1));
            this.estadoTrigonometria = false;
            this.pantallaMemoria = "";
        }
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
        }
        this.operadorActual = operador; 
        this.operando1 = this.pantalla;
        this.pantallaMemoria+= this.pantalla + operador;             
        this.recargarPantalla();
        this.estadoOperacion=true;
    }

    /**
     * Método calcularTrigonometrica
     * @param funcion 
     */
    calcularTrigonometrica(funcion) {
        if (this.pantallaMemoria.includes("=")) {
            this.pantallaMemoria="";
        }
        if (this.pantallaMemoria="") {
            this.pantallaMemoria=funcion + "(" + this.pantallaMemoria + ")";            
        } else {
            this.pantallaMemoria+=funcion + "(" + this.pantalla + ")";                        
        }        
        this.calcular("Math." + funcion + "(" + this.convertir() + ")");  
        this.estadoOperacion = true;
        this.estadoTrigonometria = true;
        this.recargarPantalla();        
    }

    /**
     * Método convertir
     * @returns valor
     */
    convertir() {
        var valor = Number(this.pantalla);
        switch (this.unidad) {
            case "DEG":                
                valor *= (Math.PI/180);
                break;
            case "RAD":
                break;
            case "GRAD":
                valor *= (Math.PI/200);
                break;
        }
        return valor;
    }

    /**
     * Método deshabilitar
     * @param opcion 
     */
    deshabilitar(opcion) {
        document.querySelector("input[type=\"button\"][value=\"MC\"]").disabled = opcion;
        document.querySelector("input[type=\"button\"][value=\"MR\"]").disabled = opcion;
    }

    /**
     * Método recargarPantalla
     */
    recargarPantalla() {
        document.getElementsByTagName("input")[0].value = this.pantallaMemoria;        
        document.getElementsByTagName("input")[1].value = this.pantalla;
    }
}

/**
 * Atributo calculadora
 */
var calculadora = new CalculadoraCientifica();

/**
 * Gestión de eventos keydown
 */
document.addEventListener('keydown', function(e) {
    switch (e.key) {
        case "g":
            calculadora.deg();
            break;
        case "h":
            calculadora.hyp();
            break;
        case "f":
            calculadora.fe();
            break;
        case "b":
            calculadora.mc();
            break;
        case "r":
            calculadora.mr();
            break;
        case "a":
            calculadora.mMas();
            break;
        case "k":
            calculadora.mMenos();
            break;
        case "m":
            calculadora.ms();
            break;
        case "q":
            calculadora.cuadrado();
            break;
        case "p":
            calculadora.potencia();
            break;
        case "s":
            calculadora.sin();
            break;
        case "c":
            calculadora.cos();
            break;
        case "t":
            calculadora.tan();
            break;            
        case "w":
            calculadora.raizCuadrada();
            break;
        case "d":
            calculadora.potenciaDeDiez();
            break;
        case "l":
            calculadora.log();
            break;
        case "e":
            calculadora.exp();
            break;
        case "o":
            calculadora.mod();
            break;
        case "u":
            calculadora.shift();
            break;        
        case "v":
            calculadora.borrarTodo();
            break;
        case "i":
            calculadora.pi();
            break;
        case "n":
            calculadora.factorial();
            break;
        case "y":
            calculadora.cambioDeSigno();
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
        case "+":
            calculadora.suma();
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
        case "Backspace":
            calculadora.borrarIzquierda();
            break;
        default:
            if (e.key >= 0 && e.key <= 9){
                calculadora.digitos(Number(e.key));
            }            
            break;
    }
});