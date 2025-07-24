// =================================================================================================
// global_functions.js - Funciones JavaScript Globales para SIPRESTA
// =================================================================================================

// Estas funciones se definen en el ámbito global (window) para ser accesibles desde cualquier onclick
// o listener que pueda existir en el HTML.
// Su propósito principal es evitar "ReferenceError: xyz is not defined" y actuar como un proxy
// hacia la implementación real si ya ha sido cargada (por ejemplo, en caja_page_functions.js).

// Si la implementación real ya existe (e.g., en otro script), se usará esa. De lo contrario,
// se mostrará una advertencia o un mensaje de función no implementada.

window.abrirCaja = function() {
    console.warn('Función global abrirCaja llamada.');
    // Implementación real de abrir caja
    $.ajax({
        url: "ajax/caja_ajax.php",
        method: "POST",
        data: {
            accion: "verificar_permisos_caja",
            sub_accion: "ABRIR_CAJA"
        },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.permisos && response.permisos.puede_ejecutar) {
                Swal.fire(
                    'Permiso Concedido!',
                    response.permisos.mensaje || 'Puede abrir caja.',
                    'success'
                );
                $("#modal_abrir_caja").modal('show');
                if (typeof cargarSucursalesModal === 'function') {
                    cargarSucursalesModal(); // Si esta función es necesaria para el modal
                }
                $("#text_descripcion").val('Apertura de Caja');
                $("#text_monto_ini").focus();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Permisos',
                    text: response.permisos ? response.permisos.mensaje : 'No tiene los permisos necesarios para abrir caja'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en abrirCaja AJAX:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error de Comunicación',
                text: 'Error al verificar permisos para abrir caja.'
            });
        }
    });
};

window.cerrarCaja = function() {
    console.warn('Función global cerrarCaja llamada.');
    Swal.fire({
        title: '¿Estás seguro de cerrar la caja?',
        text: "Esta acción cerrará la caja actual y no se podrá revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Procesando...',
                html: 'Por favor, espera mientras se cierra la caja.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "ajax/caja_ajax.php",
                method: "POST",
                data: {
                    accion: "generar_cierre_dia" // Usamos la misma acción para el cierre completo
                },
                dataType: 'json',
                success: function(response) {
                    if (response.resultado === 1 || response.success) {
                        Swal.fire(
                            '¡Caja Cerrada!',
                            response.mensaje || response.message || 'La caja ha sido cerrada con éxito.',
                            'success'
                        );
                        // Recargar la tabla principal y el dashboard
                        if (typeof tbl_caja !== 'undefined' && tbl_caja !== null) {
                            tbl_caja.ajax.reload();
                        }
                        if (typeof window.actualizarDashboard === 'function') {
                            window.actualizarDashboard();
                        }
                    } else {
                        Swal.fire(
                            'Error',
                            response.mensaje || response.message || 'No se pudo cerrar la caja.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error de Comunicación',
                        'No se pudo conectar con el servidor para cerrar la caja.',
                        'error'
                    );
                }
            });
        }
    });
};

// Eliminar todos los placeholders de funciones globales que solo muestran mensajes de 'Función no implementada'.
// Deja solo las implementaciones reales o ninguna si la función se implementa en otro archivo.
// Por ejemplo, elimina:
// window.realizarConteoFisico = function() { ... };
// window.generarReporte = function() { ... };
// window.cerrarCaja = function() { ... };
// window.abrirCaja = function() { ... };
// window.confirmarConteoFisico = function() { ... };

// Puedes añadir más funciones globales aquí según sea necesario. 