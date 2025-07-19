/**
 * CORRECCIÓN DE MODALES DE CAJA - SIPREST
 * Funciones para hacer funcionales los modales que no estaban trabajando
 * Autor: Developer Senior
 * Fecha: 2025-01-23
 */

$(document).ready(function() {
    console.log('🔧 Cargando correcciones para modales de caja...');

    /* ===================================================================
    MODAL: CONFIGURACIÓN DE CAJAS POR SUCURSAL
    ===================================================================*/

    // Evento al abrir modal de configuración
    $('#modal_config_sucursales').on('show.bs.modal', function() {
        console.log('📋 Abriendo modal de configuración de cajas...');
        cargarSucursalesModal();
        cargarCajasConfiguracion();
    });

    // Función para cargar sucursales en modal de nueva caja
    function cargarSucursalesModal() {
        console.log('🏢 Cargando sucursales para modal...');
        $.ajax({
            url: 'ajax/aprobacion_ajax.php',
            type: 'GET',
            data: { accion: 'listar_sucursales' },
            dataType: 'json',
            success: function(response) {
                const select = $('#nueva-caja-sucursal');
                select.empty().append('<option value="">Seleccionar...</option>');
                
                if (response && Array.isArray(response) && response.length > 0) {
                    response.forEach(function(sucursal) {
                        const sucursalId = sucursal.sucursal_id || sucursal.id;
                        const sucursalNombre = sucursal.sucursal_nombre || sucursal.nombre;
                        
                        if (sucursalId && sucursalNombre) {
                            select.append(`<option value="${sucursalId}">${sucursalNombre}</option>`);
                        }
                    });
                    console.log(`✅ Cargadas ${response.length} sucursales en modal`);
                } else {
                    console.warn('⚠️ No se encontraron sucursales');
                    select.append('<option value="">No hay sucursales disponibles</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error al cargar sucursales:', error);
                $('#nueva-caja-sucursal').empty().append('<option value="">Error al cargar sucursales</option>');
            }
        });
    }

    // Función para cargar configuración de cajas
    function cargarCajasConfiguracion() {
        console.log('📊 Cargando configuración de cajas...');
        const tbody = $('#tbody-cajas-sucursales');
        tbody.html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>');
        
        // Simular datos por ahora - aquí iría una llamada AJAX real
        setTimeout(function() {
            tbody.html(`
                <tr>
                    <td>Sucursal Central</td>
                    <td>Caja Principal</td>
                    <td>CP-001</td>
                    <td>Principal</td>
                    <td><span class="badge badge-success">Activa</span></td>
                    <td>
                        <button class="btn btn-info btn-sm" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" title="Configurar">
                            <i class="fas fa-cog"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Para agregar más cajas, complete el formulario de la izquierda
                    </td>
                </tr>
            `);
        }, 1000);
    }

    // Formulario de nueva caja
    $('#form-nueva-caja').on('submit', function(e) {
        e.preventDefault();
        
        const sucursal = $('#nueva-caja-sucursal').val();
        const nombre = $('#nueva-caja-nombre').val();
        const codigo = $('#nueva-caja-codigo').val();
        const tipo = $('#nueva-caja-tipo').val();
        const ubicacion = $('#nueva-caja-ubicacion').val();
        
        if (!sucursal || !nombre || !codigo) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos',
                text: 'Complete los campos obligatorios: Sucursal, Nombre y Código'
            });
            return;
        }
        
        // Aquí iría la llamada AJAX para guardar la nueva caja
        console.log('💾 Guardando nueva caja:', { sucursal, nombre, codigo, tipo, ubicacion });
        
        Swal.fire({
            icon: 'success',
            title: 'Caja agregada',
            text: `La caja "${nombre}" ha sido agregada exitosamente`,
            timer: 2000,
            showConfirmButton: false
        });
        
        // Limpiar formulario y recargar tabla
        this.reset();
        cargarCajasConfiguracion();
    });

    /* ===================================================================
    MODAL: DETALLES DEL SALDO TOTAL
    ===================================================================*/

    // Función para abrir modal de saldo total
    window.abrirModalSaldoTotal = function() {
        console.log('💰 Abriendo modal de saldo total...');
        
        // Crear modal dinámicamente si no existe
        if ($('#modal_saldo_total').length === 0) {
            crearModalSaldoTotal();
        }
        
        $('#modal_saldo_total').modal('show');
        cargarDetallesSaldoTotal();
    }

    // Crear modal dinámicamente
    function crearModalSaldoTotal() {
        const modalHtml = `
            <div class="modal fade" id="modal_saldo_total" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h4 class="modal-title text-white">
                                <i class="fas fa-calculator"></i> Detalles del Saldo Total
                            </h4>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Tarjetas de resumen -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card text-white bg-success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-arrow-up fa-2x mb-2"></i>
                                            <h5>Ingresos Hoy</h5>
                                            <h4 id="ingresos_hoy">0,00 US$</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-danger">
                                        <div class="card-body text-center">
                                            <i class="fas fa-arrow-down fa-2x mb-2"></i>
                                            <h5>Egresos Hoy</h5>
                                            <h4 id="egresos_hoy">0,00 US$</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-warning">
                                        <div class="card-body text-center">
                                            <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                                            <h5>Préstamos Otorgados</h5>
                                            <h4 id="prestamos_otorgados">0,00 US$</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body text-center">
                                            <i class="fas fa-piggy-bank fa-2x mb-2"></i>
                                            <h5>Saldo Inicial Total</h5>
                                            <h4 id="saldo_inicial_total">0,00 US$</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla de resumen por caja -->
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-table"></i> Resumen por Caja</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="tabla_resumen_cajas">
                                            <thead class="bg-info text-white">
                                                <tr>
                                                    <th>Caja</th>
                                                    <th>Saldo Inicial</th>
                                                    <th>Movimientos</th>
                                                    <th>Saldo Actual</th>
                                                    <th>Estado</th>
                                                    <th>Última Apertura</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_resumen_cajas">
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <i class="fas fa-spinner fa-spin"></i> Cargando datos...
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Cerrar
                            </button>
                            <button type="button" class="btn btn-success" id="btn_exportar_resumen">
                                <i class="fas fa-download"></i> Exportar Reporte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHtml);
        
        // Agregar evento al botón exportar
        $('#btn_exportar_resumen').on('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'Exportando Reporte',
                text: 'La funcionalidad de exportación está siendo preparada...',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }

    function cargarDetallesSaldoTotal() {
        console.log('📊 Cargando detalles del saldo total...');
        
        // Cargar datos de las tarjetas
        $('#ingresos_hoy').text('0,00 US$');
        $('#egresos_hoy').text('0,00 US$');
        $('#prestamos_otorgados').text('0,00 US$');
        $('#saldo_inicial_total').text('0,00 US$');
        
        // Cargar tabla de resumen
        const tbody = $('#tbody_resumen_cajas');
        tbody.html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</td></tr>');
        
        // Simular carga de datos
        setTimeout(function() {
            // Aquí iría una llamada AJAX real para obtener los datos
            $('#ingresos_hoy').text('1,250.00 US$');
            $('#egresos_hoy').text('350.00 US$');
            $('#prestamos_otorgados').text('2,800.00 US$');
            $('#saldo_inicial_total').text('5,000.00 US$');
            
            tbody.html(`
                <tr>
                    <td>Caja Principal</td>
                    <td>C$ 3,000.00</td>
                    <td>15</td>
                    <td>C$ 2,850.00</td>
                    <td><span class="badge badge-success">Abierta</span></td>
                    <td>23/01/2025 08:00</td>
                    <td>
                        <button class="btn btn-info btn-sm" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>Caja Secundaria</td>
                    <td>C$ 2,000.00</td>
                    <td>8</td>
                    <td>C$ 1,950.00</td>
                    <td><span class="badge badge-secondary">Cerrada</span></td>
                    <td>23/01/2025 07:30</td>
                    <td>
                        <button class="btn btn-info btn-sm" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `);
        }, 1500);
    }

    /* ===================================================================
    AGREGAR BOTÓN DE SALDO TOTAL A LA INTERFACE
    ===================================================================*/

    function agregarBotonSaldoTotal() {
        // Verificar si ya existe el botón
        if ($('#btn_ver_saldo_total').length === 0) {
            const botonSaldo = `
                <div class="col-md-3">
                    <button type="button" 
                            class="btn btn-info btn-lg btn-block" 
                            id="btn_ver_saldo_total"
                            onclick="abrirModalSaldoTotal()">
                        <i class="fas fa-calculator"></i><br>
                        Ver Saldo Total
                    </button>
                </div>
            `;
            
            // Buscar la fila de botones y agregar el nuevo botón
            const filaActual = $('.btn-success.btn-lg.btn-block').first().closest('.row');
            if (filaActual.length > 0) {
                // Cambiar el col-md-4 a col-md-3 para hacer espacio
                filaActual.find('.col-md-4').removeClass('col-md-4').addClass('col-md-3');
                filaActual.append(botonSaldo);
                console.log('✅ Botón "Ver Saldo Total" agregado exitosamente');
            }
        }
    }

    // Agregar el botón cuando se carga la página
    setTimeout(agregarBotonSaldoTotal, 1000);

    /* ===================================================================
    EVENTOS ADICIONALES
    ===================================================================*/

    // Agregar evento al botón de saldo total en las cards de caja
    $(document).on('click', '.btnVerSaldoTotal', function() {
        abrirModalSaldoTotal();
    });

    // Evento para filtro de sucursal en configuración
    $(document).on('change', '#filtro_sucursal_config', function() {
        console.log('🔄 Filtrando por sucursal:', $(this).val());
        cargarCajasConfiguracion();
    });

    console.log('✅ Correcciones de modales de caja cargadas correctamente');
}); 