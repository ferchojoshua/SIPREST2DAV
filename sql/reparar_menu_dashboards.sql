-- =================================================================================
-- SCRIPT PARA REPARAR LA JERARQUÍA Y ASIGNACIÓN DEL MENÚ DE DASHBOARDS
-- =================================================================================
-- Este script corrige la estructura de los módulos de Dashboard y asegura que
-- el perfil de 'Administrador' tenga acceso correcto a ellos.
-- =================================================================================

-- PASO 1: Declarar variables para los IDs de los módulos y el perfil.
-- Esto hace el script más legible y seguro.
SET @id_perfil_admin = (SELECT id_perfil FROM perfiles WHERE descripcion = 'Administrador' LIMIT 1);
SET @id_dash_padre = (SELECT id FROM modulos WHERE modulo = 'Dashboards' LIMIT 1);
SET @id_dash_principal = (SELECT id FROM modulos WHERE modulo = 'Dashboard Principal' LIMIT 1);
SET @id_dash_cobradores = (SELECT id FROM modulos WHERE modulo = 'Dashboard de Cobradores' LIMIT 1);

-- PASO 2: Corregir la jerarquía en la tabla 'modulos'.
-- Nos aseguramos de que los dashboards específicos sean hijos del módulo 'Dashboards'.
UPDATE modulos SET padre_id = @id_dash_padre WHERE id = @id_dash_principal;
UPDATE modulos SET padre_id = @id_dash_padre WHERE id = @id_dash_cobradores;
-- Nos aseguramos de que el módulo padre no tenga padre.
UPDATE modulos SET padre_id = NULL WHERE id = @id_dash_padre;


-- PASO 3: Limpiar asignaciones incorrectas o faltantes para el Administrador.
-- Eliminamos CUALQUIER asignación directa a los dashboards para el perfil de Admin.
DELETE FROM perfil_modulo
WHERE
    id_perfil = @id_perfil_admin
    AND id_modulo IN (@id_dash_padre, @id_dash_principal, @id_dash_cobradores);


-- PASO 4: Re-insertar la asignación correcta.
-- Asignamos ÚNICAMENTE el módulo padre 'Dashboards' al perfil de Administrador.
-- El sistema se encargará de mostrar los hijos automáticamente.
INSERT INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES (@id_perfil_admin, @id_dash_padre, 1, 1);


-- PASO 5: Verificación final.
-- Esta consulta debe devolver una sola fila: la asignación del módulo 'Dashboards'
-- al perfil 'Administrador'.
SELECT
    p.descripcion as perfil,
    m.modulo,
    m.padre_id
FROM perfil_modulo pm
JOIN perfiles p ON pm.id_perfil = p.id_perfil
JOIN modulos m ON pm.id_modulo = m.id
WHERE
    p.id_perfil = @id_perfil_admin
    AND m.id = @id_dash_padre;

-- ¡Listo! El menú de Dashboards debería estar reparado. 