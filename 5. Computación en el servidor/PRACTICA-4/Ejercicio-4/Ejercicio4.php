<!DOCTYPE HTML>

<html lang="es">
<head>    
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	<meta name ="author" content ="Omar Teixeira González, UO281847" />
	<meta name ="description" content ="Documento PHP realizado para la Tarea 1 del Ejercicio 3 de Computación en el servidor" />
	<meta name ="keywords" content ="html, css, php, consultor, oro, precios" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    <title>Ejercicio 4</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio4.css" />
</head>

<body>
    <!-- Datos con el contenidos que aparece en el navegador -->
    <header>
        <h1>Consultor de precios del Oro</h1>
</header>
    <main>
        <?php
            session_start();
            
            class ConsultorOro {
                const APIKEY = "goldapi-cn5dyytlbjtyan6-io";
                protected $url;
                protected $divisa;
                protected $fecha;
                protected $precio; 
                protected $precio24; 
                protected $precio22; 
                protected $precio21; 
                protected $precio20; 
                protected $precio18; 

                public function setFecha($fecha) {
                    $this->fecha = $fecha;
                }

                public function setDivisa($divisa) {
                    $this->divisa = $divisa;
                }

                public function consultarPrecio() {
                    $this->url = "https://www.goldapi.io/api/XAU/" . $this->divisa . "/" . $this->fecha;

                    $contexto = stream_context_create(array(
                        "http" => array(
                            "header" => "x-access-token: " .self::APIKEY                           
                        )
                    ));

                    $datos = file_get_contents($this->url, false, $contexto);
                    $json = json_decode($datos);

                    if (isset($json->price) && isset($json->price_gram_24k) && isset($json->price_gram_22k) && isset($json->price_gram_21k) && isset($json->price_gram_20k) && isset($json->price_gram_18k) ) {
                        $this->precio = $json->price . " " . $this->divisa;
                        $this->precio24 = $json->price_gram_24k . " " . $this->divisa;
                        $this->precio22 = $json->price_gram_22k . " " . $this->divisa;
                        $this->precio21 = $json->price_gram_21k . " " . $this->divisa;
                        $this->precio20 = $json->price_gram_20k . " " . $this->divisa;
                        $this->precio18 = $json->price_gram_18k . " " . $this->divisa;
                    } else {
                        $this->precio = "No existen registros del precio del oro para la divisa: " . $this->divisa . " con fecha: " . $this->fecha;
                    }                    
                }

                public function getPrecio() {
                    return $this->precio;
                }
                public function getPrecio24() {
                    return $this->precio24;
                }
                public function getPrecio22() {
                    return $this->precio22;
                }
                public function getPrecio21() {
                    return $this->precio21;
                }
                public function getPrecio20() {
                    return $this->precio20;
                }
                public function getPrecio18() {
                    return $this->precio18;
                }
            }
            
            if (!isset($_SESSION["consultorOro"])) {
                $_SESSION["consultorOro"] = new ConsultorOro();
            }
            
            if (count($_POST) > 0) {
                $consultor = $_SESSION["consultorOro"];
            
                if(isset($_POST["fecha"])) $consultor->setFecha($_POST["fecha"]);            
                if(isset($_POST["divisa"])) $consultor->setDivisa($_POST["divisa"]);
                if(isset($_POST["consultar"])) $consultor->consultarPrecio();

                $_SESSION["consultorOro"] = $consultor;
            }
        ?>
        
        <p>Para consultar el precio del Oro, seleccione la divisa en la que le interese ver el precio y la fecha a buscar.</p>
        <form action="#" method="POST">            
            <label for="divisa">Seleccione una divisa:</label>
            <select name="divisa" id="divisa">
                <option value="EUR">EUR - European Euro (€)</option>
                <option value="USD">USD - United States Dollar ($)</option>
                <option value="BTC">BTC - Bitcoin</option>
            </select>
            <label for="fecha">Seleccione una fecha:</label>
            <input type="date" name="fecha" id="fecha" value="2022-01-01" />
            <input type="submit" value="Consultar precio" name="consultar" />
        </form>
        
        <section>
            <h2>Precio del Oro:</h2>
            <ul>
                <li>Precio completo: <?php echo $_SESSION["consultorOro"]->getPrecio()?></li>
                <li>Precio de un gramo de 24k: <?php echo $_SESSION["consultorOro"]->getPrecio24()?></li>
                <li>Precio de un gramo de 22k: <?php echo $_SESSION["consultorOro"]->getPrecio22()?></li>
                <li>Precio de un gramo de 21k: <?php echo $_SESSION["consultorOro"]->getPrecio21()?></li>
                <li>Precio de un gramo de 20k: <?php echo $_SESSION["consultorOro"]->getPrecio20()?></li>
                <li>Precio de un gramo de 18k: <?php echo $_SESSION["consultorOro"]->getPrecio18()?></li>
            </ul>
        </section>
    </main>
</body>
</html>