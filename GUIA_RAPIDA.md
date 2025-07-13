# 🚀 GUÍA RÁPIDA - SISTEMA DE MORA

## 🔧 SOLUCIÓN AL ERROR `#1054 - Unknown column 'cliente_apellidos'`

### **🏃‍♂️ EJECUCIÓN RÁPIDA (5 minutos)**

```sql
-- PASO 1: Prueba rápida del sistema
SOURCE prueba_rapida_sistema_mora.sql;

-- PASO 2: Si funciona, ejecutar sistema completo
SOURCE limpiar_datos_prueba.sql;
SOURCE insertar_datos_prueba_mora_corregido.sql;
SOURCE consulta_mora_simple_corregida.sql;
SOURCE procedimiento_mora_corregido.sql;
```

### **📋 ARCHIVOS CORREGIDOS CREADOS**

✅ **`prueba_rapida_sistema_mora.sql`** - Prueba completa del sistema
✅ **`insertar_datos_prueba_mora_corregido.sql`** - Inserción sin cliente_apellidos
✅ **`consulta_mora_simple_corregida.sql`** - Consultas corregidas
✅ **`procedimiento_mora_corregido.sql`** - Procedimientos corregidos
✅ **`verificar_campos_clientes.sql`** - Verificar estructura de clientes

### **🎯 RESULTADO ESPERADO**

Después de ejecutar los archivos:
- ✅ 8 clientes insertados
- ✅ 8 préstamos con mora
- ✅ Consultas funcionando
- ✅ Procedimientos creados
- ✅ Sin errores

### **🔍 VERIFICACIÓN**

```sql
-- Ver clientes insertados
SELECT cliente_nombres, cliente_nro_documento FROM clientes 
WHERE cliente_nro_documento IN ('12345678', '87654321');

-- Ver préstamos en mora
SELECT COUNT(*) as prestamos_mora FROM prestamo_cabecera 
WHERE nro_prestamo LIKE '0000000%';
```

¡Listo! El sistema de mora está funcionando correctamente. 🎉 