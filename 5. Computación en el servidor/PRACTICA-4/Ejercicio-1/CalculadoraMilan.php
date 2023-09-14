<!DOCTYPE HTML>

<html lang="es">
<head>    
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	<meta name ="author" content ="Omar Teixeira González, UO281847" />
	<meta name ="description" content ="Documento PHP realizado para la Tarea 1 del Ejercicio 1 de Computación en el servidor" />
	<meta name ="keywords" content ="html, css, php, calculadora milán" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    <title>Calculadora Milán</title>
    <link rel="stylesheet" type="text/css" href="CalculadoraMilan.css" />
</head>

<body>
    <!-- Datos con el contenidos que aparece en el navegador -->   
    <header>        
        <h1>CALCULADORA BÁSICA</h1>
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

            if (!isset($_SESSION["calculadoraMilan"])) {
                $_SESSION["calculadoraMilan"] = new CalculadoraMilan();
            }

            if (count($_POST) > 0) {
                $calculadora = $_SESSION["calculadoraMilan"];
                
                if(isset($_POST["ON/C"])) $calculadora->onC();
                if(isset($_POST["CE"])) $calculadora->borrar();
                if(isset($_POST["+/-"])) $calculadora->cambioDeSigno();
                if(isset($_POST["√"])) $calculadora->raizCuadrada();
                if(isset($_POST["%"])) $calculadora->porcentaje();

                if(isset($_POST["7"])) $calculadora->digitos(7);
                if(isset($_POST["8"])) $calculadora->digitos(8);
                if(isset($_POST["9"])) $calculadora->digitos(9);
                if(isset($_POST["x"])) $calculadora->multiplicacion();
                if(isset($_POST["÷"])) $calculadora->division();

                if(isset($_POST["4"])) $calculadora->digitos(4);
                if(isset($_POST["5"])) $calculadora->digitos(5);
                if(isset($_POST["6"])) $calculadora->digitos(6);
                if(isset($_POST["-"])) $calculadora->resta();
                if(isset($_POST["MRC"])) $calculadora->mrc();

                if(isset($_POST["1"])) $calculadora->digitos(1);
                if(isset($_POST["2"])) $calculadora->digitos(2);
                if(isset($_POST["3"])) $calculadora->digitos(3);
                if(isset($_POST["+"])) $calculadora->suma();
                if(isset($_POST["M-"])) $calculadora->mMenos();

                if(isset($_POST["0"])) $calculadora->digitos(0);
                if(isset($_POST["punto"])) $calculadora->punto();
                if(isset($_POST["="])) $calculadora->igual();
                if(isset($_POST["M+"])) $calculadora->mMas();

                $_SESSION["calculadoraMilan"] = $calculadora;
            }
        ?>         

        <form action='#' method='POST'>
            <section>
                <h2>MILÁN</h2>
                <label for='pantalla'>Valor introducido</label>
                <input type='text' value='<?php echo $_SESSION['calculadoraMilan']->getPantalla(); ?>' id='pantalla' disabled />
            </section>
            <input type='submit' value='ON/C' name='ON/C'/>
            <input type='submit' value='CE' name='CE'/>
            <input type='submit' value='+/-' name='+/-'/>
            <input type='submit' value='√' name='√'/>
            <input type='submit' value='%' name='%'/>
            
            <input type='submit' value='7' name='7'/>
            <input type='submit' value='8' name='8'/>
            <input type='submit' value='9' name='9'/>
            <input type='submit' value='x' name='x'/>
            <input type='submit' value='÷' name='÷'/>

            <input type='submit' value='4' name='4'/>
            <input type='submit' value='5' name='5'/>
            <input type='submit' value='6' name='6'/>
            <input type='submit' value='-' name='-'/>
            <input type='submit' value='MRC' name='MRC'/>

            <input type='submit' value='1' name='1'/>
            <input type='submit' value='2' name='2'/>
            <input type='submit' value='3' name='3'/>
            <input type='submit' value='+' name='+'/>
            <input type='submit' value='M-' name='M-'/>

            <input type='submit' value='0' name='0'/>
            <input type='submit' value='.' name='punto'/>
            <input type='submit' value='=' name='='/>
            <input type='submit' value='M+' name='M+'/>
        </form>
    </main>
</body>
</html>