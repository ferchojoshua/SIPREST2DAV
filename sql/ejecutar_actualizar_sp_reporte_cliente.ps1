# Script de PowerShell para actualizar el Stored Procedure SP_REPORTE_POR_CLIENTE
# Agrega campos: nombre_cliente, fecha_apertura, fecha_vencimiento, moneda_simbolo

param(
    [string]$Server = "localhost",
    [string]$Database = "credicrece", 
    [string]$Username = "root",
    [string]$Password = ""
)

Write-Host "============================================" -ForegroundColor Yellow
Write-Host "ACTUALIZACIÓN SP_REPORTE_POR_CLIENTE" -ForegroundColor Yellow  
Write-Host "============================================" -ForegroundColor Yellow
Write-Host ""

Write-Host "Parámetros de conexión:" -ForegroundColor Cyan
Write-Host "- Servidor: $Server" -ForegroundColor White
Write-Host "- Base de datos: $Database" -ForegroundColor White
Write-Host "- Usuario: $Username" -ForegroundColor White
Write-Host ""

$ScriptPath = Join-Path $PSScriptRoot "actualizar_sp_reporte_cliente.sql"

if (-not (Test-Path $ScriptPath)) {
    Write-Host "❌ ERROR: No se encontró el archivo actualizar_sp_reporte_cliente.sql" -ForegroundColor Red
    Write-Host "   Ruta esperada: $ScriptPath" -ForegroundColor Red
    exit 1
}

Write-Host "📁 Archivo SQL encontrado: $ScriptPath" -ForegroundColor Green
Write-Host ""

try {
    Write-Host "🔄 Ejecutando actualización del Stored Procedure..." -ForegroundColor Yellow
    
    # Construir comando mysql
    $MysqlArgs = @(
        "--host=$Server"
        "--user=$Username"
        "--database=$Database"
        "--execute=SOURCE $ScriptPath"
    )
    
    if ($Password) {
        $MysqlArgs += "--password=$Password"
    }
    
    # Ejecutar comando
    $Output = & mysql $MysqlArgs 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "✅ ¡Stored Procedure actualizado exitosamente!" -ForegroundColor Green
        Write-Host ""
        Write-Host "📋 Nuevos campos agregados:" -ForegroundColor Cyan
        Write-Host "   • cliente_nombres - Nombre del cliente" -ForegroundColor White
        Write-Host "   • fecha_apertura - Fecha de apertura del préstamo" -ForegroundColor White  
        Write-Host "   • fecha_vencimiento - Fecha calculada de vencimiento" -ForegroundColor White
        Write-Host "   • moneda_simbolo - Símbolo de la moneda del préstamo" -ForegroundColor White
        Write-Host ""
        Write-Host "🎯 El reporte ahora incluye información completa del préstamo" -ForegroundColor Green
        
        if ($Output) {
            Write-Host ""
            Write-Host "📄 Salida de MySQL:" -ForegroundColor Cyan
            Write-Host $Output -ForegroundColor White
        }
    } else {
        Write-Host ""
        Write-Host "❌ ERROR al ejecutar la actualización" -ForegroundColor Red
        Write-Host "Salida de MySQL:" -ForegroundColor Red
        Write-Host $Output -ForegroundColor Red
        exit 1
    }
    
} catch {
    Write-Host ""
    Write-Host "❌ ERROR: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Yellow
Write-Host "ACTUALIZACIÓN COMPLETADA" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Yellow 