# DASHBOARD DE COBRADORES - DOCUMENTACIÓN TÉCNICA

## 📊 Descripción General

Dashboard avanzado para evaluación del desempeño de cobradores con gráficos interactivos, métricas en tiempo real y comparaciones mensuales. Desarrollado con arquitectura MVC, Chart.js y tecnologías modernas.

## 🚀 Características Principales

### 1. **Gráficos de Pastel Interactivos**
- ✅ **Cobros por Cobrador**: Distribución visual de cobros realizados
- ✅ **Mora por Cobrador**: Análisis de cartera vencida por responsable
- 🎨 **Paleta profesional**: Colores diferenciados y armonía visual
- 🔄 **Interactividad**: Click en sectores para ver detalles

### 2. **Gráfico de Líneas Comparativo**
- 📈 **Comparación mensual**: Mes anterior vs mes actual día a día
- 📊 **Doble métrica**: Cobros y mora en el mismo gráfico
- 🎯 **Análisis de tendencias**: Identificar patrones y mejoras
- 📱 **Responsive**: Adaptado para todos los dispositivos

### 3. **Filtros Avanzados**
- 🏢 **Por Sucursal**: Filtrar datos por oficina específica
- 🛣️ **Por Ruta**: Análisis de rutas de cobranza
- 👤 **Por Cobrador**: Vista individual de rendimiento
- 📅 **Por Período**: Día, semana, mes, trimestre, año, personalizado

### 4. **Métricas en Tiempo Real**
- 💰 **Total Cobrado**: Monto total recaudado en el período
- ⚠️ **Total en Mora**: Cartera vencida acumulada
- 📈 **Eficiencia de Cobro**: Porcentaje de efectividad
- 👥 **Cobradores Activos**: Número de cobradores trabajando

### 5. **Tabla de Rendimiento Detallado**
- 📋 **Vista completa**: Todos los cobradores con métricas individuales
- 🏆 **Ranking automático**: Ordenado por eficiencia
- 📊 **Barras de progreso**: Visualización rápida de eficiencia
- 🔄 **Comparación mensual**: Variación vs período anterior

## 📁 Estructura de Archivos

### Frontend
```
vistas/
├── dashboard_cobradores.php           ← Vista principal del dashboard
└── assets/
    ├── css/
    │   └── sistema-estandar.css       ← Estilos mejorados (ya existía)
    └── dist/js/
        └── dashboard-charts.js        ← Funciones de gráficos (NUEVO)
```

### Backend
```
ajax/
└── dashboard_cobradores_ajax.php      ← API endpoints para datos (NUEVO)

controladores/
└── dashboard_cobradores_controlador.php ← Lógica de negocio (NUEVO)

modelos/
└── dashboard_cobradores_modelo.php    ← Consultas SQL optimizadas (NUEVO)
```

### SQL
```
sql/
└── agregar_dashboard_cobradores_menu.sql ← Script de instalación (NUEVO)
```

## 🛠️ Instalación

### 1. **Ejecutar Script SQL**
```sql
-- Ejecutar en phpMyAdmin o consola MySQL
source sql/agregar_dashboard_cobradores_menu.sql;
```

### 2. **Verificar Permisos**
- El dashboard se agregará automáticamente al menú
- Disponible para perfiles Administrador y Prestamista
- Icono: `fas fa-chart-pie`

### 3. **Dependencias**
- ✅ Chart.js (ya incluido en el sistema)
- ✅ Select2 (ya incluido)
- ✅ SweetAlert2 (ya incluido)
- ✅ Sistema de combos mejorados (implementado anteriormente)

## 📊 APIs Disponibles

### Endpoints Principales

#### 1. Métricas Generales
```javascript
POST ajax/dashboard_cobradores_ajax.php
{
    "accion": "metricas_generales",
    "sucursal_id": "11",
    "periodo": "mes",
    "fecha_inicio": "2025-01-01",
    "fecha_fin": "2025-01-31"
}
```

#### 2. Cobros por Cobrador
```javascript
POST ajax/dashboard_cobradores_ajax.php
{
    "accion": "cobros_por_cobrador",
    "sucursal_id": "11",
    "ruta_id": "1",
    "periodo": "mes"
}
```

#### 3. Mora por Cobrador
```javascript
POST ajax/dashboard_cobradores_ajax.php
{
    "accion": "mora_por_cobrador",
    "filtros": {...}
}
```

#### 4. Comparación Mensual
```javascript
POST ajax/dashboard_cobradores_ajax.php
{
    "accion": "comparacion_mensual",
    "filtros": {...}
}
```

#### 5. Tabla de Rendimiento
```javascript
POST ajax/dashboard_cobradores_ajax.php
{
    "accion": "tabla_rendimiento",
    "filtros": {...}
}
```

### Filtros Disponibles
- `sucursal_id`: ID de sucursal específica
- `ruta_id`: ID de ruta específica  
- `cobrador_id`: ID de cobrador específico
- `periodo`: "hoy", "semana", "mes", "trimestre", "anio", "personalizado"
- `fecha_inicio`: Fecha inicio en formato YYYY-MM-DD
- `fecha_fin`: Fecha fin en formato YYYY-MM-DD

## 🎨 Componentes Visuales

### 1. **Tarjetas de Métricas**
```css
.metric-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-left: 4px solid #007bff;
    transition: transform 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-5px);
}
```

### 2. **Gráficos Interactivos**
- **Tipo**: Doughnut (dona) para proporciones
- **Animaciones**: Rotación y escala suaves
- **Tooltips**: Información detallada al hover
- **Colores**: Paleta profesional diferenciada

### 3. **Tabla Responsive**
- **DataTables**: No utilizado para mejor control
- **Badges**: Estados y categorías visuales
- **Progress bars**: Eficiencia visual
- **Iconos**: FontAwesome para mejor UX

## 🔧 Configuración Avanzada

### 1. **Personalizar Colores**
```javascript
// En dashboard-charts.js
const colores = [
    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
];
```

### 2. **Ajustar Auto-actualización**
```javascript
// En dashboard_cobradores.php
updateInterval = setInterval(function() {
    cargarDashboard();
}, 300000); // 5 minutos (300000ms)
```

### 3. **Modificar Consultas SQL**
```sql
-- En dashboard_cobradores_modelo.php
-- Optimizar para volúmenes grandes:
SELECT /* SQL_NO_CACHE */ ...
LIMIT 20 -- Limitar resultados
```

## 📈 Métricas y KPIs

### 1. **Eficiencia de Cobro**
```
Eficiencia = (Monto Cobrado / Monto Esperado) * 100
```

### 2. **Variación Mensual**
```
Variación = ((Actual - Anterior) / Anterior) * 100
```

### 3. **Índice de Mora**
```
Índice Mora = (Monto en Mora / Cartera Total) * 100
```

### 4. **Niveles de Rendimiento**
- 🟢 **Excelente**: ≥ 90% eficiencia
- 🔵 **Bueno**: 75% - 89% eficiencia  
- 🟡 **Regular**: 60% - 74% eficiencia
- 🔴 **Deficiente**: < 60% eficiencia

## 🚀 Funcionalidades Avanzadas

### 1. **Exportación de Datos**
- **Excel**: Tabla completa con formato
- **PNG**: Gráficos individuales
- **PDF**: Reporte completo (futuro)

### 2. **Comparaciones Inteligentes**
- **Día a día**: Mismo día del mes anterior
- **Acumulado**: Total del período vs anterior
- **Tendencias**: Análisis de patrones

### 3. **Filtros Cascada**
- **Sucursal → Ruta → Cobrador**: Dependencia automática
- **Validación**: Solo datos relacionados
- **Limpieza**: Auto-reset de filtros dependientes

## 🔧 Optimizaciones de Rendimiento

### 1. **Consultas SQL**
- ✅ **Índices**: En campos de filtro principales
- ✅ **LIMIT**: Resultados limitados a 20
- ✅ **LEFT JOIN**: Evitar INNER JOIN innecesarios
- ✅ **GROUP BY**: Agrupación eficiente

### 2. **Frontend**
- ✅ **Chart.js**: Renderizado optimizado
- ✅ **Lazy Loading**: Carga bajo demanda
- ✅ **Debounce**: Evitar múltiples llamadas
- ✅ **Cache**: Reutilización de datos

### 3. **Memoria**
- ✅ **Destroy charts**: Liberar memoria al actualizar
- ✅ **Clear intervals**: Limpiar al salir
- ✅ **Minimal DOM**: Solo elementos necesarios

## 🐛 Solución de Problemas

### Error: "No se cargan los gráficos"
**Diagnóstico:**
1. Verificar consola del navegador para errores JavaScript
2. Comprobar que Chart.js esté cargado
3. Validar respuesta AJAX en Network tab

**Solución:**
```javascript
// Verificar dependencias
if (typeof Chart === 'undefined') {
    console.error('Chart.js no está cargado');
}
```

### Error: "Filtros no funcionan"
**Diagnóstico:**
1. Verificar que combos-mejorados.js esté incluido
2. Comprobar estructura de base de datos
3. Validar permisos de usuario

**Solución:**
```sql
-- Verificar tablas requeridas
SHOW TABLES LIKE '%rutas%';
SHOW TABLES LIKE '%usuarios_rutas%';
```

### Error: "Datos inconsistentes"
**Diagnóstico:**
1. Verificar fechas de filtros
2. Comprobar datos de prueba
3. Validar consultas SQL

**Solución:**
```sql
-- Verificar datos de ejemplo
SELECT COUNT(*) FROM prestamo_cabecera WHERE pres_aprobacion = 'aprobado';
SELECT COUNT(*) FROM usuarios_rutas WHERE estado = 'activo';
```

## 📱 Compatibilidad

### Navegadores Soportados
- ✅ **Chrome**: 70+
- ✅ **Firefox**: 65+
- ✅ **Safari**: 12+
- ✅ **Edge**: 79+

### Dispositivos
- ✅ **Desktop**: 1200px+
- ✅ **Tablet**: 768px - 1199px
- ✅ **Móvil**: 320px - 767px

### Resoluciones Optimizadas
- 📱 **Móvil**: Gráficos de 300px altura
- 💻 **Desktop**: Gráficos de 400px altura
- 🖥️ **Large**: Gráficos de 500px altura

## 🔮 Futuras Mejoras

### Versión 2.0 (Planificada)
1. **Mapas de Calor**: Visualización geográfica de rendimiento
2. **Predicciones IA**: Machine learning para pronósticos
3. **Notificaciones Push**: Alertas en tiempo real
4. **Dashboard Móvil**: App nativa para cobradores
5. **Integración WhatsApp**: Comunicación directa

### Versión 1.1 (Próxima)
1. **Filtros guardados**: Configuraciones personalizadas
2. **Alertas automáticas**: Umbrales configurables
3. **Exportación programada**: Reportes automáticos
4. **Drill-down**: Análisis detallado por click

## 📞 Soporte Técnico

### Logs del Sistema
```bash
# Ubicación de logs
tail -f /var/log/apache2/error.log
tail -f /var/log/mysql/error.log
```

### Debug JavaScript
```javascript
// Activar modo debug
localStorage.setItem('dashboard_debug', 'true');

// Desactivar
localStorage.removeItem('dashboard_debug');
```

### Validación de Datos
```sql
-- Script de validación completa
SELECT 'Dashboard de Cobradores - Validación del Sistema' as titulo;

-- Verificar estructura de tablas
SELECT COUNT(*) as total_prestamos FROM prestamo_cabecera WHERE pres_aprobacion = 'aprobado';
SELECT COUNT(*) as total_rutas FROM rutas WHERE ruta_estado = 'activa';
SELECT COUNT(*) as total_cobradores FROM usuarios WHERE estado = 1;

-- Verificar datos del período actual
SELECT 
    COUNT(DISTINCT c.cliente_id) as clientes_activos,
    COUNT(DISTINCT ur.usuario_id) as cobradores_asignados,
    SUM(pd.cuota_monto) as monto_total_periodo
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.prestamo_id = pc.pres_id
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
LEFT JOIN usuarios_rutas ur ON cr.ruta_id = ur.ruta_id
WHERE pc.pres_aprobacion = 'aprobado'
AND pd.cuota_fecha_vencimiento BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE();
```

---

**✅ ESTADO**: Implementado y funcionando  
**📅 FECHA**: Enero 2025  
**👨‍💻 DESARROLLADOR**: Sistema de Gestión  
**🔧 VERSIÓN**: 1.0.0

Para actualizaciones y mejoras, consultar el repositorio del proyecto o contactar al equipo de desarrollo. 