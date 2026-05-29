# Configuración de Producción

## Variables de Entorno (.env)

1. Copia `.env.example` a `.env`:
```bash
cp .env.example .env
```

2. Edita `.env` con tus credenciales reales:
```
SMTP_HOST=mail.jmdevmente.com
SMTP_USERNAME=tu_email@jmdevmente.com
SMTP_PASSWORD=tu_contraseña_aquí
SMTP_PORT=465
SMTP_FROM=tu_email@jmdevmente.com
SMTP_FROM_NAME=Tu Nombre
SMTP_ADDRESS=destino@gmail.com
```

3. **IMPORTANTE**: Asegúrate de que `.env` está en `.gitignore` para que NO se suba al repositorio.

## Cambios de Seguridad Implementados

✅ **Credenciales en variables de entorno** - No expuestas en el código
✅ **Debug desactivado en producción** - Solo activo en desarrollo
✅ **Verificación SSL habilitada** - Conexión segura con el servidor SMTP
✅ **Validación mejorada** - Email, teléfono y mensaje con formato correcto
✅ **Solo POST permitido** - Previene acceso por GET
✅ **Códigos HTTP correctos** - Respuestas apropiadas para cada caso
✅ **HTML alternativo** - Para clientes de email que no soportan HTML

## Configuración en el Servidor

Para cambiar entre desarrollo y producción, edita `config/config.php`:
```php
define('PRODUCTION', true);  // Cambiar a false para desarrollo
```

## Prueba el Formulario

El formulario ahora tiene validaciones más estrictas:
- Email válido requerido
- Teléfono con 7-20 caracteres
- Mensaje de mínimo 10 caracteres
