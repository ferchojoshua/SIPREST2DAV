# ======================================================
# SCRIPT PARA CORREGIR EL TRIGGER QUE CAUSA ERROR c.nombres
# ======================================================
# Este script ejecuta la corrección del trigger automáticamente
# ======================================================

Write-Host "🔧 CORRIGIENDO TRIGGER QUE CAUSA ERROR 'c.nombres'" -ForegroundColor Yellow
Write-Host "=" * 60 -ForegroundColor Gray

# Configuración de la base de datos
$server = "localhost"
$database = "credicrece"
$username = "root"
$password = ""
$sqlFile = "sql/corregir_trigger_whatsapp.sql"

try {
    # Verificar que existe el archivo SQL
    if (-not (Test-Path $sqlFile)) {
        throw "❌ No se encontró el archivo: $sqlFile"
    }

    Write-Host "📂 Archivo SQL encontrado: $sqlFile" -ForegroundColor Green
    
    # Ejecutar el script SQL usando mysql
    Write-Host "🔄 Ejecutando corrección del trigger..." -ForegroundColor Cyan
    
    $mysqlArgs = @(
        "--host=$server"
        "--user=$username"
        "--database=$database"
        "--execute=SOURCE $sqlFile"
    )
    
    if ($password) {
        $mysqlArgs += "--password=$password"
    }
    
    # Ejecutar el comando
    $result = & mysql $mysqlArgs 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ TRIGGER CORREGIDO EXITOSAMENTE" -ForegroundColor Green
        Write-Host ""
        Write-Host "🎯 CORRECCIONES APLICADAS:" -ForegroundColor Yellow
        Write-Host "  • c.nombres → c.cliente_nombres" -ForegroundColor White
        Write-Host "  • c.id → c.cliente_id" -ForegroundColor White
        Write-Host ""
        Write-Host "🔍 El error 'Unknown column c.nombres' debería estar resuelto" -ForegroundColor Green
        Write-Host ""
        Write-Host "📋 SIGUIENTE PASO: Probar la aprobación de préstamos" -ForegroundColor Cyan
    } else {
        throw "❌ Error al ejecutar el script SQL: $result"
    }
    
} catch {
    Write-Host "❌ ERROR: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host ""
    Write-Host "🔧 SOLUCIÓN MANUAL:" -ForegroundColor Yellow
    Write-Host "1. Abrir phpMyAdmin" -ForegroundColor White
    Write-Host "2. Seleccionar base de datos 'credicrece'" -ForegroundColor White
    Write-Host "3. Ejecutar el contenido del archivo: $sqlFile" -ForegroundColor White
    exit 1
}

Write-Host ""
Write-Host "=" * 60 -ForegroundColor Gray
Write-Host "🎉 PROCESO COMPLETADO" -ForegroundColor Green 