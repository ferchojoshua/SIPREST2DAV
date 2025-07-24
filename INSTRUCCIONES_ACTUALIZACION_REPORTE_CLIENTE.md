# INSTRUCCIONES PARA ACTUALIZACIÓN DEL REPORTE POR CLIENTE

## 📋 **Mejoras Implementadas**

Se han agregado las siguientes mejoras al **Historial del Cliente**:

### ✅ **Nuevas Columnas Agregadas:**
1. **Nombre del Cliente** - Se muestra directamente en la tabla
2. **Fecha de Apertura** - Fecha cuando se abrió el préstamo  
3. **Fecha de Vencimiento** - Fecha calculada automáticamente según:
   - Pagos Diarios: fecha_apertura + número_cuotas días
   - Pagos Semanales: fecha_apertura + número_cuotas semanas
   - Pagos Quincenales: fecha_apertura + (número_cuotas × 15) días
   - Pagos Mensuales: fecha_apertura + número_cuotas meses
4. **Moneda Dinámica** - Cada préstamo muestra su moneda correcta (S/, $, €, etc.)

### 🎯 **Reportes Mejorados:**
- **Excel**: Incluye todas las nuevas columnas con formato profesional
- **PDF**: Diseño actualizado con información completa
- **Impresión**: Documento optimizado con todos los datos

---

## 🔧 **PASO 1: Actualizar Base de Datos**

### Opción A: Ejecución Automática (Recomendada)
```powershell
# Abrir PowerShell como Administrador
# Navegar a la carpeta del proyecto
cd C:\xampp\htdocs\CrediCrece\sql

# Ejecutar el script de actualización
.\ejecutar_actualizar_sp_reporte_cliente.ps1
```

### Opción B: Ejecución Manual
1. Abrir **phpMyAdmin** o **MySQL Workbench**
2. Seleccionar la base de datos `credicrece`
3. Ejecutar el contenido del archivo `sql/actualizar_sp_reporte_cliente.sql`

---

## 📁 **PASO 2: Archivos Modificados**

Los siguientes archivos han sido actualizados automáticamente:

### 🌐 **Frontend:**
- `vistas/reporte_cliente.php` - Vista principal con nuevas columnas
- JavaScript actualizado para manejar los nuevos campos

### ⚙️ **Backend:**
- `ajax/reportes_ajax.php` - Funciones de exportación actualizadas
- Exportación Excel con 7 columnas
- Exportación PDF con diseño ampliado
- Función de impresión mejorada

### 🗄️ **Base de Datos:**
- `CrediCrece.sql` - Stored Procedure `SP_REPORTE_POR_CLIENTE` actualizado
- `sql/actualizar_sp_reporte_cliente.sql` - Script de actualización

---

## ✅ **PASO 3: Verificar Funcionamiento**

### 1. **Probar la Vista Principal:**
```
- Ir a: Reportes > Reporte por Cliente
- Seleccionar un cliente
- Generar reporte
- Verificar que se muestren las 8 columnas:
  ✓ Préstamo
  ✓ Cliente  
  ✓ Fecha Apertura
  ✓ Fecha Vencimiento
  ✓ Monto (con moneda correcta)
  ✓ Estado
  ✓ Saldo (con moneda correcta)
  ✓ Acciones
```

### 2. **Probar Exportaciones:**
```
- Botón EXCEL: Descargar archivo .xlsx con 7 columnas
- Botón PDF: Generar PDF profesional con logo empresa
- Botón IMPRIMIR: Abrir ventana de impresión optimizada
- Botón VER: Mostrar detalle completo del préstamo
```

### 3. **Verificar Monedas:**
```
- Comprobar que cada préstamo muestra su moneda correcta
- Verificar totales con moneda apropiada
- Sin valores hardcodeados de "S/" o "C$"
```

---

## 🎯 **Resultado Final**

### **Antes:**
| Préstamo | Fecha | Monto | Estado | Saldo | Acciones |
|----------|-------|-------|---------|-------|----------|
| LE001-000001 | 24/07/2025 | S/ 10,000 | aprobado | S/ 11,700 | 👁️ VER |

### **Ahora:**
| Préstamo | Cliente | Fecha Apertura | Fecha Vencimiento | Monto | Estado | Saldo | Acciones |
|----------|---------|----------------|-------------------|--------|---------|-------|----------|
| LE001-000001 | Juan Pérez | 20/07/2025 | 20/01/2026 | C$ 10,000.00 | aprobado | C$ 11,700.00 | 👁️ VER |

---

## 🚨 **Solución de Problemas**

### **Error: "Unknown column 'fecha_vencimiento'"**
```sql
-- Ejecutar manualmente en MySQL:
DROP PROCEDURE IF EXISTS SP_REPORTE_POR_CLIENTE;
-- Luego ejecutar el contenido completo del archivo actualizar_sp_reporte_cliente.sql
```

### **Error: "MySQL command not found"**
```bash
# Verificar que MySQL esté en el PATH del sistema
# O usar la ruta completa:
C:\xampp\mysql\bin\mysql.exe
```

### **Las exportaciones no funcionan:**
1. Verificar que `vendor/autoload.php` existe
2. Verificar que `MPDF/vendor/autoload.php` existe  
3. Verificar permisos de escritura en carpetas

---

## 📞 **Soporte**

Si encuentras algún problema:

1. **Revisar logs de PHP** en `xampp/apache/logs/error.log`
2. **Verificar Console del navegador** (F12 > Console)
3. **Comprobar la base de datos** que el SP se haya actualizado

---

## 🎉 **¡Listo!**

El **Historial del Cliente** ahora incluye:
- ✅ Información completa del cliente y fechas
- ✅ Monedas dinámicas sin hardcodeo
- ✅ Exportaciones profesionales con datos de empresa
- ✅ Cálculo automático de fechas de vencimiento
- ✅ Diseño responsive y profesional 