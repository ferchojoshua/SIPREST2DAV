# 📊 GUÍA COMPLETA - REPORTES DE MORA

## 🎯 ¿Qué Scripts Tengo Disponibles?

Te he creado **3 archivos diferentes** para manejar la mora según tus necesidades:

### 📋 **1. `clientes_prestamos_mora.sql`** - COMPLETO
**🔸 Para análisis profundo y reportes gerenciales**

Incluye **6 consultas diferentes**:
- ✅ Lista detallada de clientes en mora
- ✅ Resumen por cliente 
- ✅ Estadísticas generales
- ✅ TOP 10 clientes con mayor mora
- ✅ Lista de clientes prioritarios para contactar
- ✅ Formato para exportar a Excel

### 📋 **2. `consulta_mora_simple.sql`** - RÁPIDO
**🔸 Para uso diario y consulta rápida**

Una sola consulta que muestra:
- Cliente, teléfono, préstamo, cuota vencida
- Días de mora con colores: 🟢🟡🟠🔴

### 📋 **3. `procedimiento_mora.sql`** - PARA PROGRAMAR
**🔸 Para integrar en tu sistema PHP**

Crea **4 procedimientos almacenados**:
- `SP_REPORTE_CLIENTES_MORA()`
- `SP_RESUMEN_MORA_CLIENTE()`
- `SP_ESTADISTICAS_MORA()`
- `SP_MORA_POR_USUARIO(id_usuario)`

---

## 🚀 ¿Cuál Usar Según Tu Necesidad?

### **🔥 Uso Diario (Recomendado para empezar)**
```sql
-- Ejecutar: consulta_mora_simple.sql
-- Te muestra quién debe pagar HOY
```

### **📈 Análisis Completo**
```sql
-- Ejecutar: clientes_prestamos_mora.sql
-- Para reportes gerenciales detallados
```

### **💻 Integración al Sistema**
```sql
-- Ejecutar: procedimiento_mora.sql
-- Para crear una página web de reportes
```

---

## 📖 Cómo Usar Cada Script

### **1️⃣ SCRIPT SIMPLE (Recomendado para comenzar)**

```sql
-- Copiar y pegar consulta_mora_simple.sql en phpMyAdmin
-- Resultado: Lista de todos los clientes que deben pagar
```

**Resultado típico:**
| Cliente | Teléfono | Préstamo | Cuota | Fecha Vencimiento | Monto | Días Mora | Estado |
|---------|----------|----------|-------|-------------------|-------|-----------|--------|
| Juan Pérez | 555-1234 | P001 | 3 | 15/12/2024 | $150 | 25 | 🟡 MODERADA |

### **2️⃣ SCRIPT COMPLETO**

```sql
-- Ejecutar clientes_prestamos_mora.sql
-- Se ejecutan 6 consultas automáticamente
-- Cada una te da diferente información
```

**Las 6 consultas te muestran:**
1. **Lista detallada** - Todos los datos de cada cuota vencida
2. **Resumen por cliente** - Cuánto debe cada cliente en total
3. **Estadísticas generales** - Números totales de mora
4. **TOP 10** - Los clientes que más deben
5. **Lista prioritaria** - A quién llamar hoy (🔴🟡🟢)
6. **Para Excel** - Datos listos para exportar

### **3️⃣ PROCEDIMIENTOS PARA PHP**

```sql
-- 1. Ejecutar procedimiento_mora.sql (una sola vez)
-- 2. Usar los procedimientos en tu código PHP
```

**Ejemplo de uso en PHP:**
```php
// En tu controlador
$stmt = $conexion->prepare("CALL SP_REPORTE_CLIENTES_MORA()");
$stmt->execute();
$clientesMora = $stmt->fetchAll();
```

---

## 🎯 Casos de Uso Específicos

### **📞 "Quiero saber a quién llamar HOY"**
```sql
-- Usar: consulta_mora_simple.sql
-- Buscar clientes con 🟡🟠🔴
```

### **📊 "Necesito un reporte para mi jefe"**
```sql
-- Usar: clientes_prestamos_mora.sql
-- Ejecutar las consultas 3, 4 y 5
```

### **💰 "Quiero ver cuánto dinero tengo en mora"**
```sql
-- Usar: clientes_prestamos_mora.sql
-- Ver la consulta 3 (Estadísticas generales)
```

### **👥 "Quiero los 10 clientes que más deben"**
```sql
-- Usar: clientes_prestamos_mora.sql
-- Ver la consulta 4 (TOP 10)
```

### **📋 "Quiero exportar a Excel"**
```sql
-- Usar: clientes_prestamos_mora.sql
-- Ver la consulta 6 (Formato Excel)
-- Copiar resultados y pegar en Excel
```

### **💻 "Quiero agregarlo a mi sistema web"**
```sql
-- 1. Ejecutar: procedimiento_mora.sql
-- 2. Crear nueva página PHP que use los procedimientos
-- 3. Agregar al menú del sistema
```

---

## 🔧 Instalación y Configuración

### **Paso 1: Elegir el Script**
- **Principiante:** Empezar con `consulta_mora_simple.sql`
- **Avanzado:** Usar `clientes_prestamos_mora.sql`
- **Programador:** Usar `procedimiento_mora.sql`

### **Paso 2: Ejecutar**
1. Abrir **phpMyAdmin**
2. Seleccionar base de datos `dbprestamo`
3. Ir a **SQL**
4. Copiar y pegar el script elegido
5. Hacer clic en **Continuar**

### **Paso 3: Interpretar Resultados**

#### **🟢 Mora Leve (1-30 días)**
- **Acción:** Llamada amigable de recordatorio
- **Mensaje:** "Recordatorio amable de pago"

#### **🟡 Mora Moderada (31-60 días)**
- **Acción:** Llamada formal
- **Mensaje:** "Ponerse al día con los pagos"

#### **🟠 Mora Alta (61-90 días)**
- **Acción:** Llamada urgente + visita
- **Mensaje:** "Necesitamos hablar personalmente"

#### **🔴 Mora Crítica (+90 días)**
- **Acción:** Proceso legal / recuperación
- **Mensaje:** "Última oportunidad antes de acciones legales"

---

## ❓ Preguntas Frecuentes

### **"¿Puedo ejecutar esto todos los días?"**
✅ **SÍ** - Todos los scripts son seguros para ejecutar diariamente.

### **"¿Afecta a mis datos existentes?"**
❌ **NO** - Son solo consultas (SELECT), no modifican datos.

### **"¿Cuál es más rápido?"**
🏃‍♂️ **consulta_mora_simple.sql** es el más rápido.

### **"¿Puedo modificar las consultas?"**
✅ **SÍ** - Puedes personalizar fechas, montos, etc.

### **"¿Cómo exporto a Excel?"**
📊 Ejecutar consulta 6 de `clientes_prestamos_mora.sql` y copiar resultados.

### **"¿Puedo agregar esto a mi sistema?"**
💻 **SÍ** - Usar `procedimiento_mora.sql` y crear páginas PHP.

---

## 🎉 ¡Listo para Usar!

Ahora tienes **todo lo necesario** para manejar la mora en tu sistema:

1. **📋 Scripts SQL** - Para consultas directas
2. **💻 Procedimientos** - Para integrar al sistema  
3. **📖 Documentación** - Esta guía completa

### **Recomendación:** 
Empieza con `consulta_mora_simple.sql` para familiarizarte, luego usa `clientes_prestamos_mora.sql` para análisis más profundos.

¡La gestión de mora nunca fue tan fácil! 🚀 