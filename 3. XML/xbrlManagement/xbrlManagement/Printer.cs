using System;
using System.Collections.Generic;

namespace xbrlManagement
{
    /// <summary>
    /// Clase Printer
    /// 
    /// Desarollado por Omar Teixeira González, UO281847
    /// </summary>
    public class Printer
    {
        /// <summary>
        /// Método printContextsList
        /// Imprime la lista de contextos
        /// 
        /// </summary>
        /// <param name="list">Lista de contextos</param>
        public static void PrintContextsList(List<Context> list)
        {
            Console.WriteLine("Lista de contextos:");
            Console.WriteLine("----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
            foreach (var context in list)
            {
                
                PrintContextForList(context);
            }
            PrintListSize(list.Count);
        }

        /// <summary>
        /// Método printContextForList
        /// Imprime un contexto para imprimir la lista
        /// 
        /// </summary>
        /// <param name="c">Contexto</param>
        private static void PrintContextForList(Context c)
        {
            Console.WriteLine(String.Format("\t{0,-16} {6,-150}\n\t{1,-16} {7,-150}\n\t{2,-16} {8,-150}\n\t{3,-16} {9,-150}\n\t{4,-16} {10,-150}\n\t{5,-16} {11,-150}\n",
                "Context id", "Identifier", "Explicit members", "Start date", "End date", "Date instant",
                c.ContextId, c.Id, GetExplicitMembers(c.ExplicitMembers), DateFormat(c.StartDate), 
                DateFormat(c.EndDate), DateFormat(c.DateInstant)));
        }

        /// <summary>
        /// Método printContextSingle
        /// Imprime un único contexto
        /// 
        /// </summary>
        /// <param name="c">Contexto</param>
        public static void PrintContextSingle(Context c)
        {
            Console.WriteLine("Contexto: \n");
            Console.WriteLine(String.Format("\t{0,-16} {6,-150}\n\t{1,-16} {7,-150}\n\t{2,-16} {8,-150}\n\t{3,-16} {9,-150}\n\t{4,-16} {10,-150}\n\t{5,-16} {11,-150}\n",
                "Context id", "Identifier", "Explicit members", "Start date", "End date", "Date instant",
                c.ContextId, c.Id, GetExplicitMembers(c.ExplicitMembers), DateFormat(c.StartDate),
                DateFormat(c.EndDate), DateFormat(c.DateInstant)));
        }

        /// <summary>
        /// Método printListSize
        /// Imprime el tamaño de la lista
        /// 
        /// </summary>
        /// <param name="size">tamaño</param>
        private static void PrintListSize(int size)
        {
            Console.WriteLine("|--------------------------------------------------|");
            if (size < 10)
            {
                Console.WriteLine($"|------- El tamaño total de la lista es: {size} --------|");
            } else if (size < 100)
            {
                Console.WriteLine($"|------- El tamaño total de la lista es: {size} -------|");
            } else if (size < 1000)
            {
                Console.WriteLine($"|------ El tamaño total de la lista es: {size} -------|");
            }            
            Console.WriteLine("|--------------------------------------------------|\n");
        }

        /// <summary>
        /// Método getExplicitMembers
        /// Devuelve la lista de miembros explicitos formateada
        /// 
        /// </summary>
        /// <param name="list">Lista de miembros explícitos</param>
        /// <returns></returns>
        private static string GetExplicitMembers(List<string> list)
        {
            string explicitMembers = "";
            foreach (var member in list)
            {
                explicitMembers += $"{member} ";
            }
            return explicitMembers;
        }

        /// <summary>
        /// Método dateFormat
        /// Formatea la fecha
        /// 
        /// </summary>
        /// <param name="date">Fecha a formatear</param>
        /// <returns></returns>
        private static string DateFormat(DateTime? date)
        {
            return date == null ? "" : date.GetValueOrDefault().ToString("yyyy-MM-dd");
        }
    }
}
