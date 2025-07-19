# 📋 MANUAL DE IMPLEMENTACIÓN - CAJAS POR SUCURSAL
## Guía Paso a Paso para SIPREST

---

## ⚠️ **SITUACIÓN ACTUAL**

Actualmente está viendo el **modal básico** porque las mejoras aún no están implementadas en la base de datos. Necesita ejecutar los scripts SQL para activar todas las funcionalidades.

---

## 🛠️ **PASOS DE IMPLEMENTACIÓN**

### **PASO 1: Ejecutar Scripts SQL** ⭐ (CRÍTICO)

#### **A. Script Principal de Mejoras**
```sql
-- Ejecutar en phpMyAdmin o MySQL Workbench:
```
1. Abra **phpMyAdmin**
2. Seleccione la base de datos `siprest`
3. Vaya a la pestaña **SQL**
4. Copie y pegue todo el contenido de `sql/mejoras_modulo_caja.sql`
5. Haga clic en **Continuar**

#### **B. Script del Menú**
```sql
-- Ejecutar después del script principal:
```
1. En la misma pestaña **SQL**
2. Copie y pegue todo el contenido de `sql/agregar_dashboard_caja_menu.sql`
3. Haga clic en **Continuar**

### **PASO 2: Verificar la Implementación**

Después de ejecutar los scripts, verifique que se crearon las nuevas tablas:

```sql
-- Ejecutar esta consulta para verificar:
SHOW TABLES LIKE 'caja_%';
```

**Debe ver estas tablas:**
- ✅ `caja_permisos`
- ✅ `caja_auditoria` 
- ✅ `cajas_sucursales`
- ✅ `caja_alertas`
- ✅ `caja_conteos_fisicos`

### **PASO 3: Configurar Permisos Iniciales**

```sql
-- Dar permisos completos a su usuario administrador:
INSERT INTO caja_permisos (
    id_usuario, 
    puede_abrir_caja, 
    puede_cerrar_caja, 
    puede_ver_reportes,
    puede_gestionar_movimientos, 
    puede_supervisar, 
    usuario_creacion
) VALUES (
    1,  -- ID de su usuario (cambiar si es diferente)
    1, 1, 1, 1, 1, 1
);
```

### **PASO 4: Crear Cajas por Sucursal**

```sql
-- Ejemplo para crear cajas por sucursal:
INSERT INTO cajas_sucursales (
    sucursal_id, 
    nombre_caja, 
    codigo_caja, 
    descripcion, 
    tipo_caja, 
    usuario_creacion
) VALUES 
(11, 'Caja Principal - Sede Central', 'CP-001', 'Caja principal de la sucursal central', 'principal', 1),
(11, 'Caja Secundaria - Sede Central', 'CS-001', 'Caja secundaria para alta demanda', 'secundaria', 1);
```

---

## 🎯 **CÓMO USAR EL SISTEMA DESPUÉS DE LA IMPLEMENTACIÓN**

### **1. Acceso al Dashboard Avanzado**

Una vez ejecutados los scripts, verá en el menú:
```
Caja
├── Dashboard de Caja (NUEVO)
├── Aperturar Caja
└── Ingresos / Egre
```

### **2. Configurar Cajas por Sucursal**

1. **Ir a** → Caja → Dashboard de Caja
2. **O** usar el botón "Configurar Cajas por Sucursal" en la vista actual
3. **Agregar nuevas cajas** con estos datos:
   - Sucursal (seleccionar de la lista)
   - Nombre de la caja (ej: "Caja Principal")
   - Código único (ej: "CP-001")
   - Tipo (Principal/Secundaria/Temporal)
   - Ubicación física

### **3. Apertura con Validaciones**

En el modal de apertura verá campos adicionales:
- ✅ **Sucursal** - Seleccione la sucursal
- ✅ **Tipo de Caja** - Principal, secundaria o temporal
- ✅ **Observaciones** - Comentarios adicionales
- ✅ **Validación Física** - Checkbox para conteo
- ✅ **Límites de Usuario** - Mostrados automáticamente

---

## 🔧 **RESOLUCIÓN DE PROBLEMAS**

### **❌ No veo el Dashboard en el menú**
**Solución:** Ejecute el script `sql/agregar_dashboard_caja_menu.sql`

### **❌ Error al ejecutar los scripts**
**Solución:** 
1. Verifique que la base de datos sea `siprest`
2. Asegúrese de tener permisos de administrador
3. Ejecute los scripts uno por uno

### **❌ No veo las opciones avanzadas en el modal**
**Solución:** 
1. Refresque la página (Ctrl+F5)
2. Verifique la consola del navegador (F12)
3. Confirme que los scripts se ejecutaron correctamente

### **❌ Error "tabla no existe"**
**Solución:** El script principal no se ejecutó correctamente. Vuelva a ejecutar `sql/mejoras_modulo_caja.sql`

---

## 📊 **FLUJO COMPLETO PARA REGISTRAR CAJAS POR SUCURSAL**

### **Método 1: Desde el Dashboard (Recomendado)**
1. Ejecutar scripts SQL
2. Ir a **Caja** → **Dashboard de Caja**
3. Usar las funciones avanzadas del dashboard

### **Método 2: Desde la Vista Actual**
1. Ejecutar scripts SQL
2. Refrescar la página de Caja actual
3. Hacer clic en **"Configurar Cajas por Sucursal"**
4. En el modal, agregar nuevas cajas:
   - Seleccionar sucursal
   - Ingresar nombre y código
   - Elegir tipo de caja
   - Especificar ubicación física
5. Hacer clic en **"Agregar Caja"**

### **Método 3: SQL Directo (Para múltiples cajas)**
```sql
-- Insertar múltiples cajas de una vez:
INSERT INTO cajas_sucursales (sucursal_id, nombre_caja, codigo_caja, descripcion, tipo_caja, usuario_creacion) VALUES
(11, 'Caja Principal Norte', 'CPN-001', 'Caja principal sucursal norte', 'principal', 1),
(11, 'Caja Secundaria Norte', 'CSN-001', 'Caja secundaria sucursal norte', 'secundaria', 1),
(12, 'Caja Principal Sur', 'CPS-001', 'Caja principal sucursal sur', 'principal', 1);
```

---

## ✅ **VERIFICACIÓN FINAL**

Después de la implementación, debe poder:

1. ✅ **Ver el nuevo dashboard** en el menú
2. ✅ **Abrir cajas con validaciones** avanzadas
3. ✅ **Seleccionar sucursal** en el modal de apertura
4. ✅ **Ver límites de usuario** según permisos
5. ✅ **Configurar múltiples cajas** por sucursal
6. ✅ **Recibir alertas automáticas** del sistema

---

## 🎯 **PRÓXIMO PASO INMEDIATO**

**EJECUTE AHORA:**
1. Abra phpMyAdmin
2. Seleccione base de datos `siprest`
3. Ejecute `sql/mejoras_modulo_caja.sql`
4. Ejecute `sql/agregar_dashboard_caja_menu.sql`
5. Refresque la página de SIPREST
6. Busque "Dashboard de Caja" en el menú

**¡Una vez hecho esto, verá todas las mejoras funcionando!**

---

## 📞 **SOPORTE**

Si tiene problemas:
1. Verifique que los scripts se ejecutaron sin errores
2. Confirme que las nuevas tablas existan
3. Refresque completamente el navegador
4. Revise la consola del navegador (F12) en busca de errores JavaScript

**Estado Actual:** ⏳ Pendiente de ejecutar scripts SQL  
**Estado Deseado:** ✅ Sistema de caja empresarial completo 