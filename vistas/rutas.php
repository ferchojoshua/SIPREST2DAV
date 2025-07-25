<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0"><i class="fas fa-route"></i> Gestión de Rutas</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Rutas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content pb-2">
    <div class="container-fluid">
        <div class="row">
            <!-- Listado de Rutas -->
            <div class="col-md-12">
                <div class="card card-info card-outline shadow">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i> Listado de Rutas
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-nueva-ruta">
                                <i class="fas fa-plus"></i> Nueva Ruta
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tabla-rutas" class="table table-bordered table-striped table-hover">
                            <thead class="bg-info">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Sucursal</th>
                                    <th>Color</th>
                                    <th>Clientes</th>
                                    <th>Responsables</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar ruta -->
<div class="modal fade" id="modal-ruta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">
                    <i class="fas fa-route"></i> <span id="titulo-modal">Nueva Ruta</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-ruta" novalidate>
                    <input type="hidden" id="ruta_id" name="ruta_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ruta_nombre">
                                    <i class="fas fa-tag"></i> Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="ruta_nombre" 
                                       name="ruta_nombre" 
                                       placeholder="Ej: Ruta Centro"
                                       required 
                                       minlength="3" 
                                       maxlength="100">
                                <div class="invalid-feedback">
                                    El nombre es requerido y debe tener entre 3 y 100 caracteres
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ruta_codigo">
                                    <i class="fas fa-barcode"></i> Código <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="ruta_codigo" 
                                       name="ruta_codigo" 
                                       placeholder="Ej: RT-CENTRO"
                                       required 
                                       minlength="2" 
                                       maxlength="20"
                                       pattern="[A-Z0-9\-_]+"
                                       style="text-transform: uppercase;">
                                <div class="invalid-feedback">
                                    El código es requerido y debe contener solo letras, números, guiones y guiones bajos
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
                                <label for="ruta_descripcion">
                                    <i class="fas fa-align-left"></i> Descripción
                                </label>
                                <textarea class="form-control" 
                                          id="ruta_descripcion" 
                                          name="ruta_descripcion" 
                                          rows="3"
                                          placeholder="Descripción de la ruta..."
                                          maxlength="500"></textarea>
                                <small class="form-text text-muted">
                                    <span id="descripcion-count">0</span>/500 caracteres
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ruta_color">
                                    <i class="fas fa-palette"></i> Color
                                </label>
                                <div class="input-group">
                                    <input type="color" 
                                           class="form-control" 
                                           id="ruta_color" 
                                           name="ruta_color" 
                                           value="#3498db">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-eye" id="preview-color"></i>
                                        </span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Color para identificar la ruta
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ruta_estado">
                                    <i class="fas fa-toggle-on"></i> Estado <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="ruta_estado" name="ruta_estado" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="activa">Activa</option>
                                    <option value="inactiva">Inactiva</option>
                                </select>
                                <div class="invalid-feedback">
                                    Debe seleccionar un estado
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ruta_orden">
                                    <i class="fas fa-sort-numeric-down"></i> Orden
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="ruta_orden" 
                                       name="ruta_orden" 
                                       placeholder="0"
                                       min="0" 
                                       max="999">
                                <small class="form-text text-muted">
                                    Orden de recorrido sugerido
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="select_sucursal_ruta_modal">
                                    <i class="fas fa-building"></i> Sucursal <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="select_sucursal_ruta_modal" name="sucursal_id" required>
                                    <option value="">-- Seleccione Sucursal --</option>
                                </select>
                                <div class="invalid-feedback">
                                    Debe seleccionar una sucursal
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="ruta_observaciones">
                                    <i class="fas fa-sticky-note"></i> Observaciones
                                </label>
                                <textarea class="form-control" 
                                          id="ruta_observaciones" 
                                          name="ruta_observaciones" 
                                          rows="2"
                                          placeholder="Observaciones adicionales..."
                                          maxlength="1000"></textarea>
                                <small class="form-text text-muted">
                                    <span id="observaciones-count">0</span>/1000 caracteres
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="responsables_info">
                                    <i class="fas fa-users"></i> Responsables
                                </label>
                                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                              id="responsables_info" 
                              name="responsables_info" 
                              readonly
                                           placeholder="Sin responsables asignados">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="btn-gestionar-responsables" title="Gestionar Responsables">
                            <i class="fas fa-user-cog"></i>
                        </button>
                    </div>
                </div>
                <small class="form-text text-muted">
                    Usuarios asignados a esta ruta
                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información adicional de la ruta -->
                    <div class="row" id="info-adicional-ruta" style="display: none;">
                        <div class="col-md-12">
                            <hr>
                            <h6><i class="fas fa-info-circle"></i> Información Adicional</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Clientes</span>
                                    <span class="info-box-number" id="info-total-clientes">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Activos</span>
                                    <span class="info-box-number" id="info-clientes-activos">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Creada</span>
                                    <span class="info-box-number" id="info-fecha-creacion">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Creada por</span>
                                    <span class="info-box-number" id="info-usuario-creacion">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-info btn-sm" id="btn-toggle-info">
                    <i class="fas fa-info-circle"></i> Más Información
                </button>
                <div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-guardar-ruta">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para gestionar clientes de ruta -->
<div class="modal fade" id="modal-clientes-ruta">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">
                    <i class="fas fa-users"></i> Gestión de Clientes - <span id="nombre-ruta-clientes"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Clientes asignados a la ruta -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-list"></i> Clientes Asignados
                                </h5>
                            </div>
                            <div class="card-body">
                                <table id="tabla-clientes-ruta" class="table table-sm table-bordered">
                                    <thead class="bg-success">
                                        <tr>
                                            <th>Orden</th>
                                            <th>Cliente</th>
                                            <th>DNI</th>
                                            <th>Teléfono</th>
                                            <th>Préstamos</th>
                                            <th>Saldo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Los datos se cargarán via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Clientes disponibles para asignar -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-user-plus"></i> Clientes Disponibles
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="select-cliente-disponible">Seleccionar Cliente:</label>
                                    <select class="form-control" id="select-cliente-disponible">
                                        <option value="">-- Seleccione --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="orden-visita">Orden de Visita:</label>
                                    <input type="number" class="form-control" id="orden-visita" min="1" value="1">
                                </div>
                                <div class="form-group">
                                    <label for="observaciones-cliente">Observaciones:</label>
                                    <textarea class="form-control" id="observaciones-cliente" rows="2"></textarea>
                                </div>
                                <button type="button" class="btn btn-success btn-sm btn-block" id="btn-asignar-cliente">
                                    <i class="fas fa-plus"></i> Asignar Cliente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para estadísticas de ruta -->
<div class="modal fade" id="modal-estadisticas-ruta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">
                    <i class="fas fa-chart-bar"></i> Estadísticas - <span id="nombre-ruta-estadisticas"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Clientes</span>
                                <span class="info-box-number" id="stat-total-clientes">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Clientes Activos</span>
                                <span class="info-box-number" id="stat-clientes-activos">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-hand-holding-usd"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Préstamos Activos</span>
                                <span class="info-box-number" id="stat-prestamos-activos">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Saldo Pendiente</span>
                                <span class="info-box-number" id="stat-saldo-pendiente">$0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Cuotas Vencidas</span>
                                <span class="info-box-number" id="stat-cuotas-vencidas">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-user-tie"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Usuarios Asignados</span>
                                <span class="info-box-number" id="stat-usuarios-asignados">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para gestionar responsables de ruta -->
<div class="modal fade" id="modal-responsables-ruta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">
                    <i class="fas fa-user-cog"></i> Gestionar Responsables - <span id="nombre-ruta-responsables"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Usuarios asignados -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users"></i> Usuarios Asignados
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="usuarios-asignados-lista">
                                    <!-- Los usuarios asignados se cargarán aquí -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Agregar nuevo usuario -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-plus"></i> Agregar Usuario
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Resumen del catálogo -->
                                <div class="alert alert-info mb-3" id="resumen-catalogo" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <div>
                                            <strong>Catálogo de Usuarios</strong>
                                            <div class="small" id="info-catalogo">Cargando información...</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <form id="form-asignar-usuario">
                                    <div class="form-group">
                                        <label for="select-usuario">
                                            <i class="fas fa-user"></i> Seleccionar Usuario
                                        </label>
                                        <select class="form-control select2" id="select-usuario" name="usuario_id" required style="width: 100%;">
                                            <option value="">Cargando usuarios...</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-search"></i> Busque por nombre, sucursal o perfil
                                        </small>
                                    </div>
                                    
                                    <!-- Información del usuario seleccionado -->
                                    <div class="alert alert-success" id="info-usuario-seleccionado" style="display: none;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-check mr-2"></i>
                                            <div>
                                                <strong>Usuario Seleccionado</strong>
                                                <div class="small" id="detalles-usuario-seleccionado"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="tipo-asignacion">
                                            <i class="fas fa-user-tag"></i> Tipo de Asignación
                                        </label>
                                        <select class="form-control" id="tipo-asignacion" name="tipo_asignacion" required>
                                            <option value="">-- Seleccione --</option>
                                            <option value="responsable">🎯 Responsable (Principal)</option>
                                            <option value="apoyo">🤝 Apoyo (Secundario)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha-inicio">
                                                    <i class="fas fa-calendar-alt"></i> Fecha Inicio
                                                </label>
                                                <input type="date" class="form-control" id="fecha-inicio" name="fecha_inicio">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha-fin">
                                                    <i class="fas fa-calendar-times"></i> Fecha Fin
                                                </label>
                                                <input type="date" class="form-control" id="fecha-fin" name="fecha_fin">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="observaciones-usuario">
                                            <i class="fas fa-comment"></i> Observaciones
                                        </label>
                                        <textarea class="form-control" id="observaciones-usuario" name="observaciones" rows="2" placeholder="Observaciones de la asignación..."></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus"></i> Asignar Usuario
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos CSS adicionales -->
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
    
    .color-indicator {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 5px;
        vertical-align: middle;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #ddd;
    }
    
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .fa-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .table-sm th, .table-sm td {
        padding: 0.3rem;
        font-size: 0.875rem;
    }
    
    .info-box {
        display: block;
        min-height: 90px;
        background: #fff;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 2px;
        margin-bottom: 15px;
    }
    
    .info-box-icon {
        border-top-left-radius: 2px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 2px;
        display: block;
        float: left;
        height: 90px;
        width: 90px;
        text-align: center;
        font-size: 45px;
        line-height: 90px;
        background: rgba(0,0,0,0.2);
    }
    
    .info-box-content {
        padding: 5px 10px;
        margin-left: 90px;
    }
    
    .info-box-text {
        text-transform: uppercase;
        font-weight: bold;
        font-size: 13px;
    }
    
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }
    
    /* Estilos para Select2 personalizado */
    .select2-result-user {
        padding: 2px 0;
    }
    
    .select2-result-user__name {
        font-weight: bold;
        color: #333;
    }
    
    .select2-result-user__details {
        margin-top: 2px;
        font-size: 0.85em;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #007bff;
        color: white;
    }
    
    .select2-container--default .select2-results__option--highlighted .select2-result-user__name {
        color: white;
    }
    
    .select2-container--default .select2-results__option--highlighted .select2-result-user__details {
        color: #e9ecef;
    }
    
    /* Mejorar apariencia de optgroups */
    .select2-container--default .select2-results__group {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: bold;
        padding: 6px 12px;
        border-bottom: 1px solid #dee2e6;
    }
    
    /* Indicadores de estado en usuarios */
    .usuario-activo {
        color: #28a745;
    }
    
    .usuario-inactivo {
        color: #dc3545;
    }
    
    /* Mejorar cards de usuarios asignados */
    .card-usuario-asignado {
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }
    
    .card-usuario-asignado:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .card-usuario-asignado.responsable {
        border-left-color: #28a745;
    }
    
    .card-usuario-asignado.apoyo {
        border-left-color: #ffc107;
    }
</style>
<?php require_once "modulos/footer.php"; ?>
<!-- JavaScript para funcionalidad -->
<script>
$(document).ready(function() {
    let tablaRutas;
    let tablaClientesRuta;
    let rutaActual = null;
    
    // Inicializar DataTable principal
    tablaRutas = $('#tabla-rutas').DataTable({
        "ajax": {
            "url": "ajax/rutas_ajax.php?accion=listar",
            "type": "GET",
            "dataSrc": function(json) {
                          
                if (json && json.data && Array.isArray(json.data)) {
                    return json.data;
                } else if (Array.isArray(json)) {
                   
                    return json;
                } else {
                   
                    return [];
                }
            },
            "beforeSend": function(xhr) {
                
            },
            "error": function(xhr, error, thrown) {
                console.error('❌ Error AJAX en tabla rutas:');
                console.error('Error:', error);
                console.error('Thrown:', thrown);
                console.error('Status Code:', xhr.status);
                console.error('Response Text:', xhr.responseText);
                console.error('Ready State:', xhr.readyState);
                
                // Mostrar mensaje de error al usuario
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudieron cargar las rutas. Detalles: ' + error + ' - ' + thrown,
                    footer: '<a href="ejecutar_sql_rutas.php" target="_blank">Crear tablas de rutas</a>'
                });
            }
        },
        "columns": [
            { "data": "ruta_codigo" },
            { "data": "ruta_nombre" },
            { "data": "ruta_descripcion" },
            { 
                "data": "sucursal_nombre",
                "render": function(data, type, row) {
                    return `<span class="badge badge-secondary">${data}</span>`;
                }
            },
            { 
                "data": "ruta_color",
                "render": function(data, type, row) {
                    return `<span class="color-indicator" style="background-color: ${data}"></span> ${data}`;
                }
            },
            { 
                "data": "total_clientes",
                "render": function(data, type, row) {
                    return `<span class="badge badge-info">${data} clientes</span>`;
                }
            },
            { 
                "data": "responsables",
                "render": function(data, type, row) {
                    return data || '<span class="text-muted">Sin asignar</span>';
                }
            },
            { 
                "data": "ruta_estado",
                "render": function(data, type, row) {
                    return data == 'activa' ? 
                        '<span class="badge badge-success">Activa</span>' : 
                        '<span class="badge badge-danger">Inactiva</span>';
                }
            },
            { 
                "data": null,
                "defaultContent": `
                    <div class="btn-group" role="group">
                        <button class="btn btn-info btn-sm btn-estadisticas" title="Estadísticas">
                            <i class="fas fa-chart-bar"></i>
                        </button>
                        <button class="btn btn-success btn-sm btn-clientes" title="Gestionar Clientes">
                            <i class="fas fa-users"></i>
                        </button>
                        <button class="btn btn-warning btn-sm btn-editar" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-eliminar" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `
            }
        ],
        "language": {
            "url": "vistas/assets/plugins/datatables/i18n/Spanish.json"
        },
        "responsive": true,
        "order": [[0, "asc"]]
    });
    
    // Configurar modal al abrir (solo si no es edición)
    $('#modal-ruta').on('show.bs.modal', function () {
        // Cargar sucursales de forma simple (sin Select2/CombosMejorados)
        cargarSucursalesSimple('#select_sucursal_ruta_modal');

        // Solo limpiar si no hay ruta_id (es nueva ruta)
        if (!$('#ruta_id').val()) {
            limpiarFormulario();
        }
    });
    
    // Función simple para cargar sucursales (SIN Select2)
    function cargarSucursalesSimple(selector) {
        $(selector).html('<option value="">Cargando sucursales...</option>');
        
        $.ajax({
            url: 'ajax/rutas_ajax.php',
            type: 'GET',
            data: { accion: 'listar_sucursales' },
            dataType: 'json',
            success: function(response) {
                let opciones = '<option value="">-- Seleccione Sucursal --</option>';
                
                if (Array.isArray(response)) {
                    response.forEach(function(sucursal) {
                        // Solo mostrar nombre simple, sin estilos adicionales
                        const texto = sucursal.sucursal_nombre || sucursal.nombre;
                        const id = sucursal.sucursal_id || sucursal.id;
                        opciones += `<option value="${id}">${texto}</option>`;
                    });
                } else {
                    opciones = '<option value="">No hay sucursales disponibles</option>';
                }
                
                $(selector).html(opciones);
                console.log('✅ Sucursales cargadas de forma simple');
            },
            error: function(xhr, error, thrown) {
                console.error('❌ Error al cargar sucursales:', error);
                $(selector).html('<option value="">Error al cargar sucursales</option>');
            }
        });
    }
    
    // Validación en tiempo real
    function initializeValidation() {
        // Remover eventos previos para evitar duplicados
        $('#ruta_descripcion').off('input.validation');
        $('#ruta_observaciones').off('input.validation');
        $('#ruta_codigo').off('input.validation');
        $('#ruta_color').off('input.validation');
        
        // Contador de caracteres para descripción
        $('#ruta_descripcion').on('input.validation', function() {
            const count = $(this).val().length;
            $('#descripcion-count').text(count);
            $('#descripcion-count').css('color', count > 500 ? '#dc3545' : '#6c757d');
        });
        
        // Contador de caracteres para observaciones
        $('#ruta_observaciones').on('input.validation', function() {
            const count = $(this).val().length;
            $('#observaciones-count').text(count);
            $('#observaciones-count').css('color', count > 1000 ? '#dc3545' : '#6c757d');
        });
        
        // Convertir código a mayúsculas
        $('#ruta_codigo').on('input.validation', function() {
            $(this).val($(this).val().toUpperCase());
        });
        
        // Preview de color
        $('#ruta_color').on('input.validation', function() {
            const color = $(this).val();
            $('#preview-color').css('color', color);
        });
    }
    
    // Función para limpiar formulario
    function limpiarFormulario() {
        $('#form-ruta')[0].reset();
        $('#ruta_id').val('');
        $('#ruta_color').val('#3498db');
        $('#ruta_estado').val('activa');
        $('#ruta_orden').val('0');
        $('#select_sucursal_ruta_modal').val(''); // Limpiar el select de sucursal
        $('#responsables_info').val('');
        $('#preview-color').css('color', '#3498db');
        $('#descripcion-count').text('0');
        $('#observaciones-count').text('0');
        $('#form-ruta .form-control').removeClass('is-valid is-invalid');
        $('#form-ruta .invalid-feedback').hide();
        $('#form-ruta').removeClass('was-validated');
    }
    
    // Función para mostrar estado de carga
    function setLoadingState(isLoading) {
        const btnGuardar = $('#btn-guardar-ruta');
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
    
    // Manejar envío del formulario
    $('#btn-guardar-ruta').click(function() {
        var form = document.getElementById('form-ruta');
        if (form.checkValidity()) {
            var formData = new FormData(form);
            formData.append('accion', 'guardar');
            
            // Mostrar indicador de carga
            setLoadingState(true);
            
            $.ajax({
                url: 'ajax/rutas_ajax.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    try {
                    // No necesitamos verificar tipo ni parsear - dataType: 'json' ya lo hace
                    if (response.estado === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.mensaje || 'Ruta guardada correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#modal-ruta').modal('hide');
                        tablaRutas.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.mensaje || 'Error al guardar la ruta'
                        });
                    }
                    } catch (e) {
                        console.error('Error al procesar respuesta:', e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al procesar la respuesta del servidor'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de comunicación con el servidor'
                    });
                },
                complete: function() {
                    // Restaurar estado del botón
                    setLoadingState(false);
                }
            });
        } else {
            form.classList.add('was-validated');
        }
    });

    // Limpiar formulario al cerrar modal
    $('#modal-ruta').on('hidden.bs.modal', function() {
        limpiarFormulario();
        $('#titulo-modal').text('Nueva Ruta');
    });
    
    // Manejar botones de cerrar modales
    $('#modal-ruta .close, #modal-ruta [data-dismiss="modal"]').on('click', function() {
        $('#modal-ruta').modal('hide');
    });
    
    $('#modal-estadisticas-ruta .close, #modal-estadisticas-ruta [data-dismiss="modal"]').on('click', function() {
        $('#modal-estadisticas-ruta').modal('hide');
    });
    
    $('#modal-clientes-ruta .close, #modal-clientes-ruta [data-dismiss="modal"]').on('click', function() {
        $('#modal-clientes-ruta').modal('hide');
    });
    
    // Manejar tecla Escape para cerrar modales
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            if ($('#modal-ruta').hasClass('show')) {
                $('#modal-ruta').modal('hide');
            }
            if ($('#modal-estadisticas-ruta').hasClass('show')) {
                $('#modal-estadisticas-ruta').modal('hide');
            }
            if ($('#modal-clientes-ruta').hasClass('show')) {
                $('#modal-clientes-ruta').modal('hide');
            }
        }
    });
    
    // Botón para nueva ruta
    $('#btn-nueva-ruta').on('click', function() {
        limpiarFormulario();
        $('#titulo-modal').text('Nueva Ruta');
        $('#info-adicional-ruta').hide();
        $('#btn-toggle-info').html('<i class="fas fa-info-circle"></i> Más Información');
        // Deshabilitar el botón de gestionar responsables para nuevas rutas
        $('#btn-gestionar-responsables').prop('disabled', true);
        $('#modal-ruta').modal('show');
        rutaActual = null; // Resetear rutaActual para nueva ruta
    });
    
    // Botón para toggle de información adicional
    $('#btn-toggle-info').on('click', function() {
        const infoSection = $('#info-adicional-ruta');
        if (infoSection.is(':visible')) {
            infoSection.hide();
            $(this).html('<i class="fas fa-info-circle"></i> Más Información');
        } else {
            infoSection.show();
            $(this).html('<i class="fas fa-eye-slash"></i> Ocultar Información');
        }
    });
    
    // Editar ruta
    $('#tabla-rutas tbody').on('click', '.btn-editar', function() {
        const data = tablaRutas.row($(this).parents('tr')).data();
        
        // Almacenar los datos de la ruta actual para uso en otros modales
        rutaActual = data;
        
        // Limpiar validaciones previas
        $('#form-ruta').removeClass('was-validated');
        $('#form-ruta .form-control').removeClass('is-valid is-invalid');
        
        // Configurar título y datos
        $('#titulo-modal').text('Editar Ruta');
        $('#ruta_id').val(data.ruta_id || '');
        $('#ruta_nombre').val(data.ruta_nombre || '');
        $('#ruta_descripcion').val(data.ruta_descripcion || '');
        $('#ruta_codigo').val(data.ruta_codigo || '');
        $('#ruta_color').val(data.ruta_color || '#3498db');
        $('#ruta_estado').val(data.ruta_estado || 'activa');
        $('#ruta_orden').val(data.ruta_orden || 0);
        $('#ruta_observaciones').val(data.ruta_observaciones || '');

        // Cargar sucursales de forma simple y luego seleccionar la correspondiente
        cargarSucursalesSimple('#select_sucursal_ruta_modal');
        
        // Esperar a que se carguen las sucursales y luego seleccionar
        setTimeout(function() {
            $('#select_sucursal_ruta_modal').val(data.sucursal_id);
        }, 500);

        $('#responsables_info').val(data.responsables || 'Sin responsables asignados');
        
        // Actualizar información adicional
        $('#info-total-clientes').text(data.total_clientes || 0);
        $('#info-clientes-activos').text(data.clientes_activos || 0);
        $('#info-fecha-creacion').text(data.fecha_creacion ? new Date(data.fecha_creacion).toLocaleDateString() : '-');
        $('#info-usuario-creacion').text(data.usuario_creacion_nombre || 'Sistema');
        
        // Mostrar la sección de información adicional si no es una nueva ruta
        if (data.ruta_id) {
        $('#info-adicional-ruta').show();
        $('#btn-toggle-info').html('<i class="fas fa-eye-slash"></i> Ocultar Información');
            // Habilitar el botón de gestionar responsables solo si es una ruta existente
            $('#btn-gestionar-responsables').prop('disabled', false);
        } else {
            $('#info-adicional-ruta').hide();
            $('#btn-toggle-info').html('<i class="fas fa-info-circle"></i> Más Información');
            // Deshabilitar el botón de gestionar responsables para nuevas rutas
            $('#btn-gestionar-responsables').prop('disabled', true);
        }
        
        // Actualizar contadores de caracteres
        const descripcionLength = (data.ruta_descripcion || '').length;
        const observacionesLength = (data.ruta_observaciones || '').length;
        $('#descripcion-count').text(descripcionLength);
        $('#observaciones-count').text(observacionesLength);
        
        // Actualizar preview de color
        $('#preview-color').css('color', data.ruta_color || '#3498db');
        
        // Mostrar modal
        $('#modal-ruta').modal('show');
    });
    
    // Eliminar ruta
    $('#tabla-rutas tbody').on('click', '.btn-eliminar', function() {
        const data = tablaRutas.row($(this).parents('tr')).data();
        
        Swal.fire({
            title: '¿Confirmar eliminación?',
            text: `¿Está seguro de eliminar la ruta "${data.ruta_nombre}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax/rutas_ajax.php',
                    type: 'POST',
                    data: {
                        accion: 'eliminar',
                        ruta_id: data.ruta_id
                    },
                    success: function(respuesta) {
                        try {
                            const res = JSON.parse(respuesta);
                            if (res.estado === 'ok') {
                                tablaRutas.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: res.mensaje,
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: res.mensaje
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al procesar la respuesta'
                            });
                        }
                    }
                });
            }
        });
    });
    
    // Gestionar clientes de ruta
    $('#tabla-rutas tbody').on('click', '.btn-clientes', function() {
        const data = tablaRutas.row($(this).parents('tr')).data();
        rutaActual = data;
        
        $('#nombre-ruta-clientes').text(data.ruta_nombre);
        cargarClientesRuta(data.ruta_id);
        cargarClientesDisponibles();
        
        $('#modal-clientes-ruta').modal('show');
    });
    
    // Ver estadísticas de ruta
    $('#tabla-rutas tbody').on('click', '.btn-estadisticas', function() {
        const data = tablaRutas.row($(this).parents('tr')).data();
        
        $('#nombre-ruta-estadisticas').text(data.ruta_nombre);
        cargarEstadisticasRuta(data.ruta_id);
        
        $('#modal-estadisticas-ruta').modal('show');
    });
    
    // Función para cargar clientes de ruta
    function cargarClientesRuta(rutaId) {
        if (tablaClientesRuta) {
            tablaClientesRuta.destroy();
        }
        
        tablaClientesRuta = $('#tabla-clientes-ruta').DataTable({
            "ajax": {
                "url": "ajax/rutas_ajax.php",
                "type": "POST",
                "data": {
                    accion: 'listar_clientes',
                    ruta_id: rutaId
                },
                "dataSrc": ""
            },
            "columns": [
                { "data": "orden_visita" },
                { "data": "cliente_nombres" },
                { "data": "cliente_dni" },
                { "data": "cliente_cel" },
                { 
                    "data": "prestamos_activos",
                    "render": function(data, type, row) {
                        return `<span class="badge badge-primary">${data}</span>`;
                    }
                },
                { 
                    "data": "saldo_pendiente",
                    "render": function(data, type, row) {
                        return `$${parseFloat(data).toFixed(2)}`;
                    }
                },
                { 
                    "data": null,
                    "defaultContent": `
                        <button class="btn btn-danger btn-xs btn-remover-cliente" title="Remover">
                            <i class="fas fa-times"></i>
                        </button>
                    `
                }
            ],
            "language": {
                "url": "vistas/assets/plugins/datatables/i18n/Spanish.json"
            },
            "pageLength": 10,
            "order": [[0, "asc"]]
        });
    }
    
    // Función para cargar clientes disponibles
    function cargarClientesDisponibles() {
        $.ajax({
            url: 'ajax/rutas_ajax.php?accion=listar_clientes_sin_ruta',
            type: 'GET',
            success: function(respuesta) {
                try {
                    const clientes = JSON.parse(respuesta);
                    let options = '<option value="">-- Seleccione --</option>';
                    
                    clientes.forEach(function(cliente) {
                        options += `<option value="${cliente.cliente_id}">${cliente.cliente_nombres} - ${cliente.cliente_dni}</option>`;
                    });
                    
                    $('#select-cliente-disponible').html(options);
                } catch (e) {
                    console.error('Error al cargar clientes disponibles:', e);
                }
            }
        });
    }
    
    // Asignar cliente a ruta
    $('#btn-asignar-cliente').on('click', function() {
        const clienteId = $('#select-cliente-disponible').val();
        const ordenVisita = $('#orden-visita').val();
        const observaciones = $('#observaciones-cliente').val();
        
        if (!clienteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe seleccionar un cliente'
            });
            return;
        }
        
        $.ajax({
            url: 'ajax/rutas_ajax.php',
            type: 'POST',
            data: {
                accion: 'asignar_cliente',
                cliente_id: clienteId,
                ruta_id: rutaActual.ruta_id,
                orden_visita: ordenVisita,
                observaciones: observaciones
            },
            dataType: 'json',
            success: function(respuesta) {
                // No necesitamos JSON.parse() - dataType: 'json' ya parsea automáticamente
                if (respuesta.estado === 'ok') {
                    tablaClientesRuta.ajax.reload();
                    cargarClientesDisponibles();
                    
                    // Limpiar formulario
                    $('#select-cliente-disponible').val('');
                    $('#orden-visita').val('1');
                    $('#observaciones-cliente').val('');
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: respuesta.mensaje,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta.mensaje
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX al agregar cliente a ruta:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de comunicación con el servidor'
                });
            }
        });
    });
    
    // Remover cliente de ruta
    $('#tabla-clientes-ruta tbody').on('click', '.btn-remover-cliente', function() {
        const data = tablaClientesRuta.row($(this).parents('tr')).data();
        
        Swal.fire({
            title: '¿Confirmar remoción?',
            text: `¿Está seguro de remover a "${data.cliente_nombres}" de esta ruta?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, remover',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax/rutas_ajax.php',
                    type: 'POST',
                    data: {
                        accion: 'remover_cliente',
                        cliente_ruta_id: data.cliente_ruta_id
                    },
                    dataType: 'json',
                    success: function(respuesta) {
                        // No necesitamos JSON.parse() - dataType: 'json' ya parsea automáticamente
                        if (respuesta.estado === 'ok') {
                            tablaClientesRuta.ajax.reload();
                            cargarClientesDisponibles();
                            
                            Swal.fire({
                                icon: 'success',
                                title: '¡Removido!',
                                text: respuesta.mensaje,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: respuesta.mensaje
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX al remover cliente de ruta:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error de comunicación con el servidor'
                        });
                    }
                });
            }
        });
    });
    
    // Función para cargar estadísticas de ruta
    function cargarEstadisticasRuta(rutaId) {
        $.ajax({
            url: 'ajax/rutas_ajax.php',
            type: 'POST',
            data: {
                accion: 'obtener_estadisticas',
                ruta_id: rutaId
            },
            success: function(respuesta) {
                try {
                    let res;
                    if (typeof respuesta === 'string') {
                        res = JSON.parse(respuesta);
                    } else {
                        res = respuesta;
                    }
                    
                    if (res.estado === 'ok') {
                        const stats = res.data;
                        
                        $('#stat-total-clientes').text(stats.total_clientes || 0);
                        $('#stat-clientes-activos').text(stats.clientes_activos || 0);
                        $('#stat-prestamos-activos').text(stats.prestamos_activos || 0);
                        $('#stat-saldo-pendiente').text('$' + parseFloat(stats.saldo_total_pendiente || 0).toFixed(2));
                        $('#stat-cuotas-vencidas').text(stats.cuotas_vencidas || 0);
                        $('#stat-usuarios-asignados').text(stats.usuarios_asignados || 0);
                    } else {
                        console.error('Error en estadísticas:', res.mensaje);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar las estadísticas'
                        });
                    }
                } catch (e) {
                    console.error('Error al procesar respuesta de estadísticas:', e);
                    console.error('Respuesta recibida:', respuesta);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar las estadísticas: ' + e.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX en estadísticas:', error);
                console.error('Status:', status);
                console.error('Respuesta:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor para obtener las estadísticas'
                });
            }
        });
    }
    
    // Manejar el clic en el botón de gestionar responsables
    $('#btn-gestionar-responsables').on('click', function() {
        if (!rutaActual || !rutaActual.ruta_id) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe seleccionar o guardar una ruta primero para gestionar responsables.'
            });
            return;
        }
        $('#nombre-ruta-responsables').text(rutaActual.ruta_nombre || 'Ruta Seleccionada');
        cargarUsuariosAsignados(rutaActual.ruta_id); // Carga los usuarios asignados a la ruta
        cargarUsuariosDisponibles(); // Carga usuarios para el select de asignación
        $('#modal-responsables-ruta').modal('show');
    });
    
    // Función para cargar usuarios disponibles (CATÁLOGO UNIFICADO)
    function cargarUsuariosDisponibles() {
        // Determinar si es administrador para mostrar todos los usuarios
        const perfilUsuario = <?php echo $_SESSION["usuario"]->id_perfil_usuario ?? 2; ?>;
        const accion = perfilUsuario === 1 ? 'listar_todos_usuarios_acceso' : 'listar_usuarios_disponibles';
        
        // Mostrar indicador de carga
        $('#select-usuario').html('<option value="">🔄 Cargando catálogo de usuarios...</option>');
        
        $.ajax({
            url: 'ajax/rutas_ajax.php',
            type: 'GET',
            data: { accion: accion },
            success: function(respuesta) {
                try {
                    let usuarios;
                    if (typeof respuesta === 'string') {
                        const res = JSON.parse(respuesta);
                        usuarios = res.data || res;
                    } else {
                        usuarios = respuesta.data || respuesta;
                    }
                    
                    // Crear opciones mejoradas con información completa
                    let options = '<option value="">-- Seleccione un usuario del catálogo --</option>';
                    
                    // Agrupar usuarios por sucursal para mejor organización
                    const usuariosPorSucursal = {};
                    usuarios.forEach(function(usuario) {
                        const sucursal = usuario.sucursal_nombre || 'Sin sucursal';
                        if (!usuariosPorSucursal[sucursal]) {
                            usuariosPorSucursal[sucursal] = [];
                        }
                        usuariosPorSucursal[sucursal].push(usuario);
                    });
                    
                    // Crear optgroups por sucursal
                    Object.keys(usuariosPorSucursal).sort().forEach(function(sucursal) {
                        options += `<optgroup label="🏢 ${sucursal}">`;
                                                 usuariosPorSucursal[sucursal].forEach(function(usuario) {
                             const perfilIcon = usuario.perfil_nombre === 'Administrador' ? '👑' : '👤';
                             const estadoIcon = usuario.estado == 1 ? '✅' : '❌';
                             const perfilInfo = usuario.perfil_nombre ? ` - ${usuario.perfil_nombre}` : '';
                             const rutasInfo = usuario.rutas_asignadas > 0 ? ` (${usuario.rutas_asignadas} rutas)` : '';
                             
                             options += `<option value="${usuario.id_usuario}" 
                                                data-sucursal="${usuario.sucursal_nombre || ''}"
                                                data-perfil="${usuario.perfil_nombre || ''}"
                                                data-estado="${usuario.estado}"
                                                data-rutas-asignadas="${usuario.rutas_asignadas || 0}"
                                                data-rutas-nombres="${usuario.rutas_nombres || ''}"
                                                data-ultima-asignacion="${usuario.ultima_asignacion || ''}">
                                         ${perfilIcon} ${usuario.nombre_completo}${perfilInfo}${rutasInfo} ${estadoIcon}
                                     </option>`;
                        });
                        options += '</optgroup>';
                    });
                    
                    $('#select-usuario').html(options);
                    
                    // Inicializar Select2 para búsqueda avanzada
                    if (typeof $.fn.select2 !== 'undefined') {
                        $('#select-usuario').select2({
                            placeholder: 'Buscar usuario por nombre, sucursal o perfil...',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#modal-responsables-ruta'),
                            templateResult: function(option) {
                                if (!option.id) return option.text;
                                
                                const $option = $(option.element);
                                const sucursal = $option.data('sucursal') || 'Sin sucursal';
                                const perfil = $option.data('perfil') || 'Sin perfil';
                                const estado = $option.data('estado') == 1 ? 'Activo' : 'Inactivo';
                                const rutasAsignadas = $option.data('rutas-asignadas') || 0;
                                const rutasNombres = $option.data('rutas-nombres') || '';
                                const ultimaAsignacion = $option.data('ultima-asignacion');
                                
                                let rutasInfo = '';
                                if (rutasAsignadas > 0) {
                                    rutasInfo = `<div class="text-success"><i class="fas fa-route"></i> ${rutasAsignadas} rutas: ${rutasNombres}</div>`;
                                } else {
                                    rutasInfo = '<div class="text-muted"><i class="fas fa-route"></i> Sin rutas asignadas</div>';
                                }
                                
                                let ultimaActividad = '';
                                if (ultimaAsignacion) {
                                    const fecha = new Date(ultimaAsignacion).toLocaleDateString();
                                    ultimaActividad = `<div class="text-info"><i class="fas fa-clock"></i> Última asignación: ${fecha}</div>`;
                                }
                                
                                return $(`
                                    <div class="select2-result-user">
                                        <div class="select2-result-user__name">${option.text}</div>
                                        <div class="select2-result-user__details">
                                            <small class="text-muted">
                                                <div>🏢 ${sucursal} | 👤 ${perfil} | ${estado}</div>
                                                ${rutasInfo}
                                                ${ultimaActividad}
                                            </small>
                                        </div>
                                    </div>
                                `);
                            },
                            templateSelection: function(option) {
                                if (!option.id) return option.text;
                                
                                const $option = $(option.element);
                                const sucursal = $option.data('sucursal') || 'Sin sucursal';
                                const perfil = $option.data('perfil') || '';
                                
                                return `${option.text.split(' - ')[0]} (${sucursal})`;
                            }
                        });
                    }
                    
                    // Mostrar información del catálogo
                    const totalUsuarios = usuarios.length;
                    const usuariosActivos = usuarios.filter(u => u.estado == 1).length;
                    const usuariosInactivos = totalUsuarios - usuariosActivos;
                    const totalSucursales = Object.keys(usuariosPorSucursal).length;
                    const administradores = usuarios.filter(u => u.perfil_nombre === 'Administrador').length;
                    const prestamistas = usuarios.filter(u => u.perfil_nombre === 'Prestamista').length;
                    
                    // Mostrar resumen del catálogo
                    const resumenHtml = `
                        <div class="row">
                            <div class="col-md-6">
                                <div><strong>📊 Total:</strong> ${totalUsuarios} usuarios</div>
                                <div><strong>✅ Activos:</strong> ${usuariosActivos} | <strong>❌ Inactivos:</strong> ${usuariosInactivos}</div>
                            </div>
                            <div class="col-md-6">
                                <div><strong>🏢 Sucursales:</strong> ${totalSucursales}</div>
                                <div><strong>👑 Admins:</strong> ${administradores} | <strong>👤 Prestamistas:</strong> ${prestamistas}</div>
                            </div>
                        </div>
                    `;
                    
                    $('#info-catalogo').html(resumenHtml);
                    $('#resumen-catalogo').show();
                    
                    // Evento para mostrar información del usuario seleccionado
                    $('#select-usuario').on('change', function() {
                        const userId = $(this).val();
                        if (userId) {
                            const $option = $(this).find('option:selected');
                            const sucursal = $option.data('sucursal') || 'Sin sucursal';
                            const perfil = $option.data('perfil') || 'Sin perfil';
                            const rutasAsignadas = $option.data('rutas-asignadas') || 0;
                            const rutasNombres = $option.data('rutas-nombres') || '';
                            const ultimaAsignacion = $option.data('ultima-asignacion');
                            
                            let infoHtml = `
                                <div><strong>🏢 Sucursal:</strong> ${sucursal}</div>
                                <div><strong>👤 Perfil:</strong> ${perfil}</div>
                                <div><strong>🔗 Rutas actuales:</strong> ${rutasAsignadas > 0 ? `${rutasAsignadas} (${rutasNombres})` : 'Ninguna'}</div>
                            `;
                            
                            if (ultimaAsignacion) {
                                const fecha = new Date(ultimaAsignacion).toLocaleDateString();
                                infoHtml += `<div><strong>📅 Última asignación:</strong> ${fecha}</div>`;
                            }
                            
                            $('#detalles-usuario-seleccionado').html(infoHtml);
                            $('#info-usuario-seleccionado').show();
                        } else {
                            $('#info-usuario-seleccionado').hide();
                        }
                    });
                    
                    console.log(`📊 Catálogo cargado: ${totalUsuarios} usuarios (${usuariosActivos} activos) de ${totalSucursales} sucursales`);
                    
                } catch (e) {
                    console.error('Error al cargar usuarios:', e);
                    $('#select-usuario').html('<option value="">❌ Error al cargar catálogo</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX al cargar usuarios:', error);
                $('#select-usuario').html('<option value="">❌ Error de conexión</option>');
            }
        });
    }
    
    // Función para cargar usuarios asignados
    function cargarUsuariosAsignados(rutaId) {
        // Implementación para cargar la lista de usuarios asignados
        // y mostrarla en #usuarios-asignados-lista
        console.log('Cargando usuarios asignados para ruta:', rutaId);
        $.ajax({
            url: 'ajax/rutas_ajax.php',
            type: 'POST',
            data: {
                accion: 'listar_usuarios_asignados',
                ruta_id: rutaId
            },
            dataType: 'json',
            success: function(response) {
                if (response.estado === 'ok' && response.data) {
                    let html = '';
                    if (response.data.length > 0) {
                        response.data.forEach(function(user) {
                            html += `
                                <div class="card card-usuario-asignado ${user.tipo_asignacion}">
                                    <div class="card-body py-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">${user.nombre_completo} (${user.usuario})</h6>
                                            <small class="text-muted">${user.perfil_nombre} | ${user.sucursal_nombre}</small>
                                                </div>
                                        <button class="btn btn-danger btn-sm btn-remover-usuario" data-id="${user.usuario_ruta_id}" title="Remover Usuario">
                                            <i class="fas fa-times"></i>
                                                </button>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<p class="text-muted">No hay responsables asignados a esta ruta.</p>';
                    }
                    $('#usuarios-asignados-lista').html(html);
                } else {
                    console.error('Error al cargar usuarios asignados:', response.mensaje);
                    $('#usuarios-asignados-lista').html('<p class="text-danger">Error al cargar responsables.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX al cargar usuarios asignados:', error);
                $('#usuarios-asignados-lista').html('<p class="text-danger">Error de comunicación.</p>');
            }
        });
    }
    
    function cargarUsuariosDisponibles() {
        console.log('Cargando usuarios disponibles...');
        $.ajax({
            url: 'ajax/rutas_ajax.php',
            type: 'POST',
            data: {
                accion: 'listar_todos_usuarios_con_acceso' // Esta acción debe devolver todos los usuarios con sus perfiles
            },
            dataType: 'json',
            success: function(response) {
                if (response.estado === 'ok' && response.data) {
                    let options = '<option value="">Cargando usuarios...</option>';
                    response.data.forEach(function(user) {
                        // Asegurarse de que el perfil 'Cobrador' exista o ajustar la lógica
                        if (user.perfil_nombre === 'Cobrador') { // Filtra solo por cobradores
                            options += `<option value="${user.id_usuario}"
                                               data-usuario="${user.usuario}"
                                               data-nombre="${user.nombre_completo}"
                                               data-sucursal="${user.sucursal_nombre}"
                                               data-perfil="${user.perfil_nombre}"
                                               data-rutas-asignadas="${user.rutas_asignadas}"
                                               data-rutas-nombres="${user.rutas_nombres}"
                                               data-ultima-asignacion="${user.ultima_asignacion || ''}">
                                            ${user.nombre_completo} (${user.usuario}) - ${user.perfil_nombre} (${user.sucursal_nombre})
                                        </option>`;
                        }
                    });
                    $('#select-usuario').html(options);
                    // Reinicializar Select2 si está en uso
                    if (typeof $.fn.select2 !== 'undefined') {
                        $('#select-usuario').select2({
                            placeholder: 'Buscar usuario por nombre, sucursal o perfil...',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#modal-responsables-ruta')
                        });
                    }
                } else {
                    console.error('Error al cargar usuarios disponibles:', response.mensaje);
                    $('#select-usuario').html('<option value="">Error al cargar usuarios</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX al cargar usuarios disponibles:', error);
                $('#select-usuario').html('<option value="">Error de comunicación</option>');
            }
        });
    }

    // Manejar la asignación de usuario
    $('#form-asignar-usuario').on('submit', function(e) {
        e.preventDefault();
        const rutaId = rutaActual.ruta_id; // Usar la ruta actual seleccionada
        const usuarioId = $('#select-usuario').val();
        const tipoAsignacion = $('#tipo-asignacion').val();
        const observaciones = $('#observaciones-asignacion-usuario').val(); // Si existe este campo

        if (!rutaId || !usuarioId || !tipoAsignacion) {
            Swal.fire('Advertencia', 'Todos los campos son requeridos para asignar un responsable.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('accion', 'asignar_usuario');
        formData.append('ruta_id', rutaId);
        formData.append('usuario_id', usuarioId);
        formData.append('tipo_asignacion', tipoAsignacion);
        formData.append('observaciones', observaciones); // Asegúrate de que este campo exista en tu formulario
        
        $.ajax({
            url: 'ajax/rutas_ajax.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.estado === 'ok') {
                    Swal.fire('Éxito', response.mensaje, 'success');
                    cargarUsuariosAsignados(rutaId); // Recargar la lista de asignados
                    // Limpiar el select de usuario o re-cargar disponibles si es necesario
                    $('#select-usuario').val('').trigger('change');
                    $('#info-usuario-seleccionado').hide();
                    } else {
                    Swal.fire('Error', response.mensaje, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX al asignar usuario:', error);
                Swal.fire('Error', 'Error de comunicación al asignar usuario.', 'error');
            }
        });
    });
    
    // Manejar la remoción de usuario
    $('#usuarios-asignados-lista').on('click', '.btn-remover-usuario', function() {
        const usuarioRutaId = $(this).data('id');
        const rutaId = rutaActual.ruta_id; // Asegúrate de tener la ruta actual
        
        Swal.fire({
            title: '¿Está seguro?',
            text: 'Se removerá este usuario de la ruta.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, remover',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax/rutas_ajax.php',
                    type: 'POST',
                    data: {
                        accion: 'remover_usuario',
                        usuario_ruta_id: usuarioRutaId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.estado === 'ok') {
                            Swal.fire('Removido', response.mensaje, 'success');
                            cargarUsuariosAsignados(rutaId); // Recargar la lista
                            } else {
                            Swal.fire('Error', response.mensaje, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX al remover usuario:', error);
                        Swal.fire('Error', 'Error de comunicación al remover usuario.', 'error');
                    }
                });
            }
        });
    });
    
    // Función para actualizar información de responsables en el modal principal
    function actualizarInfoResponsables(rutaId) {
        $.ajax({
            url: 'ajax/rutas_ajax.php',
            type: 'POST',
            data: {
                accion: 'obtener',
                ruta_id: rutaId
            },
            success: function(respuesta) {
                try {
                    let res;
                    if (typeof respuesta === 'string') {
                        res = JSON.parse(respuesta);
                    } else {
                        res = respuesta;
                    }
                    
                    if (res.estado === 'ok') {
                        $('#responsables_info').val(res.data.responsables || 'Sin responsables asignados');
                        // También actualizar la tabla principal
                        tablaRutas.ajax.reload();
                    }
                } catch (e) {
                    console.error('Error al actualizar info responsables:', e);
                }
            }
        });
    }
    
    // Manejar cierre del modal de responsables
    $('#modal-responsables-ruta .close, #modal-responsables-ruta [data-dismiss="modal"]').on('click', function() {
        $('#modal-responsables-ruta').modal('hide');
    });
    
    // Inicializar validación al cargar la página
    initializeValidation();
});
</script> 

<!-- Scripts específicos para rutas -->
<script>
$(document).ready(function() {
    // La inicialización del DataTable ya está hecha en el script anterior
});</script> 