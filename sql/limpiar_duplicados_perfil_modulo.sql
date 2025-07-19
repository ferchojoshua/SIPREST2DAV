-- =================================================================================
-- SCRIPT PARA ELIMINAR DUPLICADOS EN LA TABLA perfil_modulo
-- =================================================================================
-- Este script elimina las filas duplicadas basadas en la combinación de
-- id_perfil y id_modulo, conservando la fila con el id (PK) más bajo.

-- !IMPORTANTE!: Realice una copia de seguridad de su tabla 'perfil_modulo' o de la
-- base de datos completa antes de ejecutar este script.
-- Ejemplo para crear backup de la tabla:
-- CREATE TABLE perfil_modulo_backup AS SELECT * FROM perfil_modulo;
-- =================================================================================

-- PASO 1: Identificar los duplicados (Consulta de Diagnóstico)
-- Esta consulta no borra nada. Solo muestra los grupos de perfiles y módulos
-- que tienen más de una entrada, que son los que causan el problema.
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


-- PASO 2: Eliminar los duplicados
-- Esta es la consulta que realiza la limpieza.
-- Utiliza un SELF JOIN para encontrar las filas donde id_perfil y id_modulo son
-- iguales, pero una tiene un 'id' (Primary Key) mayor que la otra.
-- Luego, elimina la fila con el 'id' más alto, dejando solo la original.
DELETE t1
FROM
    perfil_modulo t1
INNER JOIN
    perfil_modulo t2
WHERE
    t1.id > t2.id AND
    t1.id_perfil = t2.id_perfil AND
    t1.id_modulo = t2.id_modulo;


-- PASO 3: Verificación final
-- Después de ejecutar la eliminación, esta consulta no debería devolver ningún resultado,
-- confirmando que no quedan duplicados.
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

-- Si esta consulta final devuelve filas, por favor, contacte a soporte.
-- Si no devuelve nada, el problema de menú duplicado está resuelto. 