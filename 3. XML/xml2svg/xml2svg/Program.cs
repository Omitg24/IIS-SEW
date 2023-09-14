using System;
using System.Xml;
using System.IO;
using System.Collections;

namespace xml2svg
{
    /// <summary>
    /// Clase Program
    /// </summary>
    static class Program
    {
        /// <summary>
        /// Clase Node
        /// </summary>
        private class Node
        {
            /// <summary>
            /// Atributo x
            /// </summary>
            public int x { get; set; }
            /// <summary>
            /// Atributo y
            /// </summary>
            public int y { get; set; }

            /// <summary>
            /// Constructor de la clase Node
            /// </summary>
            /// <param name="x"></param>
            /// <param name="y"></param>
            public Node(int x, int y)
            {
                this.x = x;
                this.y = y;
            }
        }

        /// <summary>
        /// Atributos x, y, rx, ry
        /// </summary>
        private static int x, y, rx, ry;
        /// <summary>
        /// Constante elementWidth
        /// </summary>
        private const int elementWidth = 425;
        /// <summary>
        /// Constante elementHeight
        /// </summary>
        private const int elementHeight = 700;
        /// <summary>
        /// Constante elementSpacingX
        /// </summary>
        private const int elementSpacingX = 475;
        /// <summary>
        /// Constante elementSpacingY
        /// </summary>
        private const int elementSpacingY = 750;
        /// <summary>
        /// Constante parents
        /// </summary>
        private static Stack parents;

        /// <summary>
        /// Método Main
        /// Ejecuta la acción del programa
        /// </summary>
        /// <param name="args"></param>
        public static void Main(string[] args)
        {
            try
            {
                if (args.Length != 2)
                {
                    Console.WriteLine("ERROR: No se ha utilizado el formato correcto.");
                    Console.WriteLine("Para ejecutar este programa debe indicar un fichero de entrada xml y el fichero de salida svg.");
                    Console.WriteLine("La forma correcta es: ");
                    Console.WriteLine("\n\t xml2svg.exe <archivoEntrada> <archivoSalida>");
                    return;
                }

                String xmlFileName = args[0];
                String svgFileName = args[1];
                x = 20;
                y = 20;
                rx = 10;
                ry = 10;
                parents = new Stack();

                //Se lee el xml
                XmlTextReader xmlReader = new XmlTextReader(xmlFileName);
                //Se prepara para escribir
                XmlWriter xmlWriter = XmlWriter.Create(svgFileName);
                int height = CalculateHeight(xmlFileName);
                WriteSVGHeader(xmlWriter, height);
                int attributeY = y;
                //Se itera a lo largo del xml
                while (xmlReader.Read())
                {
                    //Se comprueba el tipo de nodo que es
                    switch (xmlReader.NodeType)
                    {
                        //En caso de ser un elemento
                        case XmlNodeType.Element:
                            //Se comprueba el elemento que es
                            switch (xmlReader.Name.ToString())
                            {
                                case "persona":
                                    attributeY = y;
                                    WriteElement(xmlWriter, xmlReader.Name);
                                    parents.Push(new Node(x, y));
                                    x += elementSpacingX;
                                    while (xmlReader.MoveToNextAttribute())
                                    {
                                        attributeY += 10;
                                        WriteAttribute(xmlWriter, xmlReader.Name, xmlReader.Value, attributeY);
                                    }
                                    attributeY += 25;
                                    break;
                                case "datos":
                                    WriteDataElement(xmlWriter, xmlReader.Name, attributeY);
                                    attributeY += 30;
                                    //Se leen los subelementos del elemento Datos
                                    XmlReader xmlDataReader = xmlReader.ReadSubtree();
                                    //Se itera a lo largo de los subelementos de datos del xml
                                    while (xmlDataReader.Read())
                                    {
                                        //Se comprueba el tipo de nodo que es
                                        switch (xmlDataReader.NodeType)
                                        {
                                            //En caso de ser un elemento
                                            case XmlNodeType.Element:
                                                attributeY += 10;
                                                WriteDataSubelement(xmlWriter, xmlDataReader.Name, attributeY);                                                
                                                while (xmlDataReader.MoveToNextAttribute())
                                                {
                                                    attributeY += 10;
                                                    WriteDataAttribute(xmlWriter, xmlDataReader.Name, xmlDataReader.Value, attributeY);
                                                    attributeY += 5;
                                                }
                                                break;
                                            //En caso de ser el texto de un elemento
                                            case XmlNodeType.Text:                                                
                                                WriteValue(xmlWriter, xmlDataReader.Value, attributeY);
                                                attributeY += 10;
                                                break;
                                        }                                        
                                    }
                                    break;
                            }
                            break;
                        //En caso de ser un cierre de elemento
                        case XmlNodeType.EndElement:
                            if (xmlReader.Name == "persona")
                            {
                                x -= elementSpacingX;
                                y += elementSpacingY;
                                parents.Pop();
                            }
                            break;
                    }
                }
                xmlWriter.WriteEndDocument();
                xmlWriter.Close();
                Console.WriteLine("El archivo XML se ha convertido a SVG");
            }
            //En caso de que no se haya encontrado el archivo
            catch (FileNotFoundException)
            {
                Console.WriteLine("ERROR: No se ha encontrado el archivo {0}", args[0]);
                Console.WriteLine("Por favor, inténtelo de nuevo");
            }
            //En caso de que el argumento sea null
            catch (ArgumentNullException)
            {
                Console.WriteLine("ERROR: No se ha utilizado el formato correcto:");
                Console.WriteLine("La forma correcta es: ");
                Console.WriteLine("\n\t xml2kml <archivoEntrada> <archivoSalida>");
            }
            //En caso de que ocurra una excepción general
            catch (Exception e)
            {
                Console.WriteLine("Error no documentado: " + e);
            }
            //Mostrar los siguientes mensajes pase lo que pase
            finally
            {
                Console.WriteLine("\nGracias por usar xml2svg");
                Console.WriteLine("Versión 1.0, a día: 06/11/2022");
                Console.WriteLine("Autor: Omar Teixeira González, UO281847");
            }
        }

        /// <summary>
        /// Método CalculateHeight
        /// Calcula la altura del header
        /// </summary>
        /// <param name="xmlFile"></param>
        /// <returns></returns>
        private static int CalculateHeight(String xmlFile)
        {
            //Se lee el xml
            XmlTextReader reader = new XmlTextReader(xmlFile);
            int height = y;
            //Se itera a lo largo del xml
            while (reader.Read())
            {
                //Se comprueba el tipo de nodo que es
                switch (reader.NodeType)
                {
                    //En caso de ser un elemento
                    case XmlNodeType.Element: 
                        if (reader.Name == "persona")
                        {
                            height += elementSpacingY;
                        }
                        break;
                }
            }
            reader.ResetState();
            return height;
        }

        /// <summary>
        /// Método WriteSVGHeader
        /// Escribe la cabecera del SVG
        /// </summary>
        /// <param name="xmlWriter"></param>
        /// <param name="height"></param>
        private static void WriteSVGHeader(XmlWriter xmlWriter, int height)
        {
            xmlWriter.WriteStartDocument();
            xmlWriter.WriteStartElement("svg", "http://www.w3.org/2000/svg");
            xmlWriter.WriteAttributeString("width", "auto");
            xmlWriter.WriteAttributeString("height", height + "");
            xmlWriter.WriteAttributeString("style", "overflow:visible ");
            xmlWriter.WriteAttributeString("version", "1.1");
        }

        /// <summary>
        /// Método WriteElement
        /// Escribe los elementos del SVG y las lineas que lo unen
        /// </summary>
        /// <param name="xmlWriter"></param>
        /// <param name="elementValue"></param>
        private static void WriteElement(XmlWriter xmlWriter, string elementValue)
        {
            xmlWriter.WriteStartElement("rect");
            xmlWriter.WriteAttributeString("x", x.ToString());
            xmlWriter.WriteAttributeString("y", y.ToString());
            xmlWriter.WriteAttributeString("rx", rx.ToString());
            xmlWriter.WriteAttributeString("ry", ry.ToString());
            xmlWriter.WriteAttributeString("width", elementWidth.ToString());
            xmlWriter.WriteAttributeString("height", elementHeight.ToString());
            xmlWriter.WriteAttributeString("style", "fill:#a7c4f2;stroke:#000424;stroke-width:2.5");
            xmlWriter.WriteEndElement();
            xmlWriter.WriteStartElement("text");
            xmlWriter.WriteAttributeString("x", (x + 15).ToString());
            xmlWriter.WriteAttributeString("y", (y + 15).ToString());
            xmlWriter.WriteAttributeString("font-size", "16");
            xmlWriter.WriteAttributeString("style", "fill:#001433; font-weight:bold");
            xmlWriter.WriteString(elementValue.ToUpperCase());
            xmlWriter.WriteEndElement();
            if (parents.Count != 0)
            {
                Node parent = (Node)parents.Peek();
                xmlWriter.WriteStartElement("path");
                int startX = (parent.x + elementWidth);
                int startY = (parent.y + elementHeight / 2);
                int finalX = x;
                int finalY = (y + elementHeight / 2);
                xmlWriter.WriteAttributeString("d", "M" + startX + " " + startY + " C" + finalX + " " + startY + " " + startX + " " + finalY + " " + finalX + " " + finalY);
                xmlWriter.WriteAttributeString("style", "fill:transparent;stroke:#000424");
                xmlWriter.WriteEndElement();
            }
        }

        /// <summary>
        /// Método WriteDataElement
        /// Escribe el elemento datos en el elemento persona
        /// </summary>
        /// <param name="xmlWriter"></param>
        /// <param name="elementName"></param>
        /// <param name="attributeY"></param>
        private static void WriteDataElement(XmlWriter xmlWriter, string elementName, int attributeY)
        {
            Node current = (Node) parents.Peek();
            xmlWriter.WriteStartElement("rect");            
            xmlWriter.WriteAttributeString("x", current.x.ToString());
            xmlWriter.WriteAttributeString("y", (current.y + elementHeight / 8).ToString());
            xmlWriter.WriteAttributeString("rx", rx.ToString());
            xmlWriter.WriteAttributeString("ry", ry.ToString());            
            xmlWriter.WriteAttributeString("width", elementWidth.ToString());
            xmlWriter.WriteAttributeString("height", (elementHeight / 8 * 7 + 5).ToString());
            xmlWriter.WriteAttributeString("style", "fill:#85b1ff;stroke:black;stroke-width:2");
            xmlWriter.WriteEndElement();
            xmlWriter.WriteStartElement("text");
            xmlWriter.WriteAttributeString("x", (current.x + 15).ToString());
            xmlWriter.WriteAttributeString("y", (attributeY + 15).ToString());
            xmlWriter.WriteAttributeString("font-size", "14");
            xmlWriter.WriteAttributeString("style", "fill:#00033d; font-weight:bold");
            xmlWriter.WriteString(elementName.ToUpperCase());
            xmlWriter.WriteEndElement();
        }

        /// <summary>
        /// Método WriteDataSubelement
        /// Escribe los subelementos del elemento datos dentro del elemento persona,
        /// haciendo que quede estructurado en un único bloque
        /// </summary>
        /// <param name="xmlWriter"></param>
        /// <param name="elementName"></param>
        /// <param name="attributeY"></param>
        private static void WriteDataSubelement(XmlWriter xmlWriter, string elementName, int attributeY)
        {
            if (elementName != "datos")
            {
                Node current = (Node)parents.Peek();
                xmlWriter.WriteStartElement("text");
                xmlWriter.WriteAttributeString("x", ((current.x) + 15).ToString());
                xmlWriter.WriteAttributeString("y", (attributeY + 15).ToString());
                xmlWriter.WriteAttributeString("font-size", "12");
                xmlWriter.WriteAttributeString("style", "fill:#080b3d; font-weight:bold");
                xmlWriter.WriteString($"{elementName.ToUpperCase()}:");
                xmlWriter.WriteEndElement();
            }            
        }

        /// <summary>
        /// Método WriteValue
        /// Escribe el valor de los subelementos de datos
        /// </summary>
        /// <param name="xmlWriter"></param>
        /// <param name="text"></param>
        /// <param name="attributeY"></param>
        private static void WriteValue(XmlWriter xmlWriter, string text, int attributeY)
        {
            Node current = (Node)parents.Peek();            
            xmlWriter.WriteStartElement("text");
            xmlWriter.WriteAttributeString("x", ((current.x + elementWidth / 3) + 15).ToString());
            xmlWriter.WriteAttributeString("y", (attributeY + 15).ToString());
            xmlWriter.WriteAttributeString("font-size", "12");
            xmlWriter.WriteAttributeString("style", "fill:black");
            xmlWriter.WriteString(text);
            xmlWriter.WriteEndElement();
        }

        /// <summary>
        /// Método WriteAttribute
        /// Escribe los atributos de los elementos
        /// </summary>
        /// <param name="xmlWriter"></param>
        /// <param name="attributeName"></param>
        /// <param name="attributeValue"></param>
        /// <param name="attributeY"></param>
        private static void WriteAttribute(XmlWriter xmlWriter, string attributeName, string attributeValue, int attributeY)
        {
            Node current = (Node)parents.Peek();
            xmlWriter.WriteStartElement("text");
            xmlWriter.WriteAttributeString("x", ((current.x) + 20).ToString());
            xmlWriter.WriteAttributeString("y", (attributeY + 15).ToString());
            xmlWriter.WriteAttributeString("font-size", "10");
            xmlWriter.WriteAttributeString("style", "fill:black");
            xmlWriter.WriteString($"{attributeName}: {attributeValue}");
            xmlWriter.WriteEndElement();
        }

        /// <summary>
        /// Método WriteDataAttribute
        /// Escribe los atributos de los subelementos de datos
        /// </summary>
        /// <param name="xmlWriter"></param>
        /// <param name="attributeName"></param>
        /// <param name="attributeValue"></param>
        /// <param name="attributeY"></param>
        private static void WriteDataAttribute(XmlWriter xmlWriter, string attributeName,string attributeValue, int attributeY)
        {
            string text = $"{attributeName}: {attributeValue}";
            if (text != "xmlns: https://uniovi.es")
            {
                Node current = (Node)parents.Peek();
                xmlWriter.WriteStartElement("text");
                xmlWriter.WriteAttributeString("x", ((current.x) + 20).ToString());
                xmlWriter.WriteAttributeString("y", (attributeY + 15).ToString());
                xmlWriter.WriteAttributeString("font-size", "10");
                xmlWriter.WriteAttributeString("style", "fill:black; font-style:italic");
                xmlWriter.WriteString(text);
                xmlWriter.WriteEndElement();
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
    }
}