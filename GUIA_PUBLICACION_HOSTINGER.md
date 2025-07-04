# 🚀 GUÍA COMPLETA: Publicar SIPREST en Hostinger

## 📋 PASO 1: Preparación de Base de Datos

### Exportar Base de Datos Local
Tu base de datos ya fue exportada como `dbprestamo_backup.sql`

## 🗃️ PASO 2: Configurar Base de Datos en Hostinger

1. **Accede al Panel de Hostinger**
2. **Ve a "Bases de Datos MySQL"**
3. **Crear nueva base de datos:**
   - Nombre: `u123456_dbprestamo` (reemplaza prefijo)
   - Usuario: `u123456_usuario`
   - Contraseña: elige una segura
   - **¡ANOTA ESTOS DATOS!**

4. **Importar base de datos:**
   - Click en "Administrar" → phpMyAdmin
   - Selecciona tu BD → "Importar"
   - Sube `dbprestamo_backup.sql`

## 📁 PASO 3: Subir Archivos

### Usando File Manager de Hostinger:
1. Abre "Administrador de archivos"
2. Ve a carpeta `public_html`
3. **Elimina** todo el contenido actual
4. **Sube TODOS** los archivos de tu proyecto

## ⚙️ PASO 4: Configurar Conexión

Edita `conexion_reportes/r_conexion.php` con tus datos de Hostinger:

```php
<?php 
$mysqli = new mysqli('localhost','tu_usuario_hostinger','tu_password','tu_bd_name');
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
    echo 'Conexion Fallida: ', mysqli_connect_error();
    exit();
}
?>
```

## 🔐 PASO 5: Permisos de Carpetas

Configura permisos **755** o **777** para:
- `uploads/`
- `uploads/logos/`  
- `MPDF/pdf_caja/`
- `backup-restore/`

## 🧪 PASO 6: Verificar

1. Ve a `https://tudominio.com`
2. Deberías ver el login de SIPREST
3. Prueba iniciar sesión

## 🛠️ CONFIGURACIONES ADICIONALES

- **PHP:** Usa versión 7.4+
- **SSL:** Activa certificado gratuito en Hostinger
- **Permisos:** Si algo no funciona, usa permisos 777

## 🚨 PROBLEMAS COMUNES

- **Error BD:** Verifica usuario/contraseña/nombre
- **Archivos no cargan:** Revisa permisos de carpetas
- **Error 500:** Checa logs en Hostinger

## ✅ DATOS QUE NECESITAS DE HOSTINGER

Al crear la BD, Hostinger te dará:
- **Servidor:** localhost
- **Usuario:** u123456_tuusuario
- **Contraseña:** la que elijas
- **Base de datos:** u123456_tubd

¡Anota estos datos y reemplázalos en `r_conexion.php`! 