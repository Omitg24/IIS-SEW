using System;
using System.Collections.Generic;
using System.Text;

namespace xbrlManagement
{
    /// <summary>
    /// Clase Context
    /// 
    /// Desarrollado por Omar Teixeira González, UO281847
    /// </summary>
    public class Context
    {
        /// <summary>
        /// Propiedad ContextId
        /// </summary>
        public string ContextId { get; set; }

        /// <summary>
        /// Propiedad Id
        /// </summary>
        public string Id { get; set; }

        /// <summary>
        /// Propiedad ExplicitMembers
        /// </summary>
        public List<string> ExplicitMembers { get; set; }

        /// <summary>
        /// Propiedad StartDate
        /// </summary>
        public DateTime? StartDate { get; set; }

        /// <summary>
        /// Propiedad EndDate
        /// </summary>
        public DateTime? EndDate { get; set; }

        /// <summary>
        /// Propiedad DateInstant
        /// </summary>
        public DateTime? DateInstant { get; set; }

        /// <summary>
        /// Constructor sin parámetros de la clase Context
        /// </summary>
        public Context() {}
        
        /// <summary>
        /// Constructor con parámetros de la clase Context
        /// </summary>
        /// <param name="contextId">Id del contexto</param>
        /// <param name="id">Id</param>
        /// <param name="explicitMembers">Lista de miembros explícitos</param>
        /// <param name="startDate">Fecha de inicio</param>
        /// <param name="endDate">Fecha de fin</param>
        /// <param name="dateInstant">Fecha instantanea</param>
        public Context (string contextId, string id, List<string> explicitMembers,
                        string startDate, string endDate, string dateInstant)
        {
            ValidateArgument(contextId, "El id del contexto no puede ser null o estar vacío");
            ValidateArgument(id, "El id no puede ser null o estar vacío");
            this.ContextId = contextId;
            this.Id = id;
            this.ExplicitMembers = explicitMembers;
            if (startDate != "")
            {
                this.StartDate = DateTime.Parse(startDate);
            }
            else
            {
                this.StartDate = null;
            }
            if (startDate != "")
            {
                this.EndDate = DateTime.Parse(endDate);
            }
            else
            {
                this.EndDate = null;
            }
            if (dateInstant != "")
            {
                this.DateInstant = DateTime.Parse(dateInstant);
            }
            else
            {
                this.DateInstant = null;
            }
        }
        
        /// <summary>
        /// Método ValidateArgument
        /// </summary>
        /// <param name="cad">Cadena a validar</param>
        /// <param name="msg">Mensaje a lanzar</param>
        /// <exception cref="ArgumentException"></exception>
        private void ValidateArgument(string cad, string msg)
        {
            if (cad == null || cad.Length == 0)
            {
                throw new ArgumentException(msg);
            }
        }
    }
}
