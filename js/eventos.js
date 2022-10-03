(function ($, Calendario) {

    $(function () {

        $('#cargarTablas').click(function () {
            Calendario.ui.cargar_tablas();
        });
        $('#tabla_dias').on('change','.asigselect', function (){
            Calendario.ui.test_for_multipleasigs();
        });
        $('#tabla_dias').on('click', '.btnInsertar', function () {
            var _elem = $(this);
            var Codigo = $("#asignatura_codigo").val();

            if(Codigo == null){
                Calendario.ui.dialogo("Debe de seleccionar una Asignatura primero", "Error");
                return 0;
            }else{

            $('<form title="Información extra del exámen" class="dialogo-form">' +
                '<div>' +
                    '<label for="hora">Hora del exámen:</label>' +
                    '<input type="time" id="hora" placeholder="HH:MM">' +
                '</div>' +
                '<div>' +
                    '<label for="aula">Aula del exámen:</label>' +
                    '<input type="text" id="aula" placeholder="Aula">' +
                '</div>' +
                '<div>' +
                    '<label for="Tipo">Parcial</label>' +
                    '<input type="checkbox" id="Tipo" >' +
                '</div>' +
            '</form>').dialog({
                minWidth: 350,
                buttons: [
                    {
                        text: 'Insertar',
                        click: function () {
                            var dialogForm = $(this);
                            Calendario.model.insertar(
                                _elem.data('dia'),
                                _elem.data('mes'),
                                _elem.data('year'),
                                _elem.data('cuatrimestre'),
                                _elem.data('titIzq'),
                                _elem.data('curIzq'),
                                dialogForm.find('#hora').val(),
                                dialogForm.find('#aula').val(),
                                dialogForm.find('#Tipo').prop("checked")
                            );
                            dialogForm.dialog('close');
                        },
                        class: 'btn-accept'
                    },
                    {
                        text: 'Cancelar',
                        click: function () {
                            $(this).dialog('close');
                        }
                    }
                ]
            });
          }
        }).on('click', '.btnEliminar', function () {
            var _elem = $(this);
            Calendario.model.eliminar(
                _elem.data('codAsig'),
                _elem.data('cuatrimestre'),
                _elem.data('year'),
                _elem.data('tipo')
            );
        }).on('click', '.btnEditar', function () {
          var _elem = $(this);
          if(_elem.data('tipo')==1){
            var check="checked";
          }else{
            var check="";
          }

          $('<form title="Información extra del exámen" class="dialogo-form">' +
              '<div>' +
                  '<label for="hora">Hora del exámen:</label>' +
                  '<input type="time" id="hora" placeholder="HH:MM" value="'+_elem.data('hora')+'">' +
              '</div>' +
              '<div>' +
                  '<label for="aula">Aula del exámen:</label>' +
                  '<input type="text" id="aula" placeholder="Aula" value="'+_elem.data('aula')+'">' +
              '</div>' +
              '<div>' +
                  '<label for="Tipo">Parcial</label>' +
                  '<input type="checkbox" id="Tipo" '+check+'>' +
              '</div>' +
          '</form>').dialog({
              minWidth: 350,
              buttons: [
                  {
                      text: 'Guardar cambios',
                      click: function () {
                          var dialogForm = $(this);
                          Calendario.model.editar(
                              _elem.data('codAsig'),
                              _elem.data('year'),
                              _elem.data('cuatrimestre'),
                              _elem.data('titIzq'),
                              _elem.data('curIzq'),
                              dialogForm.find('#hora').val(),
                              dialogForm.find('#aula').val(),
                              dialogForm.find('#Tipo').prop("checked")
                          );
                          dialogForm.dialog('close');
                      },
                      class: 'btn-accept'
                  },
                  {
                      text: 'Cancelar',
                      click: function () {
                          $(this).dialog('close');
                      }
                  }
              ]
          });
        }).on('click','.btnxml', function () {
            var _elem = $(this);
            Calendario.xml.generar_xml(
                _elem.data('idcal'),
                _elem.data('tit'),
                _elem.data('year')
            );
        }).on('click','.btnxml2', function () {
            var _elem = $(this);
            Calendario.xml.generar_xml2(
                _elem.data('idcal'),
                _elem.data('year'),
                _elem.data('cuatrimestre')
            );
        }).on('click','.btnxml3', function () {
            var _elem = $(this);
            Calendario.xml.generar_xml3(
                _elem.data('idcal'),
                _elem.data('year'),
                _elem.data('cuatrimestre')
            );
        }).on('click','.btnxml_id_cal', function () {
            var _elem = $(this);
            Calendario.xml.generar_xml_id_cal(
                _elem.data('idcal'),
                _elem.data('tit'),
                _elem.data('year')
            );
        }).on('click','.btnxml2_id_cal', function () {
            var _elem = $(this);
            Calendario.xml.generar_xml2_id_cal(
                _elem.data('idcal'),
                _elem.data('year'),
                _elem.data('cuatrimestre')
            );
        }).on('click','.btn_prov_defi', function () {
            var _elem = $(this);
            Calendario.model.change_estado_examenes(
                _elem.data('cuat'),
                _elem.data('idcal'),
                _elem.data('tit'),
                _elem.data('cur-izq'),
            );
        });

        $('#tit_asoc_1').change( function (){
              Calendario.Asigs.cargar_asig_1();
        });

        $('#tit_asoc_2').change( function (){
              Calendario.Asigs.cargar_asig_2();
        });

        $('#Boton_asociar_asig').click(function () {
            Calendario.Asigs.asociar();
        });

        $('#Asoc_asig').click( function () {
            Calendario.Asigs.cargar_app();
        });

        $('#Subida_csv').click( function () {
            Calendario.Asigs.csv();
        });

        $('#del_exa').click( function () {
            Calendario.model.del_examenes_cal();
        });

        Calendario.Asigs.cargar_tabla();

        $(document).on('submit', '.ui-dialog form', function(ev) {
            ev.preventDefault();
            return false;
        });

        /*$('#calendario').change(function () {
            var _this = $(this);
            var pdfLink = $('#enlace-pdf');
            var oldHref = pdfLink.prop('href');
            pdfLink.prop('href', oldHref + '?calendario=' + _this.val());
        });

        $('#tit1').change(function () {
            var _this = $(this);
            var pdfLink = $('#enlace-pdf');
            var oldHref = pdfLink.prop('href');
            pdfLink.prop('href', oldHref + '?grado=' + _this.val());
        });*/

        $('#enlace-pdf').click(function (ev) {
            var _this = $(this);
            var calendario = $('#calendario').val();
            var titulacion = $('#tit1').val();
            var oldHref = _this.prop('href');
            _this.prop('href', oldHref + '?grado=' + titulacion + '&calendario=' + calendario);
            /*if (_this.prop('href').indexOf('?grado') === -1) {
                Calendario.ui.dialogo('Debes seleccionar una titulación antes de generar el pdf', 'Error');
                ev.preventDefault();
                return false;
            }*/
        });

    });

})(jQuery, Calendario);
