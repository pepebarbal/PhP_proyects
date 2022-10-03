(function ($) {

    /**
     * Objeto base con los métodos necesarios para el frontend.
     */
    var Calendario = {
        /**
         * Métodos para trabajar con cosas visuales.
         */
        ui: {
            /**
             * Constantes para definir los tipos de mensajes
             * a mostrar en los diálogos.
             */
            ASIGNATURA_INSERTADA: 1,
            ASIGNATURA_BORRADA: 2,
            EXAMENES_ELIMINADOS: 3,
            ASIGNATURA_ELIMINADA: 4,
            /**
             *Método que muestra un diálogo en función del tipo
             * pasado por parámetro.
             *
             * @param tipoMensaje
             * Tipo de mensaje de las constantes definidas arriba.
             */
            aviso: function (tipoMensaje) {
                var msg = "";
                switch (tipoMensaje) {
                    case this.ASIGNATURA_INSERTADA:
                        msg = 'asignatura ya insertada';
                        break;
                    case this.ASIGNATURA_BORRADA:
                        msg = "La asignatura no pudo ser borrada";
                        break;
                    case this.EXAMENES_ELIMINADOS:
                        msg = "Examenes eliminados correctamente";
                    break;
                    case this.ASIGNATURA_ELIMINADA:
                        msg = "Examen eliminado correctamente";
                    break;
                }
                this.dialogo(msg, "Aviso");
            },
            /**
             * Método para mostrar un mensaje por pantalla
             * haciendo uso del dialog de jQuery ui.
             *
             * @param mensaje
             * Mensaje a mostrar.
             *
             * @param titulo
             * Titulo del dialogo.
             */
            dialogo: function (mensaje, titulo) {
                $('<p title="' + titulo + '">' + mensaje + '</p>').dialog();
            },

            /**
             * Función para mostrar un dialogo de confirmación.
             *
             * @param mensaje String
             * @param evento_confirmar function
             */
            confirmacion: function(mensaje, titulo, evento_confirmar) {
                $('<p title="' + titulo + '">' + mensaje + '</p>').dialog({
                    buttons: [
                        {
                            text: 'Si',
                            click: function () {
                                if (typeof evento_confirmar === 'function') {
                                    evento_confirmar();
                                    $(this).dialog('close');
                                }
                            },
                            class: 'btn-accept'
                        },
                        {
                            text: 'No',
                            click: function () {
                                $(this).dialog('close');
                            }
                        }
                    ]
                });
            },
            /**
             * @todo Añadir comentarios de la función.
             */
            cargar_tablas: function () {
                var cuatrimestre = $("#Cuatr").val();
                var titulacion_izq = $("#tit1").val();
                var curso_izq = $("#Curso_1").val();
                var id_calendario = $("#calendario").val();
                if (titulacion_izq == "nulo") {
                    this.dialogo("Debe de seleccionar una titulacion primero", "Error");
                    return 0;
                }else if(id_calendario == "nulo"){
                    this.dialogo("Debe de seleccionar un Calendario de entre los disponibles", "Error");
                }else {
                    $("#tabla_dias").load("./ajax/get_tabla.php", {
                        Cuatrimestre: cuatrimestre,
                        Titulacion_izq: titulacion_izq,
                        Curso_izq: curso_izq,
                        Calendario: id_calendario,
                    });
                }
            },

            test_for_multipleasigs: function(){
              var Codigo = $("#asignatura_codigo").val();
              $("#get_asig_multit").load("./ajax/asig_multit.php", {
                  Codigo: Codigo,
              });

            }

        },

        ajax: function (url, data, success) {
            return $.get(url, data, success);
        },
        /**
         * Métodos para trabajar con entidades de bases de datos.
         */
        model: {
            /**
             * @todo Comentar función
             */

             editar: function (Codigo, year, Cuatrimestre, titIzq, curIzq, Hora, Aula, Tipo) {

               $("#tabla_dias").load("./ajax/get_tabla.php", {
                   Codigo: Codigo,
                   Year: year,
                   Cuatrimestre: Cuatrimestre,
                   Titulacion_izq: titIzq,
                   Curso_izq: curIzq,
                   Editar: "1",
                   Hora: Hora,
                   Aula: Aula,
                   Tipo: Tipo,
               });

             },

             del_examenes_cal: function (Identificador) {
               var id_calendario = $("#calendario").val();
               var convocatoria = $("#Cuatr").val();
               if(id_calendario == "nulo"){
                   Calendario.ui.dialogo("Debe de seleccionar un calendario primero", "Error");
                   return 0;
               }else{
                        Calendario.ui.confirmacion('¿Está seguro de querer eliminar todos los examenes de esta convocatoria para el calendario seleccionado?', 'Eliminar examenes', function () {

                          $("#del_exam_cal_id").load("./ajax/del_exam_cal.php", {
                              id_cal:id_calendario,
                              conv:convocatoria,
                          });

                          $("#tabla_dias").html("");

                        });
                 };
             },

            insertar: function (Dia, Mes, year, Cuatrimestre, Titulacion_izq
                                , Curso_izq, Hora, Aula, Tipo) {
                /*var devolver=comprobar();   //comprobara cosas
                if(devolver==false){
                    return false;
                }
                /** Turno, codigo y semanas -> hay que capturarlo de los desplegables **/
                var Codigo = $("#asignatura_codigo").val();
                  /*Recarga con ajax y paso de parametros.*/
                  //alert(Codigo);
                  // @todo Cambiar esto por un post?
                  $("#tabla_dias").load("./ajax/get_tabla.php", {
                      Dia: Dia,
                      Mes: Mes,
                      Codigo: Codigo,
                      Year: year,
                      Cuatrimestre: Cuatrimestre,
                      Titulacion_izq: Titulacion_izq,
                      Curso_izq: Curso_izq,
                      Insertar: "1",
                      Tipo: Tipo,
                      Hora: Hora,
                      Aula: Aula,
                  });

            },
            eliminar: function (codAsig, cuatrimestre, year,tipo) {

                Calendario.ui.confirmacion('¿Está seguro de querer eliminar la asignatura?', 'Eliminar asignatura', function () {
                    var titulacion_izq = $("#tit1").val();
                    var curso_izq = $("#Curso_1").val();

                    $('#tabla_dias').load("./ajax/get_tabla.php", {
                        Codigo:codAsig,
                        Cuatrimestre:cuatrimestre,
                        year:year,
                        Borrar: "1",
                        Titulacion_izq: titulacion_izq,
                        Curso_izq: curso_izq,
                        Tipo:tipo,
                    });
                });
            },

            change_estado_examenes: function (Cuatrimestre, id_cal, titulacion_izq, curso_izq) {
              $("#tabla_dias").load("./ajax/get_tabla.php", {
                  Cuatrimestre: Cuatrimestre,
                  Calendario: id_cal,
                  Titulacion_izq: titulacion_izq,
                  Curso_izq: curso_izq,
                  provdef: 1,
                  /*
                  Se pasan los datos a la pagina get_tabla para modificar el atributo de provisional/definitivo.
                  */
              });
            }
        },

        Asigs: {
          cargar_asig_1: function(){
              var Titulacion = $("#tit_asoc_1").val();
              $("#tabla_asig_1").load("./ajax/Asoc_lista_asigs.php", {
                  Titulacion: Titulacion,
                  Asignatura: 1,
              });
          },
          cargar_asig_2: function(){
              var Titulacion = $("#tit_asoc_2").val();
              $("#tabla_asig_2").load("./ajax/Asoc_lista_asigs.php", {
                  Titulacion: Titulacion,
                  Asignatura: 2,
              });
          },

          asociar: function(){
              var Titulacion = $("#tit_asoc_1").val();
              var Titulacion2 = $("#tit_asoc_2").val();
              var Asignatura_1 = $("#Asignatura_1").val();
              var Asignatura_2 = $("#Asignatura_2").val();

              var comprobar=true;
              if((Titulacion != null) && (Titulacion2 != null)){

              }else{
                  alert("no tiene seleccionada dos titulaciones");
                  comprobar=false;
              }

              if(comprobar==true){
                /*Cargamos un trocito de web que hace la inserción y muestra el mensaje*/
                $("#Insertar_asigs").load("./ajax/Insertar_lista_asigs.php", {
                    Asig_1: Asignatura_1,
                    Asig_2: Asignatura_2,
                }, function () {
                    $("#tabla_asig_1").load("./ajax/Asoc_lista_asigs.php", {
                        Titulacion: Titulacion,
                        Asignatura: 1,
                    });

                    $("#tabla_asig_2").load("./ajax/Asoc_lista_asigs.php", {
                        Titulacion: Titulacion2,
                        Asignatura: 2,
                    });

                    $("#Lista_asoc").load("./ajax/Asoc_lista_asigs_check.php");
                });

              }



          },

          cargar_tabla:function(){
            $("#Lista_asoc").load("./ajax/Asoc_lista_asigs_check.php");
          },
          cargar_app: function(){
              window.open("./App_asoc.php");
          },
            csv: function(){
                window.open("./Subida_csv.php","_self");
            }

        },

        xml: {
          generar_xml: function(idcal,tit,year){

                window.open("./xml/xml_document.php?tit="+tit+"&year="+year+"&id_cal="+idcal, "_black");

          },
          generar_xml2: function(idcal,year,cuatr){

                window.open("./xml/xml_document_total.php?year="+year+"&id_cal="+idcal+"&cuatr="+cuatr, "_black");

          },generar_xml3: function(idcal,year,cuatr){

                window.open("./xml/xml_cal_conv.php?year="+year+"&id_cal="+idcal+"&cuatr="+cuatr, "_black");

          },generar_xml_id_cal: function(idcal,tit,year){

                window.open("./xml/xml_document_id_cal.php?tit="+tit+"&year="+year+"&id_cal="+idcal, "_black");

          },
          generar_xml2_id_cal: function(idcal,year,cuatr){

                window.open("./xml/xml_document_total_id_cal.php?year="+year+"&id_cal="+idcal+"&cuatr="+cuatr, "_black");

          }
        },

    };
    window.Calendario = Calendario;
})(jQuery);
