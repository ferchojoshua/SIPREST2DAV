// Función para cargar contenido dinámicamente
function CargarContenido(pagina, contenedor) {
    try {
        // Mostrar indicador de carga
        $(contenedor).html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-2">Cargando contenido...</p>
            </div>
        `);

        // Cargar el contenido
        $.ajax({
            url: pagina,
            success: function(response) {
                try {
                    $(contenedor).html(response);
                    
                    // Inicializar componentes después de cargar
                    initializeComponents();
                } catch (error) {
                    console.error('Error al procesar respuesta:', error);
                    mostrarError();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error cargando contenido:', error);
                mostrarError();
            }
        });
    } catch (error) {
        console.error('Error en CargarContenido:', error);
        mostrarError();
    }
}

// Función para inicializar componentes después de cargar contenido
function initializeComponents() {
    try {
        // Inicializar DataTables si existen
        if ($.fn.DataTable) {
            $('table.display').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        responsive: true,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                        }
                    });
                }
            });
        }

        // Inicializar Select2 si existe
        if ($.fn.select2) {
            $('.select2').select2();
        }

        // Inicializar tooltips de Bootstrap
        $('[data-toggle="tooltip"]').tooltip();

    } catch (error) {
        console.error('Error inicializando componentes:', error);
    }
}

// Función para mostrar error
function mostrarError() {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error al cargar el contenido. Por favor, intente nuevamente.',
        showConfirmButton: true,
        confirmButtonText: 'Reintentar',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.reload();
        }
    });
} 