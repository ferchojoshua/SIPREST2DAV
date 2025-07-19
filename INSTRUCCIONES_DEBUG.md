# 🔧 DEBUG: Error de Verificación de Duplicados en Sucursales

## 🎯 Problema identificado
El error "Datos Duplicados" y "Error al verificar duplicados" ocurre al intentar agregar una sucursal.

## 🔍 Diagnóstico realizado
1. ✅ **Archivo AJAX**: `ajax/sucursales_ajax.php` - Correcto
2. ✅ **Controlador**: `controladores/sucursales_controlador.php` - Correcto  
3. ✅ **Modelo**: `modelos/sucursales_modelo.php` - Correcto
4. ✅ **JavaScript**: `vistas/assets/dist/js/sucursales.js` - Mejorado con logs

## 🚀 Pasos para probar la funcionalidad

### Opción 1: Desde navegador (RECOMENDADO)
1. Ve a: `http://localhost/siprest/test_duplicados.php`
2. Revisa los resultados de las 4 pruebas
3. Abre la consola del navegador (F12) para ver los logs detallados

### Opción 2: Desde la consola del navegador
1. Ve a: `http://localhost/siprest/vistas/sucursales.php`
2. Abre la consola del navegador (F12)
3. Copia y pega el contenido de `debug_sucursales.js`
4. Ejecuta: `ejecutarPruebas()`

### Opción 3: Probar funciones individuales
En la consola del navegador:
```javascript
// Probar verificación de duplicados
testVerificarDuplicados('SUC001', 'Sucursal Central');

// Probar listar sucursales
testListarSucursales();

// Verificar elementos DOM
verificarElementos();
```

## 🔧 Mejoras implementadas

### 1. JavaScript mejorado
- ✅ Logs detallados en consola
- ✅ Mejor manejo de errores
- ✅ Validación de respuestas JSON

### 2. Funciones de debugging
- ✅ `test_duplicados.php` - Pruebas completas
- ✅ `debug_sucursales.js` - Pruebas desde consola
- ✅ Logs mejorados en el JavaScript principal

## 🎯 Qué buscar en las pruebas

### Si las pruebas fallan:
1. **Error de conexión**: Verificar base de datos
2. **Error PHP**: Verificar archivos de modelo/controlador
3. **Error JavaScript**: Verificar la consola del navegador
4. **Error AJAX**: Verificar la respuesta del servidor

### Si las pruebas funcionan:
El problema puede estar en:
1. **Datos específicos** que están causando el error
2. **Sesión de usuario** no iniciada correctamente
3. **Permisos** de la base de datos
4. **Configuración** del servidor

## 🔍 Cómo usar los logs mejorados

### En la consola del navegador verás:
```
🔍 Verificando duplicados: {codigo: "SUC001", nombre: "Test", id: null}
✅ Respuesta cruda del servidor: {"valid":true,"message":"Datos válidos"}
✅ Respuesta parseada: {valid: true, message: "Datos válidos"}
```

### Si hay errores verás:
```
❌ Error al parsear JSON: SyntaxError: Unexpected token
❌ Respuesta que causó el error: <html>Error 500</html>
```

## 🎯 Soluciones propuestas

### Si el problema persiste:
1. **Verificar la base de datos**:
   - Tabla `sucursales` existe
   - Columnas `codigo` y `nombre` existen
   - Datos no corruptos

2. **Verificar configuración**:
   - Conexión a base de datos
   - Permisos de archivos
   - Configuración de PHP

3. **Verificar datos específicos**:
   - Caracteres especiales en código/nombre
   - Longitud de los campos
   - Codificación de caracteres

## 📞 Próximos pasos
1. Ejecutar `test_duplicados.php`
2. Reportar los resultados específicos
3. Analizar los logs de la consola
4. Implementar la solución correspondiente 

## 🔍 **Paso crítico: Abrir la consola del navegador**

**Por favor, haz lo siguiente:**

### 1. **Mantén el modal abierto** (como está en la imagen)
### 2. **Presiona F12** para abrir las herramientas de desarrollador
### 3. **Ve a la pestaña "Console"**
### 4. **Busca logs como estos:**

```
🔍 Verificando duplicados: {codigo: "LE001", nombre: "Leon", id: null}
✅ Respuesta cruda del servidor: ...
✅ Respuesta parseada: ...
```

O errores como:
```
<code_block_to_apply_changes_from>
```

## 🎯 **Lo que necesito ver:**

### **Opción A: Si hay logs de verificación**
Comparte los logs que aparecen cuando presionas "Guardar", especialmente:
- La **respuesta cruda del servidor**
- La **respuesta parseada**
- Cualquier **error** que aparezca

### **Opción B: Si no hay logs detallados**
En la consola, ejecuta:
```javascript
testVerificarDuplicados('LE001', 'Leon');
```

## 🔧 **Análisis preliminar:**

Basándome en la imagen, veo que estás intentando agregar:
- **Nombre**: `Leon`
- **Código**: `LE001`
- **Dirección**: `CURVA`
- **Teléfono**: `86595453`
- **Estado**: `Activa`

### **Posibles causas del error:**

1. **Respuesta del servidor inválida** (no JSON válido)
2. **Error en la base de datos** durante la verificación
3. **Problema con la sesión** del usuario
4. **Datos específicos** causando conflicto

## 📱 **Acción inmediata:**

**Abre F12 → Console** y comparte:

1. **Todos los logs** que aparecen cuando presionas "Guardar"
2. **El resultado** de ejecutar: `testVerificarDuplicados('LE001', 'Leon')`
3. **Cualquier error en rojo** que veas

Con esa información podré identificar exactamente dónde está fallando y darte la solución específica. 🎯

**¿Puedes compartir lo que ves en la consola del navegador?** 