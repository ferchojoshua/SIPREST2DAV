-- =================================================================
-- PASO 1: IDENTIFICAR LOS IDs IMPORTANTES
-- (Estos SELECT no modifican nada, solo nos ayudan a verificar)
-- =================================================================
-- Obtener el ID del perfil de Administrador (asumimos que es 1, pero verificamos)
SELECT id_perfil FROM perfiles WHERE descripcion = 'Administrador';

-- Obtener el ID del módulo "Dashboards" que debe ser el padre
SELECT id, modulo, padre_id FROM modulos WHERE modulo = 'Dashboards';

-- Obtener los IDs de los módulos que se están duplicando o causando problemas
SELECT id, modulo, padre_id FROM modulos WHERE modulo IN ('Dashboard Principal', 'Dashboard Ejecutivo', 'Dashboard de Cobradores');


-- =================================================================
-- PASO 2: LIMPIAR ASIGNACIONES INCORRECTAS EN PERFIL_MODULO
-- Vamos a eliminar las asignaciones de los módulos de dashboard para el perfil de Admin
-- para luego reinsertar solo la correcta.
-- =================================================================
-- !CUIDADO! Ejecutar con precaución.
-- Primero, identificamos los IDs de TODOS los módulos de dashboard.
SET @id_dashboard_padre = (SELECT id FROM modulos WHERE modulo = 'Dashboards' LIMIT 1);
SET @id_dashboard_principal = (SELECT id FROM modulos WHERE modulo = 'Dashboard Principal' LIMIT 1);
SET @id_dashboard_ejecutivo = (SELECT id FROM modulos WHERE modulo = 'Dashboard Ejecutivo' LIMIT 1);
SET @id_dashboard_cobradores = (SELECT id FROM modulos WHERE modulo = 'Dashboard de Cobradores' LIMIT 1);
SET @id_perfil_admin = (SELECT id_perfil FROM perfiles WHERE descripcion = 'Administrador' LIMIT 1);

-- Eliminamos TODAS las asignaciones de estos módulos para el Administrador
DELETE FROM perfil_modulo 
WHERE 
    id_perfil = @id_perfil_admin 
    AND id_modulo IN (
        @id_dashboard_padre, 
        @id_dashboard_principal, 
        @id_dashboard_ejecutivo, 
        @id_dashboard_cobradores
    );

-- =================================================================
-- PASO 3: CORREGIR LA JERARQUÍA EN LA TABLA MODULOS
-- Nos aseguramos que los dashboards hijos apunten al padre correcto.
-- =================================================================
-- Unificamos 'Dashboard Ejecutivo' a 'Dashboard Principal' y lo hacemos hijo de 'Dashboards'
UPDATE modulos 
SET 
    modulo = 'Dashboard Principal', -- Renombramos por consistencia
    padre_id = @id_dashboard_padre
WHERE id = @id_dashboard_principal OR id = @id_dashboard_ejecutivo;

-- Hacemos a 'Dashboard de Cobradores' hijo de 'Dashboards'
UPDATE modulos
SET
    padre_id = @id_dashboard_padre
WHERE id = @id_dashboard_cobradores;

-- Nos aseguramos que el módulo 'Dashboards' NO tenga padre.
UPDATE modulos
SET
    padre_id = NULL
WHERE id = @id_dashboard_padre;


-- =================================================================
-- PASO 4: REINSERTAR LA ASIGNACIÓN CORRECTA
-- Asignamos únicamente el módulo padre "Dashboards" al perfil de Administrador.
-- El código de la aplicación se encarga de mostrar los hijos.
-- =================================================================
INSERT INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) 
VALUES (@id_perfil_admin, @id_dashboard_padre, 1, 1);

-- =================================================================
-- PASO 5: VERIFICACIÓN FINAL
-- =================================================================
SELECT 
    p.descripcion as perfil,
    m.id as id_modulo,
    m.modulo,
    m.padre_id
FROM perfil_modulo pm
JOIN perfiles p ON pm.id_perfil = p.id_perfil
JOIN modulos m ON pm.id_modulo = m.id
WHERE 
    p.id_perfil = @id_perfil_admin
    AND m.id IN (
        @id_dashboard_padre, 
        @id_dashboard_principal,
        @id_dashboard_cobradores
    ); 