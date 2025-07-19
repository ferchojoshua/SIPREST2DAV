$(document).ready(function() {
    let tablaSucursales = $('#tabla-sucursales').DataTable({
        "ajax": {
            "url": "ajax/sucursales_ajax.php?accion=listar",
            "dataSrc": ""
        },
        "columns": [
            { "data": "id" },
            { "data": "nombre" },
            { "data": "direccion" },
            { "data": "telefono" },
            { "data": "codigo" },
            { 
                "data": "estado",
                "render": function(data, type, row) {
                    return data == 'activa' ? '<span class="badge badge-success">Activa</span>' : '<span class="badge badge-danger">Inactiva</span>';
                }
            },
            { 
                "data": null,
                "defaultContent": '<button class="btn btn-warning btn-sm btn-editar"><i class="fas fa-edit"></i></button> <button class="btn btn-danger btn-sm btn-eliminar"><i class="fas fa-trash"></i></button>'
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        }
    });

    // Validación en tiempo real
    const validationRules = {
        nombre: {
            required: true,
            minLength: 2,
            maxLength: 100,
            pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/,
            message: 'El nombre debe contener solo letras y espacios (2-100 caracteres)'
        },
        direccion: {
            required: false,
            maxLength: 255,
            message: 'La dirección no puede exceder 255 caracteres'
        },
        telefono: {
            required: false,
            pattern: /^[\d\s\-\+\(\)]+$/,
            minLength: 7,
            maxLength: 15,
            message: 'El teléfono debe contener solo números, espacios, guiones y paréntesis (7-15 caracteres)'
        },
        codigo: {
            required: true,
            minLength: 2,
            maxLength: 10,
            pattern: /^[A-Z0-9]+$/i, // Agregado 'i' para hacer case-insensitive
            message: 'El código debe contener solo letras y números (2-10 caracteres)'
        }
    };

    // Función para validar un campo específico
    function validateField(field, value) {
        const rules = validationRules[field];
        if (!rules) return true;

        if (rules.required && !value) {
            return false;
        }

        if (value) {
            if (rules.minLength && value.length < rules.minLength) return false;
            if (rules.maxLength && value.length > rules.maxLength) return false;
            if (rules.pattern && !rules.pattern.test(value)) return false;
        }

        return true;
    }

    // Función para validar todo el formulario
    function validateForm() {
        const errors = [];
        const fields = ['nombre', 'codigo', 'direccion', 'telefono'];
        
        fields.forEach(field => {
            const value = $(`#${field}`).val().trim();
            if (!validateField(field, value)) {
                errors.push(validationRules[field].message);
            }
        });

        // Validar estado
        if (!$('#estado').val()) {
            errors.push('Debe seleccionar un estado');
        }

        return {
            valid: errors.length === 0,
            errors: errors
        };
    }

    // Función para verificar duplicados
    async function checkDuplicates(codigo, nombre, id = null) {
        try {
            
            const response = await $.ajax({
                url: 'ajax/sucursales_ajax.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    accion: 'verificar_duplicados',
                    codigo: codigo,
                    nombre: nombre,
                    id: id
                }
            });
            
            return response;
            
        } catch (error) {
            console.error('❌ Error en checkDuplicates:', error);
            return {
                valid: false,
                message: 'Error al verificar duplicados: ' + error.message
            };
        }
    }

    // Función para mostrar estado de carga
    function setLoadingState(isLoading) {
        const btnGuardar = $('#btn-guardar-sucursal');
        const btnCerrar = $('.btn[data-dismiss="modal"]');
        
        if (isLoading) {
            btnGuardar.prop('disabled', true)
                     .html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            btnCerrar.prop('disabled', true);
        } else {
            btnGuardar.prop('disabled', false)
                     .html('<i class="fas fa-save"></i> Guardar');
            btnCerrar.prop('disabled', false);
        }
    }

    // Limpiar modal al abrir
    $('#modal-sucursal').on('show.bs.modal', function (event) {
        // Solo limpiar si es una nueva sucursal
        if ($(event.relatedTarget).hasClass('btn-nueva-sucursal')) {
            $('#form-sucursal')[0].reset();
            $('#id').val('');
            $('.modal-title').text('Nueva Sucursal');
            
            // Limpiar validaciones visuales
            $('#form-sucursal .form-control').removeClass('is-valid is-invalid');
            $('#form-sucursal .invalid-feedback').hide();
        }
    });

    // Manejadores para cerrar el modal
    $('#modal-sucursal .close, #modal-sucursal .btn[data-dismiss="modal"]').on('click', function() {
        $('#modal-sucursal').modal('hide');
    });

    // Cerrar modal con ESC
    $(document).on('keydown', function(event) {
        if (event.key === 'Escape' && $('#modal-sucursal').hasClass('show')) {
            $('#modal-sucursal').modal('hide');
        }
    });

    // Botón Nueva Sucursal
    $('.btn-nueva-sucursal').on('click', function() {
        $('#form-sucursal')[0].reset();
        $('#id').val('');
        $('.modal-title').text('Nueva Sucursal');
        
        // Limpiar validaciones visuales
        $('#form-sucursal .form-control').removeClass('is-valid is-invalid');
        $('#form-sucursal .invalid-feedback').hide();
    });

    // Guardar sucursal - VERSIÓN MEJORADA
    $('#btn-guardar-sucursal').on('click', async function() {
        
        // Validar formulario
        const formValidation = validateForm();
        if (!formValidation.valid) {
            Swal.fire({
                icon: 'error',
                title: 'Errores de Validación',
                html: formValidation.errors.map(error => `• ${error}`).join('<br>'),
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Obtener datos del formulario
        const formData = {
            id: $('#id').val(),
            nombre: $('#nombre').val().trim(),
            codigo: $('#codigo').val().trim().toUpperCase(),
            direccion: $('#direccion').val().trim(),
            telefono: $('#telefono').val().trim(),
            estado: $('#estado').val()
        };

        // Verificar duplicados
        setLoadingState(true);
        const duplicateCheck = await checkDuplicates(formData.codigo, formData.nombre, formData.id);
        
        if (!duplicateCheck.valid) {
            setLoadingState(false);
            Swal.fire({
                icon: 'error',
                title: 'Datos Duplicados',
                text: duplicateCheck.message,
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Confirmar acción
        const action = formData.id ? 'actualizar' : 'crear';
        const confirmResult = await Swal.fire({
            title: `¿Confirmar ${action} sucursal?`,
            text: `Se ${action === 'crear' ? 'creará' : 'actualizará'} la sucursal "${formData.nombre}"`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Sí, ${action}`,
            cancelButtonText: 'Cancelar'
        });

        if (!confirmResult.isConfirmed) {
            setLoadingState(false);
            return;
        }

        // Preparar datos para envío
        const datos = new FormData($('#form-sucursal')[0]);
        datos.append('accion', 'guardar');
        
        // Enviar datos
        $.ajax({
            url: 'ajax/sucursales_ajax.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            dataType: 'json',
            timeout: 10000,
            success: function(respuesta) {
                setLoadingState(false);
                
                if(respuesta.estado === 'ok') {
                    $('#modal-sucursal').modal('hide');
                    tablaSucursales.ajax.reload();
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: `La sucursal ha sido ${action === 'crear' ? 'creada' : 'actualizada'} correctamente.`,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error del Servidor',
                        html: `No se pudo ${action} la sucursal.<br><strong>Detalle:</strong> ${respuesta.mensaje}`,
                        confirmButtonText: 'Entendido'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                setLoadingState(false);
                
                let errorMessage = 'Error de conexión desconocido';
                if (textStatus === 'timeout') {
                    errorMessage = 'La operación ha excedido el tiempo límite';
                } else if (textStatus === 'error') {
                    errorMessage = `Error del servidor (${jqXHR.status}): ${errorThrown}`;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    html: `No se pudo conectar con el servidor.<br><strong>Detalle:</strong> ${errorMessage}`,
                    confirmButtonText: 'Entendido'
                });
            }
        });
    });

    // Editar sucursal
    $('#tabla-sucursales tbody').on('click', '.btn-editar', function(e) {
        e.preventDefault();
        let data = tablaSucursales.row($(this).parents('tr')).data();
        
        // Primero llenar los campos del formulario
        $('#id').val(data.id);
        $('#nombre').val(data.nombre || '');
        $('#direccion').val(data.direccion || '');
        $('#telefono').val(data.telefono || '');
        $('#codigo').val(data.codigo || '');
        $('#estado').val(data.estado || 'activa');
        
        // Limpiar validaciones visuales
        $('#form-sucursal .form-control').removeClass('is-valid is-invalid');
        $('#form-sucursal .invalid-feedback').hide();
        
        // Cambiar el título del modal y mostrarlo
        $('#modal-sucursal').find('.modal-title').text('Editar Sucursal');
        $('#modal-sucursal').modal('show');
    });

    // Eliminar sucursal
    $('#tabla-sucursales tbody').on('click', '.btn-eliminar', function() {
        let data = tablaSucursales.row($(this).parents('tr')).data();
        let idSucursal = data.id;

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, ¡bórralo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax/sucursales_ajax.php',
                    type: 'POST',
                    data: {
                        accion: 'eliminar',
                        id: idSucursal
                    },
                    success: function(respuesta) {
                        let res = JSON.parse(respuesta);
                        if(res.estado === 'ok') {
                            tablaSucursales.ajax.reload();
                            Swal.fire('¡Eliminado!', 'La sucursal ha sido eliminada.', 'success');
                        } else {
                            Swal.fire('Error', res.mensaje || 'No se pudo eliminar la sucursal.', 'error');
                        }
                    }
                });
            }
        });
    });

    // Función de verificación para debugging
    function verificarElementos() {
        
    }

    // Ejecutar verificación después de 1 segundo
    setTimeout(verificarElementos, 1000);
}); 