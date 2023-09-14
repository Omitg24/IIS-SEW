using System;
using System.Collections.Generic;
using System.IO;
using System.Xml;

namespace xbrlManagement
{
    /// <summary>
    /// Clase Program
    /// 
    /// Desarollado por Omar Teixeira González, UO281847
    /// </summary>
    internal class Program
    {
        /// <summary>
        /// Atributo contexts
        /// </summary>
        private static List<Context> contexts;

        /// <summary>
        /// Atributo isNotFinished
        /// </summary>
        private static bool isNotFinished;

        /// <summary>
        /// Método main
        /// </summary>
        /// <param name="args">Lista de argumentos</param>
        static void Main(string[] args)
        {
            try
            {
                if (args.Length != 1)
                {
                    Console.WriteLine("ERROR: No se ha utilizado el formato correcto.");
                    Console.WriteLine("Para ejecutar este programa debe indicar un fichero de entrada xml.");
                    Console.WriteLine("La forma correcta es: ");
                    Console.WriteLine("\n\t xbrlManagement.exe <archivoEntrada>");
                    return;
                }

                string xbrlFileName = args[0];
                isNotFinished = true;

                contexts = LoadXBRLIntoContexts(xbrlFileName);
                Initialize();
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
                Console.WriteLine("\nGracias por usar xbrlManagement");
                Console.WriteLine("Versión 1.0, a día: 06/11/2022");
                Console.WriteLine("Autor: Omar Teixeira González, UO281847");
            }
        }

        /// <summary>
        /// Método Initialize
        /// Inicializa la aplicación
        /// </summary>
        public static void Initialize()
        {
            while (isNotFinished)
            {
                LoadMenu();
            }            
        }

        /// <summary>
        /// Método LoadMenu
        /// Método que carga el menú
        /// </summary>
        private static void LoadMenu()
        {
            Console.WriteLine("Listar contextos:\n");            
            Console.WriteLine("\t1 -> Listar todos los contextos");
            Console.WriteLine("\t2 -> Listar contextos por id del contexto general");
            Console.WriteLine("\t3 -> Listar contextos por id del contexto concreto");
            Console.WriteLine("\t4 -> Listar contextos por miembro explícito");
            Console.WriteLine("\t5 -> Listar contextos por fecha concreta");
            Console.WriteLine("\t6 -> Listar contextos por rango de fechas");
            Console.WriteLine("\n\t0 -> Salir");

            Console.Write("\nPor favor, seleccione una opción: ");
            string selectedOption = Console.ReadLine();
            Console.WriteLine();
            ProcessOption(selectedOption);
        }

        /// <summary>
        /// Método ProcessOption
        /// Procesa la opción que se ha seleccionado anteriormente
        /// 
        /// </summary>
        /// <param name="selectedOption"></param>
        private static void ProcessOption(string selectedOption)
        {
            //Comprueba la opción seleccionada
            switch (selectedOption)
            {
                //Salir de la aplicación
                case "0":
                    isNotFinished = false;
                    break;
                //Mostrar todos los contextos
                case "1":
                    ListAllContexts();
                    break;
                //Mostrar los contextos por id de forma general
                case "2":
                    Console.Write("Introduzca el id de los contextos a buscar: ");
                    string generalContextId = Console.ReadLine();
                    ListContextsByGeneralContextId(generalContextId);
                    break;
                //Mostrar los contextos por id de forma específica
                case "3":
                    Console.Write("Introduzca el id del contexto a buscar: ");
                    string especificContextId = Console.ReadLine();
                    ListContextBySpecificContextId(especificContextId);
                    break;
                //Mostrar los contextos por miembro explícito
                case "4":
                    Console.Write("Introduzca el miembro explícito de los contextos a buscar: ");
                    string explicitMember = Console.ReadLine();
                    ListContextsByExplicitMember(explicitMember);
                    break;
                //Mostrar los contextos por fecha concreta
                case "5":
                    Console.Write("Introduzca la fecha (yyyy-MM-dd) concreta del contexto a buscar: ");
                    DateTime date = DateTime.Parse(Console.ReadLine());
                    ListContextsByDateInstant(date);
                    break;
                //Mostrar los contextos por rango de fechas
                case "6":
                    Console.Write("Introduzca la fecha (yyyy-MM-dd) de inicio del contexto a buscar: ");
                    DateTime startDate = DateTime.Parse(Console.ReadLine());
                    Console.Write("Introduzca la fecha (yyyy-MM-dd) de finalización del contexto a buscar: ");
                    DateTime endDate = DateTime.Parse(Console.ReadLine());
                    ListContextsByDateRange(startDate, endDate);
                    break;
            }
        }

        /// <summary>
        /// Método ListAllContexts
        /// Muestra una lista con todos los contextos
        /// </summary>
        private static void ListAllContexts()
        {
            Printer.PrintContextsList(contexts);
        }

        /// <summary>
        /// Método ListContextByGeneralContextId
        /// Muestra una lista de los contextos que contienen el id del contexto pasado por parámetro
        /// 
        /// </summary>
        /// <param name="cId"></param>
        private static void ListContextsByGeneralContextId(string cId)
        {
            List<Context> filteredContexts = new List<Context>();            

            foreach (var context in contexts)
            {
                if (context.ContextId.Contains(cId))
                {
                    filteredContexts.Add(context);
                }
            }
            Console.WriteLine();
            Printer.PrintContextsList(filteredContexts);
        }

        /// <summary>
        /// Método ListContextBySpecificContextId
        /// Muestra el contexto que tiene el id del contexto pasado por parámetro
        /// 
        /// </summary>
        /// <param name="cId">Id del contexto</param>
        private static void ListContextBySpecificContextId(string cId)
        {
            foreach (var context in contexts)
            {
                if (context.ContextId.Equals(cId))
                {
                    Console.WriteLine();
                    Printer.PrintContextSingle(context);
                    return;
                }
            }
            Console.WriteLine("\nNo hay un contexto con ese id\n");
        }

        /// <summary>
        /// Método ListContextsByExplicitMember
        /// Muestra una lista de los contextos que contienen el miembro explícito pasado por parámetro
        /// 
        /// </summary>
        /// <param name="explicitMember">Miembro explícito</param>
        private static void ListContextsByExplicitMember(string explicitMember)
        {
            List<Context> filteredContexts = new List<Context>();
            foreach (var context in contexts)
            {
                if (context.ExplicitMembers.Contains(explicitMember))
                {
                    filteredContexts.Add(context);
                }
            }
            Console.WriteLine();
            Printer.PrintContextsList(filteredContexts);
        }

        /// <summary>
        /// Método ListContextsByDateInstant
        /// Muestra una lista de los contextos que se han realizado en la fecha pasada por parámetro
        /// 
        /// </summary>
        /// <param name="date">Fecha</param>
        private static void ListContextsByDateInstant(DateTime date)
        {
            List<Context> filteredContexts = new List<Context>();
            foreach (var context in contexts)
            {
                if (context.DateInstant.Equals(date))
                {
                    filteredContexts.Add(context);
                }
            }
            Console.WriteLine();
            Printer.PrintContextsList(filteredContexts);
        }

        /// <summary>
        /// Método ListContextsByDateRange
        /// Muestra una lista de los contextos que se han realizado entre las fechas pasadas por parámetro
        /// 
        /// </summary>
        /// <param name="startDate"></param>
        /// <param name="endDate"></param>
        private static void ListContextsByDateRange(DateTime startDate, DateTime endDate)
        {
            List<Context> filteredContexts = new List<Context>();
            foreach (var context in contexts)
            {
                if (context.StartDate.Equals(startDate) || context.EndDate.Equals(endDate))
                {
                    filteredContexts.Add(context);
                }
            }
            Console.WriteLine();
            Printer.PrintContextsList(filteredContexts);
        }

        /// <summary>
        /// Método LoadXBRLIntoContexts
        /// Carga el fichero XBRL en la lista de contextos
        /// 
        /// </summary>
        /// <param name="xbrlFileName">Nombre del fichero</param>
        /// <returns></returns>
        private static List<Context> LoadXBRLIntoContexts(string xbrlFileName)
        {
            List<Context> list = new List<Context>();
            Context context;
            string contextId = "";
            string id = "";
            List<string> explicitMembers = new List<string>();
            string startDate = "";
            string endDate = "";
            string dateInstant = "";

            //Se lee el xml
            XmlReader xmlReader = XmlReader.Create(xbrlFileName);
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
                            case "xbrli:context":
                                while (xmlReader.MoveToNextAttribute())
                                {
                                    contextId = xmlReader.Value;                                    
                                }
                                break;
                            case "xbrli:identifier":
                                id = xmlReader.ReadElementContentAsString();
                                break;
                            case "xbrldi:explicitMember":
                                explicitMembers.Add(xmlReader.ReadElementContentAsString());
                                break;
                            case "xbrli:startDate":
                                startDate = xmlReader.ReadElementContentAsString();                                
                                break;
                            case "xbrli:endDate":
                                endDate = xmlReader.ReadElementContentAsString();                                
                                break;
                            case "xbrli:instant":
                                dateInstant = xmlReader.ReadElementContentAsString();                                
                                break;
                        }                        
                        break;
                    //En caso de ser un cierre de elemento
                    case XmlNodeType.EndElement:
                        if (xmlReader.Name.ToString().Equals("xbrli:context"))
                        {
                            context = new Context(contextId, id, explicitMembers, startDate, endDate, dateInstant);
                            list.Add(context);
                            ResetFields(ref contextId, ref id, ref explicitMembers, ref startDate, ref endDate, ref dateInstant);
                        }
                        break;
                }                
            }
            return list;
        }

        /// <summary>
        /// Método ResetFields
        /// Resetea los campos pasados por parámetro
        /// 
        /// </summary>
        /// <param name="contextId">Id del contexto</param>
        /// <param name="id">Id</param>
        /// <param name="explicitMembers">Lista de miembros explícitos</param>
        /// <param name="startDate">Fecha de inicio</param>
        /// <param name="endDate">Fecha de fin</param>
        /// <param name="dateInstant">Fecha instantanea</param>
        private static void ResetFields(ref string contextId, ref string id, ref List<string> explicitMembers, 
                                        ref string startDate, ref string endDate, ref string dateInstant)
        {
            contextId = "";
            id = "";
            explicitMembers = new List<string>();
            startDate = "";
            endDate = "";
            dateInstant = "";
        }
    }    
}
