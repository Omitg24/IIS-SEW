using System;
using System.IO;
using System.Xml;

namespace xml2html
{
    /// <summary>
    /// Clase Program
    /// </summary>
    static class Program
    {
        /// <summary>
        /// Atributo HTML
        /// </summary>
        private static string html = @" 
<html lang=""es""> 
<head> 
    <!-- Datos que describen el documento -->
    <meta charset=""UTF-8"" />
    <meta name = ""author"" content=""Omar Teixeira González, UO281847"" />
    <meta name = ""description"" content=""Red Social realizada en XMl y transformada a HTML mediante el programa realizado en la tarea 1"" />
    <meta name = ""keywords"" content=""red social, datos, persona"" />
    <meta name = ""viewport"" content=""width=device-width, initial-scale=1.0"" />
    <title>Social Network</title>
    <link rel = ""stylesheet"" type=""text/css"" href=""estilo/estilo.css"" />
    <link rel = ""stylesheet"" type=""text/css"" href=""estilo/layout.css"" />
</head>    
<body>
    <!-- Datos con el contenidos que aparece en el navegador -->
    <header>
        <h1> Social Network </h1>
    </header>
    <main>";

        /// <summary>
        /// Método Main
        /// Ejecuta la acción principal
        /// </summary>
        /// <param name="args"></param>
        static void Main(string[] args)
        {
            try
            {
                if (args.Length != 2)
                {
                    Console.WriteLine("ERROR: No se ha utilizado el formato correcto.");
                    Console.WriteLine("Para ejecutar este programa debe indicar un fichero de entrada xml y el fichero de salida html.");
                    Console.WriteLine("La forma correcta es: ");
                    Console.WriteLine("\n\t xml2html.exe <archivoEntrada> <archivoSalida>");
                    return;
                }

                String xmlFileName = args[0];
                String htmlFileName = args[1];
                String description = "";

                //Se lee el xml
                XmlReader xml = XmlReader.Create(xmlFileName);
                //Se itera a lo largo del xml
                while (xml.Read())
                {   
                    //Se comprueba el tipo de nodo que es
                    switch (xml.NodeType)
                    {   
                        //En caso de ser un elemento
                        case XmlNodeType.Element:
                            //Se comprueba el elemento que es
                            switch (xml.Name.ToString())
                            {
                                case "persona":
                                    html += $"\n\t\t<section>\n\t\t<h2>{xml.Name.ToString().ToUpperCase()}</h2>\n";
                                    break;
                                case "datos":
                                    html += $"\t\t<h3>{xml.Name.ToString().ToUpperCase()}</h3>\n";
                                    break;
                                case "nombre":
                                    html += $"\t\t<h4>{xml.Name.ToString().ToUpperCase()}</h4>\n";
                                    break;
                                case "apellidos":
                                    html += $"\t\t<h4>{xml.Name.ToString().ToUpperCase()}</h4>\n";
                                    break;
                                case "fnacimiento":
                                    html += $"\t\t<h4>{xml.Name.ToString().ToUpperCase()}</h4>\n";
                                    break;
                                case "lnacimiento":
                                    html += $"\t\t<h4>{xml.Name.ToString().ToUpperCase()}</h4>\n";
                                    break;
                                case "lresidencia":
                                    html += $"\t\t<h4>{xml.Name.ToString().ToUpperCase()}</h4>\n";
                                    break;
                                case "fotografias":
                                    html += $"\t\t<h4>{xml.Name.ToString().ToUpperCase()}</h4>\n";
                                    break;
                                case "videos":
                                    html += $"\t\t<h4>{xml.Name.ToString().ToUpperCase()}</h4>\n";
                                    break;
                                case "comentario":
                                    html += $"\t\t<h4>{xml.Name.ToString().ToUpperCase()}</h4>\n";
                                    break;
                                case "lnombre":
                                    html += $"\t\t<h5>{xml.Name.ToString().ToUpperCase()}</h5>\n";
                                    break;
                                case "coordenadas":
                                    html += $"\t\t<h5>{xml.Name.ToString().ToUpperCase()}</h5>\n";
                                    break;
                                case "fotografia":
                                    html += $"\t\t<h5>{xml.Name.ToString().ToUpperCase()}</h5>\n";
                                    while (xml.MoveToNextAttribute())
                                    {
                                        description = xml.Value;
                                        html += $"\t\t<h6>{xml.Name.ToString().ToUpperCase()}</h6>\n";
                                        html += $"\t\t<p>{description}</p>\n";                                        
                                    }
                                    break;
                                case "video":
                                    html += $"\t\t<h5>{xml.Name.ToString().ToUpperCase()}</h5>\n";
                                    while (xml.MoveToNextAttribute())
                                    {
                                        description = xml.Value;
                                        html += $"\t\t<h6>{xml.Name.ToString().ToUpperCase()}</h6>\n";
                                        html += $"\t\t<p>{description}</p>\n";
                                    }
                                    break;
                                case "enlace":
                                    html += $"\t\t<h6>{xml.Name.ToString().ToUpperCase()}</h6>\n";
                                    break;
                                case "longitud":
                                    html += $"\t\t<h6>{xml.Name.ToString().ToUpperCase()}</h6>\n";
                                    break;
                                case "latitud":
                                    html += $"\t\t<h6>{xml.Name.ToString().ToUpperCase()}</h6>\n";
                                    break;
                                case "altitud":
                                    html += $"\t\t<h6>{xml.Name.ToString().ToUpperCase()}</h6>\n";
                                    break;
                            }
                            break;
                        //En caso de ser un cierre de elemento
                        case XmlNodeType.EndElement:
                            if (xml.Name == "datos")
                            {
                                html += "\t\t</section>";
                            }
                            break;
                        //En caso de ser el texto de un elemento
                        case XmlNodeType.Text:
                            String value = xml.Value;
                            //Si es una imagen, muestrala en el html
                            if (value.Substring(Math.Max(0, value.Length - 4)).Equals(".png"))
                            {
                                html += $"\t\t<img alt = \"{description}\" src = \"multimedia/{value}\"/>\n";
                            //Si es un video, añade un enlace en el html
                            } else if (value.Contains("youtube.com")){
                                html += $"\t\t<a title=\"{description}\" href=\"{value}\">Enlace al video</a>\n";
                            //En caso contrario, es un párrafo
                            } else {
                                html += $"\t\t<p>{value}</p>\n";
                            }
                            break;
                    }
                }
                //Se cierra el hmtl
                html += "\n\t</main>";
                html += "\n</body>";
                html += "\n</html>";
                //Console.WriteLine(html);
                //Se avisa por consola de que se ha convertido el archivo
                Console.WriteLine("El archivo XML se ha convertido a HTML");
                //Se genera el archivo
                File.WriteAllText(htmlFileName, html.BeautifyHTML());
            }
            //En caso de que no se haya encontrado el archivo
            catch (FileNotFoundException)
            {
                Console.WriteLine("ERROR: No se ha encontrado el archivo {0}", args[0]);
                Console.WriteLine("Por favor, inténtelo de nuevo");
            }
            //En caso de que ocurra una excepción general
            catch (Exception e)
            {
                Console.WriteLine("Error no documentado: " + e);
            }
            //Mostrar los siguientes mensajes pase lo que pase
            finally
            {
                Console.WriteLine("\nGracias por usar xml2html");
                Console.WriteLine("Versión 1.0, a día: 02/11/2022");
                Console.WriteLine("Autor: Omar Teixeira González, UO281847");        
            }
        }

        /// <summary>
        /// Método ToUpperCase
        /// Convierte la primera letra del String pasado como parámetro
        /// </summary>
        /// <param name="cad"></param>
        /// <returns></returns>
        private static string ToUpperCase(this string cad)
        {
            var firstChar = cad[0].ToString().ToUpper();
            cad = cad.Substring(1);
            return firstChar + cad;
        }

        /// <summary>
        /// Método BeautifyHTML
        /// Formatea el fichero html pasado por parámetro
        /// </summary>
        /// <param name="html"></param>
        /// <returns></returns>
        private static string BeautifyHTML(this string html)
        {
            return $"<!DOCTYPE HTML>\n\n{System.Xml.Linq.XElement.Parse(html).ToString()}";
        }
    }
}
