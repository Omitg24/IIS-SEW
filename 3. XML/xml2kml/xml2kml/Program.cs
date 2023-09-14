using System;
using System.IO;
using System.Xml;

namespace xml2kml
{
    /// <summary>
    /// Clase Program
    /// </summary>
    static class Program
    {
        /// <summary>
        /// Atributo KML
        /// </summary>
        private static string kml = 
@"<?xml version=""1.0"" encoding=""UTF-8""?>
<kml xmlns = ""http://www.opengis.net/kml/2.2"">
<Document>
<name> Social Network - Coordinates</name>
";

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
                    Console.WriteLine("Para ejecutar este programa debe indicar un fichero de entrada xml y el fichero de salida kml.");
                    Console.WriteLine("La forma correcta es: ");
                    Console.WriteLine("\n\t xml2kml.exe <archivoEntrada> <archivoSalida>");
                    return;
                }

                String xmlFileName = args[0];
                String htmlFileName = args[1];
                String name = "";
                String surname = "";
                String length = "";
                String latitude = "";

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
                                case "nombre":
                                    name = xml.ReadElementContentAsString();
                                    break;
                                case "apellidos":
                                    surname = xml.ReadElementContentAsString();
                                    break;
                                case "lnacimiento":
                                    kml += "\n\t<Placemark>\n";
                                    kml += "\t<Style>\n\t<IconStyle>\n\t\t<Icon>\n";
                                    kml += "\t\t\t<href>http://www.google.com/intl/en_us/mapfiles/ms/icons/red-dot.png</href>\n";
                                    kml += "\t\t</Icon>\n\t</IconStyle>\n\t</Style >\n";
                                    kml += $"\t<description>Lugar de nacimiento de {name} {surname}</description>\n";
                                    break;
                                case "lresidencia":                                    
                                    kml += "\n\t<Placemark>\n";
                                    kml += "\t<Style>\n\t<IconStyle>\n\t\t<Icon>\n";
                                    kml += "\t\t\t<href>http://www.google.com/intl/en_us/mapfiles/ms/icons/yellow-dot.png</href>\n";
                                    kml += "\t\t</Icon>\n\t</IconStyle>\n\t</Style >\n";
                                    kml += $"\t<description>Lugar de residencia de {name} {surname}</description>\n";
                                    break;
                                case "lnombre":
                                    kml += $"\t<name>{xml.ReadElementContentAsString()}</name>\n";
                                    break;
                                case "coordenadas":
                                    kml += $"\t\t<Point>\n\t\t\t<coordinates>";
                                    break;
                                case "longitud":
                                    length = xml.ReadElementContentAsString();
                                    break;
                                case "latitud":
                                    latitude = xml.ReadElementContentAsString();
                                    break;
                                case "altitud":
                                    kml += $"{latitude},{length},{xml.Value}";
                                    break;
                            }
                            break;
                        //En caso de ser un cierre de elemento
                        case XmlNodeType.EndElement:
                            if (xml.Name == "lnacimiento" || xml.Name == "lresidencia")
                            {
                                kml += "\t</Placemark>";
                            }
                            if (xml.Name == "coordenadas")
                            {
                                kml += "</coordinates>\n\t\t</Point>\n";
                            }
                            break;
                    }
                }
                //Se cierra el kml
                kml += "\n</Document>";
                kml += "\n</kml>";                
                //Console.WriteLine(kml);
                //Se avisa por consola de que se ha convertido el archivo
                Console.WriteLine("El archivo XML se ha convertido a KML");
                //Se genera el archivo
                File.WriteAllText(htmlFileName, kml.BeautifyKML());
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
                Console.WriteLine("\nGracias por usar xml2kml");
                Console.WriteLine("Versión 1.0, a día: 04/11/2022");
                Console.WriteLine("Autor: Omar Teixeira González, UO281847");        
            }
        }

        /// <summary>
        /// Método BeautifyKML
        /// Formatea el fichero kml pasado por parámetro
        /// </summary>
        /// <param name="html"></param>
        /// <returns></returns>
        private static string BeautifyKML(this string kml)
        {
            return System.Xml.Linq.XElement.Parse(kml).ToString();
        }
    }
}
