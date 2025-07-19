# 📋 Módulo de Asignación de Rutas y Cobradores

## 🎯 Funcionalidad Implementada

Este módulo permite al administrador o supervisor asignar **sucursal**, **ruta** y **cobrador** al momento de aprobar un préstamo, integrando completamente el sistema de cobranza con el flujo de aprobación de préstamos.

## 🛠️ Componentes Implementados

### 1. **Estructura de Base de Datos**
- ✅ Campos agregados a `prestamo_cabecera`:
  - `sucursal_asignada_id`: ID de sucursal asignada
  - `ruta_asignada_id`: ID de ruta asignada 
  - `cobrador_asignado_id`: ID de usuario cobrador
  - `fecha_asignacion`: Timestamp de asignación
  - `observaciones_asignacion`: Notas sobre la asignación

### 2. **Modal de Asignación**
- ✅ Interfaz intuitiva con:
  - Información del préstamo (número, cliente, monto)
  - Combo cascada: Sucursal → Ruta → Cobrador
  - Campo de observaciones opcional
  - Validación de campos requeridos

### 3. **Flujo de Funcionamiento**

#### **Paso 1: Solicitud de Aprobación**
1. Admin/Supervisor hace clic en "Aprobar" préstamo
2. Se abre modal de asignación en lugar de aprobación directa
3. Se muestran datos del préstamo para contexto

#### **Paso 2: Selección de Asignación**
1. **Sucursal**: Lista de sucursales activas
2. **Ruta**: Se cargan rutas de la sucursal seleccionada
3. **Cobrador**: Se cargan usuarios asignados a la ruta
4. **Observaciones**: Campo opcional para notas

#### **Paso 3: Validación y Confirmación**
- Validación front-end de campos requeridos
- Confirmación con resumen de asignación
- Validación back-end de relaciones (ruta-sucursal, cobrador-ruta)

#### **Paso 4: Procesamiento**
1. Aprobación del préstamo (`pres_aprobacion = 'aprobado'`)
2. Asignación de ruta y cobrador
3. **Auto-asignación del cliente a la ruta** (si no está ya asignado)
4. Actualización de detalles del préstamo
5. Opción de imprimir contrato

## 📁 Archivos Modificados/Creados

### **Base de Datos**
- `sql/agregar_campos_asignacion_prestamos.sql` - Script de migración

### **Backend**
- `ajax/aprobacion_ajax.php` - Nuevos endpoints AJAX
- `controladores/aprobacion_controlador.php` - Lógica de asignación
- `modelos/aprobacion_modelo.php` - Validaciones y procesamiento
- `controladores/sucursales_controlador.php` - Método para listar sucursales activas
- `controladores/rutas_controlador.php` - Método para listar usuarios por ruta

### **Frontend**
- `vistas/aprobacion.php` - Modal y funcionalidad JavaScript

## 🔧 Endpoints AJAX Implementados

### **GET /ajax/aprobacion_ajax.php?accion=listar_sucursales**
- Obtiene sucursales activas para el combo

### **POST /ajax/aprobacion_ajax.php** 
- `accion=listar_rutas_sucursal` + `sucursal_id`: Rutas por sucursal
- `accion=listar_cobradores_ruta` + `ruta_id`: Cobradores por ruta  
- `accion=5` + datos completos: Aprobar con asignación

## 🔐 Validaciones Implementadas

### **Frontend**
- Campos requeridos (sucursal, ruta, cobrador)
- Validación HTML5 con Bootstrap
- Confirmación visual antes de envío

### **Backend**
- Validación de IDs numéricos válidos
- Verificación de que ruta pertenece a sucursal
- Verificación de que cobrador está asignado a ruta  
- Validación de estado del préstamo (debe estar pendiente)
- Transacciones para mantener consistencia

## 🎨 Experiencia de Usuario

### **Flujo Mejorado**
1. ✅ **Previo**: Clic → Confirmar → Aprobar (sin asignación)
2. ✅ **Nuevo**: Clic → Modal Asignación → Completar datos → Confirmar → Aprobar + Asignar

### **Características UX**
- **Combos cascada**: Selecciones dependientes automáticas
- **Información contextual**: Datos del préstamo siempre visibles
- **Validación en tiempo real**: Feedback inmediato
- **Confirmación visual**: Resumen antes de procesar
- **Estados de carga**: Indicadores de progreso
- **Integración con impresión**: Flujo completo hasta contrato

## 🚀 Beneficios del Sistema

### **Para Administradores**
- ✅ Asignación obligatoria en aprobación
- ✅ Validación automática de relaciones
- ✅ Historial de asignaciones con timestamps
- ✅ Reducción de errores manuales

### **Para Cobradores**
- ✅ Clientes automáticamente asignados a sus rutas
- ✅ Información completa de préstamos aprobados
- ✅ Organización por rutas establecidas

### **Para el Sistema**
- ✅ Integridad referencial automática
- ✅ Automatización del proceso de cobranza
- ✅ Trazabilidad completa
- ✅ Preparación para funcionalidades móviles

## 📊 Integración con Sistema de Rutas

### **Compatibilidad Total**
- ✅ Usa infraestructura existente de rutas
- ✅ Respeta asignaciones de usuarios a rutas
- ✅ Auto-asigna clientes nuevos a rutas
- ✅ Mantiene orden de visitas

### **Funcionalidades Futuras Preparadas**
- 🔄 Reasignación de préstamos entre rutas
- 📱 Sincronización con app móvil
- 📍 Optimización GPS de recorridos
- 📈 Métricas de efectividad por cobrador

## 🔧 Configuración de Uso

### **Permisos Requeridos**
- Acceso al módulo "Aprobar S/P" (módulo ID 36)
- Usuarios con perfil Administrador o Supervisor

### **Datos Necesarios**
- ✅ Sucursales configuradas y activas
- ✅ Rutas creadas y asignadas a sucursales  
- ✅ Usuarios asignados a rutas como cobradores
- ✅ Sistema de módulos y perfiles configurado

## 📝 Notas Técnicas

### **Transacciones Seguras**
- Usa transacciones de base de datos para consistencia
- Rollback automático en caso de errores
- Validaciones previas antes de modificar datos

### **Optimización de Rendimiento**
- Índices agregados en campos de asignación
- Consultas optimizadas con JOINs eficientes
- Carga bajo demanda de combos (AJAX)

### **Escalabilidad**
- Diseño preparado para múltiples sucursales
- Estructura extensible para nuevos campos
- Compatibilidad con funcionalidades de rutas existentes

## 🐛 Solución de Problemas

### **Errores Comunes**
1. **"La ruta no pertenece a la sucursal"**
   - Verificar que la ruta esté asignada a la sucursal correcta
   
2. **"El cobrador no está asignado a la ruta"**
   - Verificar asignación en módulo de rutas
   - El sistema permite flexibilidad para administradores

3. **"Préstamo no encontrado"**
   - Verificar que el préstamo esté en estado 'pendiente'
   - Refrescar la tabla de préstamos

### **Mantenimiento**
- Ejecutar script SQL solo una vez por ambiente
- Verificar permisos de módulos después de implementación
- Comprobar que tablas de rutas existan antes de usar

---

## 📞 Soporte

Para dudas o problemas con la implementación:
1. Verificar que se ejecutó el script SQL de migración
2. Comprobar permisos de usuario en módulos
3. Revisar logs de errores en navegador (F12)
4. Verificar estructura de rutas y sucursales

**✅ Implementación Completa - Lista para Producción** 