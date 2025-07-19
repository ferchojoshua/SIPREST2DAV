-- =================================================================================
-- SCRIPT PARA ELIMINAR DUPLICADOS EN 'perfil_modulo' (Versión 2 - Sin Columna ID)
-- =================================================================================
-- Este script soluciona el problema de duplicados en tablas que NO tienen una
-- clave primaria única (como una columna 'id' autoincremental).
--
-- !IMPORTANTE!: Realice una copia de seguridad de su base de datos o al menos de la
-- tabla 'perfil_modulo' antes de ejecutar este script.
-- CREATE TABLE perfil_modulo_backup_v2 AS SELECT * FROM perfil_modulo;
-- =================================================================================

-- PASO 1: Crear una tabla temporal con las filas únicas
-- Se utiliza GROUP BY para asegurar que solo haya una fila por cada combinación
-- de perfil y módulo. Usamos MAX() en las otras columnas para tomar un valor
-- consistente de los duplicados, asumiendo que deberían ser iguales.
CREATE TABLE perfil_modulo_temp AS
SELECT
    id_perfil,
    id_modulo,
    MAX(vista_inicio) as vista_inicio,
    MAX(estado) as estado
FROM
    perfil_modulo
GROUP BY
    id_perfil,
    id_modulo;

-- PASO 2: Vaciar la tabla original
-- Esto elimina TODAS las filas de la tabla, incluyendo los duplicados.
TRUNCATE TABLE perfil_modulo;

-- Si su sistema de base de datos no permite TRUNCATE o da problemas de FK,
-- puede usar DELETE en su lugar.
-- DELETE FROM perfil_modulo;


-- PASO 3: Re-insertar los datos únicos desde la tabla temporal
-- Se insertan las filas limpias que guardamos en el paso 1.
INSERT INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT
    id_perfil,
    id_modulo,
    vista_inicio,
    estado
FROM
    perfil_modulo_temp;


-- PASO 4: Eliminar la tabla temporal
-- Ya no la necesitamos, así que la eliminamos para mantener la base de datos limpia.
DROP TABLE perfil_modulo_temp;


-- PASO 5: Verificación final
-- Esta consulta no debería devolver ningún resultado, lo que confirma que el problema
-- de duplicados ha sido resuelto.
SELECT
    id_perfil,
    id_modulo,
    COUNT(*) as cantidad
FROM
    perfil_modulo
GROUP BY
    id_perfil,
    id_modulo
HAVING
    COUNT(*) > 1;

-- Si todo salió bien, el menú de la aplicación ya no debería tener duplicados. 