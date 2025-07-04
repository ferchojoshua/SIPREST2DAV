# 🚀 GUÍA COMPLETA: Publicar SIPREST en Hostinger

## 📋 **PASO 1: Preparación de Archivos**

### ✅ **Archivos ya listos:**
- ✅ `dbprestamo_backup.sql` - Backup de tu base de datos
- ✅ `conexion_reportes/r_conexion_hostinger.php` - Configuración para Hostinger

---

## 🗃️ **PASO 2: Configurar Base de Datos en Hostinger**

### **2.1 Crear Base de Datos:**
1. Ingresa al **Panel de Control de Hostinger**
2. Ve a **"Bases de Datos MySQL"**
3. Haz clic en **"Crear nueva base de datos"**
4. Nombre sugerido: `u123456789_dbprestamo` (reemplaza con tu prefijo)
5. Crear usuario y contraseña seguros
6. **¡ANOTA ESTOS DATOS!** Los necesitarás después

### **2.2 Importar Base de Datos:**
1. En Hostinger, ve a **"Administrar"** junto a tu base de datos
2. Abre **phpMyAdmin**
3. Selecciona tu base de datos
4. Haz clic en **"Importar"**
5. Selecciona el archivo `dbprestamo_backup.sql`
6. Haz clic en **"Continuar"**

---

## 📁 **PASO 3: Subir Archivos al Hosting**

### **Opción A: File Manager (Recomendado)**
1. En Hostinger, abre **"Administrador de archivos"**
2. Ve a la carpeta **`public_html`**
3. **Elimina todo** el contenido de `public_html`
4. Sube **TODOS** los archivos de tu proyecto SIPREST
5. Estructura final en `public_html`:
   ```
   public_html/
   ├── ajax/
   ├── controladores/
   ├── modelos/
   ├── vistas/
   ├── MPDF/
   ├── uploads/
   ├── vendor/
   ├── inicio.php
   ├── index.php
   └── ... (todos los demás archivos)
   ```

### **Opción B: FTP/SFTP**
1. Usa **FileZilla** o similar
2. Datos de conexión en tu panel de Hostinger
3. Sube todo a `/public_html/`

---

## ⚙️ **PASO 4: Configurar Conexión de Base de Datos**

### **4.1 Actualizar archivo de conexión:**
1. Edita `conexion_reportes/r_conexion.php`
2. Reemplaza con los datos de tu BD de Hostinger:

```php
<?php 
$mysqli = new mysqli('localhost','tu_usuario_bd','tu_password_bd','tu_nombre_bd');
$mysqli->set_charset("utf8");
if (mysqli_connect_errno()) {
    echo 'Conexion Fallida: ', mysqli_connect_error();
    exit();
}
?>
```

### **4.2 Datos que necesitas de Hostinger:**
- **Servidor:** `localhost` (generalmente)
- **Usuario:** `u123456789_usuario` (tu prefijo + nombre)
- **Contraseña:** La que creaste
- **Base de datos:** `u123456789_dbprestamo`

---

## 🔐 **PASO 5: Configurar Permisos de Carpetas**

### **Permisos necesarios:**
```
uploads/          → 755 o 777
uploads/logos/    → 755 o 777
MPDF/pdf_caja/    → 755 o 777
backup-restore/   → 755 o 777
```

### **Cómo configurar en Hostinger:**
1. En File Manager, haz clic derecho en cada carpeta
2. Selecciona **"Permisos"**
3. Cambia a **755** (si no funciona, usa **777**)

---

## 🧪 **PASO 6: Verificar Funcionamiento**

### **6.1 Probar acceso:**
1. Ve a `https://tudominio.com`
2. Deberías ver la página de login de SIPREST
3. Prueba loguearte con un usuario existente

### **6.2 Verificar funciones:**
- ✅ Login funciona
- ✅ Reportes PDF se generan
- ✅ Subida de logos funciona
- ✅ Envío de emails (si configurado)

---

## 🛠️ **PASO 7: Configuraciones Adicionales**

### **7.1 PHP Version:**
- Asegúrate de usar **PHP 7.4 o superior**
- Se configura en el panel de Hostinger

### **7.2 WhatsApp (si usas):**
1. Edita `utilitarios/whatsapp_config.php`
2. Actualiza URLs y tokens según tu dominio

### **7.3 SSL Certificate:**
1. En Hostinger, activa **SSL gratuito**
2. Fuerza HTTPS en tu dominio

---

## 🚨 **SOLUCIÓN DE PROBLEMAS COMUNES**

### **Error de conexión de BD:**
- Verifica usuario, contraseña y nombre de BD
- Asegúrate que el usuario tenga permisos

### **Archivos no cargan:**
- Verifica permisos de carpetas (755/777)
- Revisa que la estructura sea correcta

### **PDFs no se generan:**
- Verifica permisos de `MPDF/pdf_caja/`
- Asegúrate que PHP tenga extensiones necesarias

### **Error 500:**
- Revisa archivos `.htaccess`
- Verifica configuración PHP
- Checa logs de error en Hostinger

---

## 📞 **CONTACTO Y SOPORTE**

Si necesitas ayuda:
1. **Panel Hostinger:** Chat en vivo 24/7
2. **Documentación:** https://support.hostinger.com
3. **Backups:** Hostinger hace backups automáticos

---

## ✅ **CHECKLIST FINAL**

- [ ] Base de datos creada e importada
- [ ] Archivos subidos a `public_html`
- [ ] Conexión de BD configurada
- [ ] Permisos de carpetas establecidos
- [ ] SSL activado
- [ ] Login funciona correctamente
- [ ] Reportes PDF se generan
- [ ] Sistema completamente operativo

## 🎉 **¡FELICITACIONES!**
Tu sistema SIPREST está ahora público en: **https://tudominio.com** 