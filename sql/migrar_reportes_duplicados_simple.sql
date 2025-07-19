-- =====================================================
-- MIGRACIÓN SIMPLE: ELIMINAR REPORTES DUPLICADOS
-- =====================================================
-- El sistema ya tiene lógica para super admin
-- Solo eliminar duplicados y consolidar menú

-- 1. ACTUALIZAR MENU PARA USAR SOLO REPORTES FINANCIEROS
UPDATE modulos 
SET vista = 'reportes_financieros' 
WHERE modulo LIKE '%reporte%' 
  AND vista IN ('reporte_cliente', 'reportes', 'reporte_mora', 'reporte_cobranza');

-- 2. ELIMINAR REPORTES INDIVIDUALES DUPLICADOS DEL MENU  
DELETE FROM modulos 
WHERE vista IN (
    'reporte_cliente',
    'reportes', 
    'reporte_mora',
    'reporte_cobranza',
    'reporte_cuotas_pagadas',
    'reporte_cuotas_atrasadas'
) AND modulo != 'Reportes Financieros';

-- 3. VERIFICAR QUE EXISTE ENTRADA PARA REPORTES FINANCIEROS
-- Si no existe, crear entrada principal
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reportes Financieros', 10, 'reportes_financieros', 'far fa-circle', 19);

-- 4. VERIFICAR RESULTADOS
SELECT 'Consulta para verificar módulos de reportes:' as info;
SELECT id, modulo, padre_id, vista, icon_menu, orden 
FROM modulos 
WHERE modulo LIKE '%reporte%' OR vista LIKE '%reporte%'
ORDER BY orden;

-- =====================================================
-- ARCHIVOS YA ELIMINADOS:
-- =====================================================
-- ✅ vistas/reporte_cliente.php  -> ELIMINADO
-- ✅ vistas/reportes.php         -> ELIMINADO
-- ✅ vistas/reporte_mora.php     -> ELIMINADO  
-- ✅ vistas/reporte_cobranza.php -> ELIMINADO
-- ✅ vistas/reporte_cuotas_pagadas.php -> ELIMINADO
-- ✅ vistas/reporte_cuotas_atrasadas.php -> ELIMINADO

-- CONSERVADOS:
-- ✅ vistas/reportes_financieros.php -> PRINCIPAL (ya tiene lógica admin)
-- ✅ vistas/estado_cuenta_cliente.php -> Específico, no duplicado
-- ✅ vistas/reporte_recuperacion.php -> Específico, no duplicado  
-- ✅ vistas/reporte_diario.php -> Procedimiento específico
-- ✅ vistas/reporte_saldos_arrastrados.php -> Específico

SELECT 'Migración completada. Archivos duplicados ya eliminados.' as resultado; 