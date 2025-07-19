/**
 * COMBOS MEJORADOS - Sistema de combos/dropdowns optimizados
 * Autor: Sistema de Gestión
 * Fecha: 2025
 * 
 * Este archivo contiene funciones reutilizables para mejorar los combos/select
 * del sistema con Select2, datos completos y mejor visualización.
 */

// Usar IIFE para evitar redeclaraciones y conflictos globales
(function() {
    'use strict';
    
    // Verificar si ya está cargado
    if (typeof window.CombosMejoradosLoaded !== 'undefined') {
        console.log('[Combos] Ya cargado, evitando redeclaración');
        return;
    }
    
    // Marcar como cargado
    window.CombosMejoradosLoaded = true;
    console.log('[Combos] Iniciando carga de CombosMejorados');

    $(document).ready(function() {
        console.log('[Combos] Sistema de combos cargando...');
        
        // Verificar dependencias
        if (typeof $ === 'undefined') {
            console.error('[Combos] jQuery no está disponible');
            return;
        }
        
        if (typeof $.fn.select2 === 'undefined') {
            console.warn('[Combos] Select2 no está disponible. Algunas funciones pueden no funcionar correctamente.');
        }
        
        if (typeof $.fn.DataTable === 'undefined') {
            console.warn('[Combos] DataTables no está disponible. Algunas funciones pueden no funcionar correctamente.');
        }
        console.log('[Combos] Combos Mejorados cargado correctamente');
    });

    class CombosMejorados {
        constructor() {
            this.defaultSelect2Config = {
                language: 'es',
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                escapeMarkup: function(markup) { return markup; }
            };
        }

        /**
         * Inicializar Select2 en un elemento con configuración personalizada
         */
        initSelect2(selector, config = {}) {
            try {
                if (typeof $.fn.select2 !== 'undefined') {
                    const element = $(selector);
                    
                    // Destruir instancia existente si la hay
                    if (element.hasClass('select2-hidden-accessible')) {
                        element.select2('destroy');
                    }
                    
                    // Configuración final
                    const finalConfig = $.extend({}, this.defaultSelect2Config, config);
                    
                    // Inicializar
                    element.select2(finalConfig);
                    
                    return true;
                }
            } catch (error) {
                console.error('Error inicializando Select2:', error);
            }
            return false;
        }

        /**
         * Cargar sucursales con datos completos
         */
        cargarSucursales(selector, config = {}) {
            const defaultConfig = {
                placeholder: 'Buscar sucursal...',
                templateResult: this.templateSucursal,
                templateSelection: this.templateSucursalSelection
            };
            
            const finalConfig = $.extend({}, defaultConfig, config);
            
            // Mostrar indicador de carga
            $(selector).html('<option value="">🔄 Cargando sucursales...</option>').addClass('combo-loading');
            
            return $.ajax({
                url: 'ajax/aprobacion_ajax.php',
                type: 'GET',
                data: { accion: 'listar_sucursales' },
                dataType: 'json',
                success: (response) => {
                    let opciones = '<option value="">-- Seleccione Sucursal --</option>';
                    
                    if (Array.isArray(response)) {
                        response.forEach(sucursal => {
                            const textoDescriptivo = sucursal.texto_descriptivo || 
                                                   sucursal.texto_completo || 
                                                   `${sucursal.sucursal_codigo} - ${sucursal.sucursal_nombre}`;
                            
                            opciones += `<option value="${sucursal.sucursal_id}" 
                                                data-nombre="${sucursal.sucursal_nombre}"
                                                data-codigo="${sucursal.sucursal_codigo}"
                                                data-direccion="${sucursal.sucursal_direccion || ''}"
                                                data-telefono="${sucursal.sucursal_telefono || ''}"
                                                data-rutas="${sucursal.total_rutas || 0}"
                                                data-usuarios="${sucursal.total_usuarios || 0}"
                                                title="${textoDescriptivo}">
                                            ${textoDescriptivo}
                                        </option>`;
                        });
                        
                        $(selector).html(opciones).removeClass('combo-loading');
                        this.initSelect2(selector, finalConfig);
                        
                        console.log(`✅ Cargadas ${response.length} sucursales`);
                    } else {
                        $(selector).html('<option value="">❌ Error al cargar sucursales</option>').removeClass('combo-loading');
                    }
                },
                error: (error) => {
                    console.error('Error al cargar sucursales:', error);
                    $(selector).html('<option value="">❌ Error al cargar sucursales</option>').removeClass('combo-loading');
                }
            });
        }

        /**
         * Cargar rutas por sucursal con datos completos
         */
        cargarRutas(selector, sucursalId, config = {}) {
            const defaultConfig = {
                placeholder: 'Buscar ruta...',
                templateResult: this.templateRuta,
                templateSelection: this.templateRutaSelection
            };
            
            const finalConfig = $.extend({}, defaultConfig, config);
            
            // Limpiar y mostrar opción por defecto
            $(selector).html('<option value="">-- Seleccione Ruta --</option>');
            
            // Destruir Select2 si existe
            if ($(selector).hasClass('select2-hidden-accessible')) {
                $(selector).select2('destroy');
            }
            
            if (!sucursalId) {
                this.initSelect2(selector, finalConfig);
                return Promise.resolve();
            }
            
            // Mostrar indicador de carga
            $(selector).html('<option value="">🔄 Cargando rutas...</option>').addClass('combo-loading');
            
            return $.ajax({
                url: 'ajax/aprobacion_ajax.php',
                type: 'POST',
                data: {
                    accion: 'listar_rutas_sucursal',
                    sucursal_id: sucursalId
                },
                dataType: 'json',
                success: (response) => {
                    let opciones = '<option value="">-- Seleccione Ruta --</option>';
                    
                    if (Array.isArray(response)) {
                        response.forEach(ruta => {
                            const textoDescriptivo = ruta.texto_descriptivo || 
                                                   ruta.texto_completo || 
                                                   `${ruta.ruta_codigo} - ${ruta.ruta_nombre}`;
                            
                            opciones += `<option value="${ruta.ruta_id}"
                                                data-nombre="${ruta.ruta_nombre}"
                                                data-codigo="${ruta.ruta_codigo}"
                                                data-descripcion="${ruta.ruta_descripcion || ''}"
                                                data-color="${ruta.ruta_color || '#3498db'}"
                                                data-clientes="${ruta.total_clientes || 0}"
                                                data-usuarios="${ruta.total_usuarios || 0}"
                                                title="${textoDescriptivo}">
                                            ${textoDescriptivo}
                                        </option>`;
                        });
                        
                        $(selector).html(opciones).removeClass('combo-loading');
                        this.initSelect2(selector, finalConfig);
                        
                        console.log(`✅ Cargadas ${response.length} rutas para sucursal ${sucursalId}`);
                    } else {
                        $(selector).html('<option value="">❌ Error al cargar rutas</option>').removeClass('combo-loading');
                    }
                },
                error: (error) => {
                    console.error('Error al cargar rutas:', error);
                    $(selector).html('<option value="">❌ Error al cargar rutas</option>').removeClass('combo-loading');
                }
            });
        }

        /**
         * Cargar cobradores/usuarios por ruta con datos completos
         */
        cargarCobradores(selector, rutaId, config = {}) {
            const defaultConfig = {
                placeholder: 'Buscar cobrador...',
                templateResult: this.templateCobrador,
                templateSelection: this.templateCobradorSelection
            };
            
            const finalConfig = $.extend({}, defaultConfig, config);
            
            // Limpiar y mostrar opción por defecto
            $(selector).html('<option value="">-- Seleccione Cobrador --</option>');
            
            // Destruir Select2 si existe
            if ($(selector).hasClass('select2-hidden-accessible')) {
                $(selector).select2('destroy');
            }
            
            if (!rutaId) {
                this.initSelect2(selector, finalConfig);
                return Promise.resolve();
            }
            
            // Mostrar indicador de carga
            $(selector).html('<option value="">🔄 Cargando cobradores...</option>');
            
            return $.ajax({
                url: 'ajax/aprobacion_ajax.php',
                type: 'GET',
                data: {
                    accion: 'listar_cobradores'
                },
                dataType: 'json',
                success: (response) => {
                    let opciones = '<option value="">-- Seleccione Cobrador --</option>';
                    
                    if (response.estado === 'ok' && Array.isArray(response.data)) {
                        response.data.forEach(cobrador => {
                            const textoDescriptivo = `${cobrador.usuario} - ${cobrador.nombre_usuario}`;
                            
                            opciones += `<option value="${cobrador.id_usuario}"
                                                data-usuario="${cobrador.usuario}"
                                                data-nombre="${cobrador.nombre_usuario}"
                                                title="${textoDescriptivo}">
                                            ${textoDescriptivo}
                                        </option>`;
                        });
                        
                        $(selector).html(opciones);
                        this.initSelect2(selector, finalConfig);
                        
                        console.log(`✅ Cargados ${response.data.length} cobradores`);
                    } else if (response.estado === 'info') {
                        $(selector).html(`<option value="">ℹ️ ${response.mensaje}</option>`);
                        console.info('Info:', response.mensaje);
                    } else {
                        $(selector).html('<option value="">❌ Error al cargar cobradores</option>');
                        console.error('Respuesta inválida:', response);
                    }
                },
                error: (xhr, status, error) => {
                    let errorMessage = 'Error desconocido al cargar cobradores';
                    
                    try {
                        // Intentar obtener mensaje de error del servidor
                        const errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse.mensaje) {
                            errorMessage = errorResponse.mensaje;
                        }
                    } catch (e) {
                        // Si no se puede parsear la respuesta, usar el mensaje de error genérico
                        if (error) {
                            errorMessage = `Error: ${error}`;
                        } else if (status) {
                            errorMessage = `Estado: ${status}`;
                        }
                    }
                    
                    console.error('Error al cargar cobradores:', errorMessage);
                    $(selector).html(`<option value="">❌ ${errorMessage}</option>`);
                }
            });
        }

        /**
         * Cargar clientes con datos completos
         */
        cargarClientes(selector, config = {}) {
            const defaultConfig = {
                placeholder: 'Buscar cliente por nombre o DNI...',
                templateResult: this.templateCliente,
                templateSelection: this.templateClienteSelection
            };
            
            const finalConfig = $.extend({}, defaultConfig, config);
            
            // Mostrar indicador de carga
            $(selector).html('<option value="">🔄 Cargando clientes...</option>');
            
            return $.ajax({
                url: 'ajax/clientes_ajax.php',
                type: 'GET',
                data: { accion: 'listar_select' },
                dataType: 'json',
                success: (response) => {
                    let opciones = '<option value="">-- Seleccione Cliente --</option>';
                    
                    if (Array.isArray(response)) {
                        response.forEach(cliente => {
                            const textoCompleto = `${cliente.cliente_nombres} ${cliente.cliente_apellidos || ''} - ${cliente.cliente_dni}`.trim();
                            
                            opciones += `<option value="${cliente.cliente_id}"
                                                data-nombres="${cliente.cliente_nombres}"
                                                data-apellidos="${cliente.cliente_apellidos || ''}"
                                                data-dni="${cliente.cliente_dni}"
                                                data-telefono="${cliente.cliente_telefono || ''}"
                                                title="${textoCompleto}">
                                            ${textoCompleto}
                                        </option>`;
                        });
                        
                        $(selector).html(opciones);
                        this.initSelect2(selector, finalConfig);
                        
                        console.log(`✅ Cargados ${response.length} clientes`);
                    } else {
                        $(selector).html('<option value="">❌ Error al cargar clientes</option>');
                    }
                },
                error: (error) => {
                    console.error('Error al cargar clientes:', error);
                    $(selector).html('<option value="">❌ Error al cargar clientes</option>');
                }
            });
        }

        /**
         * Template personalizado para sucursales
         */
        templateSucursal(option) {
            if (!option.id) return option.text;
            
            const $option = $(option.element);
            const nombre = $option.data('nombre') || '';
            const codigo = $option.data('codigo') || '';
            const direccion = $option.data('direccion') || '';
            const rutas = $option.data('rutas') || 0;
            const usuarios = $option.data('usuarios') || 0;
            
            return $(`
                <div class="select2-result-sucursal">
                    <div class="select2-result-sucursal__name">
                        <strong>${codigo} - ${nombre}</strong>
                    </div>
                    <div class="select2-result-sucursal__details">
                        <small class="text-muted">
                            ${direccion ? `${direccion}<br/>` : ''}
                            ${rutas} rutas | ${usuarios} usuarios
                        </small>
                    </div>
                </div>
            `);
        }

        templateSucursalSelection(option) {
            if (!option.id) return option.text;
            
            const $option = $(option.element);
            const codigo = $option.data('codigo') || '';
            const nombre = $option.data('nombre') || '';
            
            return `${codigo} - ${nombre}`;
        }

        /**
         * Template personalizado para rutas
         */
        templateRuta(option) {
            if (!option.id) return option.text;
            
            const $option = $(option.element);
            const nombre = $option.data('nombre') || '';
            const codigo = $option.data('codigo') || '';
            const descripcion = $option.data('descripcion') || '';
            const color = $option.data('color') || '#3498db';
            const clientes = $option.data('clientes') || 0;
            const usuarios = $option.data('usuarios') || 0;
            
            return $(`
                <div class="select2-result-ruta">
                    <div class="select2-result-ruta__name">
                        <strong>${codigo} - ${nombre}</strong>
                    </div>
                    <div class="select2-result-ruta__details">
                        <small class="text-muted">
                            ${descripcion ? `${descripcion}<br/>` : ''}
                            ${clientes} clientes | ${usuarios} usuarios asignados
                        </small>
                    </div>
                </div>
            `);
        }

        templateRutaSelection(option) {
            if (!option.id) return option.text;
            
            const $option = $(option.element);
            const codigo = $option.data('codigo') || '';
            const nombre = $option.data('nombre') || '';
            
            return `${codigo} - ${nombre}`;
        }

        /**
         * Template personalizado para cobradores
         */
        templateCobrador(option) {
            if (!option.id) return option.text;
            
            const $option = $(option.element);
            const usuario = $option.data('usuario') || '';
            const nombre = $option.data('nombre') || '';
            const sucursal = $option.data('sucursal') || '';
            const perfil = $option.data('perfil') || '';
            const tipo = $option.data('tipo') || '';
            const fecha = $option.data('fecha') || '';
            const estado = $option.data('estado') || '';
            
            return $(`
                <div class="select2-result-cobrador">
                    <div class="select2-result-cobrador__name">
                        <strong>${usuario} - ${nombre}</strong>
                        <span class="badge badge-info">${tipo}</span>
                    </div>
                    <div class="select2-result-cobrador__details">
                        <small class="text-muted">
                            ${sucursal} | ${perfil}<br/>
                            Asignado: ${fecha} | ${estado}
                        </small>
                    </div>
                </div>
            `);
        }

        templateCobradorSelection(option) {
            if (!option.id) return option.text;
            
            const $option = $(option.element);
            const usuario = $option.data('usuario') || '';
            const nombre = $option.data('nombre') || '';
            
            return `${usuario} - ${nombre}`;
        }

        /**
         * Template personalizado para clientes
         */
        templateCliente(option) {
            if (!option.id) return option.text;
            
            const $option = $(option.element);
            const nombres = $option.data('nombres') || '';
            const apellidos = $option.data('apellidos') || '';
            const dni = $option.data('dni') || '';
            const telefono = $option.data('telefono') || '';
            
            return $(`
                <div class="select2-result-cliente">
                    <div class="select2-result-cliente__name">
                        <strong>${nombres} ${apellidos}</strong>
                    </div>
                    <div class="select2-result-cliente__details">
                        <small class="text-muted">
                            DNI: ${dni}
                            ${telefono ? ` | Tel: ${telefono}` : ''}
                        </small>
                    </div>
                </div>
            `);
        }

        templateClienteSelection(option) {
            if (!option.id) return option.text;
            
            const $option = $(option.element);
            const nombres = $option.data('nombres') || '';
            const apellidos = $option.data('apellidos') || '';
            const dni = $option.data('dni') || '';
            
            return `${nombres} ${apellidos} - ${dni}`.trim();
        }

        /**
         * Configurar cascada de combos (Sucursal -> Ruta -> Cobrador)
         */
        configurarCascada(selectorSucursal, selectorRuta, selectorCobrador, config = {}) {
            // Cargar sucursales inicialmente
            this.cargarSucursales(selectorSucursal, config.sucursal || {});
            
            // Configurar evento de cambio de sucursal
            $(document).on('change', selectorSucursal, (e) => {
                const sucursalId = $(e.target).val();
                this.cargarRutas(selectorRuta, sucursalId, config.ruta || {});
                
                // Limpiar cobrador
                $(selectorCobrador).html('<option value="">-- Seleccione Cobrador --</option>');
                if ($(selectorCobrador).hasClass('select2-hidden-accessible')) {
                    $(selectorCobrador).select2('destroy');
                }
                this.initSelect2(selectorCobrador, config.cobrador || {});
            });
            
            // Configurar evento de cambio de ruta
            $(document).on('change', selectorRuta, (e) => {
                const rutaId = $(e.target).val();
                this.cargarCobradores(selectorCobrador, rutaId, config.cobrador || {});
            });
        }
    }
    
    // Instancia global - Evitar redeclaración
    if (typeof window.CombosMejorados === 'undefined') {
        window.CombosMejorados = new CombosMejorados();
        console.log('[Combos] CombosMejorados instanciado globalmente');
    }
    
    // Funciones de conveniencia para compatibilidad
    window.cargarSucursales = window.cargarSucursales || function(selector, config) {
        return window.CombosMejorados.cargarSucursales(selector, config);
    };
    
    window.cargarRutas = window.cargarRutas || function(selector, sucursalId, config) {
        return window.CombosMejorados.cargarRutas(selector, sucursalId, config);
    };
    
    window.cargarCobradores = window.cargarCobradores || function(selector, rutaId, config) {
        return window.CombosMejorados.cargarCobradores(selector, rutaId, config);
    };
    
    window.cargarClientes = window.cargarClientes || function(selector, config) {
        return window.CombosMejorados.cargarClientes(selector, config);
    };
    
    // Configuración automática al cargar el documento
    $(document).ready(function() {
        // Auto-detectar modales de asignación y configurar cascada
        if ($('#modal-asignacion-ruta').length && $('#select_sucursal_asignacion').length) {
            window.CombosMejorados.configurarCascada(
                '#select_sucursal_asignacion',
                '#select_ruta_asignacion', 
                '#select_cobrador_asignacion',
                {
                    sucursal: { dropdownParent: $('#modal-asignacion-ruta') },
                    ruta: { dropdownParent: $('#modal-asignacion-ruta') },
                    cobrador: { dropdownParent: $('#modal-asignacion-ruta') }
                }
            );
        }
        
        // Auto-detectar filtros de reportes
        if ($('#filtro_sucursal').length) {
            window.CombosMejorados.cargarSucursales('#filtro_sucursal');
            
            $('#filtro_sucursal').on('change', function() {
                const sucursalId = $(this).val();
                window.CombosMejorados.cargarRutas('#filtro_ruta', sucursalId);
            });
        }
        
        // Auto-detectar selector de clientes
        if ($('#cliente_estado_cuenta').length) {
            window.CombosMejorados.cargarClientes('#cliente_estado_cuenta');
        }
    });


})(); // Fin del IIFE de protección

