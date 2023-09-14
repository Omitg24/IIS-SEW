<!DOCTYPE HTML>

<html lang="es">
<head>    
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	<meta name ="author" content ="Omar Teixeira González, UO281847" />
	<meta name ="description" content ="Documento PHP realizado para la Tarea 1 del Ejercicio 2 de Computación en el servidor" />
	<meta name ="keywords" content ="html, css, php, calculadora científica" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    <title>Calculadora Científica</title>   
    <link rel="stylesheet" type="text/css" href="CalculadoraCientifica.css" />
</head>

<body>
    <!-- Datos con el contenidos que aparece en el navegador -->
    <header>
        <h1>Calculadora Científica</h1>
    </header>        
    <main>
        <?php
            session_start();

            class CalculadoraMilan {
              
                protected $estadoEncendido;
                protected $estadoOperacion;
                protected $pantalla;
                protected $memoria;
                protected $operando1;        
                protected $operando2;
                protected $operadorActual;
                protected $operadorAnterior;

                public function __construct() {                    
                    $this->estadoEncendido = false;
                    $this->estadoOperacion = false;
                    $this->pantalla = "OFF";
                    $this->memoria = "";
                    $this->operando1 = "";
                    $this->operando2 = "";
                    $this->operadorActual = "";
                    $this->operadorAnterior = "";
                }                
                
                public function digitos($digito) {
                    if ($this->estadoEncendido) {
                        if ($this->estadoOperacion 
                            || str_contains($this->pantalla, "M")) {
                            $this->pantalla = "0";    
                            $this->estadoOperacion = false;
                        }
                        if (strlen($this->pantalla) < 8) {     
                            if ($this->pantalla != "0") {
                                $this->pantalla .= $digito;
                            } else {
                                $this->pantalla = strval($digito);
                            }
                            $this->operando2 = $this->pantalla;
                            if (($this->pantalla === $this->operando1 
                                || ($this->pantalla === "M " . $this->operando1)) 
                                && $this->estadoOperacion) {
                                $this->pantalla = "0";
                                $this->operando1 = "";
                                $this->operando2 = "";
                                $this->operadorActual = "";
                            }              
                        }            
                    }        
                }
                
                public function punto() {
                    if ($this->estadoEncendido) {
                        if (!str_contains($this->pantalla, ".")) {
                            $this->pantalla .= ".";
                        }
                    }
                }

                public function suma() {
                    $this->calcularOperador("+");
                }

                public function resta() {
                    $this->calcularOperador("-");
                }

                public function multiplicacion() {
                    $this->calcularOperador("*");
                }

                public function division() {
                    $this->calcularOperador("/");
                }

                public function mrc() {
                    if ($this->estadoEncendido) {
                        if ($this->memoria != "") {
                            if (str_contains($this->pantalla, "M")) {
                                $this->pantalla = substr($this->pantalla, 2, strlen($this->pantalla));
                            } else {
                                $this->pantalla = "M " . $this->memoria;    
                            }
                            $this->operando2 = substr($this->pantalla, 2, strlen($this->pantalla));
                        }            
                    }       
                }

                public function mMenos() {
                    if ($this->estadoEncendido) {
                        if (str_contains($this->pantalla, "M") || str_contains($this->pantalla, "E")){
                            $this->pantalla = substr($this->pantalla, 2, strlen($this->pantalla));
                        }
                        $this->memoria = floatval($this->memoria) - floatval($this->pantalla);
                    }
                }

                public function mMas() {
                    if ($this->estadoEncendido) {
                        if (str_contains($this->pantalla, "M") || str_contains($this->pantalla, "E")){
                            $this->pantalla = substr($this->pantalla, 2, strlen($this->pantalla));
                        }
                        $this->memoria = floatval($this->memoria) + floatval($this->pantalla);
                    }
                }

                public function borrar() {
                    if ($this->estadoEncendido) {            
                        $this->pantalla = "0";
                    }       
                }

                public function igual() {
                    if ($this->estadoEncendido) {
                        if (str_contains($this->operando1, "E")){
                            $this->operando1 = floatval(substr($this->pantalla, 2, strlen($this->pantalla)));
                        }
                        if (str_contains($this->operando2, "E")){
                            $this->operando2 = floatval(substr($this->pantalla, 2, strlen($this->pantalla)));
                        }
                        if ($this->operando1 == "" && $this->operadorActual == "") {
                            $this->calcular(floatval($this->operando2));
                        }
                        if ($this->operadorActual != "=") {
                            $this->operadorAnterior = $this->operadorActual;
                            $valorCalculado = floatval($this->operando1) . $this->operadorActual . floatval($this->operando2);
                            $this->calcular($valorCalculado);
                            $this->operadorActual = "=";
                            $this->estadoOperacion = true;
                        } else {
                            $valorCalculado = floatval($this->operando1) . $this->operadorAnterior . floatval($this->operando2);
                            $this->calcular($valorCalculado);
                            $this->estadoOperacion = true;
                        }
                    }
                }

                public function porcentaje() {
                    if ($this->estadoEncendido) {
                        if ($this->pantalla != "") {
                            if (str_contains($this->pantalla, "M")) {
                                $this->pantalla =  substr($this->pantalla, 2, strlen($this->pantalla));
                            }
                            if ($this->operadorActual == "." || $this->operadorActual == "-") {
                                $this->operando2 = (floatval($this->operando1) * floatval($this->operando2))/100;            
                                $valorCalculado = floatval($this->operando1) . $this->operadorActual . floatval($this->operando2);
                                $this->estadoOperacion = true;
                            } else {
                                $this->operando2 = 0;
                                $valorCalculado = floatval($this->operando2);
                                if ($this->operando1 != "" && $this->operadorActual != "" && $this->operadorActual != "=") {
                                    $this->operando2 = (floatval($this->pantalla)/100);
                                    $valorCalculado = floatval($this->operando1) . $this->operadorActual . floatval($this->operando2);
                                }
                                $this->estadoOperacion = true;
                            }
                            $this->calcular($valorCalculado);    
                        }
                    }
                }

                public function raizCuadrada() {
                    if ($this->estadoEncendido) {
                        if ($this->pantalla != "" && $this->operando2 != "") {
                            if (str_contains($this->pantalla, "M") || str_contains($this->pantalla, "E")) {
                                $this->pantalla =  substr($this->pantalla, 2, strlen($this->pantalla));
                            }
                            $this->operando2 = $this->pantalla;
                            $valorObtenido = floatval($this->operando2);            
                            $valorCalculado = sqrt($valorObtenido);
                            $this->estadoOperacion = true;
                            $this->calcular($valorCalculado);
                        }
                    }
                }

                public function cambioDeSigno() {
                    if ($this->estadoEncendido){
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
                }

                public function onC() {
                    $this->estadoEncendido = !$this->estadoEncendido;
                    $this->pantalla = "0";
                    if (!$this->estadoEncendido) {
                        $this->pantalla = "OFF";
                        $this->memoria = "";
                        $this->operando1 = "";
                        $this->operando2 = "";
                        $this->operadorActual = "";
                        $this->operadorAnterior = "";
                    }
                }

                protected function calcularOperador($operador) {
                    $this->calcularOperacion();
                    if ($this->estadoEncendido) {
                        $this->operando1 = $this->pantalla;
                        if (str_contains($this->operando1, "M")) {
                            $this->operando1 = substr($this->pantalla, 2, strlen($this->operando1));
                        }
                        $this->pantalla = "0";
                        $this->operadorActual = $operador;
                        $this->estadoOperacion = true;
                    }
                }

                protected function calcular($valor) {
                    try {
                        $this->pantalla = strval(eval("return $valor;"));
                        if (strlen($this->pantalla) > 8) {
                            $this->pantalla = "E ". substr($this->pantalla, 0, 8);
                        }
                        $this->operando1 = $this->pantalla;
                    }catch(err) {
                        $this->pantalla = "ERROR";
                    }
                }   

                protected function calcularOperacion() {
                    if ($this->operando1 != "" && $this->operadorActual != "" && $this->operadorActual != "=" && $this->operando2 != "") {
                        $valorCalculado = floatval($this->operando1) . $this->operadorActual . floatval($this->operando2);
                        $this->calcular($valorCalculado);
                    }
                }

                public function getPantalla() {
                    return $this->pantalla;
                }
            }

            class CalculadoraCientifica extends CalculadoraMilan {
                protected $estadoTrigonometria;
                protected $estadoHiperbolico;
                protected $estadoShift;
                protected $pantallaMemoria;
                protected $valorDeg;
                protected $valorSin;
                protected $valorCos;
                protected $valorTan;
                protected $mcHabilitado;
                protected $mrHabilitado;

                public function __construct() {                    
                    parent::__construct();                    
                    $this->estadoEncendido = true;
                    $this->estadoTrigonometria = false;
                    $this->estadoHiperbolico = false;
                    $this->estadoShift = false;
                    $this->pantalla = "0";
                    $this->pantallaMemoria = "";     
                    $this->valorDeg="DEG";
                    $this->valorSin="sin";
                    $this->valorCos="cos";
                    $this->valorTan="tan";

                    $this->deshabilitar("disabled");
                }
            
                public function digitos($digito) {
                    if ($this->estadoOperacion) {            
                        $this->pantalla = "0";            
                        $this->estadoOperacion = false;
                    } 
                    if ($this->estadoTrigonometria) {
                        $this->pantalla="0";
                        $this->pantallaMemoria = "";
                        $this->estadoTrigonometria = false;
                    } 
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                        $this->pantalla = "";
                        $this->operando1 = "";
                        $this->operando2 = "";
                    } 
                    if (str_contains($this->pantalla, ",e+")) {
                        if (substr($this->pantalla, strlen($this->pantalla)-1, 1) == "0") {
                            $this->pantalla = substr($this->pantalla, 0, -1);
                        }
                        $this->pantalla.= $digito;
                    } else if ($this->pantalla != "0") {
                        $this->pantalla .= $digito;
                    } else {
                        $this->pantalla = strval($digito);
                    }
                    $this->operando2 = $this->pantalla;
                    if ($this->pantalla === $this->operando1) {
                        $this->pantalla = "0";
                        $this->operando1 = "";
                        $this->operando2 = "";
                        $this->operadorActual = "";
                    }
                }   
            
                public function igual() {
                    if ($this->pantalla == "0" ) {
                        $this->pantallaMemoria = "0";
                    }
                    if ($this->operadorActual == "**2" 
                        || $this->operadorActual == "10**"
                        || $this->operadorActual == "fact"
                        || $this->operadorActual == "log") {
                        $this->pantallaMemoria = $this->pantalla;
                        $this->operadorActual = "="; 
                    } else if ($this->operadorActual == "Mod") {
                        $this->pantallaMemoria.= $this->operando2;            
                        $this->calcular(str_replace("Mod", "%", $this->pantallaMemoria));
                        $this->operadorAnterior = $this->operadorActual;
                        $this->operadorActual = "=";  
                    } else if ($this->operadorActual == "Exp") {
                        $posicion = strpos($this->pantalla, ",");
                        $this->operando2 = str_replace(",e+","", substr($this->pantalla, $posicion, strlen($this->pantalla)));
                        $this->calcular($this->operando1 + "*10**" + $this->operando2);
                        $this->pantallaMemoria=$this->pantalla;
                        $this->operadorActual="=";
                    } else if ($this->estadoTrigonometria) {     
                        $this->operando2 = floatval($this->pantalla);
                        $this->calcular(floatval($this->operando1) + floatval($this->pantalla));
                        $this->estadoTrigonometria = false;
                        $this->operadorAnterior = $this->operadorActual;
                        $this->operadorActual = "=";        
                    } else if ($this->operadorActual != "=") {
                        if ($this->pantalla=="" && $this->estadoOperacion) {                                
                            $this->pantallaMemoria .= $this->operando1;
                            $this->estadoOperacion = false;
                        } else {
                            $this->operadorAnterior = $this->operadorActual;
                            $this->pantallaMemoria .= $this->operando2;                          
                        }   
                        $this->calcular(str_replace("^", "**", $this->pantallaMemoria));
                        $this->operadorActual = "=";         
                    } else {
                        if ($this->pantallaMemoria == "" 
                            || substr($this->pantallaMemoria, 0, strlen($this->pantallaMemoria)-1) == $this->pantalla) {                
                            $this->pantallaMemoria = $this->pantalla;
                        } else {                
                            $this->pantallaMemoria = $this->operando1 . str_replace(str_replace($this->operadorAnterior,"**", "^"), "%", "Mod") . $this->operando2;
                        }
                        $this->calcular(str_replace("Mod", "%", str_replace("^", "**", $this->pantallaMemoria)));
                    }
                    if ($this->pantallaMemoria[strlen($this->pantallaMemoria)-1]!="=") {
                        $this->pantallaMemoria .= "=";
                    }
                    $this->estadoOperacion = true;
                }
            
                public function cuadrado() {
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                    }
                    if ($this->pantallaMemoria == "") {
                        $this->pantallaMemoria = "sqr(" . $this->pantalla . ")";            
                    } else {
                        $this->pantallaMemoria = "sqr(" . $this->pantalla . ")";              
                    }
                    $this->estadoOperacion = true;
                    $this->operadorActual = "**2";
                    $this->calcular($this->pantalla."**2");
                }
            
                public function potencia() {
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                    }
                    $this->pantallaMemoria=$this->pantalla . "^";
                    $this->estadoOperacion = true;
                    $this->operadorActual = "**";
                }
            
                public function raizCuadrada() {
                    $this->pantallaMemoria = "√(" . $this->pantalla . ")";        
                    parent::raizCuadrada();
                    $this->pantallaMemoria = "";
                }
            
                public function potenciaDeDiez() {
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                    }
                    if ($this->pantallaMemoria =="") {
                        $this->pantallaMemoria = "10^(" . $this->pantalla . ")";            
                    } else {
                        $this->pantallaMemoria = "10^(" . $this->pantalla . ")";                        
                    }       
                    $this->estadoOperacion = true;
                    $this->operadorActual = "10**";
                    $this->calcular(str_replace("^", "**", $this->pantallaMemoria));
                }
            
                public function log () {
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                    }
                    if ($this->pantallaMemoria=="") {
                        $this->pantallaMemoria = "log(" . $this->pantalla . ")";            
                    } else {
                        $this->pantallaMemoria = "log(" . $this->pantalla . ")";                        
                    }
                    $this->estadoOperacion = true;
                    $this->operadorActual = "log";
                    $this->calcular("log10(" . $this->pantalla . ")");
                }
            
                public function exp() {
                    $this->operando1 = $this->pantalla;
                    $this->pantalla .= ",e+0";
                    $this->operadorActual = "Exp";
                }
            
                public function mod() {
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                    }
                    $this->operando1 = $this->pantalla;
                    $this->pantallaMemoria .= $this->pantalla . "Mod";        
                    $this->estadoOperacion = true;
                    $this->operadorActual = "Mod";
                }
            
                public function sin() {
                    if ($this->estadoHiperbolico) {
                        if ($this->estadoShift) {
                            $this->calcularTrigonometrica("asinh");
                        } else {
                            $this->calcularTrigonometrica("sinh");
                        }            
                    } else {
                        if ($this->estadoShift) {
                            $this->calcularTrigonometrica("asin");
                        } else {
                            $this->calcularTrigonometrica("sin");
                        }
                    }     
                }
            
                public function cos() {
                    if ($this->estadoHiperbolico) {
                        if ($this->estadoShift) {
                            $this->calcularTrigonometrica("acosh");
                        } else {
                            $this->calcularTrigonometrica("cosh");
                        }            
                    } else {
                        if ($this->estadoShift) {
                            $this->calcularTrigonometrica("acos");
                        } else {
                            $this->calcularTrigonometrica("cos");
                        }
                    }
                }
            
                public function tan() {
                    if ($this->estadoHiperbolico) {
                        if ($this->estadoShift) {
                            $this->calcularTrigonometrica("atanh");
                        } else {
                            $this->calcularTrigonometrica("tanh");
                        }            
                    } else {
                        if ($this->estadoShift) {
                            $this->calcularTrigonometrica("atan");
                        } else {
                            $this->calcularTrigonometrica("tan");
                        }            
                    }  
                }
            
                public function shift() {
                    $this->estadoShift = !$this->estadoShift;        
                    if ($this->estadoShift) {            
                        if ($this->estadoHiperbolico) {
                            $this->valorSin="asinh";                            
                            $this->valorCos="acosh";                            
                            $this->valorTan="atanh";                            
                        } else {
                            $this->valorSin="asin";
                            $this->valorCos="acos";
                            $this->valorTan="atan";
                        }
                    } else {
                        if ($this->estadoHiperbolico) {
                            $this->valorSin="sinh";                            
                            $this->valorCos="cosh";                            
                            $this->valorTan="tanh";
                        } else {
                            $this->valorSin="sin";
                            $this->valorCos="cos";
                            $this->valorTan="tan";
                        }
                    }
                }
            
                public function pi() {
                    $this->pantalla = pi();
                }
            
                public function factorial() {
                    if (str_contains($this->pantallaMemoria, "fact")) {
                        $this->pantallaMemoria = "fact(" . $this->pantallaMemoria . ")";            
                    } else {
                        $this->pantallaMemoria = "fact(" . $this->pantalla . ")";                        
                    }
                    $fact = 1;
                    $valor = floatval($this->pantalla);        
                    while ($valor > 1) {
                        $fact*=$valor--;
                    }
                    $this->pantalla = strval($fact);
                    $this->estadoOperacion = true;
                    $this->operadorActual = "fact";
                }
            
                public function borrarTodo() {
                    $this->pantallaMemoria="";
                    $this->memoria="";
                    $this->operando1="";
                    $this->operando2="";
                    $this->operadorActual="";
                    $this->operadorAnterior="";
                    parent::borrar();
                }
            
                public function borrarIzquierda() {
                    if ($this->pantalla != "0" && !$this->estadoOperacion) {
                        $copia = $this->pantalla;
                        $this->pantalla = substr($copia, 0, -1);
                        if (strlen($this->pantalla)==0){
                            $this->pantalla = "0";
                        }
                    }
                }
            
                public function abreParentesis() {
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                    }        
                    $this->pantallaMemoria.="(";
                    $this->estadoOperacion = true;
                }
            
                public function cierraParentesis() {        
                    if (str_contains($this->pantallaMemoria, "(")) {            
                        $this->pantallaMemoria.=$this->pantalla . ")";
                        $this->pantalla = "";
                        $this->estadoOperacion = true;
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
            
                public function hyp() {
                    $this->estadoHiperbolico = !$this->estadoHiperbolico;
                    if ($this->estadoHiperbolico) {
                        if ($this->estadoShift) {
                            $this->valorSin="asinh";                            
                            $this->valorCos="acosh";                            
                            $this->valorTan="atanh";   
                        } else {
                            $this->valorSin="sinh";                            
                            $this->valorCos="cosh";                            
                            $this->valorTan="tanh";
                        }
                    } else {
                        if ($this->estadoShift){
                            $this->valorSin="asin";
                            $this->valorCos="acos";
                            $this->valorTan="atan";
                        } else {
                            $this->valorSin="sin";
                            $this->valorCos="cos";
                            $this->valorTan="tan";
                        }            
                    }
                }
            
                public function mc() {
                    $this->memoria = "";
                    $this->pantallaMemoria = "";
                    $this->deshabilitar("disabled");
                }
            
                public function mr() {
                    if (!$this->estadoOperacion) {
                        $this->pantallaMemoria = "";
                    }
                    $this->pantalla = $this->memoria;
                    $this->deshabilitar("");
                }
            
                public function mMas() {
                    $this->memoria= floatval($this->memoria) + floatval($this->pantalla);        
                    $this->deshabilitar("");
                }
            
                public function mMenos() {
                    $this->memoria= floatval($this->memoria) - floatval($this->pantalla);        
                    $this->deshabilitar("");
                }
            
                public function ms() {
                    $this->memoria = $this->pantalla;
                    $this->deshabilitar("");
                }
            
                protected function calcular($valor) {
                    try {
                        $this->pantalla = strval(eval("return $valor;"));
                        $this->operando1 = $this->pantalla;                       
                    }catch(err) {
                        $this->pantalla = "ERROR";            
                    }
                }
            
                protected function calcularOperador($operador) {
                    if ($this->estadoOperacion) {
                        $this->pantallaMemoria = "";
                    }
                    if ($this->operadorActual == "Exp") {
                        $posicion = strpos($this->pantalla, ",");
                        $this->operando2 = str_replace(",e+","", substr($this->pantalla, $posicion, strlen($this->pantalla)));
                        $this->calcular($this->operando1 . "*10**" . $this->operando2);
                    } else if ($this->estadoTrigonometria) {     
                        $this->calcular(floatval($this->operando1));
                        $this->estadoTrigonometria = false;
                        $this->pantallaMemoria = "";
                    }
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                    }
                    $this->operadorActual = $operador; 
                    $this->operando1 = $this->pantalla;
                    $this->pantallaMemoria.= $this->pantalla . $operador;
                    $this->estadoOperacion=true;
                }
            
                protected function calcularTrigonometrica($funcion) {
                    if (str_contains($this->pantallaMemoria, "=")) {
                        $this->pantallaMemoria="";
                    }
                    if ($this->pantallaMemoria="") {
                        $this->pantallaMemoria=$funcion . "(" . $this->pantallaMemoria . ")";            
                    } else {
                        $this->pantallaMemoria.=$funcion . "(" . $this->pantalla . ")";                        
                    }        
                    $this->calcular($funcion . "(" . $this->convertir() . ")");  
                    $this->estadoOperacion = true;
                    $this->estadoTrigonometria = true; 
                }
            
                protected function convertir() {
                    $valor = floatval($this->pantalla);
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
            
                protected function deshabilitar($opcion) {
                    $this->mcHabilitado = $opcion;
                    $this->mrHabilitado = $opcion;
                }

                public function getPantallaMemoria() {
                    return $this->pantallaMemoria;
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

                public function getMcHabilitado() {
                    return $this->mcHabilitado;
                }

                public function getMrHabilitado() {
                    return $this->mrHabilitado;
                }
            }

            if (!isset($_SESSION["calculadoraCientifica"])) {
                $_SESSION["calculadoraCientifica"] = new CalculadoraCientifica();
            }

            if (count($_POST) > 0) {
                $calculadora = $_SESSION["calculadoraCientifica"];

                if(isset($_POST["DEG"])) $calculadora->deg();
                if(isset($_POST["HYP"])) $calculadora->hyp();

                if(isset($_POST["MC"])) $calculadora->mc();
                if(isset($_POST["MR"])) $calculadora->mr();
                if(isset($_POST["M+"])) $calculadora->mMas();
                if(isset($_POST["M-"])) $calculadora->mMenos();
                if(isset($_POST["MS"])) $calculadora->ms();

                if(isset($_POST["x^2"])) $calculadora->cuadrado();
                if(isset($_POST["x^y"])) $calculadora->potencia();
                if(isset($_POST["sin"])) $calculadora->sin();
                if(isset($_POST["cos"])) $calculadora->cos();
                if(isset($_POST["tan"])) $calculadora->tan();

                if(isset($_POST["√"])) $calculadora->raizCuadrada();
                if(isset($_POST["10^x"])) $calculadora->potenciaDeDiez();
                if(isset($_POST["log"])) $calculadora->log();
                if(isset($_POST["Exp"])) $calculadora->exp();
                if(isset($_POST["Mod"])) $calculadora->mod();

                if(isset($_POST["shift"])) $calculadora->shift();
                if(isset($_POST["CE"])) $calculadora->borrar();
                if(isset($_POST["C"])) $calculadora->borrarTodo();
                if(isset($_POST["⌫"])) $calculadora->borrarIzquierda();
                if(isset($_POST["÷"])) $calculadora->division();

                if(isset($_POST["𝝿"])) $calculadora->pi();
                if(isset($_POST["7"])) $calculadora->digitos(7);
                if(isset($_POST["8"])) $calculadora->digitos(8);
                if(isset($_POST["9"])) $calculadora->digitos(9);
                if(isset($_POST["x"])) $calculadora->multiplicacion();

                if(isset($_POST["n!"])) $calculadora->factorial();
                if(isset($_POST["4"])) $calculadora->digitos(4);
                if(isset($_POST["5"])) $calculadora->digitos(5);
                if(isset($_POST["6"])) $calculadora->digitos(6);
                if(isset($_POST["-"])) $calculadora->resta();

                if(isset($_POST["±"])) $calculadora->cambioDeSigno();
                if(isset($_POST["1"])) $calculadora->digitos(1);
                if(isset($_POST["2"])) $calculadora->digitos(2);
                if(isset($_POST["3"])) $calculadora->digitos(3);
                if(isset($_POST["+"])) $calculadora->suma();
                
                if(isset($_POST["("])) $calculadora->abreParentesis();
                if(isset($_POST[")"])) $calculadora->cierraParentesis();
                if(isset($_POST["0"])) $calculadora->digitos(0);
                if(isset($_POST["punto"])) $calculadora->punto();
                if(isset($_POST["="])) $calculadora->igual();

                $_SESSION["calculadoraCientifica"] = $calculadora;
            }
        ?>

        <form action='#' method='POST'>
            <label for='historial'>Historial</label>
            <input type='text' value='<?php echo $_SESSION["calculadoraCientifica"]->getPantallaMemoria(); ?>' id='historial' disabled />
            <label for='valor'>Valor introducido</label>        
            <input type='text' value='<?php echo $_SESSION["calculadoraCientifica"]->getPantalla(); ?>' id='valor' disabled />

            <section>
                <h2>Formato</h2>
                <input type='submit' value='<?php echo $_SESSION["calculadoraCientifica"]->getValorDeg(); ?>' name='DEG' />
                <input type='submit' value='HYP' name='HYP' />
                <input type='submit' value='F-E' name='FE' disabled />
            </section>

            <section>
                <h2>Memoria</h2>
                <input type='submit' value='MC' name='MC' <?php echo $_SESSION["calculadoraCientifica"]->getMcHabilitado(); ?> />
                <input type='submit' value='MR' name='MR' <?php echo $_SESSION["calculadoraCientifica"]->getMrHabilitado(); ?> />
                <input type='submit' value='M+' name='M+' />
                <input type='submit' value='M-' name='M-' />
                <input type='submit' value='MS' name='MS' />
            </section>
            
            <input type='submit' value='x^2' name='x^2'>
            <input type='submit' value='x^y' name='x^y'>
            <input type='submit' value='<?php echo $_SESSION["calculadoraCientifica"]->getValorSin(); ?>' name='sin' />
            <input type='submit' value='<?php echo $_SESSION["calculadoraCientifica"]->getValorCos(); ?>' name='cos' />
            <input type='submit' value='<?php echo $_SESSION["calculadoraCientifica"]->getValorTan(); ?>' name='tan' />

            <input type='submit' value='√' name='√' />
            <input type='submit' value='10^x' name='10^x' />
            <input type='submit' value='log' name='log' />
            <input type='submit' value='Exp' name='Exp' />
            <input type='submit' value='Mod' name='Mod' />

            <input type='submit' value='↑' name='shift' />
            <input type='submit' value='CE' name='CE' />
            <input type='submit' value='C' name='C' />		
            <input type='submit' value='⌫' name='⌫' />
            <input type='submit' value='÷' name='÷' />

            <input type='submit' value='𝝿' name='𝝿' />
            <input type='submit' value='7' name='7' />
            <input type='submit' value='8' name='8' />
            <input type='submit' value='9' name='9' />
            <input type='submit' value='x' name='x' />

            <input type='submit' value='n!' name='n!' />
            <input type='submit' value='4' name='4' />
            <input type='submit' value='5' name='5' />
            <input type='submit' value='6' name='6' />
            <input type='submit' value='-' name='-' />

            <input type='submit' value='±' name='±' />
            <input type='submit' value='1' name='1' />
            <input type='submit' value='2' name='2' />
            <input type='submit' value='3' name='3' />
            <input type='submit' value='+' name='+' />

            <input type='submit' value='(' name='(' />
            <input type='submit' value=')' name=')' />
            <input type='submit' value='0' name='0' />
            <input type='submit' value='.' name='punto' />
            <input type='submit' value='=' name='=' />
        </form> 
    </main>
</body>
</html>