# 📱 Configuración de Mensajes WhatsApp

Este sistema envía automáticamente mensajes de confirmación por WhatsApp cuando un cliente paga una cuota de su préstamo.

## 🎯 Funcionalidad

Al momento de pagar una cuota, el sistema automáticamente:
- ✅ Registra el pago en la base de datos
- 📱 Envía un mensaje WhatsApp al cliente con:
  - Nombre del cliente
  - Número de préstamo
  - Número de cuota pagada
  - Monto pagado
  - Saldo restante del préstamo
  - Fecha y hora del pago

## 🛠️ Configuración Inicial

### 1. Crear cuenta en Twilio

1. Ve a [https://www.twilio.com/](https://www.twilio.com/)
2. Crea una cuenta gratuita
3. Verifica tu número de teléfono
4. Obtén tus credenciales del Dashboard:
   - **Account SID**: Empieza con "AC..."
   - **Auth Token**: Token de autenticación

### 2. Configurar WhatsApp

#### Opción A: Sandbox (Para pruebas) 🧪
1. En Twilio Console, ve a **Develop > Messaging > Try it out > Send a WhatsApp message**
2. Usa el número sandbox: `+1 415 523 8886`
3. Los usuarios deben enviar el código de activación al sandbox antes de recibir mensajes

#### Opción B: WhatsApp Business API (Para producción) 🏢
1. Solicita acceso a WhatsApp Business API
2. Requiere aprobación de WhatsApp y Facebook
3. Necesitas un número comercial verificado

### 3. Configurar el sistema

Edita el archivo `utilitarios/whatsapp_config.php`:

```php
// Configuración de Twilio
define('TWILIO_ACCOUNT_SID', 'ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); // Tu Account SID real
define('TWILIO_AUTH_TOKEN', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');  // Tu Auth Token real
define('TWILIO_WHATSAPP_NUMBER', 'whatsapp:+14155238886');        // Número de WhatsApp

// Configuración del país
define('CODIGO_PAIS_DEFAULT', '+51'); // Perú: +51, México: +52, Colombia: +57

// Activar envío
define('WHATSAPP_ACTIVO', true); // Cambiar a true para activar
```

## 📋 Requisitos de Base de Datos

Asegúrate de que la tabla `clientes` tenga la columna `cliente_celular`:

```sql
-- Verificar si existe la columna
DESCRIBE clientes;

-- Si no existe, agregarla
ALTER TABLE clientes ADD COLUMN cliente_celular VARCHAR(20) AFTER cliente_email;
```

## 🔧 Archivos Modificados/Creados

### Archivos Nuevos:
- `utilitarios/WhatsAppAPI.php` - Clase principal para envío de mensajes
- `utilitarios/whatsapp_config.php` - Configuración de credenciales
- `INSTRUCCIONES_WHATSAPP.md` - Esta documentación

### Archivos Modificados:
- `modelos/admin_prestamos_modelo.php` - Agregado método `mdlObtenerInfoWhatsApp()`
- `controladores/admin_prestamos_controlador.php` - Modificado `ctrPagarCuota()` para incluir WhatsApp

## 🧪 Pruebas

### 1. Configurar Sandbox
```
1. Envía "join [código]" al +1 415 523 8886
2. Recibirás confirmación de unión al sandbox
```

### 2. Realizar Pago de Prueba
```
1. Ve a Administrar Préstamos
2. Selecciona un préstamo con cuotas pendientes
3. Paga una cuota
4. Verifica que el cliente reciba el mensaje
```

## 📱 Ejemplo de Mensaje

```
🎉 PAGO CONFIRMADO 🎉

Estimado(a) Juan Pérez,

Hemos recibido su pago correspondiente a:

📋 Préstamo N°: PR-2024-001
📅 Cuota N°: 3
💰 Monto Pagado: S/ 250.00
💳 Saldo Restante: S/ 1,750.00
🕐 Fecha de Pago: 15/03/2024 14:30

📌 Recordatorio: Su próxima cuota vence según el cronograma establecido.

Gracias por confiar en nosotros. 🙏

Este es un mensaje automático generado por nuestro sistema.
```

## 🔍 Solución de Problemas

### Error: "WhatsApp desactivado en configuración"
- Cambiar `WHATSAPP_ACTIVO` a `true` en `whatsapp_config.php`

### Error: "Account SID no configurado"
- Reemplazar las X's con tus credenciales reales de Twilio

### Error: "Error al enviar WhatsApp"
- Verificar que el número del cliente esté en formato correcto
- Verificar que las credenciales de Twilio sean correctas
- Revisar logs del servidor para más detalles

### Cliente no recibe mensajes
- Verificar que el cliente haya enviado el código al sandbox
- Verificar que el número esté guardado con código de país
- Revisar los logs de error en el servidor

## 📊 Logs y Monitoreo

Los mensajes de WhatsApp se registran en los logs del servidor:
- Mensajes exitosos: "WhatsApp enviado exitosamente a [nombre]"
- Errores: "Error al enviar WhatsApp a [nombre]"

Para ver los logs:
```bash
tail -f /var/log/apache2/error.log
# o
tail -f /var/log/nginx/error.log
```

## 💰 Costos

### Sandbox (Gratis):
- ✅ Ilimitado para pruebas
- ❌ Solo números que se unan manualmente
- ❌ Mensajes con marca "via Twilio"

### WhatsApp Business API:
- 💰 Costo por mensaje (varía por país)
- ✅ Cualquier número de WhatsApp
- ✅ Mensajes sin marca de terceros
- ✅ Funciones avanzadas

## 🔒 Seguridad

- Las credenciales están en archivo separado
- Usar HTTPS en producción
- No mostrar credenciales en logs
- Validar números de teléfono antes de enviar

## 📞 Soporte

Para obtener ayuda:
1. Revisar esta documentación
2. Verificar logs del servidor
3. Consultar documentación de Twilio: [https://www.twilio.com/docs/whatsapp](https://www.twilio.com/docs/whatsapp)

---

**¡Importante!** Recuerda configurar correctamente las credenciales y activar el envío antes de usar en producción. 