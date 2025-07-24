# 📋 STORED PROCEDURES Y ARCHIVOS PARA APROBACIÓN DE PRÉSTAMOS

## 🔧 **STORED PROCEDURES PRINCIPALES**

### 1. **SP_LISTAR_PRESTAMOS_POR_APROBACION**
```sql
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PRESTAMOS_POR_APROBACION` (
    IN `fecha_ini` DATE, 
    IN `fecha_fin` DATE
)   
BEGIN
    SELECT 
        pc.pres_id,
        pc.nro_prestamo,
        pc.cliente_id,
        c.cliente_nombres,
        pc.pres_monto,
        pc.pres_interes,
        pc.pres_cuotas,
        pc.fpago_id,
        fp.fpago_descripcion,
        pc.id_usuario,
        u.usuario,
        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS fecha,
        pc.pres_aprobacion AS estado,
        '' AS opciones,
        pc.pres_monto_cuota,
        pc.pres_monto_interes,
        pc.pres_monto_total,
        pc.pres_cuotas_pagadas
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    WHERE pc.pres_fecha_registro BETWEEN fecha_ini AND fecha_fin
    ORDER BY pc.pres_fecha_registro DESC;
END$$
DELIMITER ;
```
**Propósito:** Lista todos los préstamos filtrados por fecha para mostrar en la tabla de aprobación.

### 2. **SP_DESAPROBAR_PRESTAMO**
```sql
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DESAPROBAR_PRESTAMO` (
    IN `N_PRESTAMO` VARCHAR(8)
)   
BEGIN 
    DECLARE CANTIDAD INT;
    DECLARE CLIENTE INT;
    SET CLIENTE=(SELECT cliente_id FROM prestamo_cabecera WHERE nro_prestamo = N_PRESTAMO);
    SET @CANTIDAD:=(SELECT COUNT(*) FROM prestamo_detalle WHERE pdetalle_estado_cuota ='pagada' AND nro_prestamo = N_PRESTAMO);
    
    IF @CANTIDAD = 0 THEN
        UPDATE prestamo_cabecera SET 
            pres_aprobacion = 'pendiente',
            pres_estado_caja = 'VIGENTE',
            pres_estado = 'Pendiente' 
        WHERE nro_prestamo = N_PRESTAMO;
        
        UPDATE prestamo_detalle SET 
            pdetalle_estado_cuota = 'pendiente', 
            pdetalle_aprobacion = 'pendiente', 
            pdetalle_caja = 'VIGENTE' 
        WHERE nro_prestamo = N_PRESTAMO;
        
        UPDATE clientes SET
            cliente_estado_prestamo = 'con prestamo'
        WHERE cliente_id = CLIENTE;
        
        SELECT 1;
    ELSE
        SELECT 2;
    END IF;
END$$
DELIMITER ;
```
**Propósito:** Desaprueba un préstamo si no tiene cuotas pagadas.

### 3. **SP_ANULAR_PRESTAMO**
```sql
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ANULAR_PRESTAMO` (
    IN `N_PRESTAMO` VARCHAR(8)
)   
BEGIN
    DECLARE CLIENTE INT;
    
    UPDATE prestamo_cabecera SET 
        pres_aprobacion = 'anulado', 
        pres_estado_caja = '', 
        pres_estado = 'Anulado' 
    WHERE nro_prestamo = N_PRESTAMO;

    UPDATE prestamo_detalle SET 
        pdetalle_estado_cuota = 'Anulado', 
        pdetalle_caja = '', 
        pdetalle_aprobacion = 'anulado'  
    WHERE nro_prestamo = N_PRESTAMO;

    SET CLIENTE = (SELECT cliente_id FROM prestamo_cabecera WHERE nro_prestamo = N_PRESTAMO);
    
    UPDATE clientes SET
        cliente_estado_prestamo = 'DISPONIBLE'
    WHERE cliente_id = CLIENTE;

    SELECT "ok";
END$$
DELIMITER ;
```
**Propósito:** Anula completamente un préstamo.

### 4. **SP_ACTUALIZAR_ESTADO_CLIENTE_PRESTAMO**
```sql
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ACTUALIZAR_ESTADO_CLIENTE_PRESTAMO` (
    IN `ID` INT
)   
BEGIN
    UPDATE clientes SET 
        cliente_estado_prestamo = 'con prestamo' 
    WHERE cliente_id = ID;
END$$
DELIMITER ;
```
**Propósito:** Actualiza el estado del cliente cuando obtiene un préstamo.

---

## 📁 **ARCHIVOS DEL SISTEMA**

### 🎯 **MODELOS**

#### **modelos/aprobacion_modelo.php**
```php
class AprobacionModelo
{
    // Listar préstamos por aprobación
    static public function mdlListarPrestamosPorAprobacion($fecha_ini, $fecha_fin)
    
    // Aprobar préstamo simple
    static public function mdlActualizarEstadoPrest($nro_prestamo)
    
    // Validar préstamo para aprobación
    static public function mdlValidarPrestamoParaAprobacion($nro_prestamo)
    
    // Validar ruta-sucursal
    static public function mdlValidarRutaSucursal($ruta_id, $sucursal_id)
    
    // Aprobar préstamo con asignación de ruta/cobrador
    static public function mdlAprobarPrestamoConAsignacion($nro_prestamo, $sucursal_asignada_id, $ruta_asignada_id, $cobrador_asignado_id, $observaciones_asignacion)
    
    // Obtener datos completos del préstamo
    static public function mdlObtenerDatosCompletoPrestamo($nro_prestamo)
    
    // Listar usuarios activos (cobradores)
    static public function mdlListarUsuariosActivos()
    
    // Obtener estado de aprobación
    static public function mdlGetPrestamoAprobacionStatus($nro_prestamo)
}
```

### 🎮 **CONTROLADORES**

#### **controladores/aprobacion_controlador.php**
```php
class AprobacionControlador
{
    // Listar préstamos por aprobación
    static public function ctrListarPrestamosPorAprobacion($fecha_ini, $fecha_fin)
    
    // Aprobar préstamo simple
    static public function ctrActualizarEstadoPrest($nro_prestamo)
    
    // Aprobar préstamo con asignación
    static public function ctrAprobarPrestamoConAsignacion($nro_prestamo, $sucursal_asignada_id, $ruta_asignada_id, $cobrador_asignado_id, $observaciones_asignacion)
    
    // Desaprobar préstamo
    static public function ctrDesaprobarPrest($nro_prestamo)
}
```

### 🌐 **AJAX**

#### **ajax/aprobacion_ajax.php**
```php
class AjaxAprobacion
{
    // ACCIÓN 1: Listar préstamos por aprobación
    public function ListarPrestamosPorAprobacion($fecha_ini, $fecha_fin)
    
    // ACCIÓN 2: Aprobar préstamo simple
    public function ajaxActualizarEstadoPrest()
    
    // ACCIÓN 3: Desaprobar préstamo
    public function ajaxDesaprobarPrest()
    
    // ACCIÓN 4: Anular préstamo
    public function ajaxAnularPrest()
    
    // ACCIÓN 5: Aprobar con asignación de ruta/cobrador
    public function ajaxAprobarPrestamoConAsignacion()
    
    // ACCIÓN 6: Listar sucursales activas
    public function ajaxListarSucursales()
    
    // ACCIÓN 7: Listar rutas por sucursal
    public function ajaxListarRutasPorSucursal()
    
    // ACCIÓN 8: Listar cobradores activos
    public function ajaxListarCobradoresActivos()
    
    // ACCIÓN 9: Obtener datos completos del préstamo
    public function ajaxObtenerDatosCompletoPrestamo()
}
```

### 🖥️ **VISTAS**

#### **vistas/aprobacion.php**
**Funciones JavaScript principales:**
```javascript
// Cargar tabla de préstamos
function CargarContenido()

// Función para aprobar préstamo
function aprobarPrestamo(nro_prestamo)

// Función para desaprobar préstamo
function desaprobarPrestamo(nro_prestamo)

// Función para anular préstamo
function anularPrestamo(nro_prestamo)

// Modal de plan de pagos
function cargarPlanPagoModal(nro_prestamo)

// Modal de asignación de ruta/cobrador
function mostrarModalAsignacion(nro_prestamo)

// Obtener datos completos del préstamo
function obtenerDatosCompletoPrestamo(nro_prestamo)
```

---

## 🔄 **FLUJO COMPLETO DE APROBACIÓN**

### **1. LISTA DE PRÉSTAMOS:**
1. **Vista:** `vistas/aprobacion.php` → Carga tabla
2. **AJAX:** `ajax/aprobacion_ajax.php` (acción 1)
3. **Controlador:** `ctrListarPrestamosPorAprobacion()`
4. **Modelo:** `mdlListarPrestamosPorAprobacion()`
5. **SP:** `SP_LISTAR_PRESTAMOS_POR_APROBACION`

### **2. APROBAR PRÉSTAMO SIMPLE:**
1. **Vista:** Click en botón "Aprobar"
2. **AJAX:** `ajax/aprobacion_ajax.php` (acción 2)
3. **Controlador:** `ctrActualizarEstadoPrest()`
4. **Modelo:** `mdlActualizarEstadoPrest()` → UPDATE directo

### **3. APROBAR CON ASIGNACIÓN:**
1. **Vista:** Click en "Aprobar y Asignar"
2. **Modal:** Selección de sucursal/ruta/cobrador
3. **AJAX:** `ajax/aprobacion_ajax.php` (acción 5)
4. **Controlador:** `ctrAprobarPrestamoConAsignacion()`
5. **Modelo:** `mdlAprobarPrestamoConAsignacion()` → Transacción completa

### **4. DESAPROBAR PRÉSTAMO:**
1. **Vista:** Click en "Desaprobar"
2. **AJAX:** `ajax/aprobacion_ajax.php` (acción 3)
3. **Controlador:** `ctrDesaprobarPrest()`
4. **Modelo:** `mdlDesaprobarPrest()`
5. **SP:** `SP_DESAPROBAR_PRESTAMO`

### **5. ANULAR PRÉSTAMO:**
1. **Vista:** Click en "Anular"
2. **AJAX:** `ajax/aprobacion_ajax.php` (acción 4)
3. **Controlador:** `ctrAnularPrest()`
4. **Modelo:** `mdlAnularPrest()`
5. **SP:** `SP_ANULAR_PRESTAMO`

### **6. VER PLAN DE PAGOS:**
1. **Vista:** Click en "Plan de Pagos"
2. **AJAX:** `ajax/aprobacion_ajax.php` (acción 9)
3. **Modelo:** `mdlObtenerDatosCompletoPrestamo()`
4. **Cálculo:** Frontend JavaScript (Francés/Simple)

---

## 🛠️ **TABLAS INVOLUCRADAS**

### **Principales:**
- `prestamo_cabecera` - Datos principales del préstamo
- `prestamo_detalle` - Cuotas del préstamo
- `clientes` - Información del cliente
- `usuarios` - Usuario que registra/aprueba
- `forma_pago` - Tipo de pago
- `moneda` - Moneda del préstamo

### **Para Asignación:**
- `sucursales` - Sucursales disponibles
- `rutas` - Rutas de cobranza
- `usuarios_rutas` - Asignación de cobradores a rutas
- `clientes_rutas` - Asignación de clientes a rutas

---

## ⚠️ **ESTADOS DE PRÉSTAMO**

### **pres_aprobacion:**
- `pendiente` - Esperando aprobación
- `aprobado` - Aprobado y activo
- `anulado` - Cancelado completamente

### **pres_estado_caja:**
- `VIGENTE` - Activo en caja
- `""` (vacío) - Inactivo

### **pdetalle_estado_cuota:**
- `pendiente` - Cuota por pagar
- `pagada` - Cuota pagada
- `Anulado` - Cuota anulada

---

## 🚨 **PUNTOS CRÍTICOS IDENTIFICADOS**

1. **Error `c.nombres`** - Debe ser `c.cliente_nombres`
2. **Transacciones** - Importantes para mantener consistencia
3. **Validaciones** - Verificar estados antes de aprobar/desaprobar
4. **Asignación** - Opcional según campos disponibles en BD
5. **Logs** - Sistema de debug implementado para troubleshooting

---

Esta documentación cubre todos los stored procedures, archivos PHP y flujos relacionados con la aprobación de préstamos en el sistema CrediCrece. 