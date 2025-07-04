# INSTRUCCIONES PARA IMPLEMENTAR EL LOGO DE LA EMPRESA

## 1. Actualizar la Base de Datos

Ejecuta el siguiente comando SQL en tu base de datos `dbprestamo`:

```sql
ALTER TABLE empresa ADD COLUMN config_logo VARCHAR(255) DEFAULT NULL;
```

O puedes usar el archivo `actualizar_bd_logo.sql` que se creó:
- Abre phpMyAdmin o tu gestor de base de datos
- Selecciona la base de datos `dbprestamo`
- Ejecuta el contenido del archivo `actualizar_bd_logo.sql`

## 2. Funcionalidades Implementadas

### A. Pantalla de Configuración de Empresa
- **Archivo:** `vistas/configuracion.php`
- **Nuevas funciones:**
  - Campo para cargar logo de empresa
  - Vista previa del logo seleccionado
  - Validación de formato (JPG, PNG, GIF)
  - Validación de tamaño (máximo 2MB)

### B. Procesamiento del Logo
- **Archivo:** `ajax/configuracion_ajax.php`
- **Funciones:**
  - Carga y validación del archivo de logo
  - Guardado en la carpeta `uploads/logos/`
  - Nomenclatura: `logo_empresa_[timestamp].[extension]`

### C. Mostrar Logo en Documentos PDF
Se actualizaron los siguientes archivos para mostrar el logo dinámicamente:

1. **Tickets de Pago de Cuotas:**
   - `MPDF/ticket_pago_cuota.php`
   - `MPDF/ticket_pago_cuota_Email_mejorado.php`

2. **Reportes de Arqueo de Caja:**
   - `MPDF/reporte_arqueocaja_mejorado.php`
   - `MPDF/reporte_arqueocaja.php`
   - `MPDF/reporte_arqueocaja_Email.php`

3. **Documentos de Préstamos:**
   - `MPDF/historial_prestamo_nuevo.php`
   - `MPDF/historial_prestamo.php`
   - `MPDF/contrato_mejorado.php`

### D. Logo en Pantalla de Login
- **Archivo:** `vistas/assets/login/login.php`
- **Función:** Muestra el logo de la empresa en lugar del avatar genérico

## 3. Estructura de Carpetas Creadas

```
uploads/
└── logos/
    ├── (aquí se guardan los logos subidos)
    └── logo_empresa_[timestamp].jpg/png/gif
```

## 4. Lógica de Funcionamiento

### Carga del Logo:
1. El usuario selecciona un archivo de imagen en la configuración
2. Se valida el formato y tamaño
3. Se muestra una vista previa
4. Al guardar, se sube a `uploads/logos/`
5. Se guarda el nombre del archivo en la base de datos

### Mostrar Logo:
1. En cada documento PDF, se consulta si existe logo
2. Si existe y el archivo está disponible, se usa el logo de la empresa
3. Si no existe, se usa el logo por defecto (`img/logo.png`)

## 5. Archivos de Respaldo

Se mantienen los logos por defecto en:
- `img/logo.png` (para PDFs)
- `vistas/assets/img/default-logo.png` (para vista previa)

## 6. Cómo Usar

1. **Ejecutar el SQL** para agregar la columna a la base de datos
2. **Ir a Configuración** en el sistema
3. **Seleccionar un logo** usando el campo "Logo de la Empresa"
4. **Ver la vista previa** del logo seleccionado
5. **Guardar** la configuración
6. **Verificar** que el logo aparece en:
   - Pantalla de login
   - Todos los documentos PDF generados

## 7. Formatos Soportados

- **JPG/JPEG**
- **PNG**
- **GIF**
- **Tamaño máximo:** 2MB
- **Dimensiones recomendadas:** 200x200 píxeles o similar

## 8. Consideraciones Técnicas

- Los logos se redimensionan automáticamente en los PDFs
- Se mantiene la proporción original
- Fallback automático al logo por defecto si hay problemas
- Validación tanto en frontend como backend

¡La funcionalidad está lista para usar una vez que ejecutes el comando SQL! 