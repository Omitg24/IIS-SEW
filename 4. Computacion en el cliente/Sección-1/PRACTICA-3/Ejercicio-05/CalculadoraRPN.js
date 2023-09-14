/**
 * Titulo: Clase Stack
 * Descripción: Clase que realiza la funcionalidad de una pila
 * 
 * @author Omar Teixeira González, UO281847
 * @version 18/11/2022
 */
 class Stack {
    /**
     * Constructor de la pila
     */
    constructor() {
        this.ultimo = -1;
        this.valores = new Array();
    }

    /**
     * Método push
     * @param valor 
     */
    push(valor) {
        this.valores.push(valor);
        this.ultimo += 1;
    }

    /**
     * Método pop
     * @returns valor
     */
    pop() {
        if (!this.isEmpty()) {
            this.ultimo -= 1;
            return this.valores.pop();
        }
    }

    /**
     * Método peek
     * @returns valor
     */
    peek() {
        if (!this.isEmpty()) {
            return this.valores[this.ultimo];
        }
    }

    /**
     * Método isEmpty
     * @returns true o false
     */
    isEmpty() {
        return this.ultimo == -1;
    }

    /**
     * Método size
     * @returns size
     */
    size() {
        return this.ultimo + 1;
    }

    /**
     * Método get
     * @param index 
     * @returns valor
     */
    get(index) {
        if (index <= this.size() - 1) {
            return this.valores[index];
        }
    }

    /**
     * Método getStack
     * @returns valores
     */
    getStack() {
        return this;
    }

    /**
     * Método clear
     */
    clear() {
        for (var i = 0; i < this.size(); i++) {
            this.valores.pop();
        } 
        this.ultimo =  - 1;
    }

    /**
     * Método print
     * @returns valor
     */
    print() {
        var elemento;
        var pila = new Array();
        var resultado = "";
        for (var i = 0; i < this.size(); i++) {
            elemento = this.valores.pop();
            resultado += elemento + " :[" + Number(i+1) + "]\n";
            pila.push(elemento);
        }
        for (var i = 0; i < this.size(); i++) {
            elemento = pila.pop();
            this.valores.push(elemento);
        }
        return resultado;
    }
}

/**
 * Titulo: Clase CalculadoraRPN
 * Descripción: Clase que realiza la funcionalidad de una calculadora RPN
 * 
 * @author Omar Teixeira González, UO281847
 * @version 18/11/2022
 */
class CalculadoraRPN {
    /**
     * Constructor de la calculadora cientifica
     */
    constructor() {        
        this.pila = new Stack();
        this.estadoShift = false;
        this.pantalla = "0";        
        this.pantallaPila = "";
        this.unidad="DEG";
        this.recargarPantalla();
    }

    /**
     * Método digitos
     * @param digito 
     */
    digitos(digito) {
        if (this.pantalla == "0") {
            this.pantalla = "";
        }
        this.pantalla += digito;
        this.recargarPantalla();
    }

    /**
     * Método punto
     */
    punto() {
        if (!this.pantalla.includes(".")) {
            this.pantalla += ".";
        }            
        this.recargarPantalla();
    }

    /**
     * Método suma
     */
    suma() {
        this.operacionCompuesta("+");
    }

    /**
     * Método resta
     */
    resta() {
        this.operacionCompuesta("-");
    }

    /**
     * Método multiplicacion
     */
    multiplicacion() {
        this.operacionCompuesta("*");
    }

    /**
     * Método division
     */
    division() {
        this.operacionCompuesta("/");
    }

    /**
     * Método cambioDeSigno
     */
    cambioDeSigno() {
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

    /**
     * Método raizCuadrada
     */
    raizCuadrada() {
        if (this.pila.size() >= 1) {            
            this.pila.push(Math.sqrt(this.pila.pop()));
        }
        this.pantallaPila = this.pila.print();
        this.recargarPantalla();
    }

    /**
     * Método cuadrado
     */
    cuadrado() {
        if (this.pila.size() >= 1) {            
            this.pila.push(this.pila.pop()**2);
        }
        this.pantallaPila = this.pila.print();
        this.recargarPantalla();
    }

    /**
     * Método potencia
     */
    potencia() {
        this.operacionCompuesta("**");
    }

    /**
     * Método potenciaDeDiez
     */
    potenciaDeDiez() {
        if (this.pila.size() >= 1) {            
            this.pila.push(10**this.pila.pop());
        }
        this.pantallaPila = this.pila.print();
        this.recargarPantalla();
    }

    /**
     * Método factorial
     */
    factorial() {
        if (this.pila.size() >= 1) {
            var fact = 1;
            var valor = Number(this.pila.pop());        
            while (valor > 1) {
                fact*=valor--;
            }
            this.pila.push(fact);
        }
        this.pantallaPila = this.pila.print();
        this.recargarPantalla();
    }

    /**
     * Método log
     */
    log() {
        if (this.pila.size() >= 1) {            
            this.pila.push(Math.log10(this.pila.pop()));
        }
        this.pantallaPila = this.pila.print();
        this.recargarPantalla();
    }

    /**
     * Método ln
     */
    ln() {
        if (this.pila.size() >= 1) {            
            this.pila.push(Math.log(this.pila.pop()));
        }
        this.pantallaPila = this.pila.print();
        this.recargarPantalla();
    }

    /**
     * Método borrar
     */
    borrar() {
        this.pantalla = "0";
        this.recargarPantalla();
    }

    /**
     * Método borrarTodo
     */
    borrarTodo() {
        this.pila.clear();
        this.pantallaPila = "";
        this.borrar();
    }

    /**
     * Método borrarIzquierda
     */
    borrarIzquierda() {
        this.pantalla = this.pantalla.slice(0, -1);
        if (this.pantalla == "") {
            this.pantalla = "0";
        }
        this.recargarPantalla();
    }

    /**
     * Método sin
     */
    sin() {
        if (this.estadoShift) {
            this.operacionTrigonometrica("asin");
        } else {
            this.operacionTrigonometrica("sin");
        }
    }

    /**
     * Método cos
     */
    cos() {
        if (this.estadoShift) {
            this.operacionTrigonometrica("acos");
        } else {
            this.operacionTrigonometrica("cos");
        }
    }

    /**
     * Método tan
     */
    tan() {
        if (this.estadoShift) {
            this.operacionTrigonometrica("atan");
        } else {
            this.operacionTrigonometrica("tan");
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
     * Método shift
     */
    shift() {
        this.estadoShift = !this.estadoShift;
        if (this.estadoShift) {
            document.querySelector("input[type=\"button\"][value=\"sin\"]").value="asin";
            document.querySelector("input[type=\"button\"][value=\"cos\"]").value="acos";
            document.querySelector("input[type=\"button\"][value=\"tan\"]").value="atan";
        } else {
            document.querySelector("input[type=\"button\"][value=\"asin\"]").value="sin";
            document.querySelector("input[type=\"button\"][value=\"acos\"]").value="cos";
            document.querySelector("input[type=\"button\"][value=\"atan\"]").value="tan";
        }
    }

    /**
     * Método enter
     */
    enter() {
        this.pila.push(Number(this.pantalla))
        this.pantallaPila = this.pila.print();
        this.pantalla = "0";
        this.recargarPantalla();
    }

    /**
     * Método operacionCompuesta
     * @param operador 
     */
    operacionCompuesta(operador) {
        if (this.pila.size() >= 2) {
            var operacion;
            switch (operador) {
                case "+":
                    operacion = this.pila.pop() + this.pila.pop();
                    break;
                case "-":
                    var sustraendo = this.pila.pop();
                    var minuendo = this.pila.pop();
                    operacion = minuendo - sustraendo;
                    break;
                case "*":
                    operacion = this.pila.pop() * this.pila.pop();
                    break;
                case "/":
                    var dividendo = this.pila.pop();
                    var divisor = this.pila.pop();
                    operacion = divisor / dividendo;
                    break;
                case "**":
                    var exponente = this.pila.pop();
                    var base = this.pila.pop();
                    operacion = base**exponente;
                    break;
            }
            this.pila.push(operacion);
        }
        this.pantallaPila = this.pila.print();
        this.recargarPantalla();
    }

    /**
     * Método operacionTrigonometrica
     * @param funcion 
     */
    operacionTrigonometrica(funcion) {
        if (this.pila.size() >= 1) {
            var operacion;
            switch(funcion) {
                case "sin":
                    operacion = Math.sin(this.convertir());
                    break;
                case "cos":
                    operacion = Math.cos(this.convertir());
                    break;
                case "tan":
                    operacion = Math.tan(this.convertir());
                    break;
                case "asin":
                    operacion = Math.asin(this.convertir());
                    break;
                case "acos":
                    operacion = Math.acos(this.convertir());
                    break;
                case "atan":
                    operacion = Math.atan(this.convertir());
                    break;
            }
            this.pila.push(operacion);
        }
        this.pantallaPila = this.pila.print();
        this.recargarPantalla();
    }

    /**
     * Método convertir
     * @returns valor
     */
    convertir() {
        var valor = Number(this.pila.pop());
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

    recargarPantalla() {
        document.getElementsByTagName("input")[0].value = this.pantalla;
        document.getElementsByTagName("textarea")[0].value = this.pantallaPila;
    }
}

/**
 * Atributo calculadora
 */
var calculadora = new CalculadoraRPN();

/**
 * Gestión de eventos keydown
 */
document.addEventListener('keydown', function(e) {
    switch (e.key) {
        case "e":
            calculadora.deg();
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
        case "q":
            calculadora.cuadrado();
            break;
        case "l":
            calculadora.log();
            break;
        case "u":
            calculadora.shift();
            break;
        case "r":
            calculadora.raizCuadrada();
            break;
        case "d":
            calculadora.potenciaDeDiez();
            break;
        case "n":
            calculadora.factorial();
            break;
        case "p":
            calculadora.potencia();
            break;
        case "o":
            calculadora.ln();
            break;
        case "y":
            calculadora.cambioDeSigno();
            break;
        case "v":
            calculadora.borrarTodo();
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
        case "Enter":
            calculadora.enter();
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