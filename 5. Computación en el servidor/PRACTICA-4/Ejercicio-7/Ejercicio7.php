<?php
    session_start();

    class BaseDatos {
        protected $usuario;
        protected $contraseña;
        protected $db;
        protected $msgAviso;

        public function __construct() {
            $this->usuario = "DBUSER2022";
            $this->contraseña = "DBPSWD2022";
            $this->msgAviso = "Esperando acciones...";
            $this->creardb();
            $this->crearTablas();
        }

        public function insertarPeliculas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $insercion = "INSERT INTO PELICULA (CODPELICULA, TITULO, DURACION, TIPO, CODPELICULA_ORIGINAL) values (?,?,?,?,?)";
            $pst = $this->db->prepare($insercion);
            $pst->bind_param(
                "ssiss",
                $_REQUEST["codpelicula"],
                $_REQUEST["titulo"],
                $_REQUEST["duracion"],
                $_REQUEST["tipo"],
                $_REQUEST["codpeliculaOriginal"]
            );
            try {
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se ha insertado la película.";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido insertar los datos.";
                }
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: La película ya existe.";
            }
        }

        public function insertarCines() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $insercion = "INSERT INTO CINE (CODCINE, LOCALIDAD) values (?,?)";
            $pst = $this->db->prepare($insercion);
            $pst->bind_param(
                "ss",
                $_REQUEST["codcine"],
                $_REQUEST["localidad"]
            );
            try {            
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se ha insertado el cine.";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido insertar los datos.";
                }
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: El cine ya existe.";
            }
        }

        public function insertarSalas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $insercion = "INSERT INTO SALA (CODSALA, AFORO) values (?,?)";
            $pst = $this->db->prepare($insercion);
            $pst->bind_param(
                "si",
                $_REQUEST["codsala"],
                $_REQUEST["aforo"]
            );
            try {
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se ha insertado la sala.";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido insertar los datos.";
                }
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: La sala ya existe.";
            }
        }

        public function insertarProyecciones() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $insercion = "INSERT INTO PROYECTA (CODPELICULA, CODSALA, SESION, FECHA, ENTRADAS_VENDIDAS) values (?,?,?,?,?)";
            $pst = $this->db->prepare($insercion);
            $pst->bind_param(
                "ssiss",
                $_REQUEST["codpelicula"],
                $_REQUEST["codsala"],
                $_REQUEST["sesion"],
                $_REQUEST["fecha"],
                $_REQUEST["entradasVendidas"]
            );
            try {
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se ha insertado la proyeccion.";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido insertar los datos.";
                }
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: La proyección ya existe.";
            }
        }

        public function insertarEntradas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $insercion = "INSERT INTO ENTRADA (CODENTRADA, PRECIO, CODPELICULA, CODSALA, SESION, FECHA) values (?,?,?,?,?,?)";
            $pst = $this->db->prepare($insercion);
            $pst->bind_param(
                "sissis",
                $_REQUEST["codentrada"],
                $_REQUEST["precio"],
                $_REQUEST["codpelicula"],
                $_REQUEST["codsala"],
                $_REQUEST["sesion"],
                $_REQUEST["fecha"]
            );
            try {
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se ha insertado la entrada.";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido insertar los datos.";
                }
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: La entrada ya existe.";
            }
        }

        public function buscarPeliculas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");
    
            $busqueda = "SELECT * FROM PELICULA WHERE CODPELICULA=?";
            $pst = $this->db->prepare($busqueda);
            if ($_REQUEST["codpelicula"] != null) {
                $pst->bind_param("s", $_REQUEST["codpelicula"]);
                $pst->execute();
                $resultado = $pst->get_result();

                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_array()) {
                        $this->msgAviso = "Datos buscados: " .
                            "\n\t➞ Código de la película: " . $fila["CODPELICULA"] .
                            "\n\t➞ Título de la película: " . $fila["TITULO"] .
                            "\n\t➞ Duración de la película: " . $fila["DURACION"] .
                            "\n\t➞ Tipo de la película: " . $fila["TIPO"] .
                            "\n\t➞ Código de la película original: " . $fila["CODPELICULA_ORIGINAL"];
                    }
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código de la película.";
            }
        }

        public function buscarCines() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");
    
            $busqueda = "SELECT * FROM CINE WHERE CODCINE=?";
            $pst = $this->db->prepare($busqueda);
            if ($_REQUEST["codcine"] != null) {
                $pst->bind_param("s", $_REQUEST["codcine"]);
                $pst->execute();
                $resultado = $pst->get_result();

                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_array()) {
                        $this->msgAviso = "Datos buscados: " .
                            "\n\t➞ Código del cine: " . $fila["CODCINE"] .
                            "\n\t➞ Localidad del cine: " . $fila["LOCALIDAD"];
                    }
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código del cine.";
            }
        }

        public function buscarSalas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");
    
            $busqueda = "SELECT * FROM SALA WHERE CODSALA=? AND CODCINE=?";
            $pst = $this->db->prepare($busqueda);
            if ($_REQUEST["codsala"] != null && $_REQUEST["codcine"] != null) {
                $pst->bind_param("ss", $_REQUEST["codsala"], $_REQUEST["codcine"]);
                $pst->execute();
                $resultado = $pst->get_result();

                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_array()) {
                        $this->msgAviso = "Datos buscados: " .
                            "\n\t➞ Código de la sala: " . $fila["CODSALA"] .
                            "\n\t➞ Aforo de la sala: " . $fila["AFORO"] .
                            "\n\t➞ Código del cine: " . $fila["CODCINE"];
                    }
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código de la sala.";
            }
        }

        public function buscarProyecciones() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");
    
            $busqueda = "SELECT * FROM PROYECTA WHERE CODPELICULA=? AND CODSALA=? AND SESION=? AND FECHA=?";
            $pst = $this->db->prepare($busqueda);
            if ($_REQUEST["codpelicula"] != null && $_REQUEST["codsala"] != null && $_REQUEST["sesion"] != null && $_REQUEST["fecha"] != null) {
                $pst->bind_param("ssis", $_REQUEST["codpelicula"], $_REQUEST["codsala"], $_REQUEST["sesion"], $_REQUEST["fecha"]);
                $pst->execute();
                $resultado = $pst->get_result();

                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_array()) {
                        $this->msgAviso = "Datos buscados: " .
                            "\n\t➞ Código de la película: " . $fila["CODPELICULA"] .
                            "\n\t➞ Código de la sala: " . $fila["CODSALA"] .
                            "\n\t➞ Sesion de la proyección: " . $fila["SESION"] .
                            "\n\t➞ Fecha de la proyección: " . $fila["FECHA"] .
                            "\n\t➞ Entradas vendidas de la proyección: " . $fila["ENTRADAS_VENDIDAS"];
                    }
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código de la película, la sala, la sesión y la fecha";
            }
        }

        public function buscarEntradas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");
    
            $busqueda = "SELECT * FROM ENTRADA WHERE CODENTRADA=?";
            $pst = $this->db->prepare($busqueda);
            if ($_REQUEST["codentrada"] != null) {
                $pst->bind_param("s", $_REQUEST["codentrada"]);
                $pst->execute();
                $resultado = $pst->get_result();

                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_array()) {
                        $this->msgAviso = "Datos buscados: " .
                            "\n\t➞ Código de la entrada: " . $fila["CODENTRADA"] .
                            "\n\t➞ Precio de la entrada: " . $fila["PRECIO"] .
                            "\n\t➞ Código de la película: " . $fila["CODPELICULA"] .
                            "\n\t➞ Código de la sala: " . $fila["CODSALA"] .
                            "\n\t➞ Sesion de la proyección: " . $fila["SESION"] .
                            "\n\t➞ Fecha de la proyección: " . $fila["FECHA"];
                    }
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código de la entrada.";
            }
        }

        public function eliminarPeliculas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $eliminación = "DELETE FROM PELICULA WHERE CODPELICULA=?";
            $pst = $this->db->prepare($eliminación);
            if ($_REQUEST["codpelicula"] != null) {
                $pst->bind_param("s", $_REQUEST["codpelicula"]);
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se han eliminado los datos con éxito.";
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código de la película.";
            }
        }

        public function eliminarCines() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $eliminación = "DELETE FROM CINE WHERE CODCINE=?";
            $pst = $this->db->prepare($eliminación);
            if ($_REQUEST["codcine"] != null) {
                $pst->bind_param("s", $_REQUEST["codcine"]);
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se han eliminado los datos con éxito.";
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código del cine.";
            }
        }

        public function eliminarSalas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $eliminación = "DELETE FROM SALA WHERE CODSALA=?";
            $pst = $this->db->prepare($eliminación);
            if ($_REQUEST["codsala"] != null) {
                $pst->bind_param("s", $_REQUEST["codsala"]);
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se han eliminado los datos con éxito.";
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código de la sala.";
            }
        }

        public function eliminarProyecciones() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $eliminación = "DELETE FROM PROYECTA WHERE CODPELICULA=? AND CODSALA=? AND SESION=? AND FECHA=?";
            $pst = $this->db->prepare($eliminación);
            if ($_REQUEST["codpelicula"] != null && $_REQUEST["codsala"] != null && $_REQUEST["sesion"] != null && $_REQUEST["fecha"] != null) {
                $pst->bind_param("ssis", $_REQUEST["codpelicula"], $_REQUEST["codsala"], $_REQUEST["sesion"], $_REQUEST["fecha"]);
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se han eliminado los datos con éxito.";
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código de la película, la sala, la sesión y la fecha.";
            }
        }

        public function eliminarEntradas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $eliminación = "DELETE FROM ENTRADA WHERE CODENTRADA=?";
            $pst = $this->db->prepare($eliminación);
            if ($_REQUEST["codentrada"] != null) {
                $pst->bind_param("s", $_REQUEST["codentrada"]);
                $resultado = $pst->execute();
                if ($resultado) {
                    $this->msgAviso = "Se han eliminado los datos con éxito.";
                } else {
                    $this->msgAviso = "ERROR: No se han encontrado resultados.";
                }
            } else {
                $this->msgAviso = "ERROR: Por favor, introduzca el código de la entrada.";
            }
        }

        public function modificarPeliculas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $modificacion = "UPDATE PELICULA SET TITULO=?, DURACION=?, TIPO=?, CODPELICULA_ORIGINAL=? WHERE CODPELICULA=?";
            $pst = $this->db->prepare($modificacion);
            $pst->bind_param(
                "sissi",                
                $_REQUEST["titulo"],
                $_REQUEST["duracion"],
                $_REQUEST["tipo"],
                $_REQUEST["codpeliculaOriginal"],
                $_REQUEST["codpelicula"]
            );
            try {
                $pst->execute();
                $this->msgAviso = "Se han modificado los datos con éxito.";
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: La película no existe.";
            }
        }

        public function modificarCines() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $modificacion = "UPDATE CINE SET LOCALIDAD=? WHERE CODCINE=?";
            $pst = $this->db->prepare($modificacion);
            $pst->bind_param(
                "ss",                
                $_REQUEST["localidad"],                
                $_REQUEST["codcine"]
            );
            try {
                $pst->execute();
                $this->msgAviso = "Se han modificado los datos con éxito.";
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: El cine no existe.";
            }
        }

        public function modificarSalas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $modificacion = "UPDATE SALA SET AFORO=? AND CODCINE=? WHERE CODSALA=?";
            $pst = $this->db->prepare($modificacion);
            $pst->bind_param(
                "iss",                
                $_REQUEST["aforo"],                
                $_REQUEST["codcine"],
                $_REQUEST["codsala"]
            );
            try {
                $pst->execute();
                $this->msgAviso = "Se han modificado los datos con éxito.";
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: La sala no existe.";
            }
        }

        public function modificarProyecciones() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $modificacion = "UPDATE PROYECTA SET ENTRADAS_VENDIDAS=? WHERE CODPELICULA=? AND CODSALA=? AND SESION=? AND FECHA=?";
            $pst = $this->db->prepare($modificacion);
            $pst->bind_param(
                "issis",                
                $_REQUEST["entradasVendidas"],                
                $_REQUEST["codpelicula"],
                $_REQUEST["codsala"],
                $_REQUEST["sesion"],
                $_REQUEST["fecha"]
            );
            try {
                $pst->execute();
                $this->msgAviso = "Se han modificado los datos con éxito.";
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: La proyección no existe.";
            }
        }

        public function modificarEntradas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $modificacion = "UPDATE ENTRADA SET PRECIO=? AND CODPELICULA=? AND CODSALA=? AND SESION=? AND FECHA=? WHERE CODENTRADA=?";
            $pst = $this->db->prepare($modificacion);
            $pst->bind_param(
                "ississ",                
                $_REQUEST["precio"],                
                $_REQUEST["codpelicula"],
                $_REQUEST["codsala"],
                $_REQUEST["sesion"],
                $_REQUEST["fecha"],
                $_REQUEST["codentrada"]
            );
            try {
                $pst->execute();
                $this->msgAviso = "Se han modificado los datos con éxito.";
            } catch (mysqli_sql_exception) {
                $this->msgAviso = "ERROR: La entrada no existe.";
            }
        }

        public function cargarPeliculas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $nombre = null;
            if (isset($_FILES["csv"])) {
                $nombre = $_FILES["csv"]["tmp_name"];
            }

            $fichero = fopen($nombre, "r");                
            while (($datos = fgetcsv($fichero)) != false) {
                $insercion = "INSERT INTO PELICULA (CODPELICULA, TITULO, DURACION, TIPO, CODPELICULA_ORIGINAL) values (?,?,?,?,?)";
                $pst = $this->db->prepare($insercion);
                $pst->bind_param(
                    "ssiss",
                    $datos[0],
                    $datos[1],
                    $datos[2],
                    $datos[3],
                    $datos[4]
                );
                $resultado = $pst->execute();

                if ($resultado) {
                    $this->msgAviso = "Se han cargado las películas con éxito";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                }
            }
            fclose($fichero);
        }

        public function cargarCines() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $nombre = null;
            if (isset($_FILES["csv"])) {
                $nombre = $_FILES["csv"]["tmp_name"];
            }

            $fichero = fopen($nombre, "r");                
            while (($datos = fgetcsv($fichero)) != false) {
                $insercion = "INSERT INTO CINE (CODCINE, LOCALIDAD) values (?,?)";
                $pst = $this->db->prepare($insercion);
                $pst->bind_param(
                    "ss",
                    $datos[0],
                    $datos[1]
                );
                $resultado = $pst->execute();

                if ($resultado) {
                    $this->msgAviso = "Se han cargado los cines con éxito";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                }
            }
            fclose($fichero);
        }

        public function cargarSalas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $nombre = null;
            if (isset($_FILES["csv"])) {
                $nombre = $_FILES["csv"]["tmp_name"];
            }

            $fichero = fopen($nombre, "r");                
            while (($datos = fgetcsv($fichero)) != false) {
                $insercion = "INSERT INTO SALA (CODSALA, AFORO, CODCINE) values (?,?,?)";
                $pst = $this->db->prepare($insercion);
                $pst->bind_param(
                    "sis",
                    $datos[0],
                    $datos[1],
                    $datos[2]
                );
                $resultado = $pst->execute();

                if ($resultado) {
                    $this->msgAviso = "Se han cargado las salas con éxito";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                }
            }
            fclose($fichero);
        }

        public function cargarProyecciones() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $nombre = null;
            if (isset($_FILES["csv"])) {
                $nombre = $_FILES["csv"]["tmp_name"];
            }

            $fichero = fopen($nombre, "r");                
            while (($datos = fgetcsv($fichero)) != false) {
                $insercion = "INSERT INTO PROYECTA (CODPELICULA, CODSALA, SESION, FECHA, ENTRADAS_VENDIDAS) values (?,?,?,?,?)";
                $pst = $this->db->prepare($insercion);
                $pst->bind_param(
                    "ssisi",
                    $datos[0],
                    $datos[1],
                    $datos[2],
                    $datos[3],
                    $datos[4]
                );
                $resultado = $pst->execute();

                if ($resultado) {
                    $this->msgAviso = "Se han cargado las proyecciones con éxito";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                }
            }
            fclose($fichero);
        }

        public function cargarEntradas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $nombre = null;
            if (isset($_FILES["csv"])) {
                $nombre = $_FILES["csv"]["tmp_name"];
            }

            $fichero = fopen($nombre, "r");                
            while (($datos = fgetcsv($fichero)) != false) {
                $insercion = "INSERT INTO ENTRADA (CODENTRADA, PRECIO, CODPELICULA, CODSALA, SESION, FECHA) values (?,?,?,?,?,?)";
                $pst = $this->db->prepare($insercion);
                $pst->bind_param(
                    "sissis",
                    $datos[0],
                    $datos[1],
                    $datos[2],
                    $datos[3],
                    $datos[4],
                    $datos[5]
                );
                $resultado = $pst->execute();

                if ($resultado) {
                    $this->msgAviso = "Se han cargado las proyecciones con éxito";
                } else {
                    $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                }
            }
            fclose($fichero);
        }

        public function exportarCSV() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");

            $resultado = $this->ejecutarQuery("SELECT * FROM PELICULA");
            $nombreFichero = "datosPeliculas.csv";
            $this->exportar($resultado, $nombreFichero);
            
            $resultado = $this->ejecutarQuery("SELECT * FROM CINE");
            $nombreFichero = "datosCines.csv";
            $this->exportar($resultado, $nombreFichero);

            $resultado = $this->ejecutarQuery("SELECT * FROM SALA");
            $nombreFichero = "datosSalas.csv";
            $this->exportar($resultado, $nombreFichero);

            $resultado = $this->ejecutarQuery("SELECT * FROM PROYECTA");
            $nombreFichero = "datosProyecciones.csv";
            $this->exportar($resultado, $nombreFichero);

            $resultado = $this->ejecutarQuery("SELECT * FROM ENTRADA");
            $nombreFichero = "datosEntradas.csv";
            $this->exportar($resultado, $nombreFichero);
        }

        protected function exportar($resultado, $nombreFichero) {
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
                }
            }
        }

        public function creardb() {
            $this->conectarBaseDatos();
            $this->ejecutarQuery("CREATE DATABASE IF NOT EXISTS dbGestorCines;");
            $this->db->select_db("dbGestorCines");
            $this->msgAviso = "Se ha creado la base de datos con éxito.";
        }

        public function crearTablas() {
            $this->conectarBaseDatos();
            $this->db->select_db("dbGestorCines");
    
            $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS CINE 
            (
                CODCINE varchar(4),
                LOCALIDAD varchar(20),   
                PRIMARY KEY (CODCINE)
            );");
            
            $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS SALA 
            (
                CODSALA varchar(4),
                AFORO decimal(3,0),   
                CODCINE varchar(4) NOT NULL,
                PRIMARY KEY (CODSALA),
                FOREIGN KEY (CODCINE) REFERENCES CINE (CODCINE)            
            );");
            
            $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS PELICULA
            (
                CODPELICULA varchar(4),
                TITULO varchar(20), 
                DURACION decimal(2,0),   
                TIPO varchar(20) NOT NULL, 
                CODPELICULA_ORIGINAL varchar(4),
                PRIMARY KEY (CODPELICULA),                
                FOREIGN KEY (CODPELICULA_ORIGINAL) REFERENCES PELICULA (CODPELICULA),
                CHECK (TIPO IN ('ficcion','aventuras','terror')) 
            );");
            
            $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS PROYECTA
            (
                CODPELICULA varchar(4),
                CODSALA varchar(4),
                SESION decimal(2,0),
                FECHA date,
                ENTRADAS_VENDIDAS decimal(3,0),
                PRIMARY KEY (CODPELICULA,CODSALA,SESION,FECHA),  
                FOREIGN KEY (CODPELICULA) REFERENCES PELICULA (CODPELICULA),
                FOREIGN KEY (CODSALA) REFERENCES SALA (CODSALA),           
                CHECK (SESION IN (5,7,10))      
            );");
            
            $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS ENTRADA
            (
                CODENTRADA varchar(4),
                PRECIO decimal(3,0),   
                CODPELICULA varchar(4) NOT NULL,
                CODSALA varchar(4) NOT NULL,
                SESION decimal(2,0) NOT NULL,
                FECHA date NOT NULL,  
                PRIMARY KEY (CODENTRADA),
                FOREIGN KEY (CODPELICULA,CODSALA,SESION,FECHA) REFERENCES PROYECTA (CODPELICULA,CODSALA,SESION,FECHA)
            );");
    
            $this->msgAviso = "Se han creado las tablas con éxito.";
        }

        public function conectarBaseDatos() {
            $this->db = new mysqli("localhost", $this->usuario, $this->contraseña);
            if ($this->db->connect_errno) {
                $this->msgAviso = "ERROR: No se ha podido conectar a la base de datos.";
            } else {
                $this->msgAviso = "Se ha conectado a la base de datos con éxito.";
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

        public function getMsgAviso() {
            return $this->msgAviso;
        }
    }

    if (!isset($_SESSION["db"])) {
        $db = new BaseDatos();
        $db->conectarBaseDatos();
        $_SESSION["db"] = $db;
    }
    
    if (count($_POST) > 0) {
        $db = $_SESSION["db"];
    
        if (isset($_POST["cargarPeliculas"])) $db->cargarPeliculas();
        if (isset($_POST["cargarCines"])) $db->cargarCines();
        if (isset($_POST["cargarSalas"])) $db->cargarSalas();
        if (isset($_POST["cargarProyecciones"])) $db->cargarProyecciones();
        if (isset($_POST["cargarEntradas"])) $db->cargarEntradas();

        if (isset($_POST["insertarPeliculas"])) $db->insertarPeliculas();
        if (isset($_POST["insertarCines"])) $db->insertarCines();
        if (isset($_POST["insertarSalas"])) $db->insertarSalas();
        if (isset($_POST["insertarProyecciones"])) $db->insertarProyecciones();
        if (isset($_POST["insertarEntradas"])) $db->insertarEntradas(); 

        if (isset($_POST["buscarPeliculas"])) $db->buscarPeliculas();
        if (isset($_POST["buscarCines"])) $db->buscarCines();
        if (isset($_POST["buscarSalas"])) $db->buscarSalas();
        if (isset($_POST["buscarProyecciones"])) $db->buscarProyecciones();
        if (isset($_POST["buscarEntradas"])) $db->buscarEntradas();

        if (isset($_POST["eliminarPeliculas"])) $db->eliminarPeliculas();
        if (isset($_POST["eliminarCines"])) $db->eliminarCines();
        if (isset($_POST["eliminarSalas"])) $db->eliminarSalas();
        if (isset($_POST["eliminarProyecciones"])) $db->eliminarProyecciones();
        if (isset($_POST["eliminarEntradas"])) $db->eliminarEntradas();

        if (isset($_POST["modificarPeliculas"])) $db->modificarPeliculas();
        if (isset($_POST["modificarCines"])) $db->modificarCines();
        if (isset($_POST["modificarSalas"])) $db->modificarSalas();
        if (isset($_POST["modificarProyecciones"])) $db->modificarProyecciones();
        if (isset($_POST["modificarEntradas"])) $db->modificarEntradas();           

        if (isset($_POST["exportarCSV"])) $db->exportarCSV();
    
        $db->desconectarBaseDatos();
        $_SESSION["db"] = $db;
    }
?>
<!DOCTYPE HTML>

<html lang="es">
<head>    
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
	<meta name ="author" content ="Omar Teixeira González, UO281847" />
	<meta name ="description" content ="Documento PHP realizado para la Tarea 1 del Ejercicio 7 de Computación en el servidor" />
	<meta name ="keywords" content ="html, css, php, mysql, base de datos, cines, peliculas, estudios" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    <title>Ejercicio 7</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio7.css" />
</head>

<body>
    <!-- Datos con el contenidos que aparece en el navegador -->

    <header>
        <h1>Gestor de Cines</h1>
        <nav>
            <a title="Acceda a la carga de datos desde archivos" tabindex="1" accesskey="A" href="#cargar">Cargar datos desde un archivo CSV</a>
            <a title="Acceda a la inserción de datos" tabindex="2" accesskey="I" href="#insertar">Inserción</a>
            <a title="Acceda a la búsqueda de datos" tabindex="3" accesskey="B" href="#buscareliminar">Búsqueda</a>
            <a title="Acceda a la eliminación de datos" tabindex="4" accesskey="E" href="#buscareliminar">Eliminación</a>
            <a title="Acceda a la modificación de datos" tabindex="5" accesskey="M" href="#modificar">Modificación</a>
            <a title="Acceda a la exportación de datos a archivos" tabindex="6" accesskey="D" href="#exportar">Exportar datos a un archivo CSV</a>   
        </nav>
    </header>
    <main>
        <h2>Estado de la acción solicitada</h2>
        <pre><?php echo $_SESSION["db"]->getMsgAviso(); ?></pre>
        <article id="cargar">
            <h3>Cargar datos desde un archivo CSV</h3>
            <aside>
            <section>
                    <h4>Películas</h4>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <label>Cargue el archivo CSV de películas: <input type="file" name="csv" /></label>
                        <input type="submit" value="Presione para cargar el archivo" name="cargarPeliculas" />
                    </form>
                </section>
                <section>
                    <h4>Cines</h4>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <label>Cargue el archivo CSV de cines: <input type="file" name="csv" /></label>
                        <input type="submit" value="Presione para cargar el archivo" name="cargarCines" />
                    </form>
                </section>
                <section>
                    <h4>Salas</h4>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <label>Cargue el archivo CSV de salas: <input type="file" name="csv" /></label>                        
                        <input type="submit" value="Presione para cargar el archivo" name="cargarSalas" />
                    </form>
                </section>
                <section>
                    <h4>Proyecciones</h4>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <label>Cargue el archivo CSV de proyecciones: <input type="file" name="csv" /></label>
                        <input type="submit" value="Presione para cargar el archivo" name="cargarProyecciones" />
                    </form>
                </section>
                <section>
                    <h4>Entradas</h4>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <label>Cargue el archivo CSV de entradas: <input type="file" name="csv" /></label>
                        <input type="submit" value="Presione para cargar el archivo" name="cargarEntradas" />
                    </form>
                </section>
            <aside>
        </article>
        <article id="insertar">
            <h3>Inserción</h3>
            <aside>
                <section>
                    <h4>Películas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código de la película: <input type="text" name="codpelicula" maxlength="4" required /></label>
                            <label>Título de la película: <input type="text" name="titulo" maxlength="20" required /></label>
                            <label>Duración de la película: <input type="number" name="duracion" /></label>
                            <label>Tipo de la película: <input type="text" name="tipo" maxlength="20" required /></label>
                            <label>Código de la película original: <input type="text" name="codpeliculaOriginal" maxlength="4" /></label>
                        </fieldset>
                        <input type="submit" value="Presione para insertar" name="insertarPeliculas" />
                    </form>
                </section>
                <section>
                    <h4>Cines</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código del cine: <input type="text" name="codcine" maxlength="4" required /></label>
                            <label>Localidad del cine: <input type="text" name="localidad" maxlength="20" required /></label>
                        </fieldset>
                        <input type="submit" value="Presione para insertar" name="insertarCines" />
                    </form>
                </section>
                <section>
                    <h4>Salas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>
                            <label>Código del cine: <input type="text" name="codcine" maxlength="4" required /></label>
                            <label>Aforo de la sala: <input type="number" name="aforo" required /></label>
                        </fieldset>
                        <input type="submit" value="Presione para insertar" name="insertarSalas" />
                    </form>
                </section>
                <section>
                    <h4>Proyecciones</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código de la película: <input type="text" name="codpelicula" maxlength="4" required /></label>
                            <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>
                            <label>Sesión de la proyección: <input type="number" name="sesion" max="10" required /></label>
                            <label>Fecha de la proyección: <input type="date" name="fecha" required /></label>
                            <label>Entradas vendidas de la proyección: <input type="number" name="entradasVendidas" required /></label>
                        </fieldset>
                        <input type="submit" value="Presione para insertar" name="insertarProyecciones" />
                    </form>
                </section>
                <section>
                    <h4>Entradas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código de la entrada: <input type="text" name="codentrada" maxlength="4" required /></label>
                            <label>Precio de la entrada: <input type="number" name="precio" required /></label>
                            <label>Código de la película: <input type="text" name="codpelicula" maxlength="4" required /></label>
                            <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>
                            <label>Sesión de la proyección: <input type="number" name="sesion" max="10" required /></label>
                            <label>Fecha de la proyección: <input type="date" name="fecha" required /></label>
                        </fieldset>
                        <input type="submit" value="Presione para insertar" name="insertarEntradas" />
                    </form>
                </section>
            </aside>
        </article>
        <article id="buscareliminar">
            <h3>Búsqueda y Eliminación</h3>
            <aside>
                <section>
                    <h4>Películas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código de la película: <input type="text" name="codpelicula" maxlength="4" required /></label>                        
                        </fieldset>
                        <input type="submit" value="Presione para buscar" name="buscarPeliculas" />
                        <input type="submit" value="Presione para eliminar" name="eliminarPeliculas" />
                    </form>
                </section>
                <section>
                    <h4>Cines</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código del cine: <input type="text" name="codcine" maxlength="4" required /></label>                        
                        </fieldset>
                        <input type="submit" value="Presione para buscar" name="buscarCines" />
                        <input type="submit" value="Presione para eliminar" name="eliminarCines" />
                    </form>
                </section>
                <section>
                    <h4>Salas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>
                            <label>Código del cine: <input type="text" name="codcine" maxlength="4" required /></label>                                      
                        </fieldset>
                        <input type="submit" value="Presione para buscar" name="buscarSalas" />
                        <input type="submit" value="Presione para eliminar" name="eliminarSalas" />
                    </form>
                </section>
                <section>
                    <h4>Proyecciones</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código de la película: <input type="text" maxlength="4" name="codpelicula" required /></label>
                            <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>                      
                            <label>Sesión de la proyección: <input type="number" name="sesion" max="10" required /></label> 
                            <label>Fecha de la proyección: <input type="date" name="fecha" required /></label>
                        </fieldset>               
                        <input type="submit" value="Presione para buscar" name="buscarProyecciones" />
                        <input type="submit" value="Presione para eliminar" name="eliminarProyecciones" />
                    </form>
                </section>
                <section>
                    <h4>Entradas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>
                            <label>Código de la entrada: <input type="text" name="codentrada" maxlength="4" required /></label>             
                        </fieldset>
                        <input type="submit" value="Presione para buscar" name="buscarEntradas" />
                        <input type="submit" value="Presione para eliminar" name="eliminarEntradas" />
                    </form>
                </section>
            </aside>
        </article>
        <article id="modificar">
            <h3>Modificación</h3>
            <aside>
                <section>
                    <h4>Películas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>                            
                            <label>Título de la película: <input type="text" name="titulo" maxlength="20" required /></label>
                            <label>Duración de la película: <input type="number" name="duracion" /></label>
                            <label>Tipo de la película: <input type="text" name="tipo" maxlength="20" required /></label>
                            <label>Código de la película original: <input type="text" name="codpeliculaOriginal" maxlength="4" /></label>
                        </fieldset>
                        <label>Código de la película: <input type="text" name="codpelicula" maxlength="4" required /></label>
                        <input type="submit" value="Presione para modificar" name="modificarPeliculas" />
                    </form>
                </section>
                <section>
                    <h4>Cines</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>                            
                            <label>Localidad del cine: <input type="text" name="localidad" maxlength="20" required /></label>
                        </fieldset>
                        <label>Código del cine: <input type="text" name="codcine" maxlength="4" required /></label>
                        <input type="submit" value="Presione para modificar" name="modificarCines" />
                    </form>
                </section>
                <section>
                    <h4>Salas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>                            
                            <label>Aforo de la sala: <input type="number" name="aforo" required /></label>
                        </fieldset>
                        <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>
                        <input type="submit" value="Presione para modificar" name="modificarSalas" />
                    </form>
                </section>
                <section>
                    <h4>Proyecciones</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>                            
                            <label>Entradas vendidas de la proyección: <input type="number" name="entradasVendidas" required /></label>
                        </fieldset>
                        <label>Código de la película: <input type="text" name="codpelicula" maxlength="4" required /></label>
                        <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>
                        <label>Sesión de la proyección: <input type="number" name="sesion" max="10" required /></label>
                        <label>Fecha de la proyección: <input type="date" name="fecha" required /></label>
                        <input type="submit" value="Presione para modificar" name="modificarProyecciones" />
                    </form>
                </section>
                <section>
                    <h4>Entradas</h4>
                    <form action="#" method="POST">
                        <fieldset>
                            <legend>Datos</legend>                            
                            <label>Precio de la entrada: <input type="number" name="precio" required /></label>
                            <label>Código de la película: <input type="text" name="codpelicula" maxlength="4" required /></label>
                            <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>
                            <label>Sesión de la proyección: <input type="number" name="sesion" max="10" required /></label>
                            <label>Fecha de la proyección: <input type="date" name="fecha" required /></label>
                        </fieldset>
                        <label>Código de la entrada: <input type="text" name="codentrada" maxlength="4" required /></label>
                        <input type="submit" value="Presione para modificar" name="modificarEntradas" />
                    </form>
                </section>
            </aside>
        </article>
        <article id="exportar">
            <h3>Exportar datos a archivos CSV</h3>
            <form action="#" method="POST">
                <input type="submit" value="Presione para exportar los archivos" name="exportarCSV" />
            </form>
        </article>
    </main>
</body>
</html>