<?php
    session_start();

    class BaseDatos {
        protected $usuario;
        protected $contraseña;
        protected $db;
        protected $msgEstado;

        public function __construct() {
            $this->usuario = "DBUSER2022";
            $this->contraseña = "DBPSWD2022";
            $this->msgEstado = "Esperando acciones...";
        }

        public function crearBaseDeDatos() {
            $this->conectarBaseDatos();
            $this->ejecutarQuery("CREATE DATABASE IF NOT EXISTS dbPruebasUsabilidad;");
            $this->db->select_db("dbPruebasUsabilidad");
            $this->msgEstado = "Se ha creado la base de datos con éxito.";
        }

        public function crearTabla() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbPruebasUsabilidad");
    
            $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS PruebasUsabilidad (
                dni VARCHAR(40) NOT NULL,
                nombre VARCHAR(40) NOT NULL,
                apellidos VARCHAR(40) NOT NULL,
                email VARCHAR(40),
                telefono VARCHAR(40),
                edad INT NOT NULL,
                sexo VARCHAR(40) NOT NULL,
                nivelInformatico INT NOT NULL,
                tiempoInvertido INT NOT NULL,
                realizadoCorrectamente VARCHAR(40) NOT NULL,
                comentarios VARCHAR(255),
                mejoras VARCHAR(255),
                valoracion INT NOT NULL,
                PRIMARY KEY (dni)
                );
            ");
    
            $this->msgEstado = "Se ha creado la tabla \"PruebasUsabilidad\" con éxito.";
        }

        public function insertarDatos() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbPruebasUsabilidad");

            $insercion = "INSERT INTO PruebasUsabilidad (dni, nombre, apellidos, email, telefono, edad, sexo, nivelInformatico, tiempoInvertido, realizadoCorrectamente, comentarios, mejoras, valoracion) values (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $pst = $this->db->prepare($insercion);
            $pst->bind_param(
                "sssssisiisssi",
                $_REQUEST["dni"],
                $_REQUEST["nombre"],
                $_REQUEST["apellidos"],
                $_REQUEST["email"],
                $_REQUEST["telefono"],
                $_REQUEST["edad"],
                $_REQUEST["sexo"],
                $_REQUEST["nivelInformatico"],
                $_REQUEST["tiempoInvertido"],
                $_REQUEST["realizadoCorrectamente"],
                $_REQUEST["comentarios"],
                $_REQUEST["mejoras"],
                $_REQUEST["valoracion"]
            );
            $resultado = $pst->execute();
            if ($resultado) {
                $this->msgEstado = "Se han insertado los datos con éxito.";
            } else {
                $this->msgEstado = "ERROR: No se ha podido insertar los datos.";
            }
        }

        public function buscarDatos() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbPruebasUsabilidad");
    
            $busqueda = "SELECT * FROM PruebasUsabilidad WHERE dni=?";
            $pst = $this->db->prepare($busqueda);
            if ($_REQUEST["dni"] != null) {
                $pst->bind_param("s", $_REQUEST["dni"]);
                $pst->execute();
                $resultado = $pst->get_result();

                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_array()) {
                        $this->msgEstado = "Datos buscados: " .
                            "\n\t➞ DNI: " . $fila["dni"] .
                            "\n\t➞ Nombre: " . $fila["nombre"] .
                            "\n\t➞ Apellidos: " . $fila["apellidos"] .
                            "\n\t➞ Email: " . $fila["email"] .
                            "\n\t➞ Telefono: " . $fila["telefono"] .
                            "\n\t➞ Edad: " . $fila["edad"] .
                            "\n\t➞ Sexo: " . $fila["sexo"] .
                            "\n\t➞ Nivel informático: " . $fila["nivelInformatico"] .
                            "\n\t➞ Tiempo invertido: " . $fila["tiempoInvertido"] .
                            "\n\t➞ Realizado correctamente: " . $fila["realizadoCorrectamente"] .
                            "\n\t➞ Comentarios: " . $fila["comentarios"] .
                            "\n\t➞ Mejoras: " . $fila["mejoras"] .
                            "\n\t➞ Valoracion: " . $fila["valoracion"];
                    }
                } else {
                    $this->msgEstado = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgEstado = "ERROR: Por favor, introduzca un dni.";
            }
        }

        public function modificarDatos() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbPruebasUsabilidad");

            $modificacion = "UPDATE PruebasUsabilidad SET nombre=?, apellidos=?, email=?, telefono=?, edad=?, sexo=?, nivelInformatico=?, tiempoInvertido=?, realizadoCorrectamente=?, comentarios=?, mejoras=?, valoracion=? WHERE dni=?";
            $pst = $this->db->prepare($modificacion);
            $pst->bind_param(
                "ssssisiisssis",                
                $_REQUEST["nombre"],
                $_REQUEST["apellidos"],
                $_REQUEST["email"],
                $_REQUEST["telefono"],
                $_REQUEST["edad"],
                $_REQUEST["sexo"],
                $_REQUEST["nivelInformatico"],
                $_REQUEST["tiempoInvertido"],
                $_REQUEST["realizadoCorrectamente"],
                $_REQUEST["comentarios"],
                $_REQUEST["mejoras"],
                $_REQUEST["valoracion"],
                $_REQUEST["dni"]
            );
            $pst->execute();
            $this->msgEstado = "Se han modificado los datos con éxito.";
        }

        public function eliminarDatos() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbPruebasUsabilidad");

            $eliminación = "DELETE FROM PruebasUsabilidad WHERE dni=?";
            $pst = $this->db->prepare($eliminación);
            if ($_REQUEST["dni"] != null) {
                $pst->bind_param("s", $_REQUEST["dni"]);
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgEstado = "Se han eliminado los datos con éxito.";
                } else {
                    $this->msgEstado = "ERROR: No se han encotnrado resultados.";
                }
            } else {
                $this->msgEstado = "ERROR: Por favor, introduzca un dni.";
            }
        }

        public function generarInforme() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbPruebasUsabilidad");

            $edadMedia = $this->ejecutarQuery("SELECT AVG(edad) as edadMedia FROM PruebasUsabilidad")->fetch_array()["edadMedia"];
            $frecuenciaSexo = $this->ejecutarQuery("SELECT sexo, COUNT(*) as frecuencias FROM pruebasUsabilidad GROUP BY sexo");
            $nivelInformaticoMedio = $this->ejecutarQuery("SELECT AVG(nivelInformatico) as nivelInformaticoMedio FROM PruebasUsabilidad")->fetch_array()["nivelInformaticoMedio"];
            $tiempoInvertidoMedio = $this->ejecutarQuery("SELECT AVG(tiempoInvertido) as tiempoInvertidoMedio FROM PruebasUsabilidad")->fetch_array()["tiempoInvertidoMedio"];
            $realizadoCorrectamentePorcentaje = $this->ejecutarQuery("SELECT COUNT(*) as realizadoCorrectamentePorcentaje FROM PruebasUsabilidad WHERE realizadoCorrectamente='Si'")->fetch_array()["realizadoCorrectamentePorcentaje"];
            $valoracionMedia = $this->ejecutarQuery("SELECT AVG(valoracion) as valoracionMedia FROM PruebasUsabilidad")->fetch_array()["valoracionMedia"];

            $frecuenciaHombres = 0;
            $frecuenciaMujeres = 0;
            while ($fila = $frecuenciaSexo->fetch_assoc()) {
                if ($fila["sexo"] == "Masculino")
                    $frecuenciaHombres = $fila["frecuencias"];
    
                if ($fila["sexo"] == "Femenino")
                    $frecuenciaMujeres = $fila["frecuencias"];
            }
            $total = ($frecuenciaMujeres+$frecuenciaHombres);
            $this->msgEstado = "Informe:" .
                            "\n\t➞ Edad media de los participantes: " . $edadMedia .
                            "\n\t➞ Frecuencias de sexo de los participantes: " . 
                            "\n\t\t• Hombres: " . $frecuenciaHombres/$total*100 . "%" .
                            "\n\t\t• Mujeres: " . $frecuenciaMujeres/$total*100 . "%" .
                            "\n\t➞ Nivel informático medio de los participantes: " . $nivelInformaticoMedio .
                            "\n\t➞ Tiempo invertido medio de los participantes: " . $tiempoInvertidoMedio .
                            "\n\t➞ Porcentaje de tareas realizadas correctamente de los participantes: " . $realizadoCorrectamentePorcentaje/$total*100 . "%" .
                            "\n\t➞ Valoración media de los participantes: " . $valoracionMedia;
        }

        public function cargarCSV() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbPruebasUsabilidad");

            $nombre = null;
            if (isset($_FILES["csv"])) {
                $nombre = $_FILES["csv"]["tmp_name"];
            }

            $fichero = fopen($nombre, "r");                
            while (($datos = fgetcsv($fichero)) != false) {
                $insercion = "INSERT INTO PruebasUsabilidad (dni, nombre, apellidos, email, telefono, edad, sexo, nivelInformatico, tiempoInvertido, realizadoCorrectamente, comentarios, mejoras, valoracion) values (?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $pst = $this->db->prepare($insercion);
                $pst->bind_param(
                    "sssssisiisssi",
                    $datos[0],
                    $datos[1],
                    $datos[2],
                    $datos[3],
                    $datos[4],
                    $datos[5],
                    $datos[6],
                    $datos[7],
                    $datos[8],
                    $datos[9],
                    $datos[10],
                    $datos[11],
                    $datos[12]
                );
                $resultado = $pst->execute();

                if ($resultado) {
                    $this->msgEstado = "Se ha cargado el fichero CSV con éxito.";
                } else {
                    $this->msgEstado = "ERROR: No se ha podido cargar el fichero CSV.";
                }
            }
            fclose($fichero);
        }

        public function exportarCSV() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbPruebasUsabilidad");

            $resultado = $this->ejecutarQuery("SELECT * FROM PruebasUsabilidad");
            $nombreFichero = "pruebasUsabilidad.csv";

            if ($resultado->fetch_assoc() != null) {
                $fichero = fopen($nombreFichero, "w");

                foreach ($resultado as $fila) {
                    fputcsv($fichero, $fila);
                }

                fclose($fichero);

                $nombreDescarga = basename($nombreFichero);
                $filePath = "" . $nombreDescarga;
                if (!empty($nombreDescarga) && file_exists($filePath)) {
                    header("Cache-Control: public");
                    header("Content-Description: File Transfer");
                    header("Content-Disposition: attachment; filename=$nombreDescarga");
                    header("Content-Type: text/csv");
                    header("Content-Transfer-Encoding: binary");
                    readfile($filePath);
                    exit;
                }
            }
        }

        public function conectarBaseDatos() {
            $this->db = new mysqli("localhost", $this->usuario, $this->contraseña);
            if ($this->db->connect_errno) {
                $this->msgEstado = "ERROR: No se ha podido conectar a la base de datos.";
            } else {
                $this->msgEstado = "Se ha conectado a la base de datos con éxito.";
            }
        }

        public function desconectarBaseDatos() {
            $this->db->close();
        }

        public function ejecutarQuery($query) {
            $resultado = $this->db->query($query);
            if ($resultado) {
                return $resultado;
            }
        }

        public function getMsgEstado() {
            return $this->msgEstado;
        }
    }

    if (!isset($_SESSION["basededatos"])) {
        $basededatos = new BaseDatos();
        $basededatos->conectarBaseDatos();
        $_SESSION["basededatos"] = $basededatos;
    }
    
    if (count($_POST) > 0) {
        $basededatos = $_SESSION["basededatos"];
    
        if (isset($_POST["crearBaseDeDatos"])) $basededatos->crearBaseDeDatos();
        if (isset($_POST["crearTabla"])) $basededatos->crearTabla();
        if (isset($_POST["insertarDatos"])) $basededatos->insertarDatos();
        if (isset($_POST["buscarDatos"])) $basededatos->buscarDatos();
        if (isset($_POST["modificarDatos"])) $basededatos->modificarDatos();
        if (isset($_POST["eliminarDatos"])) $basededatos->eliminarDatos();
        if (isset($_POST["generarInforme"])) $basededatos->generarInforme();
        if (isset($_POST["cargarCSV"])) $basededatos->cargarCSV();
        if (isset($_POST["exportarCSV"])) $basededatos->exportarCSV();
    
        $basededatos->desconectarBaseDatos();
        $_SESSION["basededatos"] = $basededatos;
    }
?>
<!DOCTYPE HTML>

<html lang="es">
<head>    
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	<meta name ="author" content ="Omar Teixeira González, UO281847" />
	<meta name ="description" content ="Documento PHP realizado para la Tarea 1 del Ejercicio 6 de Computación en el servidor" />
	<meta name ="keywords" content ="html, css, php, mysql, base de datos" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    <title>Ejercicio 6</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio6.css" />
</head>

<body>
    <!-- Datos con el contenidos que aparece en el navegador -->
    <header>
        <h1>Gestor de Bases de Datos</h1>
        <nav>
            <a title="Acceda a la creación de la base de datos" tabindex="1" accesskey="C" href="#basededatos">Crear Base de Datos</a>
            <a title="Acceda a la creación de una tabla" tabindex="2" accesskey="T" href="#tabla">Crear una tabla</a>
            <a title="Acceda a la inserción de datos en una tabla" tabindex="3" accesskey="I" href="#insertar">Insertar datos en una tabla</a>
            <a title="Acceda a la búsqueda de datos en una tabla" tabindex="4" accesskey="B" href="#buscar">Buscar datos en una tabla</a>
            <a title="Acceda a la modificación de datos de una tabla" tabindex="5" accesskey="M" href="#modificar">Modificar datos en una tabla</a>
            <a title="Acceda a la eliminación de datos de una tabla" tabindex="6" accesskey="E" href="#eliminar">Eliminar datos de una tabla</a>
            <a title="Acceda a la generación de un informe" tabindex="7" accesskey="G" href="#generar">Generar informe</a>
            <a title="Acceda a la carga de datos desde un archivo" tabindex="8" accesskey="A" href="#cargar">Cargar datos desde un archivo CSV</a>
            <a title="Acceda a la exportación de datos a un archivo" tabindex="9" accesskey="D" href="#exportar">Exportar datos a un archivo CSV</a>
        </nav>
    </header>
    <main>
        <h2>Estado de la acción solicitada</h2>
        <pre><?php echo $_SESSION["basededatos"]->getMsgEstado(); ?></pre>
        <section id="basededatos">
            <h3>Crear Base de Datos</h3>
            <form action="#" method="POST">
                <input type="submit" value="Presione para crear" name="crearBaseDeDatos">
            </form>
        </section>
        <section id="tabla">
            <h3>Crear una tabla</h3>
            <form action="#" method="POST">
                <input type="submit" value="Presione para crear" name="crearTabla">
            </form>
        </section>
        <section id="insertar">
            <h3>Insertar datos en una tabla</h3>
            <form action="#" method="POST">
                <fieldset>
                    <legend>Datos personales</legend>
                    <label>DNI: <input type="text" name="dni" required /></label>
                    <label>Nombre: <input type="text" name="nombre" required /></label>
                    <label>Apellidos: <input type="text" name="apellidos" required /></label>
                    <label>Email: <input type="email" name="email" /></label>
                    <label>Telefono: <input type="text" name="telefono" /></label>
                    <label>Edad: <input type="number" min="18" step="1" name="edad" value="18" required></label>
                    <label>Sexo: </label>
                    <p><input type="radio" value="Masculino" name="sexo" id="masculinoInsertar" checked="" /><label for="masculinoInsertar">Masculino</label></p><p><input type="radio" value="Femenino" name="sexo" id="femeninoInsertar" /><label for="femeninoInsertar">Femenino</label></p>
                </fieldset>                                        
                <fieldset>                        
                    <legend>Datos informáticos: </legend>
                    <label>Nivel: <input type="number" min="0" max="10" name="nivelInformatico" value="0" required /></label>
                    <label>Tiempo invertido (segundos): <input type="number" min="0" name="tiempoInvertido" value="0" required /></label>
                    <label>Realizado correctamente:</label>
                    <p><input type="radio" value="Si" name="realizadoCorrectamente" id="siInsertar" checked="" /><label for="siInsertar">Sí</label></p><p><input type="radio" value="No" name="realizadoCorrectamente" id="noInsertar" /><label for="noInsertar">No</label></p>
                </fieldset>
                <fieldset>
                    <legend>Acerca del programa</legend>
                    <label>Comentarios: <textarea name="comentarios"></textarea></label>
                    <label>Mejoras: <textarea name="mejoras"></textarea></label>
                    <label>Valoración: <input type="number" min="0" max="10" name="valoracion" required /></label>
                </fieldset>
                <input type="submit" value="Presione para insertar" name="insertarDatos" />
            </form>
        </section>
        <section id="buscar">
            <h3>Buscar datos en una tabla</h3>
            <form action="#" method="POST">
                <label>DNI: <input type="text" name="dni" required /></label>                
                <input type="submit" value="Presione para buscar" name="buscarDatos" />
            </form>
        </section>
        <section id="modificar">
            <h3>Modificar datos en una tabla</h3>
            <form action="#" method="POST">
                <fieldset>
                    <legend>Datos personales</legend>
                    <label>Nombre: <input type="text" name="nombre" required /></label>
                    <label>Apellidos: <input type="text" name="apellidos" required /></label>
                    <label>Email: <input type="email" name="email" /></label>
                    <label>Telefono: <input type="text" name="telefono" /></label>
                    <label>Edad: <input type="number" min="18" step="1" name="edad" value="18" required /></label>
                    <label>Sexo: </label>
                    <p><input type="radio" value="Masculino" name="sexo" id="masculinoModificar" checked /><label for="masculinoModificar">Masculino</label></p><p><input type="radio" value="Femenino" name="sexo" id="femeninoModificar" /><label for="femeninoModificar">Femenino</label></p>
                </fieldset>                                        
                <fieldset>                        
                    <legend>Datos informáticos: </legend>
                    <label>Nivel: <input type="number" min="0" max="10" name="nivelInformatico" value="0" required /></label>
                    <label>Tiempo invertido (segundos): <input type="number" min="0" name="tiempoInvertido" value="0" required /></label>
                    <label>Realizado correctamente:</label>
                    <p><input type="radio" value="Si" name="realizadoCorrectamente" id="siModificar" checked /><label for="siModificar">Sí</label></p><p><input type="radio" value="No" name="realizadoCorrectamente" id="noModificar" /><label for="noModificar">No</label></p>
                </fieldset>
                <fieldset>
                    <legend>Acerca del programa</legend>
                    <label>Comentarios: <textarea name="comentarios"></textarea></label>
                    <label>Mejoras: <textarea name="mejoras"></textarea></label>
                    <label>Valoración: <input type="number" min="0" max="10" name="valoracion" required /></label>
                </fieldset>
                <label>DNI: <input type="text" name="dni" required /></label>
                <input type="submit" value="Presione para modificar" name="modificarDatos" />
            </form>
        </section>
        <section id="eliminar">
            <h3>Eliminar datos en una tabla</h3>
            <form action="#" method="POST">
                <label>DNI: <input type="text" name="dni" required /></label>                
                <input type="submit" value="Presione para eliminar" name="eliminarDatos" />
            </form>
        </section>
        <section id="generar">
            <h3>Generar informe</h3>
            <form action="#" method="POST">
                <input type="submit" value="Presione para generar" name="generarInforme" />
            </form>
        </section>
        <section id="cargar">
            <h3>Cargar datos desde un archivo CSV</h3>
            <form action="#" method="POST" enctype="multipart/form-data">
                <label>Cargue un archivo CSV: <input type="file" name="csv" /></label>                
                <input type="submit" value="Presione para cargar el archivo" name="cargarCSV" />
            </form>
        </section>
        <section id="exportar">
            <h3>Exportar datos a un archivo CSV</h3>
            <form action="#" method="POST">
                <input type="submit" value="Presione para exportar al archivo" name="exportarCSV" />
            </form>
        </section>
    </main>
</body>
</html>