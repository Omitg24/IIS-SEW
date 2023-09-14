<!DOCTYPE HTML>

<html lang="es">
<head>    
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	<meta name ="author" content ="Omar Teixeira González, UO281847" />
	<meta name ="description" content ="Documento PHP realizado para la Tarea 1 del Ejercicio 3 de Computación en el servidor" />
	<meta name ="keywords" content ="html, css, php, calculadora RPN" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    <title>Calculadora RPN</title>   
    <link rel="stylesheet" type="text/css" href="CalculadoraRPN.css" />
</head>

<body>
    <!-- Datos con el contenidos que aparece en el navegador -->
    <header>
        <h1>Calculadora RPN</h1>
    </header>  
    <main>        
        <?php

            session_start();

            class Stack {
                protected $ultimo;
                protected $valores;

                public function __construct() {
                    $this->ultimo = -1;
                    $this->valores = array();
                }
            
                public function push($valor) {
                    array_push($this->valores, $valor);
                    $this->ultimo += 1;
                }
            
                public function pop() {
                    if (!$this->isEmpty()) {
                        $this->ultimo -= 1;
                        return array_pop($this->valores);
                    }
                }
            
                public function peek() {
                    if (!$this->isEmpty()) {
                        return $this->valores[$this->ultimo];
                    }
                }
            
                public function isEmpty() {
                    return $this->ultimo == -1;
                }
            
                public function size() {
                    return $this->ultimo + 1;
                }
            
                public function get($index) {
                    if ($index <= $this->size() - 1) {
                        return $this->valores[$index];
                    }
                }
            
                public function getStack() {
                    return $this;
                }
            
                public function clear() {
                    for ($i = 0; $i < $this->size(); $i++) {
                        array_pop($this->valores);
                    } 
                    $this->ultimo =  - 1;
                }
            
                public function print() {
                    $elemento = "";
                    $pila = array();
                    $resultado = "";
                    for ($i = 0; $i < $this->size(); $i++) {
                        $elemento = array_pop($this->valores);
                        $resultado .= $elemento . " :[" . floatval($i+1) . "]\n";
                        array_push($pila,$elemento);
                    }
                    for ($i = 0; $i < $this->size(); $i++) {
                        $elemento = array_pop($pila);
                        array_push($this->valores, $elemento);
                    }
                    return $resultado;
                }
            }
            
            class CalculadoraRPN {
                protected $pila;
                protected $estadoShift;
                protected $pantalla;
                protected $pantallaPila;                    
                protected $valorDeg;
                protected $valorSin;
                protected $valorCos;
                protected $valorTan;                    

                public function __construct() {        
                    $this->pila = new Stack();
                    $this->estadoShift = false;
                    $this->pantalla = "0";        
                    $this->pantallaPila = "";
                    $this->valorDeg="DEG";
                    $this->valorSin="sin";
                    $this->valorCos="cos";
                    $this->valorTan="tan";
                }
            
                public function digitos($digito) {
                    if ($this->pantalla == "0") {
                        $this->pantalla = "";
                    }
                    $this->pantalla .= $digito;
                }
            
                public function punto() {
                    if (!str_contains($this->pantalla, ".")) {
                        $this->pantalla .= ".";
                    }
                }
            
                public function suma() {
                    $this->operacionCompuesta("+");
                }
            
                public function resta() {
                    $this->operacionCompuesta("-");
                }
            
                public function multiplicacion() {
                    $this->operacionCompuesta("*");
                }
            
                public function division() {
                    $this->operacionCompuesta("/");
                }
            
                public function cambioDeSigno() {
                    $valorObtenido = floatval($this->pantalla);
                    if (is_nan($valorObtenido) === false) {                
                        if ($valorObtenido >= 0) {
                            $valorCalculado = -$valorObtenido;
                        } else if ($valorObtenido < 0) {
                            $valorCalculado = abs($valorObtenido);
                        }    
                    }            
                    $this->pantalla = strval($valorCalculado);
                }
            
                public function raizCuadrada() {
                    if ($this->pila->size() >= 1) {            
                        $this->pila->push(sqrt($this->pila->pop()));
                    }
                    $this->pantallaPila = $this->pila->print();
                }
            
                public function cuadrado() {
                    if ($this->pila->size() >= 1) {            
                        $this->pila->push($this->pila->pop()**2);
                    }
                    $this->pantallaPila = $this->pila->print();
                }
            
                public function potencia() {
                    $this->operacionCompuesta("**");
                }
            
                public function potenciaDeDiez() {
                    if ($this->pila->size() >= 1) {            
                        $this->pila->push(10**$this->pila->pop());
                    }
                    $this->pantallaPila = $this->pila->print();
                }
            
                public function factorial() {
                    if ($this->pila->size() >= 1) {
                        $fact = 1;
                        $valor = floatval($this->pila->pop());        
                        while ($valor > 1) {
                            $fact*=$valor--;
                        }
                        $this->pila->push($fact);
                    }
                    $this->pantallaPila = $this->pila->print();
                }
            
                public function log() {
                    if ($this->pila->size() >= 1) {            
                        $this->pila->push(log10($this->pila->pop()));
                    }
                    $this->pantallaPila = $this->pila->print();
                }
            
                public function ln() {
                    if ($this->pila->size() >= 1) {            
                        $this->pila->push(log($this->pila->pop()));
                    }
                    $this->pantallaPila = $this->pila->print();
                }
            
                public function borrar() {
                    $this->pantalla = "0";
                }
            
                public function borrarTodo() {
                    $this->pila->clear();
                    $this->pantallaPila = "";
                    $this->borrar();
                }
            
                public function borrarIzquierda() {
                    $this->pantalla = substr($this->pantalla, 0, -1);
                    if ($this->pantalla == "") {
                        $this->pantalla = "0";
                    }
                }
            
                public function sin() {
                    if ($this->estadoShift) {
                        $this->operacionTrigonometrica("asin");
                    } else {
                        $this->operacionTrigonometrica("sin");
                    }
                }
            
                public function cos() {
                    if ($this->estadoShift) {
                        $this->operacionTrigonometrica("acos");
                    } else {
                        $this->operacionTrigonometrica("cos");
                    }
                }
            
                public function tan() {
                    if ($this->estadoShift) {
                        $this->operacionTrigonometrica("atan");
                    } else {
                        $this->operacionTrigonometrica("tan");
                    }
                }
            
                public function deg() {
                    switch($this->valorDeg) {
                        case "DEG":                                
                            $this->valorDeg ="RAD";
                            break;
                        case "RAD":                                
                            $this->valorDeg = "GRAD";
                            break;
                        case "GRAD":                                
                            $this->valorDeg = "DEG";
                            break;
                    }
                }
            
                public function shift() {
                    $this->estadoShift = !$this->estadoShift;
                    if ($this->estadoShift) {
                        $this->valorSin="asin";                            
                        $this->valorCos="acos";
                        $this->valorTan="atan";
                    } else {
                        $this->valorSin="sin";
                        $this->valorCos="cos";
                        $this->valorTan="tan";
                    }
                }
            
                public function enter() {
                    $this->pila->push(floatval($this->pantalla));
                    $this->pantallaPila = $this->pila->print();
                    $this->pantalla = "0";
                }
            
                public function operacionCompuesta($operador) {
                    if ($this->pila->size() >= 2) {
                        $operacion = "";
                        switch ($operador) {
                            case "+":
                                $operacion = $this->pila->pop() + $this->pila->pop();
                                break;
                            case "-":
                                $sustraendo = $this->pila->pop();
                                $minuendo = $this->pila->pop();
                                $operacion = $minuendo - $sustraendo;
                                break;
                            case "*":
                                $operacion = $this->pila->pop() * $this->pila->pop();
                                break;
                            case "/":
                                $dividendo = $this->pila->pop();
                                $divisor = $this->pila->pop();
                                $operacion = $divisor / $dividendo;
                                break;
                            case "**":
                                $exponente = $this->pila->pop();
                                $base = $this->pila->pop();
                                $operacion = $base**$exponente;
                                break;
                        }
                        $this->pila->push($operacion);
                    }
                    $this->pantallaPila = $this->pila->print();
                }
            
                public function operacionTrigonometrica($funcion) {
                    if ($this->pila->size() >= 1) {
                        $operacion = "";
                        switch($funcion) {
                            case "sin":
                                $operacion = sin($this->convertir());
                                break;
                            case "cos":
                                $operacion = cos($this->convertir());
                                break;
                            case "tan":
                                $operacion = tan($this->convertir());
                                break;
                            case "asin":
                                $operacion = asin($this->convertir());
                                break;
                            case "acos":
                                $operacion = acos($this->convertir());
                                break;
                            case "atan":
                                $operacion = atan($this->convertir());
                                break;
                        }
                        $this->pila->push($operacion);
                    }
                    $this->pantallaPila = $this->pila->print();
                }
            
                public function convertir() {
                    $valor = floatval($this->pila->pop());
                    switch ($this->valorDeg) {
                        case "DEG":                
                            $valor *= (pi()/180);
                            break;
                        case "RAD":
                            break;
                        case "GRAD":
                            $valor *= (pi()/200);
                            break;
                    }
                    return $valor;
                }

                public function getPantalla() {
                    return $this->pantalla;
                }

                public function getPantallaPila() {
                    return $this->pantallaPila;
                }

                public function getValorDeg() {
                    return $this->valorDeg;
                }

                public function getValorSin() {
                    return $this->valorSin;
                }

                public function getValorCos() {
                    return $this->valorCos;
                }

                public function getValorTan() {
                    return $this->valorTan;
                }
            }

            if (!isset($_SESSION["calculadoraRPN"])) {
                $_SESSION["calculadoraRPN"] = new CalculadoraRPN();
            }

            if (count($_POST) > 0) {
                $calculadora = $_SESSION["calculadoraRPN"];

                if(isset($_POST["DEG"])) $calculadora->deg();                
                if(isset($_POST["sin"])) $calculadora->sin();
                if(isset($_POST["cos"])) $calculadora->cos();
                if(isset($_POST["tan"])) $calculadora->tan();
                if(isset($_POST["x^2"])) $calculadora->cuadrado();
                if(isset($_POST["log"])) $calculadora->log();

                if(isset($_POST["shift"])) $calculadora->shift();
                if(isset($_POST["√"])) $calculadora->raizCuadrada();
                if(isset($_POST["10^x"])) $calculadora->potenciaDeDiez();
                if(isset($_POST["n!"])) $calculadora->factorial();
                if(isset($_POST["x^y"])) $calculadora->potencia();
                if(isset($_POST["ln"])) $calculadora->ln();

                if(isset($_POST["±"])) $calculadora->cambioDeSigno();
                if(isset($_POST["CE"])) $calculadora->borrar();
                if(isset($_POST["C"])) $calculadora->borrarTodo();
                if(isset($_POST["⌫"])) $calculadora->borrarIzquierda();
                if(isset($_POST["Enter"])) $calculadora->enter();
                
                if(isset($_POST["7"])) $calculadora->digitos(7);
                if(isset($_POST["8"])) $calculadora->digitos(8);
                if(isset($_POST["9"])) $calculadora->digitos(9);
                if(isset($_POST["÷"])) $calculadora->division();

                if(isset($_POST["4"])) $calculadora->digitos(4);
                if(isset($_POST["5"])) $calculadora->digitos(5);
                if(isset($_POST["6"])) $calculadora->digitos(6);
                if(isset($_POST["x"])) $calculadora->multiplicacion();
                
                if(isset($_POST["1"])) $calculadora->digitos(1);
                if(isset($_POST["2"])) $calculadora->digitos(2);
                if(isset($_POST["3"])) $calculadora->digitos(3);
                if(isset($_POST["+"])) $calculadora->suma();

                if(isset($_POST["0"])) $calculadora->digitos(0);
                if(isset($_POST["punto"])) $calculadora->punto();
                if(isset($_POST["-"])) $calculadora->resta();

                $_SESSION["calculadoraRPN"] = $calculadora;
            }
        ?>
        <form action="#" method="POST">
            <section>
                <h2>HP</h2>
                <label for='pila'>Prime Graphing Calculator</label>
                <textarea id='pila' disabled><?php echo $_SESSION['calculadoraRPN']->getPantallaPila(); ?></textarea>
                <label for='valores'>Valor introducido</label>  
                <input type='text' value='<?php echo $_SESSION["calculadoraRPN"]->getPantalla(); ?>' id='valores' disabled />
                
                <input type='submit' value='<?php echo $_SESSION["calculadoraRPN"]->getValorDeg(); ?>' name='DEG' />
                <input type='submit' value='<?php echo $_SESSION["calculadoraRPN"]->getValorSin(); ?>' name='sin' />
                <input type='submit' value='<?php echo $_SESSION["calculadoraRPN"]->getValorCos(); ?>' name='cos' />
                <input type='submit' value='<?php echo $_SESSION["calculadoraRPN"]->getValorTan(); ?>' name='tan' />
                <input type='submit' value='x^2' name='x^2' />            
                <input type='submit' value='log' name='log' />         
                            
                <input type='submit' value='↑' name='shift' />          
                <input type='submit' value='√' name='√' />         
                <input type='submit' value='10^x' name='10^x' />           
                <input type='submit' value='n!' name='n!' />
                <input type='submit' value='x^y' name='x^y' />
                <input type='submit' value='ln' name='ln' />

                <input type='submit' value='±' name='±' />
                <input type='submit' value='CE' name='CE' />
                <input type='submit' value='C' name='C' />		
                <input type='submit' value='⌫' name='⌫' />
                <input type='submit' value='Enter' name='Enter' />
            </section>
            
            <input type='submit' value='7' name='7' />
            <input type='submit' value='8' name='8' />
            <input type='submit' value='9' name='9' />       
            <input type='submit' value='÷' name='÷' />

            <input type='submit' value='4' name='4' />
            <input type='submit' value='5' name='5' />
            <input type='submit' value='6' name='6' />
            <input type='submit' value='x' name='x' />
            
            <input type='submit' value='1' name='1' />
            <input type='submit' value='2' name='2' />
            <input type='submit' value='3' name='3' />
            <input type='submit' value='+' name='+' />

            <input type='submit' value='0' name='0' />
            <input type='submit' value='.' name='punto' />
            <input type='submit' value='-' name='-' />        
        </form>
    </main>
</body>
</html>