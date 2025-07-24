# ======================================================
# SCRIPT SEGURO PARA CORREGIR EL TRIGGER
# ======================================================
# Prueba diferentes versiones hasta encontrar una que funcione
# ======================================================

Write-Host "🔧 CORRIGIENDO TRIGGER CON MÚLTIPLES ESTRATEGIAS" -ForegroundColor Yellow
Write-Host "=" * 60 -ForegroundColor Gray

# Configuración
$server = "localhost"
$database = "credicrece"
$username = "root"
$password = ""

# Lista de scripts a probar (del más completo al más simple)
$scripts = @(
    @{ file = "sql/corregir_trigger_whatsapp.sql"; name = "Versión completa con CONVERT" },
    @{ file = "sql/corregir_trigger_whatsapp_simple.sql"; name = "Versión simplificada sin emojis" },
    @{ file = "sql/corregir_trigger_definitivo.sql"; name = "Versión definitiva con CAST" }
)

$success = $false

foreach ($script in $scripts) {
    Write-Host ""
    Write-Host "🔄 Probando: $($script.name)" -ForegroundColor Cyan
    
    try {
        # Verificar que existe el archivo
        if (-not (Test-Path $script.file)) {
            Write-Host "⚠️  Archivo no encontrado: $($script.file)" -ForegroundColor Yellow
            continue
        }

        # Ejecutar el script
        $mysqlArgs = @(
            "--host=$server"
            "--user=$username"
            "--database=$database"
            "--execute=SOURCE $($script.file)"
        )
        
        if ($password) {
            $mysqlArgs += "--password=$password"
        }
        
        $result = & mysql $mysqlArgs 2>&1
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ ÉXITO: $($script.name)" -ForegroundColor Green
            $success = $true
            break
        } else {
            Write-Host "❌ Falló: $result" -ForegroundColor Red
        }
        
    } catch {
        Write-Host "❌ Error: $($_.Exception.Message)" -ForegroundColor Red
    }
}

if ($success) {
    Write-Host ""
    Write-Host "🎉 TRIGGER CORREGIDO EXITOSAMENTE" -ForegroundColor Green
    Write-Host ""
    Write-Host "🎯 PROBLEMA RESUELTO:" -ForegroundColor Yellow
    Write-Host "  • Error 'c.nombres' eliminado" -ForegroundColor White
    Write-Host "  • Problemas de collation solucionados" -ForegroundColor White
    Write-Host "  • Trigger funcional creado" -ForegroundColor White
    Write-Host ""
    Write-Host "📋 PRUEBA AHORA:" -ForegroundColor Cyan
    Write-Host "  1. Ve a la pantalla de aprobación" -ForegroundColor White
    Write-Host "  2. Intenta aprobar un préstamo" -ForegroundColor White
    Write-Host "  3. El error debería haber desaparecido" -ForegroundColor White
} else {
    Write-Host ""
    Write-Host "❌ TODAS LAS OPCIONES FALLARON" -ForegroundColor Red
    Write-Host ""
    Write-Host "🔧 OPCIÓN MANUAL - Ejecuta esto en phpMyAdmin:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "DROP TRIGGER IF EXISTS trg_prestamo_aprobado_whatsapp;" -ForegroundColor White
    Write-Host ""
    Write-Host "Esto eliminará el trigger problemático completamente." -ForegroundColor Gray
}

Write-Host ""
Write-Host "=" * 60 -ForegroundColor Gray
Write-Host "🎯 PROCESO COMPLETADO" -ForegroundColor Green 