# Script de PowerShell para ejecutar la corrección de stored procedures
# Ejecutar este script desde PowerShell

Write-Host "=== CORRECCIÓN DE STORED PROCEDURES ===" -ForegroundColor Green
Write-Host ""

# Verificar si MySQL está disponible
try {
    $mysqlVersion = (mysql --version 2>$null)
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ MySQL encontrado" -ForegroundColor Green
    } else {
        Write-Host "✗ MySQL no encontrado en PATH" -ForegroundColor Red
        Write-Host "Por favor, asegúrate de que MySQL esté instalado y en el PATH" -ForegroundColor Yellow
        exit 1
    }
} catch {
    Write-Host "✗ Error al verificar MySQL: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Solicitar credenciales de base de datos
Write-Host ""
Write-Host "Ingresa las credenciales de tu base de datos:" -ForegroundColor Yellow
$host = Read-Host "Host (default: localhost)"
if ([string]::IsNullOrEmpty($host)) { $host = "localhost" }

$user = Read-Host "Usuario (default: root)"
if ([string]::IsNullOrEmpty($user)) { $user = "root" }

$database = Read-Host "Base de datos (default: credicrece)"
if ([string]::IsNullOrEmpty($database)) { $database = "credicrece" }

$password = Read-Host "Contraseña" -AsSecureString
$passwordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))

Write-Host ""
Write-Host "Ejecutando corrección de stored procedures..." -ForegroundColor Cyan

# Ejecutar el script SQL
try {
    $sqlFile = "ejecutar_fix_procedures.sql"
    if (Test-Path $sqlFile) {
        $command = "mysql -h {0} -u {1} -p{2} {3} < {4}" -f $host, $user, $passwordPlain, $database, $sqlFile
        Invoke-Expression $command
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host ""
            Write-Host "✓ Stored procedures corregidos exitosamente!" -ForegroundColor Green
            Write-Host "Ahora puedes probar aprobar un préstamo." -ForegroundColor Cyan
        } else {
            Write-Host ""
            Write-Host "✗ Error al ejecutar el script SQL" -ForegroundColor Red
            Write-Host "Verifica las credenciales y que la base de datos exista." -ForegroundColor Yellow
        }
    } else {
        Write-Host "✗ No se encontró el archivo {0}" -f $sqlFile -ForegroundColor Red
    }
} catch {
    Write-Host "✗ Error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "Presiona cualquier tecla para continuar..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown") 