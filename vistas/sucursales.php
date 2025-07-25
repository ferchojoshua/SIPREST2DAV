<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Administrar Sucursales</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Administrar Sucursales</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Sucursales</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-nueva-sucursal" data-toggle="modal" data-target="#modal-sucursal">
                                <i class="fas fa-plus"></i> Nueva Sucursal
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabla-sucursales" class="table table-bordered table-striped">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th >ID</th>
                                    <th >Nombre</th>
                                    <th >Dirección</th>
                                    <th >Teléfono</th>
                                    <th >Código</th>
                                    <th >Estado</th>
                                    <th >Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data de sucursales se cargará aquí -->
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<!-- Modal para agregar/editar sucursal -->
<div class="modal fade" id="modal-sucursal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Nueva Sucursal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-sucursal" novalidate>
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="empresa_id" name="empresa_id" value="1">
                    
                    <!-- Token CSRF para seguridad -->
                    <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">
                                    <i class="fas fa-building"></i> Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre" 
                                       name="nombre" 
                                       placeholder="Ingrese el nombre de la sucursal"
                                       required 
                                       minlength="2" 
                                       maxlength="100"
                                       pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                       title="Solo se permiten letras y espacios"
                                       autocomplete="organization">
                                <div class="invalid-feedback">
                                    El nombre es requerido y debe contener solo letras y espacios (2-100 caracteres)
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo">
                                    <i class="fas fa-barcode"></i> Código <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="codigo" 
                                       name="codigo" 
                                       placeholder="Ej: SUC001"
                                       required 
                                       minlength="2" 
                                       maxlength="10"
                                       pattern="[A-Z0-9]+"
                                       title="Solo se permiten letras mayúsculas y números"
                                       style="text-transform: uppercase;">
                                <div class="invalid-feedback">
                                    El código es requerido y debe contener solo letras mayúsculas y números (2-10 caracteres)
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Se convertirá automáticamente a mayúsculas
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="direccion">
                                    <i class="fas fa-map-marker-alt"></i> Dirección
                                </label>
                                <textarea class="form-control" 
                                          id="direccion" 
                                          name="direccion" 
                                          rows="2"
                                          placeholder="Ingrese la dirección de la sucursal"
                                          maxlength="255"
                                          autocomplete="street-address"></textarea>
                                <div class="invalid-feedback">
                                    La dirección no puede exceder 255 caracteres
                                </div>
                                <small class="form-text text-muted">
                                    <span id="direccion-count">0</span>/255 caracteres
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefono">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="telefono" 
                                       name="telefono" 
                                       placeholder="Ej: +1 234 567 8900"
                                       minlength="7" 
                                       maxlength="15"
                                       pattern="[\d\s\-\+\(\)]+"
                                       title="Solo se permiten números, espacios, guiones y paréntesis"
                                       autocomplete="tel">
                                <div class="invalid-feedback">
                                    El teléfono debe contener solo números, espacios, guiones y paréntesis (7-15 caracteres)
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado">
                                    <i class="fas fa-toggle-on"></i> Estado <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="estado" name="estado" required>
                                    <option value="">-- Seleccione un estado --</option>
                                    <option value="activa">
                                        <i class="fas fa-check-circle"></i> Activa
                                    </option>
                                    <option value="inactiva">
                                        <i class="fas fa-times-circle"></i> Inactiva
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Debe seleccionar un estado
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-muted">
                                    <i class="fas fa-info-circle"></i> Información
                                </label>
                                <div class="alert alert-info py-2 mb-0">
                                    <small>
                                        <strong>Tip:</strong> El código debe ser único y se usará para identificar la sucursal en reportes.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="btn-guardar-sucursal">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales para mejorar la UX -->
<style>
    .form-control.is-valid {
        border-color: #28a745;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.4.4c.2.2.5.2.7 0l2.6-2.6c.2-.2.2-.5 0-.7l-.4-.4c-.2-.2-.5-.2-.7 0L4.1 4.8 3.7 4.4c-.2-.2-.5-.2-.7 0l-.4.4c-.2.2-.2.5 0 .7z'/%3e%3c/svg%3e");
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4M7.2 4.6l-1.4 1.4'/%3e%3c/svg%3e");
    }
    
    .invalid-feedback {
        display: block;
        font-size: 0.875em;
        color: #dc3545;
        margin-top: 0.25rem;
    }
    
    .modal-dialog.modal-lg {
        max-width: 800px;
    }
    
    .form-group label {
        font-weight: 500;
        color: #495057;
    }
    
    .form-group label i {
        margin-right: 5px;
        color: #6c757d;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }
    
    #direccion-count {
        font-weight: bold;
    }
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
<?php require_once "modulos/footer.php"; ?>
<script>
// Contador de caracteres para la dirección
document.addEventListener('DOMContentLoaded', function() {
    const direccionField = document.getElementById('direccion');
    const direccionCount = document.getElementById('direccion-count');
    
    if (direccionField && direccionCount) {
        direccionField.addEventListener('input', function() {
            const count = this.value.length;
            direccionCount.textContent = count;
            
            if (count > 255) {
                direccionCount.style.color = '#dc3545';
            } else if (count > 200) {
                direccionCount.style.color = '#ffc107';
            } else {
                direccionCount.style.color = '#6c757d';
            }
        });
    }
});
</script>

<!-- Incluir el archivo JavaScript específico de sucursales -->
<script src="vistas/assets/dist/js/sucursales.js"></script> 