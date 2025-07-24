(function() {
    var accion;
    var tbl_caja, tbl_resgitros_caja, tbl_resgitros_movi;

    var Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000
    });

    // Función para formatear moneda
    function formatearMoneda(valor) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(valor);
    }

    // Función para actualizar el "Estado de Caja Actual"
    function actualizarEstadoCaja(estado) {
        if (estado && estado.caja_estado === 'VIGENTE') {
            $('#estado_caja_monto_inicial').html(formatearMoneda(estado.caja_monto_inicial));
            $('#estado_caja_saldo_actual').html(formatearMoneda(estado.caja_monto_total));
            $('#estado_caja_label').html("<span class='badge badge-success'>VIGENTE</span>");
        } else {
            $('#estado_caja_monto_inicial').html(formatearMoneda(0));
            $('#estado_caja_saldo_actual').html(formatearMoneda(0));
            $('#estado_caja_label').html("<span class='badge badge-secondary'>SIN CAJA</span>");
        }
    }

    // Función para actualizar las estadísticas del dashboard
    function actualizarEstadisticasDashboard(estadisticas) {
        try {
            console.log('Actualizando estadísticas del dashboard:', estadisticas);
            
            // Actualizar las tarjetas del dashboard
            if (estadisticas.cajas_abiertas !== undefined) {
                $('.card-dashboard .info-box-number, .small-box h3').each(function() {
                    const $this = $(this);
                    const parent = $this.closest('.small-box, .info-box');
                    
                    // Buscar por clases o texto para identificar cada tarjeta
                    if (parent.find('.small-box-footer, .info-box-text').text().includes('Estado de Caja') || 
                        parent.hasClass('bg-info') || 
                        $this.text().includes('$')) {
                        
                        // Esta es la tarjeta de estado de caja
                        if (estadisticas.cajas_abiertas > 0) {
                            $this.text('$' + parseFloat(estadisticas.saldo_total).toLocaleString('es-CO', {minimumFractionDigits: 2}));
                            parent.find('.progress-description, small').text('CON CAJA');
                        } else {
                            $this.text('$0.00');
                            parent.find('.progress-description, small').text('SIN CAJA');
                        }
                    }
                });
            }
            
            // Actualizar información adicional si existe
            $('#cajas_abiertas_count').text(estadisticas.cajas_abiertas || 0);
            $('#cajas_cerradas_count').text(estadisticas.cajas_cerradas || 0);
            $('#saldo_total_display').text('$' + parseFloat(estadisticas.saldo_total || 0).toLocaleString('es-CO', {minimumFractionDigits: 2}));
            
        } catch (error) {
            console.error('Error actualizando estadísticas del dashboard:', error);
        }
    }

    function inicializarDataTable() {
        try {
           
            /*===================================================================*/
            //INICIALIZAR LA TABLA DE CAJA
            /*===================================================================*/
            tbl_caja = $("#tbl_caja").DataTable({
                responsive: true,

                dom: 'Bfrtip',
                select: true,
                buttons: [{
                        "extend": 'excelHtml5',
                        "title": 'Arqueo de Caja',
                        "exportOptions": {
                            'columns': [1, 2, 3, 4, 5, 6, 7, 8, 9]
                        },
                        "text": '<i class="fa fa-file-excel"></i>',
                        "titleAttr": 'Exportar a Excel'
                    },
                    {
                        "extend": 'print',
                        "text": '<i class="fa fa-print"></i> ',
                        "titleAttr": 'Imprimir',
                        "exportOptions": {
                            'columns': [1, 2, 3, 4, 5, 6, 7, 8, 9]
                        },
                        "download": 'open'
                    },

                    'pageLength',
                ],
                ajax: {
                    url: "ajax/caja_ajax.php",
                    dataSrc: function(json) {
                        // Unificar el manejo de la respuesta
                        if(json.error) {
                            console.error("Error desde el backend:", json.error);
                            return [];
                        }
                        
                        // Actualizar el estado de la caja actual con los datos recibidos
                        if (json.estado_caja) {
                            actualizarEstadoCaja(json.estado_caja);
                        } else {
                            // Si no hay caja vigente, limpiar las tarjetas
                            actualizarEstadoCaja(null);
                        }
                        // Devolver los datos para la tabla
                        return json.data;
                    },
                    type: "POST",
                    data: {
                        'accion': 'listar_caja' // Esta es la única acción que necesitamos ahora
                    },
                    error: function(xhr, status, error) {
                        console.error('Error Crítico AJAX:', error);
                        // Limpiar todo en caso de fallo de comunicación
                        actualizarEstadoCaja(null);
                        $('#tbl_caja').DataTable().clear().draw();
                    }
                },
                columnDefs: [{
                    targets: 0, // id
                    visible: false
                },
                {
                    targets: [1,2,3,4,8], // Montos
                    render: function(data, type, row) {
                        return formatearMoneda(data);
                    }
                },
                {
                    targets: 9, // Estado
                    createdCell: function(td, cellData, rowData, row, col) {

                        if (cellData == 'VIGENTE') {
                            $(td).html("<span class='badge badge-success'>VIGENTE</span>");
                        } else {
                            $(td).html("<span class='badge badge-danger'>CERRADO</span>");
                        }
                    }
                }, {
                    targets: 10, // Opciones
                    sortable: false,
                    render: function(data, type, row, meta) {
                        var estado = row[9]; // Columna de estado
                        if (estado == 'VIGENTE') {
                            return "<center>" +
                                "<span class='btnCerrarCaja text-warning px-1' style='cursor:pointer;' data-id='" + data + "' title='Cerrar Caja'> " +
                                "<i class='fas fa-lock fs-6'></i> " +
                                "</span> " +
                                "<span class='btnVerRegistrosC text-primary px-1' style='cursor:pointer;' data-id='" + data + "' title='Ver Registros'> " +
                                "<i class='fa fa-eye fs-6'> </i> " +
                                "</span>" +
                                "</center>";
                        } else { 
                            return "<center>" +
                                "<span class='btnVerRegistrosC text-primary px-1' style='cursor:pointer;' data-id='" + data + "' title='Ver Registros'> " +
                                "<i class='fa fa-eye fs-6'> </i> " +
                                "</span>" +
                                "</center>";
                        }
                    }
                }],
                "order": [
                    [5, 'desc'] // Ordenar por fecha de apertura
                ],
                lengthMenu: [5, 10, 15, 20, 50],
                "pageLength": 10,
                "language": idioma_espanol,
                // Callback para aplicar estilos a las cabeceras después de la inicialización de DataTables
                "initComplete": function(settings, json) {
                    $('#tbl_caja thead th').css({
                        'background-color': '#17a2b8',
                        'color': '#ffffff',
                        'background-image': 'none' // Eliminar iconos de ordenamiento si los hay
                    });
                }

            });

        } catch (error) {
            console.error('Error al inicializar DataTable de caja:', error);
        }
    }

    // Función para verificar permisos y estado de la sesión
    function verificarPermisos(callback) {
        $.ajax({
            url: "ajax/caja_ajax.php",
            method: "POST",
            data: {
                accion: "verificar_permisos_caja",
                sub_accion: "ABRIR_CAJA"
            },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta de verificación de permisos:', response);
                
                if (response.success && response.permisos && response.permisos.puede_ejecutar) {
                    if (typeof callback === 'function') {
                        callback(response);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Permisos',
                        text: response.permisos ? response.permisos.mensaje : 'No tiene los permisos necesarios',
                        showConfirmButton: true,
                        confirmButtonText: 'Reintentar',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al verificar permisos:', error);
                if (xhr.status === 401 || xhr.responseJSON?.message === 'Sesión no válida') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sesión Expirada',
                        text: 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.',
                        showConfirmButton: true,
                        confirmButtonText: 'Iniciar Sesión',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al verificar permisos. Por favor, intente nuevamente.',
                        showConfirmButton: true,
                        confirmButtonText: 'Reintentar',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }
            }
        });
    }

    // Función global para abrir caja
    window.abrirCaja = function() {
        verificarPermisos(function(response) {
            // Mostrar el modal de apertura
            $("#modal_abrir_caja").modal('show');
            
            // Mostrar información de permisos
            $('#info-permisos').show();
            $('#permisos-details').html(`
                <p><i class="fas fa-user text-primary"></i> Usuario: ${response.usuario.nombre}</p>
                <p><i class="fas fa-id-badge text-success"></i> Perfil: ${response.usuario.perfil}</p>
                ${response.usuario.sucursal ? `<p><i class="fas fa-building text-info"></i> Sucursal: ${response.usuario.sucursal}</p>` : ''}
            `);
            
            // Establecer valores por defecto
            $("#text_descripcion").val('Apertura de Caja');
            $("#text_monto_ini").focus();
        });
    };

    $(document).ready(function() {
            
        // Verificar que tenemos los elementos necesarios
        if (!$('#tbl_caja').length) {
            console.warn('Tabla de caja no encontrada, saltando inicialización DataTable');
            return;
        }

        // Inicializar DataTable directamente sin verificación de permisos
        inicializarDataTable();

        $('.js-example-basic-single').select2();

        // NUEVO: Funcionalidad para botones de acción
        $('#btnConteoFisico').on('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'Conteo Físico',
                text: 'Funcionalidad de conteo físico por implementar.',
                showConfirmButton: true
            });
        });

        $('#btnGenerarReporteCaja').on('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'Generar Reporte',
                text: 'Funcionalidad de generación de reporte por implementar.',
                showConfirmButton: true
            });
        });

        /*===================================================================*/
        // FILTRAR CAJA POR CLIENTE AL CAMBIAR SELECTOR O HACER CLIC EN BUSCAR
        /*===================================================================*/
        $("#btnFiltrar").on('click', function() {
            if ($("#select_clientes").val() == '') {
                Toast.fire({
                    icon: 'error',
                    title: ' Debe Seleccionar un cliente'
                })
                $("#select_clientes").focus();

            } else {
                tbl_caja.ajax.reload(); // Recargar la tabla de caja
            }
        });

        $('#select_clientes').on('change', function() {
            tbl_caja.ajax.reload(); // Recargar la tabla de caja al cambiar el cliente
        });

        /*===================================================================*/
        // INICIAMOS EL DATATABLE
        /*===================================================================*/
        // tbl_caja = $("#tbl_caja").DataTable({
        //     responsive: true,

        //     dom: 'Bfrtip',
        //     select: true,
        //     buttons: [{
        //             "extend": 'excelHtml5',
        //             "title": 'Arqueo de Caja',
        //             "exportOptions": {
        //                 'columns': [1, 2, 3, 4, 5, 6, 7, 8, 9]
        //             },
        //             "text": '<i class="fa fa-file-excel"></i>',
        //             "titleAttr": 'Exportar a Excel'
        //         },
        //         {
        //             "extend": 'print',
        //             "text": '<i class="fa fa-print"></i> ',
        //             "titleAttr": 'Imprimir',
        //             "exportOptions": {
        //                 'columns': [1, 2, 3, 4, 5, 6, 7, 8, 9]
        //             },
        //             "download": 'open'
        //         },

        //         'pageLength',
        //     ],
        //     ajax: {
        //         url: "ajax/caja_ajax.php",
        //         dataSrc: "data",
        //         type: "POST",
        //         data: {
        //             'accion': 'listar_caja'
        //         },
        //         success: function(response) {
        //             console.log('Datos recibidos para DataTable:', response);
                    
        //             // Actualizar estadísticas del dashboard si están disponibles
        //             if (response.estadisticas) {
        //                 actualizarEstadisticasDashboard(response.estadisticas);
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error al cargar datos de caja:', error);
        //             console.error('Respuesta del servidor:', xhr.responseText);
        //         }
        //     },
        //     columnDefs: [{
        //             targets: 0,
        //             visible: false

        //         },
        //         {
        //             targets: 9,
        //             //sortable: false,
        //             createdCell: function(td, cellData, rowData, row, col) {

        //                 if (rowData[9] == 'VIGENTE') {
        //                     $(td).html("<span class='badge badge-success'>VIGENTE</span>")
        //                 } else {
        //                     $(td).html("<span class='badge badge-danger'>CERRADO</span>")
        //                 }

        //             }
        //         }, {
        //             targets: 10, //columna 2
        //             sortable: false, //no ordene
        //             render: function(td, cellData, rowData, row, col) {

        //                 if (rowData[9] == 'VIGENTE') {
        //                     return "<center>" +
        //                         "<span class='btnCerrarCaja text-warning px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cerrar Caja'> " +
        //                         "<i class='fas fa-lock fs-6'></i> " +
        //                         "</span> " +
        //                         "<span class='text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir'> " +
        //                         "<i class='fa fa-print fs-6'> </i> " +
        //                         "</span>" +
        //                         "<span class='btnVerRegistrosC text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Registros de Caja'> " +
        //                         "<i class='fa fa-eye fs-6'> </i> " +
        //                         "</span>" +
        //                         "</center>"
        //                 } else { //pendiente
        //                     return "<center>" +
        //                         "<span class='text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' > " +
        //                         "<i class='fas fa-lock fs-6'></i> " +
        //                         "</span> " +
        //                         "<span class='ImprimirCaja text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir Cierre de Caja'> " +
        //                         "<i class='fa fa-print fs-6'> </i> " +
        //                         "</span>" +
        //                         "<span class='EnviarCorreo text-warning px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Enviar por Correo'> " +
        //                         "<i class='fas fa-envelope fs-6'> </i> " +
        //                         "</span>" +
        //                         "<span class='btnVerRegistrosC text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Registros de Caja ' > " +
        //                         "<i class='fa fa-eye fs-6'> </i> " +
        //                         "</span>" +
        //                         "</center>"

        //                 }
        //             }
        //         }

        //     ],
        //     "order": [
        //         [0, 'desc']
        //     ],
        //     lengthMenu: [0, 5, 10, 15, 20, 50],
        //     "pageLength": 10,
        //     "language": idioma_espanol,
        //     // Callback para aplicar estilos a las cabeceras después de la inicialización de DataTables
        //     "initComplete": function(settings, json) {
        //         $('#tbl_caja thead th').css({
        //             'background-color': '#17a2b8',
        //             'color': '#ffffff',
        //             'background-image': 'none' // Eliminar iconos de ordenamiento si los hay
        //         });
        //     }

        // });

        /*===================================================================*/
        // ABRIR MODAL caja
        /*===================================================================*/
        $("#abrirmodal_caja").on('click', function() {
            abrirCaja();
            //AbrirModalAbrirCerrarCaja()

        })



        /*===================================================================*/
        // ABRIR MODAL CERRAR CAJA
        /*===================================================================*/
        // CERRAR CAJA - FUNCIONALIDAD MEJORADA
        /*===================================================================*/
        // Este evento ahora es manejado por la función configurarEventosNuevos()
        // en el JavaScript mejorado al final del archivo

        /*===================================================================*/
        // ABRIR MODAL CERRAR CAJA
        /*===================================================================*/
        $('#tbl_caja').on('click', '.btnVerRegistrosC', function() { //class foto tiene que ir en el boton
            //accion = 7;
            if (tbl_caja.row(this).child.isShown()) {
                var data = tbl_caja.row(this).data();
            } else {
                var data = tbl_caja.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
            }


            var caja_id = data[0]
           // console.log(caja_id);

            AbrirModalVerRegistrosPorCaja();
            Traer_RegistrosporIDCaja(caja_id)
            Traer_MovimientosporIDCaja(caja_id);

        });



        /*===================================================================*/
        //EVENTO QUE GUARDAAR APERTURA CAJA
        /*===================================================================*/

        document.getElementById("btnregistrar_caja").addEventListener("click", function() {


            var monto = $('#text_monto_ini').val();


            if (monto == "") {
                Toast.fire({
                    icon: 'warning',
                    title: 'Digitar un monto para aperturar la caja'

                });
                //  $('#btnregistrar_caja').show();
                // document.getElementsByClassName("btn-activa")[0].focus();

            } else {
                // console.log("Listo para registrar el producto")
                Swal.fire({
                    title: 'Esta seguro de Apertura Caja',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8FCE00',
                cancelButtonColor: '#d50',
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {

                    if (result.isConfirmed) {

                        var datos = new FormData();

                        datos.append("accion", 'registrar_caja_sucursal');
                        datos.append("caja_descripcion", $("#text_descripcion").val());
                        datos.append("caja_monto_inicial", $("#text_monto_ini").val());
                        datos.append("sucursal_id", $("#select_sucursal").val()); 

                        $.ajax({
                            url: "ajax/caja_ajax.php",
                            method: "POST",
                            data: datos, //enviamos lo de la variable datos
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function(respuesta) {
                                console.log('Respuesta del servidor:', respuesta);

                                // Manejar diferentes formatos de respuesta
                                if (respuesta && (respuesta.resultado || respuesta.success)) {
                                    const resultado = respuesta.resultado || (respuesta.success ? 1 : 2);
                                    const mensaje = respuesta.mensaje || respuesta.message || '';
                                    
                                    if (resultado == 1 || respuesta.success === true) {
                                        Toast.fire({
                                            icon: 'success',
                                            title: mensaje || 'Caja abierta correctamente'
                                        });

                                        // Recargar tabla y cerrar modal
                                        if (typeof tbl_caja !== 'undefined') {
                                            tbl_caja.ajax.reload();
                                        }
                                        $("#modal_abrir_caja").modal('hide');
                                        $("#text_monto_ini").val("");
                                        $("#text_descripcion").val("Apertura de Caja");
                                        $("#select_sucursal").val("");
                                        $("#text_observaciones").val("");
                                        $("#check_validacion_fisica").prop('checked', false);

                                    } else {
                                        Toast.fire({
                                            icon: 'warning',
                                            title: mensaje || 'No se pudo abrir la caja'
                                        });
                                    }

                                } else {
                                    // Si no hay estructura esperada, pero la caja se creó (verificar si hay caja_id)
                                    if (respuesta && respuesta.caja_id) {
                                        Toast.fire({
                                            icon: 'success',
                                            title: 'Caja abierta correctamente'
                                        });
                                        
                                        if (typeof tbl_caja !== 'undefined') {
                                            tbl_caja.ajax.reload();
                                        }
                                        $("#modal_abrir_caja").modal('hide');
                                        $("#text_monto_ini").val("");
                                        $("#text_descripcion").val("Apertura de Caja");
                                        $("#select_sucursal").val("");
                                        $("#text_observaciones").val("");
                                        $("#check_validacion_fisica").prop('checked', false);
                                    } else {
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Error en la respuesta del servidor'
                                        });
                                    }
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error AJAX:', error);
                                console.error('Respuesta del servidor:', xhr.responseText);
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Error de comunicación con el servidor'
                                });
                            }
                        });

                    }
                })
            }


        });



        /*===================================================================*/
        //EVENTO QUE CIERRA LA CAJA
        /*===================================================================*/
        $("#btnCerrar_caja").on('click', function() {

            Swal.fire({
                title: '¿Está seguro de Cerrar Caja?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8FCE00',
                cancelButtonColor: '#d50',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar',
            }).then((result) => {

                if (result.isConfirmed) {

                    var caja_monto_ingreso = $("#text_monto_ingreso").val();
                    var caja_prestamo = $("#text_monto_prestamo").val();
                    var caja__monto_egreso = $("#text_monto_egreso").val();
                    var caja_monto_total = $("#text_monto_total").val();
                    var caja_count_prestamo = $("#text_cant_prestamo").val();
                    var caja_count_ingreso = $("#text_cant_ingreso").val();
                    var caja_count_egreso = $("#text_cant_egreso").val();
                    var caja_interes = $("#text_interes").val();

                    var datos = new FormData();
                    datos.append("accion", 4); //PARA REGISTRAR EL  CERRAR LA CAJA
                    datos.append("caja_monto_ingreso", caja_monto_ingreso);
                    datos.append("caja_prestamo", caja_prestamo);
                    datos.append("caja__monto_egreso", caja__monto_egreso);
                    datos.append("caja_monto_total", caja_monto_total);
                    datos.append("caja_count_prestamo", caja_count_prestamo);
                    datos.append("caja_count_ingreso", caja_count_ingreso);
                    datos.append("caja_count_egreso", caja_count_egreso);
                    datos.append("caja_interes", caja_interes);

                    $.ajax({
                        url: "ajax/caja_ajax.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(respuesta) {

                            if (respuesta == 1) { //validamos la respuesta del procedure si retorna 1 o 2
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Caja Cerrada Correctamente'
                                });
                                $("#modal_cerrar_caja").modal('hide');
                                tbl_caja.ajax.reload(); //recargamos la tabla
                                $("#caja_id").val("");
                                $("#text_monto_ingreso").val("");
                                $("#text_monto_prestamo").val("");
                                $("#text_monto_egreso").val("");
                                $("#text_monto_total").val("");
                                $("#text_cant_prestamo").val("");
                                $("#text_cant_ingreso").val("");
                                $("#text_cant_egreso").val("");
                                $("#text_interes").val("");
                            } else if (respuesta == 2) {
                                Toast.fire({
                                    icon: 'warning',
                                    title: 'Tienes Préstamos Aprobados que no han Finalizado'
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Error al cerrar la caja'
                                });
                            }

                        }
                    });

                }
            })

        })



        /********************************************************************
              IMPRIMIR COMPROBANTE
          ********************************************************************/
        $('#tbl_caja').on('click', '.ImprimirCaja', function() { //class foto tiene que ir en el boton
            var data = tbl_caja.row($(this).parents('tr')).data(); //tamaño de escritorio
            if (tbl_caja.row(this).child.isShown()) {
                var data = tbl_caja.row(this).data(); //para celular y usas el responsive datatable

            }

            window.open("MPDF/reporte_arqueocaja_mejorado.php?codigo=" + parseInt(data[0]) + "#zoom=120", "Arqueo de Caja", "scrollbards=NO");





        });



        /********************************************************************
              ENVIAR POR CORREO
          ********************************************************************/
        $('#tbl_caja').on('click', '.EnviarCorreo', function() { //class foto tiene que ir en el boton
            var data = tbl_caja.row($(this).parents('tr')).data(); //tamaño de escritorio
            if (tbl_caja.row(this).child.isShown()) {
                var data = tbl_caja.row(this).data(); //para celular y usas el responsive datatable

            }

            Swal.fire({
                title: 'Esta seguro de Enviar el correo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8FCE00',
                cancelButtonColor: '#d50',
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("MPDF/reporte_arqueocaja_Email.php?codigo=" + parseInt(data[0]) + "#zoom=120", "Arqueo de Caja", "scrollbards=NO");
                    Toast.fire({
                        icon: 'success',
                        title: 'Correo enviado correctamente'
                    });


                }

            })

        });


        /* ======================================================================================\
         EVENTO QUE LIMPIA EL INPUT  AL CERRAR LA VENTANA MODAL
        =========================================================================================*/
        $("#btncerrarmodal_caja_cierre, #btncerrar_caja_cierre").on('click', function() {
            $("#text_monto_aper").val("");
            $("#text_monto_prestamo").val("");
            $("#text_cant_prestamo").val("");
            $("#text_monto_ingreso").val("");
            $("#text_cant_ingreso").val("");
            $("#text_monto_egreso").val("");
            $("#text_cant_egreso").val("");
            $("#text_monto_total").val("");
            $("#text_interes").val("");
        })


        /*===================================================================*/
        //EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
        /*===================================================================*/
        document.getElementById("btncerrar_caja_cierre").addEventListener("click", function() {
            $(".needs-validation").removeClass("was-validated");
        })
        document.getElementById("btncerrarmodal_caja_cierre").addEventListener("click", function() {
            $(".needs-validation").removeClass("was-validated");
        })



    }) // FIN DOCUMENT READY


    // NUEVO: Cargar estadísticas rápidas del día
    function cargarEstadisticasRapidas() {
        $.ajax({
            url: "ajax/caja_ajax.php",
            method: "POST",
            dataType: "json",
            data: { accion: 'obtener_estadisticas_rapidas' }, // Acción para obtener estadísticas rápidas
            success: function(response) {
                if (response && response.success) {
                    $('#aperturas_hoy').text(response.aperturas_hoy);
                    $('#cierres_hoy').text(response.cierres_hoy);
                    // Implementar lógica para eficiencia operacional si los datos están disponibles
                    // Por ahora, se mantendrá en 0%
                } else {
                            $('#aperturas_hoy').text('--');
                    $('#cierres_hoy').text('--');
                }
            },
            error: function(xhr, status, error) {
                console.error('[Estadisticas Rapidas] Error AJAX:', error);
                console.error('[Estadisticas Rapidas] Respuesta del servidor:', xhr.responseText);
                $('#aperturas_hoy').text('Error');
                $('#cierres_hoy').text('Error');
            }
        });
    }

    // Llamar a la función al cargar la página
    cargarEstadisticasRapidas();

    // FUNCIONES

    /********************************************************************
          ABRIR MODAL abrir caja
    ********************************************************************/
    function AbrirModalAbrirCaja() {
        //para que no se nos salga del modal haciendo click a los costados
        $("#modal_abrir_caja").modal({
            backdrop: 'static',
            keyboard: false
        });
        
        // CARGAR SUCURSALES ANTES DE MOSTRAR EL MODAL
        cargarSucursalesModal();
        
        $("#modal_abrir_caja").modal('show'); //abrimos el modal

        $("#text_descripcion").val('Apertura de Caja');
        $("#text_monto_ini").focus();
        accion = 'registrar_caja_sucursal'; 
    }
    
    /********************************************************************
          CARGAR SUCURSALES EN EL MODAL
    ********************************************************************/
    function cargarSucursalesModal() {
        console.log('[Caja] Cargando sucursales para modal de apertura...');
        
        // Mostrar indicador de carga
        $("#select_sucursal").html('<option value="">🔄 Cargando sucursales...</option>');
        
        $.ajax({
            url: "ajax/aprobacion_ajax.php",
            type: "GET",
            data: { accion: 'listar_sucursales' },
            dataType: 'json',
            success: function(response) {
                console.log('[Caja] Respuesta de sucursales:', response);
                
                let opciones = '<option value="">-- Seleccionar sucursal --</option>';
                
                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(function(sucursal) {
                        // Usar estructura real de la tabla sucursales
                        const sucursalId = sucursal.sucursal_id || sucursal.id;
                        const textoDescriptivo = sucursal.texto_descriptivo || 
                                               sucursal.texto_completo || 
                                               sucursal.sucursal_nombre ||
                                               sucursal.nombre;
                        
                        if (sucursalId && textoDescriptivo) {
                            opciones += `<option value="${sucursalId}">${textoDescriptivo}</option>`;
                        }
                    });
                    console.log(`[Caja] ✅ Cargadas ${response.length} sucursales exitosamente`);
                } else {
                    opciones += '<option value="">No hay sucursales disponibles</option>';
                    console.warn('[Caja] No se encontraron sucursales');
                }
                
                $("#select_sucursal").html(opciones);
            },
            error: function(xhr, status, error) {
                console.error('[Caja] Error al cargar sucursales:', error);
                console.error('[Caja] Respuesta del servidor:', xhr.responseText);
                $("#select_sucursal").html('<option value="">Error al cargar sucursales</option>');
                
                // Mostrar notificación de error
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar las sucursales. Verifique su conexión.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }
        });
    }


    /********************************************************************
          ABRIR MODAL  VER PRESTAMOS POR CAJA 
    ********************************************************************/
    function AbrirModalVerRegistrosPorCaja() {
        $("#modal_registros_caja").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modal_registros_caja").modal('show'); //abrimos el modal

        $("#text_descripcion").val('Ver Registros de Caja');

        // accion = 2;

    }


    /********************************************************************
          ABRIR MODAL CERRAR CAJA 
    ********************************************************************/
    function AbrirModalAbrirCerrarCaja() {
        //para que no se nos salga del modal haciendo click a los costados
        $("#modal_cerrar_caja").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modal_cerrar_caja").modal('show'); //abrimos el modal
        //suma();

    }


    /*===================================================================*/
    //FUNCION PARA CARGAR DATOS CANTIDADES
    /*===================================================================*/
    function CargarDatosCierreCaja() {
        $.ajax({
            async: false,
            url: "ajax/caja_ajax.php",
            method: "POST",
            data: {
                'accion': 3
            },
            dataType: 'json',
            success: function(respuesta) {

                // console.log(respuesta);


                monto_inicial_caja = respuesta["monto_inicial_caja"];
                 suma_prestamo_capital = respuesta["suma_prestamo_capital"]; 
                suma_total = respuesta["suma_total"];
                cant_prestamo = respuesta["cant_prestamo"];
                cant_ingresos = respuesta["cant_ingresos"];
                suma_ingresos = respuesta["suma_ingresos"];
                suma_egresos = respuesta["suma_egresos"];
                cant_egresos = respuesta["cant_egresos"];
                suma_prestamo_interes = respuesta["suma_prestamo_interes"];

                $("#text_monto_aper").val(monto_inicial_caja);
                $("#text_monto_prestamo").val(suma_prestamo_capital); //SETEAMOS EN LOS TEXTBOX
                //$("#text_monto_prestamo").val(suma_total);
                $("#text_cant_prestamo").val(cant_prestamo);
                $("#text_monto_ingreso").val(suma_ingresos);
                $("#text_cant_ingreso").val(cant_ingresos);
                $("#text_monto_egreso").val(suma_egresos);
                $("#text_cant_egreso").val(cant_egresos);
                $("#text_interes").val(suma_prestamo_interes);


            }
        });
    }



    function Sumar() {
        //var suma = 0;
        monto_inicial_caja = $("#text_monto_aper").val();
        suma_prestamo_capital = $("#text_monto_prestamo").val();
        suma_ingresos = $("#text_monto_ingreso").val();
        suma_egresos = $("#text_monto_egreso").val();
        interes = $("#text_interes").val();

        // suma = monto_inicial_caja + suma_prestamo_capital + suma_ingresos;
        //console.log(suma);
        ope = (parseFloat(monto_inicial_caja) + parseFloat(interes) + parseFloat(suma_ingresos));

        suma = (parseFloat(ope - suma_egresos).toFixed(2));



        $("#text_monto_total").val(suma);

    }

    /*===================================================================*/
    //TRAER TODOS LOS PRESTAMOS FINALIZADOS DE LA CAJA ACTUAL
    /*===================================================================*/
    function Traer_RegistrosporIDCaja(caja_id) {
        tbl_resgitros_caja = $("#tbl_resgitros_caja").DataTable({
            responsive: true,
            destroy: true,
            searching: false,
            dom: 'tp',
            ajax: {
                url: "ajax/caja_ajax.php",
                dataSrc: "",
                type: "POST",
                data: {
                    'accion': 7,
                    'caja_id': caja_id
                }, //LISTAR 
            },
            columnDefs: [
               {
                    targets: 1,
                    visible: false

                },{
                    targets: 7,
                    visible: false

                },
                {
                targets: 8,
                //sortable: false,
                createdCell: function(td, cellData, rowData, row, col) {

                    if (rowData[8] == 'aprobado') {
                        $(td).html("<span class='badge badge-success'>aprobado</span>")
                    } else if (rowData[8] == 'finalizado') {
                        $(td).html("<span class='badge badge-info'>finalizado</span>")
                    }else {
                        $(td).html("<span class='badge badge-warning'>pendiente</span>")
                    }

                }
            }

            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var intval = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                total = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intval(a) + intval(b);
                    }, 0);
                pageTotal = api
                    .column(5, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intval(a) + intval(b);
                    }, 0);
                $(api.column(5).footer()).html(
                  '' + pageTotal 
                  //   '' + pageTotal + ' ( ' + total + ' total)'
                );

            },
            

            "language": idioma_espanol,
            select: true
        });
    }


    /*===================================================================*/
    //TRAER TODOS LOS MOVIMIENTOS DE LA CAJA ACTUAL
    /*===================================================================*/
    function Traer_MovimientosporIDCaja(caja_id) {
      tbl_resgitros_movi = $("#tbl_resgitros_movi").DataTable({
            responsive: true,
            destroy: true,
            searching: false,
            dom: 'tp',
            ajax: {
                url: "ajax/caja_ajax.php",
                dataSrc: "",
                type: "POST",
                data: {
                    'accion': 8,
                    'caja_id': caja_id
                }, 
            },
            columnDefs: [
               {
                    targets: 4,
                    visible: false

                }
                

            ],
            
           

            "language": idioma_espanol,
            select: true
        });
    }



    var idioma_espanol = {
        select: {
            rows: "%d fila seleccionada"
        },
        "sProcessing": "Procesando...",
        "sLengthMenu": "Ver _MENU_ ",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "No hay informacion en esta tabla",
        "sInfo": "Mostrando (_START_ a _END_) total de _TOTAL_ registros",
        "sInfoEmpty": "Registros del (0 al 0) total de 0 registros",
        "sInfoFiltered": "(Filtrado de un total de _MAX_ registros)",
        "SInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "<b>No se encontraron datos</b>",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Ultimo",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "aria": {
            "sSortAscending": ": ordenar de manera Ascendente",
            "SSortDescending": ": ordenar de manera Descendente ",
        }
    }

    function seleccionarModulosPerfil(pin_idPerfil) {
    try {
        $('#modulos').jstree('deselect_all');
        
        // Obtener el perfil del usuario
        $.ajax({
            async: false,
            url: "ajax/perfil_ajax.php",
            method: 'POST',
            data: {
                accion: "obtener_perfil",
                id_perfil: pin_idPerfil
            },
            dataType: 'json',
            success: function(perfil) {
                // Si es un perfil administrativo, seleccionar todos los módulos
                if (perfil && ['administrador', 'developer senior', 'super administrador'].includes(perfil.descripcion.toLowerCase())) {
                    $('#modulos').jstree('select_all');
        } else {
                    // Para otros perfiles, seleccionar solo los módulos asignados
                    for (let i = 0; i < modulos_sistema.length; i++) {
                        if (parseInt(modulos_sistema[i]["id"]) == parseInt(modulos_usuario[i]["id"]) && 
                            parseInt(modulos_usuario[i]["sel"]) == 1) {
                            $("#modulos").jstree("select_node", modulos_sistema[i]["id"]);
                        }
                    }
                }
            },
            error: function(error) {
                console.error("Error al obtener perfil:", error);
            }
        });
    } catch (error) {
        console.error("Error en seleccionarModulosPerfil:", error);
    }
}

    // Exponer funciones al ámbito global para los onclick (asegurando que existan)
    window.AbrirModalAbrirCaja = AbrirModalAbrirCaja;
    window.cerrarCaja = typeof cerrarCaja === 'function' ? cerrarCaja : function() { console.warn('cerrarCaja no implementada o accesible.'); Swal.fire({ icon: 'info', title: 'Función no implementada', text: 'La función de cierre de caja aún no está completamente implementada o accesible.' }); };
    window.realizarConteoFisico = typeof realizarConteoFisico === 'function' ? realizarConteoFisico : function() { console.warn('realizarConteoFisico no implementada o accesible.'); Swal.fire({ icon: 'info', title: 'Función no implementada', text: 'La función de conteo físico aún no está completamente implementada o accesible.' }); };
    window.generarReporte = typeof generarReporte === 'function' ? generarReporte : function() { console.warn('generarReporte no implementada o accesible.'); Swal.fire({ icon: 'info', title: 'Función no implementada', text: 'La función de generar reporte aún no está completamente implementada o accesible.' }); };

    // =================================================================================================
    // CIERRE DE DÍA
    // =================================================================================================
    // function generarCierreDia() { ... } // Función movida a global_functions.js

    // =================================================================================================
    // Asignación de eventos y funciones globales
    // =================================================================================================
    
    $(document).ready(function() {
        // Asignar el evento al botón de apertura (delegado a global_functions.js si es un click en id)
        $("#btnAbrirCaja, .btnAbrirCaja").off('click').on('click', function(e) {
            e.preventDefault();
            window.abrirCaja(); // Llama a la función globalmente expuesta
        });

        // Asignar eventos a otros botones si tienen IDs específicos en esta vista y no en el dashboard
        // $("#btnCierreDiaRapido").off('click').on('click', window.cerrarCaja);
        // (Asegurarse de que cualquier otro botón con un ID específico aquí llame a la función global)

    });

})(); 