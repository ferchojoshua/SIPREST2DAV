-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 05:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbprestamo`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ACTUALIZAR_ESTADO_CLIENTE_PRESTAMO` (IN `ID` INT)   BEGIN
DECLARE CANTIDAD INT;
SET @CANTIDAD:=(SELECT COUNT(*) FROM prestamo_cabecera where cliente_id =  ID );

UPDATE clientes SET 
cliente_estado_prestamo = 'con prestamo' 
-- cliente_cant_prestamo = @CANTIDAD + 1
WHERE
	cliente_id = ID;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ALERTA_PRESTAMO_CAJA` ()   SELECT 

(select  ROUND(SUM(pres_monto),2) from prestamo_cabecera where pres_aprobacion in ('aprobado','pendiente') and pres_estado_caja = 'VIGENTE') as pres_monto,

(select caja_monto_inicial from caja WHERE caja_estado = 'VIGENTE')  AS monto_inicial_caja,
 
(select IFNULL(ROUND(SUM(movi_monto),2),0) from movimientos WHERE movi_tipo = 'INGRESO' AND  movi_caja = 'VIGENTE') as ingreso,


(select IFNULL(ROUND(SUM(movi_monto),2),0) from movimientos WHERE movi_tipo = 'EGRESO' AND  movi_caja = 'VIGENTE') as egreso,

(select  IFNULL(ROUND(SUM(pres_monto_interes),2),0) from prestamo_cabecera where pres_aprobacion in ('finalizado') and pres_estado_caja = 'VIGENTE') as interes$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ANULAR_PRESTAMO` (IN `N_PRESTAMO` VARCHAR(8))   BEGIN

DECLARE CLIENTE INT;
UPDATE prestamo_cabecera SET pres_aprobacion = 'anulado', pres_estado_caja = '', pres_estado = 'Anulado' where nro_prestamo = N_PRESTAMO;

UPDATE prestamo_detalle SET pdetalle_estado_cuota = 'Anulado', pdetalle_caja = '', pdetalle_aprobacion = 'anulado'  where nro_prestamo = N_PRESTAMO;

 SET CLIENTE = (select cliente_id from prestamo_cabecera where nro_prestamo = N_PRESTAMO);
 
 UPDATE clientes set
 cliente_estado_prestamo = 'DISPONIBLE'
 WHERE cliente_id = CLIENTE;


SELECT "ok";

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ASIGNAR_CLIENTE_RUTA` (IN `p_cliente_id` INT, IN `p_ruta_id` INT, IN `p_direccion_especifica` TEXT, IN `p_observaciones` TEXT, IN `p_usuario_id` INT)   BEGIN
    INSERT INTO clientes_rutas (
        cliente_id,
        ruta_id,
        direccion_especifica,
        observaciones,
        estado,
        usuario_asignacion
    ) VALUES (
        p_cliente_id,
        p_ruta_id,
        p_direccion_especifica,
        p_observaciones,
        'activo',
        p_usuario_id
    ) ON DUPLICATE KEY UPDATE
        direccion_especifica = p_direccion_especifica,
        observaciones = p_observaciones,
        estado = 'activo',
        usuario_asignacion = p_usuario_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ASIGNAR_COBRADOR_RUTA` (IN `p_usuario_id` INT, IN `p_ruta_id` INT, IN `p_tipo_asignacion` ENUM('responsable','apoyo'), IN `p_usuario_asignacion` INT)   BEGIN
    INSERT INTO usuarios_rutas (
        usuario_id,
        ruta_id,
        tipo_asignacion,
        estado,
        usuario_asignacion
    ) VALUES (
        p_usuario_id,
        p_ruta_id,
        p_tipo_asignacion,
        'activo',
        p_usuario_asignacion
    ) ON DUPLICATE KEY UPDATE
        tipo_asignacion = p_tipo_asignacion,
        estado = 'activo',
        usuario_asignacion = p_usuario_asignacion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CAMBIAR_ESTADO_CABECERA` (IN `prestamo` VARCHAR(8))   BEGIN
DECLARE ESTADO INT;
DECLARE CLIENTE INT;
SET @ESTADO:=(select count(*) from prestamo_detalle pd where pd.nro_prestamo = prestamo and pd.pdetalle_estado_cuota like '%pendiente%' );
SET @CLIENTE:=(select cliente_id from prestamo_cabecera where nro_prestamo = prestamo);

 IF  @ESTADO = 0 THEN 
        UPDATE prestamo_cabecera SET
	pres_aprobacion = 'finalizado',
	pres_estado = 'Finalizado'
	WHERE nro_prestamo = prestamo;
	
	UPDATE clientes SET
	cliente_estado_prestamo = 'DISPONIBLE'
	WHERE cliente_id = @CLIENTE;
	

	
	
SELECT 'ok';
ELSE
	SELECT 'error';
END IF;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CLIENTES_CON_PRESTAMOS` ()   BEGIN
SELECT
	-- pc.cliente_id,
	c.cliente_dni,
	c.cliente_nombres,
	count( pc.nro_prestamo ) AS cant,
	SUM( pc.pres_monto_total ) AS total 
FROM
	prestamo_cabecera pc
	INNER JOIN clientes c ON pc.cliente_id = c.cliente_id 
WHERE
	pc.pres_aprobacion IN ( 'aprobado', 'finalizado' ) 
GROUP BY
	pc.cliente_id 
ORDER BY
	SUM(
		ROUND( pc.pres_monto_total, 2 )) DESC 
		LIMIT 10;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CUOTAS_PAGADAS` (IN `prestamo` VARCHAR(8))   select IFNULL(count(pdetalle_estado_cuota),0) as pdetalle_estado_cuota ,
			(select 	IFNULL(count(pdetalle_estado_cuota),0) from prestamo_detalle where nro_prestamo = prestamo AND  pdetalle_estado_cuota = 'pendiente') as pendiente
			from prestamo_detalle 
where nro_prestamo = prestamo AND 
			pdetalle_estado_cuota = 'pagada'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CUOTAS_VENCIDAS` ()   BEGIN
    SELECT 
        c.cliente_nombres,
        COUNT(pd.pdetalle_id) AS cantidad_cuotas,
        SUM(pd.pdetalle_monto_cuota) AS monto_total
    FROM prestamo_detalle pd
    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    WHERE pd.pdetalle_estado_cuota = 'pendiente' AND DATE(pd.pdetalle_fecha) < CURDATE()
    GROUP BY c.cliente_id, c.cliente_nombres
    ORDER BY monto_total DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DATOS_DASHBOARD` ()   BEGIN
	DECLARE
		CAJA FLOAT;
	DECLARE
		CLIENTES FLOAT;
	DECLARE
		PRESTAMOSPEN FLOAT;
	DECLARE
		TOTALACOBRAR FLOAT;
		DECLARE
		CAJACAPI FLOAT;
		
	DECLARE PRODUCTOSINSTOCK INT;
	DECLARE INTERES FLOAT;
	DECLARE MONTO_PRESTADO FLOAT;
	DECLARE CUOTA_PAGADA FLOAT;
	DECLARE TOTALPRES INT;
	
		-- SET CAJA = (select ROUND(IFNULL(SUM(pres_monto_total),0),2) from prestamo_cabecera where pres_estado_caja = 'VIGENTE' AND pres_aprobacion in (  'finalizado', 'aprobado') );
		SET CAJACAPI = (select ROUND(MAX(caja_monto_inicial),2) from caja where caja_estado = 'VIGENTE'); -- 100
		SET INTERES = (select ROUND(IFNULL(SUM(pres_monto_interes),0),2) from prestamo_cabecera where pres_estado_caja = 'VIGENTE' AND pres_aprobacion in (  'finalizado', 'aprobado') ); -- 30
		SET MONTO_PRESTADO = (select ROUND(IFNULL(SUM(pres_monto),0),2) from prestamo_cabecera where pres_estado_caja = 'VIGENTE' AND pres_aprobacion in (  'finalizado', 'aprobado') );
		SET CUOTA_PAGADA = (select ROUND(IFNULL(SUM(pdetalle_monto_cuota),0),2)  from prestamo_detalle  where pdetalle_estado_cuota = 'pagada' and pdetalle_caja = 'VIGENTE' and pdetalle_aprobacion = 'aprobado' );
		SET CAJA = (CAJACAPI - MONTO_PRESTADO ) + CUOTA_PAGADA; -- 100
		SET CLIENTES = (select COUNT(*) from clientes c WHERE c.cliente_estatus ='1');
		SET PRESTAMOSPEN =(select COUNT(*)  from prestamo_cabecera pc where pc.pres_aprobacion in ('aprobado'));

		SET TOTALACOBRAR = (select sum(pdetalle_monto_cuota) from prestamo_detalle  where pdetalle_estado_cuota = 'pendiente' );
		
		SET TOTALPRES = (select caja_monto_inicial from caja WHERE caja_estado = 'VIGENTE');
			
		
	SELECT
	  IFNULL(ROUND(CAJA,2),0)	as caja ,
		IFNULL(CLIENTES,0) as clientes,
		IFNULL(PRESTAMOSPEN,0) as prestamospen,
	  IFNULL(ROUND(TOTALACOBRAR,2), 0) as totalacobrar;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DATOS_DASHBOARD_FILTRADO` (IN `p_sucursal_id` INT, IN `p_periodo` VARCHAR(50))   BEGIN
    DECLARE fecha_inicio DATE;
    DECLARE fecha_fin DATE;

    -- Calcular fechas según el período
    IF p_periodo = 'hoy' THEN
        SET fecha_inicio = CURDATE();
        SET fecha_fin = CURDATE();
    ELSEIF p_periodo = 'semana' THEN
        SET fecha_inicio = SUBDATE(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) DAY); -- Lunes de esta semana
        SET fecha_fin = ADDDATE(fecha_inicio, INTERVAL 6 DAY); -- Domingo de esta semana
    ELSEIF p_periodo = 'mes' THEN
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY); -- Primer día del mes actual
        SET fecha_fin = LAST_DAY(CURDATE()); -- Último día del mes actual
    ELSEIF p_periodo = 'trimestre' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL (QUARTER(CURDATE()) - 1) QUARTER;
        SET fecha_fin = LAST_DAY(MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) * 3 - 1 MONTH);
    ELSEIF p_periodo = 'año' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1);
        SET fecha_fin = MAKEDATE(YEAR(CURDATE()), 366); -- Fin de año (maneja bisiestos)
    ELSE
        -- Default a 'mes' si el período no es reconocido
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    END IF;

    SELECT
        ROUND(IFNULL((SELECT SUM(mc.movi_monto) FROM movimientos_caja mc
                      WHERE mc.movi_tipo = 'INGRESO'
                      AND (p_sucursal_id IS NULL OR mc.id_sucursal = p_sucursal_id)
                      AND DATE(mc.movi_fecha) BETWEEN fecha_inicio AND fecha_fin), 0) -
              IFNULL((SELECT SUM(mc.movi_monto) FROM movimientos_caja mc
                      WHERE mc.movi_tipo = 'EGRESO'
                      AND (p_sucursal_id IS NULL OR mc.id_sucursal = p_sucursal_id)
                      AND DATE(mc.movi_fecha) BETWEEN fecha_inicio AND fecha_fin), 0), 2) AS caja,
        IFNULL((SELECT COUNT(c.id_cliente) FROM clientes c
                WHERE (p_sucursal_id IS NULL OR c.id_sucursal = p_sucursal_id)
                AND DATE(c.cliente_fecha_registro) BETWEEN fecha_inicio AND fecha_fin), 0) AS clientes,
        IFNULL((SELECT COUNT(pc.pres_id) FROM prestamo_cabecera pc
                WHERE pc.pres_aprobacion = 'aprobado'
                AND (p_sucursal_id IS NULL OR pc.id_sucursal = p_sucursal_id)
                AND DATE(pc.pres_fecha_registro) BETWEEN fecha_inicio AND fecha_fin), 0) AS prestamos,
        ROUND(IFNULL((SELECT SUM(pd.pdetalle_saldo) FROM prestamo_detalle pd
                      INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                      WHERE pd.pdetalle_estado_cuota = 'PENDIENTE'
                      AND (p_sucursal_id IS NULL OR pc.id_sucursal = p_sucursal_id)
                      AND DATE(pd.pdetalle_fecha) BETWEEN fecha_inicio AND fecha_fin), 0), 2) AS total_cobrar;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_DESAPROBAR_PRESTAMO` (IN `N_PRESTAMO` VARCHAR(8))   BEGIN 

DECLARE CANTIDAD INT;
DECLARE CLIENTE INT;
SET CLIENTE=(select cliente_id from prestamo_cabecera where nro_prestamo = N_PRESTAMO);
SET @CANTIDAD:=(SELECT COUNT(*) FROM prestamo_detalle where pdetalle_estado_cuota ='pagada' AND nro_prestamo = N_PRESTAMO);
if @CANTIDAD = 0 THEN
	
		UPDATE prestamo_cabecera SET 
		pres_aprobacion = 'pendiente' ,
		pres_estado_caja = 'VIGENTE',
		pres_estado = 'Pendiente' 
		where nro_prestamo = N_PRESTAMO;
		
		
		UPDATE prestamo_detalle SET pdetalle_estado_cuota = 'pendiente', pdetalle_aprobacion = 'pendiente', pdetalle_caja = 'VIGENTE' where nro_prestamo = N_PRESTAMO;
		
		
 
		 UPDATE clientes set
		 cliente_estado_prestamo = 'con prestamo'
		 WHERE cliente_id = CLIENTE;

		
		/*UPDATE prestamo_cabecera SET 
		pres_estado_caja = "" 
		where nro_prestamo = N_PRESTAMO;*/
		
SELECT 1;
ELSE
SELECT 2;
END IF;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_EDITAR_MOVIMIENTO` (IN `ID` VARCHAR(11), IN `TIPO_MOV` VARCHAR(100), IN `DESCRIPCION` VARCHAR(100), IN `MONTO` FLOAT)   BEGIN 

DECLARE CANTIDAD INT;
SET @CANTIDAD:=(SELECT COUNT(*) FROM movimientos where movi_caja ='CERRADO' AND movimientos_id = ID);
if @CANTIDAD = 0 THEN
		
		UPDATE movimientos SET
		movi_tipo = TIPO_MOV,
		movi_descripcion = DESCRIPCION,
		movi_monto = MONTO
		where movimientos_id = ID;
		
SELECT 1;
ELSE
SELECT 2;
END IF;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ELIMINAR_MOVIMIENTO` (IN `ID` VARCHAR(11))   BEGIN 

DECLARE CANTIDAD INT;
SET @CANTIDAD:=(SELECT COUNT(*) FROM movimientos where movi_caja ='CERRADO' AND movimientos_id = ID);
if @CANTIDAD = 0 THEN
	
		DELETE FROM movimientos  
		where movimientos_id = ID;
		
SELECT 1;
ELSE
SELECT 2;
END IF;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ESTADISTICAS_RUTA` (IN `p_ruta_id` INT)   BEGIN
    SELECT 
        r.ruta_nombre,
        r.ruta_codigo,
        COUNT(DISTINCT cr.cliente_id) as total_clientes,
        COUNT(DISTINCT CASE WHEN cr.estado = 'activo' THEN cr.cliente_id END) as clientes_activos,
        COUNT(DISTINCT CASE WHEN cr.estado = 'inactivo' THEN cr.cliente_id END) as clientes_inactivos,
        COUNT(DISTINCT pc.nro_prestamo) as prestamos_activos,
        COALESCE(SUM(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' THEN pd.pdetalle_saldo_cuota ELSE 0 END), 0) as saldo_total_pendiente,
        COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN 1 END) as cuotas_vencidas,
        COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as cuotas_proximas_7_dias,
        COUNT(DISTINCT ur.usuario_id) as usuarios_asignados
    FROM rutas r
    LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
    LEFT JOIN clientes c ON cr.cliente_id = c.cliente_id
    LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_estado = 'VIGENTE'
    LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
    LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
    WHERE r.ruta_id = p_ruta_id
    GROUP BY r.ruta_id, r.ruta_nombre, r.ruta_codigo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ESTADO_CUENTA_CLIENTE` (IN `p_cliente_id` INT)   BEGIN
    SELECT
        -- Información del préstamo
        pc.pres_id,
        pc.nro_prestamo,
        pc.cliente_id,
        c.cliente_nombres,
        c.cliente_dni,
        c.cliente_celular,
        c.cliente_direccion,
        
        -- Datos financieros del préstamo
        pc.pres_monto,
        pc.pres_interes,
        pc.pres_monto_interes,
        pc.pres_monto_total,
        pc.pres_monto_cuota,
        pc.pres_cuotas,
        pc.pres_cuotas_pagadas,
        
        -- Fechas importantes
        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha_registro,
        DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') as fecha_emision,
        
        -- Estado y forma de pago
        pc.pres_aprobacion as estado,
        fp.fpago_descripcion,
        m.moneda_simbolo,
        m.moneda_nombre,
        u.usuario,
        
        -- Cálculos de saldo
        ROUND((pc.pres_monto_total - (pc.pres_cuotas_pagadas * pc.pres_monto_cuota)), 2) as saldo_pendiente,
        ROUND((pc.pres_cuotas_pagadas * pc.pres_monto_cuota), 2) as monto_pagado,
        (pc.pres_cuotas - pc.pres_cuotas_pagadas) as cuotas_pendientes,
        
        -- Porcentaje de avance
        ROUND((pc.pres_cuotas_pagadas / pc.pres_cuotas * 100), 2) as porcentaje_avance
        
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    WHERE pc.cliente_id = p_cliente_id
    ORDER BY pc.pres_fecha_registro DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_GENERAR_ALERTA_CAJA` (IN `p_caja_id` INT, IN `p_tipo_alerta` VARCHAR(50), IN `p_nivel_criticidad` VARCHAR(20), IN `p_titulo` VARCHAR(200), IN `p_mensaje` TEXT, IN `p_datos_adicionales` TEXT, IN `p_usuario_notificado` INT)   BEGIN
    INSERT INTO caja_alertas (
        caja_id, tipo_alerta, nivel_criticidad, titulo,
        mensaje, datos_adicionales, usuario_notificado
    ) VALUES (
        p_caja_id, p_tipo_alerta, p_nivel_criticidad, p_titulo,
        p_mensaje, p_datos_adicionales, p_usuario_notificado
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_KPIs_FILTRADOS` (IN `p_sucursal_id` INT, IN `p_periodo` VARCHAR(50))   BEGIN
    DECLARE fecha_inicio DATE;
    DECLARE fecha_fin DATE;

    -- Calcular fechas según el período
    IF p_periodo = 'hoy' THEN
        SET fecha_inicio = CURDATE();
        SET fecha_fin = CURDATE();
    ELSEIF p_periodo = 'semana' THEN
        SET fecha_inicio = SUBDATE(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) DAY);
        SET fecha_fin = ADDDATE(fecha_inicio, INTERVAL 6 DAY);
    ELSEIF p_periodo = 'mes' THEN
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    ELSEIF p_periodo = 'trimestre' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL (QUARTER(CURDATE()) - 1) QUARTER;
        SET fecha_fin = LAST_DAY(MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) * 3 - 1 MONTH);
    ELSEIF p_periodo = 'año' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1);
        SET fecha_fin = MAKEDATE(YEAR(CURDATE()), 366);
    ELSE
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    END IF;

    SELECT
        ROUND(IFNULL(SUM(pc.pres_saldo_actual), 0), 2) AS saldo_cartera,
        IFNULL(COUNT(DISTINCT c.id_cliente), 0) AS clientes_activos,
        ROUND(IFNULL(SUM(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' AND pd.pdetalle_fecha < CURDATE() THEN pd.pdetalle_saldo ELSE 0 END), 0), 2) AS monto_en_mora,
        ROUND(IFNULL((SUM(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' AND pd.pdetalle_fecha < CURDATE() THEN pd.pdetalle_saldo ELSE 0 END) / NULLIF(SUM(pc.pres_saldo_actual), 0)) * 100, 0), 2) AS porcentaje_mora
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.id_cliente = c.id_cliente
    LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
    WHERE pc.pres_aprobacion = 'aprobado'
    AND (p_sucursal_id IS NULL OR pc.id_sucursal = p_sucursal_id)
    AND DATE(pc.pres_fecha_registro) BETWEEN fecha_inicio AND fecha_fin;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LIQUIDAR_PRESTAMO` (IN `prestamo` VARCHAR(8), IN `cuota` VARCHAR(8))   BEGIN

DECLARE MONTOTOTAL INT;
DECLARE MONTOCUOTA INT;
DECLARE CANTCUOTADETA INT;
DECLARE CANTCUOCABE INT;
DECLARE MONTORESTANTECABE INT;
DECLARE MAXINROCUOTA INT;
DECLARE CLIENTE INT;

SET @MONTOTOTAL:=(select pres_monto_total from prestamo_cabecera where nro_prestamo = prestamo);   -- 768
-- SET @MONTOCUOTA:=(select ROUND(SUM(pdetalle_monto_cuota),2) from prestamo_detalle where nro_prestamo = prestamo AND pdetalle_estado_cuota = 'pagada' ); -- 172.5
 SET @MONTORESTANTECABE:=(select pres_monto_restante from prestamo_cabecera where nro_prestamo = prestamo  ); 
SET @MAXINROCUOTA:=(select max(pdetalle_monto_cuota) from prestamo_detalle where nro_prestamo = prestamo  );
SET @MONTOCUOTA:=(select ROUND(SUM(pdetalle_monto_cuota),2) from prestamo_detalle where nro_prestamo = prestamo AND pdetalle_monto_liquidar = '1' );

--  SET @CANTCUOTADETA:=(select count(pdetalle_estado_cuota) from prestamo_detalle where nro_prestamo = prestamo AND pdetalle_estado_cuota = 'pagada');
 SET @CANTCUOTADETA:=(select count(pdetalle_liquidar) from prestamo_detalle where nro_prestamo = prestamo AND pdetalle_liquidar = '1');
SET @CANTCUOCABE:=(select pres_cuotas from prestamo_cabecera where nro_prestamo = prestamo);

SET @CLIENTE:=(select cliente_id from prestamo_cabecera where nro_prestamo = prestamo);

UPDATE prestamo_detalle SET 
pdetalle_liquidar = '1',
pdetalle_monto_liquidar = '1',
 pdetalle_saldo_cuota = @MONTORESTANTECABE,
pdetalle_cant_cuota_pagada = (@CANTCUOCABE - @CANTCUOTADETA) -1
where nro_prestamo = prestamo 
and pdetalle_nro_cuota = cuota;
-- and pdetalle_estado_cuota = 'pendiente';

UPDATE clientes SET
cliente_estado_prestamo = 'DISPONIBLE'
WHERE cliente_id = @CLIENTE;




END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CAJA` ()   SELECT
	caja_id,
	-- caja_descripcion,
	caja_monto_inicial,
	IFNULL(caja_monto_ingreso,0),
	caja__monto_egreso,
	caja_prestamo,
 CONCAT_WS(' ',DATE_FORMAT(caja_f_apertura, '%d/%m/%Y'), caja_hora_apertura) as f_apert,
  CONCAT_WS(' ',DATE_FORMAT(caja_f_cierre, '%d/%m/%Y'), caja_hora_cierre) as caja_f_cierre,
 -- caja_f_cierre,
-- 	(select count(*) from prestamo_cabecera where pres_aprobacion = 'Aprobado' and pres_fecha_registro = CURDATE()) as cant_pres,
caja_count_prestamo,
	caja_monto_total,
	caja_estado,
	'' as opciones
FROM
	caja$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CLIENTES` (IN `_sucursal_id` INT)   BEGIN
    SELECT
        c.cliente_id,
        c.cliente_nombres,
        c.cliente_dni,
        c.cliente_cel,
        c.cliente_estado_prestamo,
        c.cliente_direccion,
        c.cliente_obs,
        c.cliente_correo,
        c.cliente_estatus,
        c.cliente_cant_prestamo,
        c.cliente_refe,
        c.cliente_cel_refe,
        s.nombre as sucursal_nombre
    FROM
        clientes c
    INNER JOIN
        sucursales s ON c.sucursal_id = s.id
    WHERE
        c.sucursal_id = _sucursal_id
    ORDER BY
        c.cliente_id
    DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CLIENTES_PRESTAMO` ()   BEGIN
    SELECT
        cliente_id, 
        cliente_nombres, 
        cliente_dni, 
        cliente_estado_prestamo, 
        CASE 
            WHEN cliente_estatus = '1' THEN 'Activo'
            WHEN cliente_estatus = '0' THEN 'Desactivado'
            ELSE 'Desconocido'
        END AS cliente_estatus
    FROM
        clientes
    ORDER BY cliente_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CLIENTES_RUTA` (IN `p_ruta_id` INT)   BEGIN
    SELECT 
        cr.cliente_ruta_id,
        cr.cliente_id,
        c.cliente_nombres,
        c.cliente_dni,
        c.cliente_cel,
        c.cliente_direccion,
        cr.direccion_especifica,
        cr.orden_visita,
        cr.observaciones,
        cr.estado,
        cr.fecha_asignacion,
        
        COUNT(DISTINCT pc.nro_prestamo) as prestamos_activos,
        COALESCE(SUM(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' THEN pd.pdetalle_saldo_cuota ELSE 0 END), 0) as saldo_pendiente,
        
        MIN(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN pd.pdetalle_fecha END) as proxima_cuota_vencida,
        COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN 1 END) as cuotas_vencidas,
        '' as opciones
    FROM clientes_rutas cr
    INNER JOIN clientes c ON cr.cliente_id = c.cliente_id
    LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_estado = 'VIGENTE'
    LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
    WHERE cr.ruta_id = p_ruta_id
    GROUP BY cr.cliente_ruta_id, cr.cliente_id, c.cliente_nombres, c.cliente_dni, 
             c.cliente_cel, c.cliente_direccion, cr.direccion_especifica, 
             cr.orden_visita, cr.observaciones, cr.estado, cr.fecha_asignacion
    ORDER BY cr.orden_visita ASC, c.cliente_nombres ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CLIENTES_TABLE` ()   BEGIN
    SELECT
        cliente_id, 
        cliente_nombres, 
        cliente_dni, 
        cliente_cel, 
        cliente_estado_prestamo, 
        CASE 
            WHEN cliente_estatus = '1' THEN 'Activo'
            WHEN cliente_estatus = '0' THEN 'Desactivado'
            ELSE 'Desconocido'
        END AS cliente_estatus,
        cliente_direccion,
        cliente_correo,
        '' as opciones,
        cliente_refe,
        cliente_cel_refe
    FROM
        clientes
    ORDER BY cliente_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_ID_CAJA_PARA_PRESTAMOS` ()   SELECT
caja_id,
caja_f_apertura 
FROM
	caja 
WHERE
	caja_estado = 'VIGENTE'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_MONEDAS_TABLE` ()   SELECT
	moneda_id, 
	moneda_nombre, 
	moneda_abrevia, 
	moneda_simbolo, 
	moneda_Descripcion,
	'' as opciones
FROM
	moneda$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_MOVIMIENTOS` ()   SELECT
	movimientos_id,
	movi_tipo,
	movi_descripcion,
	ROUND(movi_monto,2) as monto,
	 DATE_FORMAT(movi_fecha, '%d/%m/%Y') as fecha,
	 movi_caja,
	'' as opciones
	
FROM
	movimientos$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_MOVIMIENTOS_POR_CAJA` (IN `CAJA_ID` INT)   SELECT
		m.movi_tipo,
		m.movi_descripcion,
		m.movi_monto,
		 DATE_FORMAT(m.movi_fecha, '%d/%m/%Y') as fecha,
		m.caja_id
FROM
	movimientos m
	where m.caja_id = CAJA_ID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_NOTIFICACION` (IN `id_usuario` INT)   SELECT
		pd.nro_prestamo,
		pc.cliente_id,
		c.cliente_nombres,
		pd.pdetalle_nro_cuota,
		DATE_FORMAT(pd.pdetalle_fecha,'%d/%m/%Y') as fecha,
		pd.pdetalle_monto_cuota,
		pc.id_usuario,
		u.nombre_usuario

	FROM
		prestamo_cabecera pc
		INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
		INNER JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo 
		INNER JOIN usuarios u on pc.id_usuario =  u.id_usuario
	WHERE
		CURDATE() >= DATE(pd.pdetalle_fecha) and
		pc.pres_aprobacion = 'aprobado' 
		and pd.pdetalle_estado_cuota = 'pendiente'
		and pc.id_usuario = id_usuario
		ORDER BY 	DATE(pd.pdetalle_fecha) ASC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PRESTAMOS_POR_APROBACION` (IN `fecha_ini` DATE, IN `fecha_fin` DATE)   select pc.pres_id ,
				pc.nro_prestamo,
				pc.cliente_id,
				c.cliente_nombres,
				pc.pres_monto,
			  pc.pres_interes,
			  pc.pres_cuotas,
				pc.fpago_id,
				fp.fpago_descripcion,
				pc.id_usuario,
				u.usuario,		
				DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha,
				pc.pres_aprobacion as estado,
				'' as opciones,
				pc.pres_monto_cuota,
				pc.pres_monto_interes,
				pc.pres_monto_total,
				pc.pres_cuotas_pagadas
							
				 from prestamo_cabecera pc
				 INNER JOIN clientes c on
				 pc.cliente_id = c.cliente_id
				 INNER JOIN forma_pago fp on 
				 pc.fpago_id = fp.fpago_id
				 INNER JOIN usuarios u on
				 pc.id_usuario = u.id_usuario
				 WHERE pc.pres_fecha_registro BETWEEN fecha_ini AND fecha_fin$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PRESTAMOS_POR_CAJA` (IN `CAJA_ID` INT)   SELECT
	pc.nro_prestamo,
	pc.cliente_id,
	c.cliente_nombres,
	pres_monto,
	pres_monto_interes,
	pres_monto_total, 
	DATE_FORMAT(pres_fecha_registro, '%d/%m/%Y') as fecha,
	pc.caja_id,
	pc.pres_aprobacion
FROM
	prestamo_cabecera pc
	INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
	where pc.caja_id = CAJA_ID and pc.pres_aprobacion in ('aprobado','finalizado', 'pendiente')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PRESTAMOS_POR_USUARIO` (IN `p_id_usuario` INT)   BEGIN
    SELECT 
        pc.pres_id,
        pc.nro_prestamo,
        pc.cliente_id,
        c.cliente_nombres,
        pc.pres_monto,
        pc.pres_interes,
        pc.pres_cuotas,
        pc.fpago_id,
        fp.fpago_descripcion,
        pc.id_usuario,
        u.usuario,
        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha,
        pc.pres_aprobacion as estado,
        '' as opciones,
        pc.pres_monto_cuota,
        pc.pres_monto_interes,
        pc.pres_monto_total,
        pc.pres_cuotas_pagadas,
        IFNULL(pc.reimpreso_admin, 0) as reimpreso_admin
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    WHERE pc.id_usuario = p_id_usuario
    ORDER BY pc.pres_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_REFERENCIAS_EN_CLIENTE_EDIT` (IN `ID` INT)   SELECT
cliente_id,
refe_personal,
refe_cel_per,
refe_familiar,
refe_cel_fami
	
FROM
	referencias
	where cliente_id = ID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_RUTAS` (IN `p_sucursal_id` INT)   BEGIN
    SELECT 
        r.ruta_id,
        r.ruta_nombre,
        r.ruta_descripcion,
        r.ruta_codigo,
        r.ruta_color,
        r.ruta_estado,
        r.ruta_orden,
        r.ruta_observaciones,
        s.nombre as sucursal_nombre,
        COUNT(DISTINCT cr.cliente_id) as total_clientes,
        COUNT(DISTINCT CASE WHEN cr.estado = 'activo' THEN cr.cliente_id END) as clientes_activos,
        GROUP_CONCAT(DISTINCT CONCAT(u.nombres, ' ', u.apellidos) SEPARATOR ', ') as responsables,
        r.fecha_creacion,
        CONCAT(uc.nombres, ' ', uc.apellidos) as usuario_creacion_nombre,
        '' as opciones
    FROM rutas r
    INNER JOIN sucursales s ON r.sucursal_id = s.id
    LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
    LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo' AND ur.tipo_asignacion = 'responsable'
    LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
    LEFT JOIN usuarios uc ON r.usuario_creacion = uc.id_usuario
    WHERE r.sucursal_id = p_sucursal_id
    GROUP BY r.ruta_id, r.ruta_nombre, r.ruta_descripcion, r.ruta_codigo, r.ruta_color, 
             r.ruta_estado, r.ruta_orden, r.ruta_observaciones, s.nombre, r.fecha_creacion, 
             uc.nombres, uc.apellidos
    ORDER BY r.ruta_orden ASC, r.ruta_nombre ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_SELECT_ANIO_RECORD` ()   SELECT YEAR(pres_fecha_registro) as anio FROM prestamo_cabecera
where pres_aprobacion IN  ('aprobado', 'finalizado' )
GROUP BY YEAR(pres_fecha_registro)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_SELECT_USUARIO_RECORD` ()   SELECT u.id_usuario,
	CONCAT_WS( ' | ', u.usuario, u.nombre_usuario ) AS usu 
FROM
	prestamo_cabecera pc
	INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario 
WHERE
	pres_aprobacion IN ( 'aprobado', 'finalizado' ) 
GROUP BY
	u.id_usuario$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_USUARIOS` ()   BEGIN
    SELECT
        u.id_usuario,
        u.nombre_usuario,
        u.apellido_usuario,
        u.usuario, 
        u.clave,
        u.id_perfil_usuario, 
        p.descripcion,
        u.sucursal_id,
        COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre,
        CASE 
            WHEN u.estado = 1 THEN 'Activo'
            ELSE 'Inactivo'
        END as estado_texto,
        '' as opciones
    FROM usuarios u
    INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    LEFT JOIN sucursales s ON u.sucursal_id = s.id
    ORDER BY u.id_usuario ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_USUARIOS_DISPONIBLES` (IN `p_sucursal_id` INT)   BEGIN
    SELECT 
        u.id_usuario,
        CONCAT(u.nombres, ' ', u.apellidos) as nombre_completo,
        u.usuario,
        p.descripcion as perfil,
        u.estado,
        COUNT(DISTINCT ur.ruta_id) as rutas_asignadas
    FROM usuarios u
    INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    LEFT JOIN usuarios_rutas ur ON u.id_usuario = ur.usuario_id AND ur.estado = 'activo'
    WHERE u.sucursal_id = p_sucursal_id 
    AND u.estado = 1
    GROUP BY u.id_usuario, u.nombres, u.apellidos, u.usuario, p.descripcion, u.estado
    ORDER BY u.nombres ASC, u.apellidos ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MONTO_POR_CUOTA_PAGADA_D` (IN `prestamo` VARCHAR(8), IN `cuota` VARCHAR(8))   BEGIN

DECLARE MONTOTOTAL INT;
DECLARE MONTOCUOTA INT;
DECLARE CANTCUOTADETA INT;
DECLARE CANTCUOCABE INT;

SET @MONTOTOTAL:=(select pres_monto_total from prestamo_cabecera where nro_prestamo = prestamo);
SET @MONTOCUOTA:=(select SUM(pdetalle_monto_cuota) from prestamo_detalle where nro_prestamo = prestamo AND pdetalle_estado_cuota = 'pagada' );

SET @CANTCUOTADETA:=(select count(pdetalle_estado_cuota) from prestamo_detalle where nro_prestamo = prestamo AND pdetalle_estado_cuota = 'pagada');
SET @CANTCUOCABE:=(select pres_cuotas from prestamo_cabecera where nro_prestamo = prestamo);

UPDATE prestamo_detalle SET 
pdetalle_saldo_cuota = @MONTOTOTAL -  @MONTOCUOTA,
pdetalle_cant_cuota_pagada = @CANTCUOCABE - @CANTCUOTADETA
where nro_prestamo = prestamo 
and pdetalle_nro_cuota = cuota;

	UPDATE prestamo_detalle SET
	pdetalle_saldo_cuota = '0'
	where nro_prestamo = prestamo and pdetalle_cant_cuota_pagada = '0' ;



END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_DATA_CLIENTE_TEX` (IN `cliente_dni` VARCHAR(20))   SELECT
c.cliente_id,
c.cliente_nombres,
cliente_dni
FROM
	clientes c
	where c.cliente_dni = cliente_dni$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_DATA_EMPRESA` ()   SELECT
	empresa.confi_id, 
	empresa.confi_razon, 
	empresa.confi_ruc, 
	empresa.confi_direccion, 
	empresa.confi_correlativo, 
	empresa.config_correo,
	empresa.config_moneda
FROM
	empresa$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_ESTADISTICAS_RAPIDAS` ()   BEGIN
    SELECT
        (SELECT COUNT(caja_id) FROM caja WHERE DATE(caja_f_apertura) = CURDATE()) AS aperturas_hoy,
        (SELECT COUNT(caja_id) FROM caja WHERE DATE(caja_f_cierre) = CURDATE()) AS cierres_hoy;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_ESTADO_CAJA` ()   SELECT
	caja_estado,
	caja_f_apertura,
	caja_hora_apertura,
	caja_monto_total,
	caja_monto_ingreso 
FROM
	caja 
WHERE
	caja_estado = 'VIGENTE'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_NRO_CORRELATIVO` ()   SELECT 
	
		IFNULL(LPAD(MAX(c.confi_correlativo)+1,8,'0'),'00000001') nro_prestamo
		
		from empresa c$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_PRESTAMOS_MES_ACTUAL` ()   BEGIN
	SELECT
		DATE_FORMAT(pc.pres_fecha_registro,'%d/%m/%Y') as fecha,
		SUM(ROUND(pc.pres_monto_total,2)) as totalprestamo
	FROM
		prestamo_cabecera pc
	WHERE
			DATE(pc.pres_fecha_registro) >= DATE( LAST_DAY( NOW() - INTERVAL 1 MONTH ) + INTERVAL 1 DAY ) 
		AND 	DATE(pc.pres_fecha_registro) <= LAST_DAY(DATE( CURRENT_DATE )) 
		and  pc.pres_aprobacion in ('aprobado', 'pendiente', 'finalizado')
		GROUP BY 	DATE(pc.pres_fecha_registro) ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_PRESTAMOS_MES_ACTUAL_FILTRADO` (IN `p_sucursal_id` INT, IN `p_periodo` VARCHAR(50))   BEGIN
    DECLARE fecha_inicio DATE;
    DECLARE fecha_fin DATE;

    -- Calcular fechas según el período
    IF p_periodo = 'hoy' THEN
        SET fecha_inicio = CURDATE();
        SET fecha_fin = CURDATE();
    ELSEIF p_periodo = 'semana' THEN
        SET fecha_inicio = SUBDATE(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) DAY);
        SET fecha_fin = ADDDATE(fecha_inicio, INTERVAL 6 DAY);
    ELSEIF p_periodo = 'mes' THEN
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    ELSEIF p_periodo = 'trimestre' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL (QUARTER(CURDATE()) - 1) QUARTER;
        SET fecha_fin = LAST_DAY(MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) * 3 - 1 MONTH);
    ELSEIF p_periodo = 'año' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1);
        SET fecha_fin = MAKEDATE(YEAR(CURDATE()), 366);
    ELSE
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    END IF;

    SELECT
        DATE_FORMAT(pres_fecha_registro, '%Y-%m-%d') AS fecha,
        COUNT(pres_id) AS total_prestamos,
        SUM(pres_monto_total) AS monto_total
    FROM prestamo_cabecera
    WHERE pres_aprobacion = 'aprobado'
    AND (p_sucursal_id IS NULL OR id_sucursal = p_sucursal_id)
    AND DATE(pres_fecha_registro) BETWEEN fecha_inicio AND fecha_fin
    GROUP BY DATE_FORMAT(pres_fecha_registro, '%Y-%m-%d')
    ORDER BY fecha;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_ANULACION` (IN `p_tipo_documento` VARCHAR(20), IN `p_documento_id` VARCHAR(50), IN `p_nro_prestamo` VARCHAR(8), IN `p_usuario_id` INT, IN `p_motivo` TEXT, IN `p_datos_originales` JSON, IN `p_sucursal_id` INT, IN `p_ip_origen` VARCHAR(45))   BEGIN
    DECLARE v_usuario_nombre VARCHAR(255);
    
    -- Obtener nombre del usuario
    SELECT CONCAT(nombre_usuario, ' ', apellido_usuario) INTO v_usuario_nombre
    FROM usuarios WHERE id_usuario = p_usuario_id;
    
    -- Insertar registro de anulación
    INSERT INTO anulaciones_auditoria (
        tipo_documento, documento_id, nro_prestamo, usuario_id, usuario_nombre,
        motivo_anulacion, datos_originales, sucursal_id, ip_origen
    ) VALUES (
        p_tipo_documento, p_documento_id, p_nro_prestamo, p_usuario_id, v_usuario_nombre,
        p_motivo, p_datos_originales, p_sucursal_id, p_ip_origen
    );
    
    SELECT LAST_INSERT_ID() as anulacion_id, 'ok' as resultado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_APERTURA_CAJA` (IN `DESCRIPCION` VARCHAR(100), IN `MONTO_INI` FLOAT)   BEGIN
DECLARE CANTIDAD INT;
SET @CANTIDAD:=(SELECT COUNT(*) FROM caja where caja_estado='VIGENTE');
if @CANTIDAD = 0 THEN
	INSERT INTO caja (caja_descripcion, caja_monto_inicial, caja_f_apertura, caja_estado, caja_hora_apertura) VALUES(DESCRIPCION, MONTO_INI, CURDATE(), 'VIGENTE', CURRENT_TIME());
SELECT 1;
ELSE
SELECT 2;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_AUDITORIA_CAJA` (IN `p_caja_id` INT, IN `p_id_usuario` INT, IN `p_accion` VARCHAR(50), IN `p_descripcion` TEXT, IN `p_datos_anteriores` TEXT, IN `p_datos_nuevos` TEXT, IN `p_ip_address` VARCHAR(45), IN `p_monto_involucrado` DECIMAL(15,2), IN `p_resultado` VARCHAR(20), IN `p_observaciones` TEXT)   BEGIN
    INSERT INTO caja_auditoria (
        caja_id, id_usuario, accion, descripcion, 
        datos_anteriores, datos_nuevos, ip_address,
        monto_involucrado, resultado, observaciones
    ) VALUES (
        p_caja_id, p_id_usuario, p_accion, p_descripcion,
        p_datos_anteriores, p_datos_nuevos, p_ip_address,
        p_monto_involucrado, p_resultado, p_observaciones
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_CAJA_CIERRE` (IN `MONTO_INGRESO` FLOAT, IN `MONTO_PRES` FLOAT, IN `MONTO_EGRES` FLOAT, IN `MONTO_TOTAL` FLOAT, IN `CANT_PRESTA` VARCHAR(100), IN `CANT_INGRES` VARCHAR(100), IN `CANT_EGRESO` VARCHAR(100), IN `INTERES` FLOAT)   BEGIN 

DECLARE CANTIDAD INT;
SET @CANTIDAD:=(SELECT COUNT(*) FROM prestamo_cabecera where pres_estado_caja ='VIGENTE' AND pres_aprobacion = 'aprobado');
if @CANTIDAD = 0 THEN
	
		UPDATE caja SET 
		caja_monto_ingreso = MONTO_INGRESO,
		caja_prestamo =  MONTO_PRES,
		caja_f_cierre = CURDATE(),
		caja__monto_egreso = MONTO_EGRES,
		caja_monto_total = MONTO_TOTAL,
		caja_estado = 'CERRADO',
		caja_hora_cierre = CURRENT_TIME(),
		caja_count_prestamo = CANT_PRESTA,
		caja_count_ingreso = CANT_INGRES,
		caja_count_egreso = CANT_EGRESO,
		caja_interes = INTERES
		WHERE caja_estado = 'VIGENTE';
		
SELECT 1;
ELSE
SELECT 2;
END IF;





END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_REFERENCIAS` (IN `ID_CLI` INT, IN `REFE_PER` VARCHAR(255), IN `CEL_PER` VARCHAR(20), IN `REFE_FAM` VARCHAR(255), IN `CEL_FAM` VARCHAR(20))   BEGIN
INSERT INTO referencias (cliente_id, refe_personal, refe_cel_per, refe_familiar, refe_cel_fami) values(ID_CLI, REFE_PER, CEL_PER, REFE_FAM, CEL_FAM);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_DIARIO` (IN `p_fecha` DATE)   BEGIN
    SELECT
        'PRÉSTAMOS' as tipo_operacion,
        COUNT(pc.pres_id) as cantidad,
        ROUND(IFNULL(SUM(pc.pres_monto),0),2) as monto_capital,
        ROUND(IFNULL(SUM(pc.pres_monto_interes),0),2) as monto_interes,
        ROUND(IFNULL(SUM(pc.pres_monto_total),0),2) as monto_total,
        m.moneda_simbolo,
        m.moneda_nombre
    FROM prestamo_cabecera pc
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    WHERE DATE(pc.pres_fecha_registro) = p_fecha
    AND pc.pres_aprobacion IN ('aprobado', 'finalizado')
    GROUP BY m.moneda_id, m.moneda_simbolo, m.moneda_nombre

    UNION ALL

    SELECT
        'PAGOS DE CUOTAS' as tipo_operacion,
        COUNT(pd.pdetalle_id) as cantidad,
        ROUND(IFNULL(SUM(pd.pdetalle_monto_cuota),0),2) as monto_capital,
        0 as monto_interes,
        ROUND(IFNULL(SUM(pd.pdetalle_monto_cuota),0),2) as monto_total,
        m.moneda_simbolo,
        m.moneda_nombre
    FROM prestamo_detalle pd
    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    WHERE DATE(pd.pdetalle_fecha_registro) = p_fecha
    AND pd.pdetalle_estado_cuota = 'pagada'
    GROUP BY m.moneda_id, m.moneda_simbolo, m.moneda_nombre

    UNION ALL

    SELECT
        'INGRESOS' as tipo_operacion,
        COUNT(mv.movimientos_id) as cantidad,
        ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_capital,
        0 as monto_interes,
        ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_total,
        '$' as moneda_simbolo,
        'Mixta' as moneda_nombre
    FROM movimientos mv
    WHERE DATE(mv.movi_fecha) = p_fecha
    AND mv.movi_tipo = 'INGRESO'

    UNION ALL

    SELECT
        'EGRESOS' as tipo_operacion,
        COUNT(mv.movimientos_id) as cantidad,
        ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_capital,
        0 as monto_interes,
        ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_total,
        '$' as moneda_simbolo,
        'Mixta' as moneda_nombre
    FROM movimientos mv
    WHERE DATE(mv.movi_fecha) = p_fecha
    AND mv.movi_tipo = 'EGRESO'

    ORDER BY tipo_operacion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_LISTAR_CUOTAS_PAGADAS` ()   SELECT
	pc.cliente_id,
	c.cliente_nombres,
	pd.nro_prestamo ,
	pd.pdetalle_nro_cuota,
	pd.pdetalle_monto_cuota,
	pd.pdetalle_fecha_registro,
	pc.moneda_id,
	m.moneda_nombre,
	'' as opciones
	 
FROM
 prestamo_detalle pd

	INNER JOIN 	prestamo_cabecera pc ON 
	pd.nro_prestamo = pc.nro_prestamo
	INNER JOIN clientes c
	on pc.cliente_id = c.cliente_id
	INNER JOIN moneda m 
	on pc.moneda_id = m.moneda_id
WHERE
 pd.pdetalle_estado_cuota = 'pagada'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_LISTAR_TOTAL_CIERRE_CAJA` ()   SELECT 
	(select ROUND(MAX(caja_monto_inicial),2) from caja where caja_estado = 'VIGENTE') as monto_inicial_caja,

	-- (select COUNT(pres_monto) from prestamo_cabecera where pres_estado_caja = 'VIGENTE' AND pres_aprobacion in ( 'aprobado' , 'finalizado')) as cant_prestamo,
	(select COUNT(pres_monto) from prestamo_cabecera where pres_estado_caja = 'VIGENTE' AND pres_aprobacion in ( 'finalizado', 'aprobado')) as cant_prestamo,
	(select ROUND(IFNULL(SUM(pres_monto),0),2) from prestamo_cabecera where pres_estado_caja = 'VIGENTE' AND pres_aprobacion in (  'finalizado', 'aprobado') ) as suma_prestamo_capital,
	(select ROUND(IFNULL(SUM(pres_monto_interes),0),2) from prestamo_cabecera where pres_estado_caja = 'VIGENTE' AND pres_aprobacion in (  'finalizado', 'aprobado') ) as suma_prestamo_interes,
	(Select COUNT(*) from movimientos where movi_tipo = 'INGRESO' AND movi_caja = 'VIGENTE') as cant_ingresos,
	(select ROUND(IFNULL(SUM(movi_monto),0),2) from movimientos where movi_tipo = 'INGRESO' AND movi_caja = 'VIGENTE') as suma_ingresos,
	(Select COUNT(*) from movimientos where movi_tipo = 'EGRESO' AND movi_caja = 'VIGENTE') as cant_egresos,
	(select ROUND(IFNULL(SUM(movi_monto),0),2) from movimientos where movi_tipo = 'EGRESO' AND movi_caja = 'VIGENTE') as suma_egresos,
	
	(select caja_estado from caja where caja_estado = 'VIGENTE' ) as estado,
	(select  CONCAT_WS(' ',DATE_FORMAT(caja_f_apertura, '%d/%m/%Y'), caja_hora_apertura)  from caja where caja_estado = 'VIGENTE' ) as fecha_apertura,
	
	(select ROUND(IFNULL(SUM(pres_monto_total),0),2) from prestamo_cabecera where pres_estado_caja = 'VIGENTE' AND pres_aprobacion in (  'finalizado') ) as suma_total$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_MOROSOS` ()   BEGIN
    SELECT
        c.cliente_id,
        c.cliente_nombres AS cliente_nombres,
        pc.nro_prestamo,
        pd.pdetalle_nro_cuota,
        IF(pd.pdetalle_fecha = '0000-00-00 00:00:00', '', DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y')) AS fecha_vencimiento, -- Formato de fecha con manejo de fechas inválidas
        pd.pdetalle_monto_cuota,
        pd.pdetalle_saldo_cuota,
        IFNULL(DATEDIFF(CURDATE(), pd.pdetalle_fecha), 0) AS dias_mora, -- Manejo de NULL para días de mora
        m.moneda_simbolo,
        (SELECT COUNT(*) FROM prestamo_detalle pd2 WHERE pd2.nro_prestamo = pc.nro_prestamo AND pd2.pdetalle_estado_cuota = 'pendiente') AS cuotas_pendientes_prestamo
    FROM prestamo_detalle pd
    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    WHERE pd.pdetalle_estado_cuota = 'pendiente'
      AND pd.pdetalle_fecha < CURDATE()
    ORDER BY dias_mora DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_PIVOT` ()   SELECT YEAR(pres_f_emision) as anio,
SUM(CASE WHEN MONTH(pres_f_emision)=1 THEN pres_monto_total ELSE 0 END) AS enero,
SUM(CASE WHEN MONTH(pres_f_emision)=2 THEN pres_monto_total ELSE 0 END) AS febrero,
SUM(CASE WHEN MONTH(pres_f_emision)=3 THEN pres_monto_total ELSE 0 END) AS marzo,
SUM(CASE WHEN MONTH(pres_f_emision)=4 THEN pres_monto_total ELSE 0 END) AS abril,
SUM(CASE WHEN MONTH(pres_f_emision)=5 THEN pres_monto_total ELSE 0 END) AS mayo,
SUM(CASE WHEN MONTH(pres_f_emision)=6 THEN pres_monto_total ELSE 0 END) AS junio,
SUM(CASE WHEN MONTH(pres_f_emision)=7 THEN pres_monto_total ELSE 0 END) AS julio,
SUM(CASE WHEN MONTH(pres_f_emision)=8 THEN pres_monto_total ELSE 0 END) AS agosto,
SUM(CASE WHEN MONTH(pres_f_emision)=9 THEN pres_monto_total ELSE 0 END) AS setiembre,
SUM(CASE WHEN MONTH(pres_f_emision)=10 THEN pres_monto_total ELSE 0 END) AS octubre,
SUM(CASE WHEN MONTH(pres_f_emision)=11 THEN pres_monto_total ELSE 0 END) AS noviembre,
SUM(CASE WHEN MONTH(pres_f_emision)=12 THEN pres_monto_total ELSE 0 END) AS diciembre,
SUM(pres_monto_total) as total
FROM prestamo_cabecera
WHERE pres_aprobacion ='finalizado'
GROUP BY YEAR(pres_f_emision)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_POR_CLIENTE` (IN `id` INT)   BEGIN
    SELECT 
        pc.pres_id,
        pc.nro_prestamo,
        pc.cliente_id,
        c.cliente_nombres,
        pc.pres_monto AS monto_prestamo,
        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS fecha_prestamo,
        pc.pres_monto_total,
        pc.pres_monto_cuota,
        pc.pres_cuotas,
        pc.fpago_id,
        fp.fpago_descripcion,
        pc.pres_aprobacion AS estado,
        '' AS opciones,
        pc.pres_interes,
        pc.pres_monto_interes,
        pc.pres_cuotas_pagadas,
        (pc.pres_monto_total - (pc.pres_cuotas_pagadas * pc.pres_monto_cuota)) AS saldo_pendiente,
        DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') AS femision
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
    WHERE pc.cliente_id = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_PRESTAMOS_POR_ANIO_AND_USUARIO` (IN `ID` INT, IN `ANIO` VARCHAR(10))   SELECT 
YEAR(pc.pres_fecha_registro) as anio, 
case month(pc.pres_fecha_registro) 
WHEN 1 THEN 'Enero'
WHEN 2 THEN  'Febrero'
WHEN 3 THEN 'Marzo' 
WHEN 4 THEN 'Abril' 
WHEN 5 THEN 'Mayo'
WHEN 6 THEN 'Junio'
WHEN 7 THEN 'Julio'
WHEN 8 THEN 'Agosto'
WHEN 9 THEN 'Septiembre'
WHEN 10 THEN 'Octubre'
WHEN 11 THEN 'Noviembre'
WHEN 12 THEN 'Diciembre'
 END mesnombre ,
 u.usuario as usu_nombre,
 count(pc.pres_monto_total) as cant_prestamos,
 SUM(pc.pres_monto_total) as total,
MONTH(pc.pres_fecha_registro) as numero_mes, 
MONTHname(pc.pres_fecha_registro) as mes,
pc.id_usuario, 
u.usuario as usu_nombre

FROM prestamo_cabecera pc
INNER JOIN
	usuarios u
	ON 
		pc.id_usuario = u.id_usuario
where pc.pres_aprobacion ='finalizado' and YEAR(pc.pres_fecha_registro) =ANIO and pc.id_usuario = ID
GROUP BY YEAR(pc.pres_fecha_registro),
month(pc.pres_fecha_registro)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_SALDOS_ARRASTRADOS` (IN `fecha_inicio` DATE, IN `fecha_fin` DATE)   BEGIN
    SELECT 
        `log_id`,
        `nro_prestamo`,
        `cuota_origen`,
        `cuota_destino`,
        `monto_arrastrado`,
        DATE_FORMAT(`fecha_movimiento`, '%d/%m/%Y %H:%i:%s') as `fecha_movimiento`
    FROM 
        `log_saldos_arrastrados`
    WHERE 
        DATE(`fecha_movimiento`) BETWEEN `fecha_inicio` AND `fecha_fin`
    ORDER BY 
        `fecha_movimiento` DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_VERIFICAR_PERMISOS_ANULACION` (IN `p_usuario_id` INT, IN `p_tipo_documento` VARCHAR(20), IN `p_fecha_documento` DATETIME)   BEGIN
    DECLARE v_puede_anular BOOLEAN DEFAULT FALSE;
    DECLARE v_requiere_justificacion BOOLEAN DEFAULT TRUE;
    DECLARE v_limite_horas INT DEFAULT NULL;
    DECLARE v_perfil_id INT;
    DECLARE v_horas_transcurridas INT;
    
    -- Obtener perfil del usuario
    SELECT id_perfil_usuario INTO v_perfil_id 
    FROM usuarios 
    WHERE id_usuario = p_usuario_id AND estado = 1;
    
    -- Obtener permisos
    SELECT puede_anular, requiere_justificacion, limite_tiempo_horas
    INTO v_puede_anular, v_requiere_justificacion, v_limite_horas
    FROM permisos_anulacion 
    WHERE id_perfil = v_perfil_id 
    AND tipo_documento = p_tipo_documento 
    AND activo = TRUE;
    
    -- Calcular horas transcurridas
    IF p_fecha_documento IS NOT NULL THEN
        SET v_horas_transcurridas = TIMESTAMPDIFF(HOUR, p_fecha_documento, NOW());
    ELSE
        SET v_horas_transcurridas = 0;
    END IF;
    
    -- Verificar límite de tiempo
    IF v_limite_horas IS NOT NULL AND v_horas_transcurridas > v_limite_horas THEN
        SET v_puede_anular = FALSE;
    END IF;
    
    -- Retornar resultados
    SELECT 
        v_puede_anular as puede_anular,
        v_requiere_justificacion as requiere_justificacion,
        v_limite_horas as limite_horas,
        v_horas_transcurridas as horas_transcurridas,
        CASE 
            WHEN NOT v_puede_anular AND v_limite_horas IS NOT NULL AND v_horas_transcurridas > v_limite_horas THEN 'Tiempo límite excedido'
            WHEN NOT v_puede_anular THEN 'Sin permisos para anular este tipo de documento'
            ELSE 'Permitido'
        END as mensaje;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_VERIFICAR_PERMISOS_CAJA` (IN `p_id_usuario` INT, IN `p_accion` VARCHAR(50), IN `p_monto` DECIMAL(15,2))   BEGIN
    DECLARE v_puede_ejecutar TINYINT DEFAULT 0;
    DECLARE v_es_admin TINYINT DEFAULT 0;
    DECLARE v_limite_monto DECIMAL(15,2) DEFAULT 0;
    
    -- Verificar si es administrador
    SELECT COUNT(*) INTO v_es_admin
    FROM usuarios u 
    INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    WHERE u.id_usuario = p_id_usuario 
    AND p.descripcion = 'Administrador' 
    AND u.estado = 1;
    
    -- Si es admin, puede todo
    IF v_es_admin > 0 THEN
        SET v_puede_ejecutar = 1;
    ELSE
        -- Verificar permisos específicos
        CASE p_accion
            WHEN 'ABRIR_CAJA' THEN
                SELECT puede_abrir_caja, COALESCE(limite_monto_apertura, 999999999) 
                INTO v_puede_ejecutar, v_limite_monto
                FROM caja_permisos 
                WHERE id_usuario = p_id_usuario AND estado = 'activo';
                
                IF p_monto > v_limite_monto THEN
                    SET v_puede_ejecutar = 0;
                END IF;
                
            WHEN 'CERRAR_CAJA' THEN
                SELECT puede_cerrar_caja INTO v_puede_ejecutar
                FROM caja_permisos 
                WHERE id_usuario = p_id_usuario AND estado = 'activo';
                
            WHEN 'GESTIONAR_MOVIMIENTOS' THEN
                SELECT puede_gestionar_movimientos, COALESCE(limite_monto_movimiento, 999999999)
                INTO v_puede_ejecutar, v_limite_monto
                FROM caja_permisos 
                WHERE id_usuario = p_id_usuario AND estado = 'activo';
                
                IF p_monto > v_limite_monto THEN
                    SET v_puede_ejecutar = 0;
                END IF;
                
            WHEN 'SUPERVISAR' THEN
                SELECT puede_supervisar INTO v_puede_ejecutar
                FROM caja_permisos 
                WHERE id_usuario = p_id_usuario AND estado = 'activo';
                
            ELSE
                SET v_puede_ejecutar = 0;
        END CASE;
    END IF;
    
    -- Devolver resultado
    SELECT 
        COALESCE(v_puede_ejecutar, 0) as puede_ejecutar,
        v_es_admin as es_administrador,
        COALESCE(v_limite_monto, 0) as limite_monto;
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_VER_DETALLE_PRESTAMO` (IN `nro_prestamo` VARCHAR(8))   select pd.pdetalle_id,
pd.nro_prestamo,
				pd.pdetalle_nro_cuota as cuota,
				-- DATE(pd.pdetalle_fecha) as fecha,
				DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y') as fecha,
				pd.pdetalle_monto_cuota as monto,
				pd.pdetalle_estado_cuota as estado,
				'' as accion
				from prestamo_detalle pd
				where pd.nro_prestamo = nro_prestamo$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `anulaciones_auditoria`
--

CREATE TABLE `anulaciones_auditoria` (
  `anulacion_id` int(11) NOT NULL,
  `tipo_documento` enum('pago','cuota','prestamo','contrato','nota_debito') NOT NULL,
  `documento_id` varchar(50) NOT NULL COMMENT 'ID del documento anulado',
  `nro_prestamo` varchar(8) DEFAULT NULL COMMENT 'Número de préstamo relacionado',
  `usuario_id` int(11) NOT NULL COMMENT 'Usuario que realizó la anulación',
  `usuario_nombre` varchar(255) NOT NULL COMMENT 'Nombre del usuario para auditoría',
  `motivo_anulacion` text NOT NULL COMMENT 'Justificación obligatoria',
  `datos_originales` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Datos del documento antes de anular' CHECK (json_valid(`datos_originales`)),
  `fecha_anulacion` datetime NOT NULL DEFAULT current_timestamp(),
  `sucursal_id` int(11) DEFAULT NULL COMMENT 'Sucursal donde se realizó la anulación',
  `ip_origen` varchar(45) DEFAULT NULL COMMENT 'IP desde donde se realizó la anulación',
  `estado` enum('activa','revertida') DEFAULT 'activa' COMMENT 'Estado de la anulación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Auditoría de todas las anulaciones realizadas en el sistema';

--
-- Dumping data for table `anulaciones_auditoria`
--

INSERT INTO `anulaciones_auditoria` (`anulacion_id`, `tipo_documento`, `documento_id`, `nro_prestamo`, `usuario_id`, `usuario_nombre`, `motivo_anulacion`, `datos_originales`, `fecha_anulacion`, `sucursal_id`, `ip_origen`, `estado`) VALUES
(1, 'prestamo', '00000003', '00000003', 1, 'Sistema', 'Anulación desde sistema legacy', '{\"monto_original\": 10000, \"estado_anterior\": \"pendiente\"}', '2025-07-18 15:43:28', NULL, NULL, 'activa');

-- --------------------------------------------------------

--
-- Table structure for table `caja`
--

CREATE TABLE `caja` (
  `caja_id` int(11) NOT NULL,
  `caja_descripcion` varchar(100) DEFAULT NULL,
  `caja_monto_inicial` float DEFAULT NULL,
  `caja_monto_ingreso` float DEFAULT NULL,
  `caja_prestamo` float DEFAULT NULL,
  `caja_f_apertura` date DEFAULT NULL,
  `caja_f_cierre` date DEFAULT NULL,
  `caja__monto_egreso` float DEFAULT NULL,
  `caja_monto_total` float DEFAULT NULL,
  `caja_estado` varchar(50) DEFAULT NULL,
  `caja_hora_apertura` time DEFAULT NULL,
  `caja_hora_cierre` time DEFAULT NULL,
  `caja_count_prestamo` varchar(100) DEFAULT NULL,
  `caja_count_ingreso` varchar(100) DEFAULT NULL,
  `caja_count_egreso` varchar(100) DEFAULT NULL,
  `caja_correo` varchar(100) DEFAULT NULL,
  `caja_interes` float DEFAULT NULL,
  `caja_sucursal_id` int(11) DEFAULT NULL COMMENT 'Referencia a caja específica de sucursal',
  `usuario_apertura` int(11) DEFAULT NULL COMMENT 'Usuario que abrió la caja',
  `usuario_cierre` int(11) DEFAULT NULL COMMENT 'Usuario que cerró la caja',
  `ip_apertura` varchar(45) DEFAULT NULL COMMENT 'IP de apertura',
  `ip_cierre` varchar(45) DEFAULT NULL COMMENT 'IP de cierre',
  `validacion_fisica_apertura` tinyint(1) DEFAULT 0 COMMENT 'Si se realizó conteo físico en apertura',
  `validacion_fisica_cierre` tinyint(1) DEFAULT 0 COMMENT 'Si se realizó conteo físico en cierre',
  `observaciones_apertura` text DEFAULT NULL,
  `observaciones_cierre` text DEFAULT NULL,
  `nivel_criticidad` enum('NORMAL','ALTO','CRITICO') DEFAULT 'NORMAL' COMMENT 'Nivel de criticidad de la sesión'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `caja`
--

INSERT INTO `caja` (`caja_id`, `caja_descripcion`, `caja_monto_inicial`, `caja_monto_ingreso`, `caja_prestamo`, `caja_f_apertura`, `caja_f_cierre`, `caja__monto_egreso`, `caja_monto_total`, `caja_estado`, `caja_hora_apertura`, `caja_hora_cierre`, `caja_count_prestamo`, `caja_count_ingreso`, `caja_count_egreso`, `caja_correo`, `caja_interes`, `caja_sucursal_id`, `usuario_apertura`, `usuario_cierre`, `ip_apertura`, `ip_cierre`, `validacion_fisica_apertura`, `validacion_fisica_cierre`, `observaciones_apertura`, `observaciones_cierre`, `nivel_criticidad`) VALUES
(4, 'Apertura de Caja', 90000, NULL, NULL, '2025-07-07', NULL, NULL, NULL, 'VIGENTE', '23:10:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 'NORMAL');

--
-- Triggers `caja`
--
DELIMITER $$
CREATE TRIGGER `TG_AUDITORIA_CAJA_APERTURA` AFTER INSERT ON `caja` FOR EACH ROW BEGIN
    DECLARE v_usuario_actual INT DEFAULT 1;
    
    -- Obtener usuario de sesión si está disponible
    SET v_usuario_actual = COALESCE(NEW.usuario_apertura, 1);
    
    INSERT INTO caja_auditoria (
        caja_id, id_usuario, accion, descripcion,
        datos_nuevos, monto_involucrado, resultado
    ) VALUES (
        NEW.caja_id, 
        v_usuario_actual,
        'APERTURA',
        CONCAT('Apertura de caja: ', NEW.caja_descripcion),
        JSON_OBJECT(
            'monto_inicial', NEW.caja_monto_inicial,
            'fecha_apertura', NEW.caja_f_apertura,
            'hora_apertura', NEW.caja_hora_apertura,
            'estado', NEW.caja_estado
        ),
        NEW.caja_monto_inicial,
        'EXITOSO'
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TG_AUDITORIA_CAJA_CIERRE` AFTER UPDATE ON `caja` FOR EACH ROW BEGIN
    DECLARE v_usuario_actual INT DEFAULT 1;
    
    -- Solo si se está cerrando la caja
    IF OLD.caja_estado = 'VIGENTE' AND NEW.caja_estado = 'CERRADO' THEN
        
        SET v_usuario_actual = COALESCE(NEW.usuario_cierre, 1);
        
        INSERT INTO caja_auditoria (
            caja_id, id_usuario, accion, descripcion,
            datos_anteriores, datos_nuevos, monto_involucrado, resultado
        ) VALUES (
            NEW.caja_id,
            v_usuario_actual,
            'CIERRE',
            CONCAT('Cierre de caja: ', NEW.caja_descripcion),
            JSON_OBJECT(
                'estado_anterior', OLD.caja_estado,
                'monto_inicial', OLD.caja_monto_inicial
            ),
            JSON_OBJECT(
                'monto_total', NEW.caja_monto_total,
                'fecha_cierre', NEW.caja_f_cierre,
                'hora_cierre', NEW.caja_hora_cierre,
                'estado', NEW.caja_estado,
                'prestamos', NEW.caja_prestamo,
                'ingresos', NEW.caja_monto_ingreso,
                'egresos', NEW.caja__monto_egreso
            ),
            NEW.caja_monto_total,
            'EXITOSO'
        );
        
        -- Generar alerta si hay discrepancias significativas
        IF ABS(NEW.caja_monto_total - NEW.caja_monto_inicial) > 10000 THEN
            INSERT INTO caja_alertas (
                caja_id, tipo_alerta, nivel_criticidad, titulo, mensaje
            ) VALUES (
                NEW.caja_id,
                'ALTA_ACTIVIDAD',
                'WARNING',
                'Actividad alta detectada en caja',
                CONCAT('La caja ', NEW.caja_descripcion, ' registró movimientos por un total significativo: ', 
                       FORMAT(NEW.caja_monto_total, 2))
            );
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TG_CERRAR_MOVI_INGRESO` BEFORE UPDATE ON `caja` FOR EACH ROW BEGIN

UPDATE movimientos SET
movi_caja= 'CERRADO'
where movi_caja='VIGENTE';
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `TG_CERRAR_PRESTAMO` BEFORE UPDATE ON `caja` FOR EACH ROW BEGIN

UPDATE prestamo_cabecera SET
pres_estado_caja= 'CERRADO'
where pres_estado_caja='VIGENTE';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cajas_sucursales`
--

CREATE TABLE `cajas_sucursales` (
  `caja_sucursal_id` int(11) NOT NULL,
  `sucursal_id` int(11) NOT NULL,
  `nombre_caja` varchar(100) NOT NULL COMMENT 'Ej: Caja Principal, Caja 2, etc.',
  `codigo_caja` varchar(20) NOT NULL COMMENT 'Código único de la caja',
  `descripcion` text DEFAULT NULL,
  `monto_limite` decimal(15,2) DEFAULT NULL COMMENT 'Monto límite para alertas',
  `usuario_responsable` int(11) DEFAULT NULL COMMENT 'Usuario responsable principal',
  `estado` enum('activa','inactiva','mantenimiento') DEFAULT 'activa',
  `tipo_caja` enum('principal','secundaria','temporal') DEFAULT 'principal',
  `ubicacion_fisica` varchar(200) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `usuario_creacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Configuración de múltiples cajas por sucursal';

--
-- Dumping data for table `cajas_sucursales`
--

INSERT INTO `cajas_sucursales` (`caja_sucursal_id`, `sucursal_id`, `nombre_caja`, `codigo_caja`, `descripcion`, `monto_limite`, `usuario_responsable`, `estado`, `tipo_caja`, `ubicacion_fisica`, `fecha_creacion`, `fecha_modificacion`, `usuario_creacion`) VALUES
(1, 1, 'Caja Principal - Leon', 'CP-LE001', 'Caja principal de la sucursal Leon', NULL, NULL, 'activa', 'principal', NULL, '2025-07-17 17:18:09', NULL, 1),
(2, 2, 'Caja Principal - Chinandega', 'CP-CH001', 'Caja principal de la sucursal Chinandega', NULL, NULL, 'activa', 'principal', NULL, '2025-07-17 17:18:09', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `caja_alertas`
--

CREATE TABLE `caja_alertas` (
  `alerta_id` int(11) NOT NULL,
  `caja_id` int(11) NOT NULL,
  `tipo_alerta` enum('SALDO_BAJO','TIEMPO_PROLONGADO','ALTA_ACTIVIDAD','DISCREPANCIA','LIMITE_EXCEDIDO','SISTEMA') NOT NULL,
  `nivel_criticidad` enum('INFO','WARNING','CRITICAL','URGENT') DEFAULT 'INFO',
  `titulo` varchar(200) NOT NULL,
  `mensaje` text NOT NULL,
  `datos_adicionales` text DEFAULT NULL COMMENT 'JSON con datos contextuales',
  `usuario_notificado` int(11) DEFAULT NULL,
  `fecha_generacion` datetime DEFAULT current_timestamp(),
  `fecha_lectura` datetime DEFAULT NULL,
  `fecha_resolucion` datetime DEFAULT NULL,
  `estado` enum('pendiente','leida','resuelta','ignorada') DEFAULT 'pendiente',
  `acciones_tomadas` text DEFAULT NULL,
  `usuario_resolucion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Sistema de alertas y notificaciones para el módulo de caja';

-- --------------------------------------------------------

--
-- Table structure for table `caja_auditoria`
--

CREATE TABLE `caja_auditoria` (
  `auditoria_id` int(11) NOT NULL,
  `caja_id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `accion` enum('APERTURA','CIERRE','MOVIMIENTO','CONSULTA','MODIFICACION','AUTORIZACION') NOT NULL,
  `descripcion` text NOT NULL COMMENT 'Descripción detallada de la acción',
  `datos_anteriores` text DEFAULT NULL COMMENT 'JSON con datos antes del cambio',
  `datos_nuevos` text DEFAULT NULL COMMENT 'JSON con datos después del cambio',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `monto_involucrado` decimal(15,2) DEFAULT NULL,
  `resultado` enum('EXITOSO','FALLIDO','PENDIENTE') DEFAULT 'EXITOSO',
  `observaciones` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Registro completo de auditoría para todas las operaciones de caja';

-- --------------------------------------------------------

--
-- Table structure for table `caja_conteos_fisicos`
--

CREATE TABLE `caja_conteos_fisicos` (
  `conteo_id` int(11) NOT NULL,
  `caja_id` int(11) NOT NULL,
  `usuario_conteo` int(11) NOT NULL,
  `tipo_conteo` enum('APERTURA','CIERRE','INTERMEDIO','SUPERVISION') NOT NULL,
  `saldo_sistema` decimal(15,2) NOT NULL COMMENT 'Saldo según el sistema',
  `saldo_fisico` decimal(15,2) NOT NULL COMMENT 'Saldo contado físicamente',
  `diferencia` decimal(15,2) GENERATED ALWAYS AS (`saldo_fisico` - `saldo_sistema`) STORED,
  `denominaciones` text DEFAULT NULL COMMENT 'JSON con detalle de billetes y monedas',
  `observaciones` text DEFAULT NULL,
  `foto_evidencia` varchar(500) DEFAULT NULL COMMENT 'Ruta de foto del conteo',
  `requiere_justificacion` tinyint(1) GENERATED ALWAYS AS (abs(`diferencia`) > 0) STORED,
  `justificacion` text DEFAULT NULL,
  `supervisor_validacion` int(11) DEFAULT NULL,
  `fecha_conteo` datetime DEFAULT current_timestamp(),
  `fecha_validacion` datetime DEFAULT NULL,
  `estado` enum('pendiente','validado','rechazado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Registro de conteos físicos y conciliación de cajas';

-- --------------------------------------------------------

--
-- Table structure for table `caja_permisos`
--

CREATE TABLE `caja_permisos` (
  `permiso_id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `puede_abrir_caja` tinyint(1) DEFAULT 0 COMMENT 'Puede abrir cajas',
  `puede_cerrar_caja` tinyint(1) DEFAULT 0 COMMENT 'Puede cerrar cajas',
  `puede_ver_reportes` tinyint(1) DEFAULT 1 COMMENT 'Puede ver reportes de caja',
  `puede_gestionar_movimientos` tinyint(1) DEFAULT 0 COMMENT 'Puede crear ingresos/egresos',
  `puede_supervisar` tinyint(1) DEFAULT 0 COMMENT 'Puede supervisar todas las cajas',
  `limite_monto_apertura` decimal(15,2) DEFAULT NULL COMMENT 'Monto máximo para apertura',
  `limite_monto_movimiento` decimal(15,2) DEFAULT NULL COMMENT 'Monto máximo por movimiento',
  `requiere_autorizacion` tinyint(1) DEFAULT 0 COMMENT 'Requiere autorización para acciones',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `usuario_creacion` int(11) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Permisos específicos para operaciones de caja por usuario';

--
-- Dumping data for table `caja_permisos`
--

INSERT INTO `caja_permisos` (`permiso_id`, `id_usuario`, `puede_abrir_caja`, `puede_cerrar_caja`, `puede_ver_reportes`, `puede_gestionar_movimientos`, `puede_supervisar`, `limite_monto_apertura`, `limite_monto_movimiento`, `requiere_autorizacion`, `fecha_creacion`, `fecha_modificacion`, `usuario_creacion`, `estado`) VALUES
(1, 1, 1, 1, 1, 1, 1, NULL, NULL, 0, '2025-07-17 17:18:09', NULL, 1, 'activo'),
(2, 2, 1, 1, 1, 1, 1, NULL, NULL, 0, '2025-07-17 17:18:09', NULL, 1, 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `caja_permisos_basico`
--

CREATE TABLE `caja_permisos_basico` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `puede_todo` tinyint(1) DEFAULT 1,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `caja_permisos_basico`
--

INSERT INTO `caja_permisos_basico` (`id`, `usuario_id`, `puede_todo`, `activo`, `fecha_creacion`) VALUES
(1, 1, 1, 1, '2025-07-18 08:01:41');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `cliente_id` int(11) NOT NULL,
  `cliente_nombres` varchar(255) DEFAULT NULL,
  `cliente_dni` varchar(50) DEFAULT NULL,
  `cliente_cel` varchar(20) DEFAULT NULL,
  `cliente_estado_prestamo` varchar(50) DEFAULT NULL,
  `cliente_direccion` varchar(255) DEFAULT NULL,
  `cliente_obs` varchar(255) DEFAULT NULL,
  `cliente_correo` varchar(255) DEFAULT NULL,
  `cliente_estatus` varchar(255) DEFAULT NULL,
  `cliente_cant_prestamo` char(10) DEFAULT NULL,
  `cliente_refe` varchar(255) DEFAULT NULL,
  `cliente_cel_refe` varchar(20) DEFAULT NULL,
  `cliente_empresa_laboral` varchar(255) DEFAULT NULL COMMENT 'Empresa donde trabaja el cliente',
  `cliente_cargo_laboral` varchar(255) DEFAULT NULL COMMENT 'Cargo o posición laboral',
  `cliente_tel_laboral` varchar(20) DEFAULT NULL COMMENT 'Teléfono del trabajo',
  `cliente_dir_laboral` text DEFAULT NULL COMMENT 'Dirección del trabajo',
  `cliente_refe_per_nombre` varchar(255) DEFAULT NULL COMMENT 'Nombre de referencia personal',
  `cliente_refe_per_cel` varchar(20) DEFAULT NULL COMMENT 'Celular de referencia personal',
  `cliente_refe_per_dir` text DEFAULT NULL COMMENT 'Dirección de referencia personal',
  `cliente_refe_fami_nombre` varchar(255) DEFAULT NULL COMMENT 'Nombre de referencia familiar',
  `cliente_refe_fami_cel` varchar(20) DEFAULT NULL COMMENT 'Celular de referencia familiar',
  `cliente_refe_fami_dir` text DEFAULT NULL COMMENT 'Dirección de referencia familiar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`cliente_id`, `cliente_nombres`, `cliente_dni`, `cliente_cel`, `cliente_estado_prestamo`, `cliente_direccion`, `cliente_obs`, `cliente_correo`, `cliente_estatus`, `cliente_cant_prestamo`, `cliente_refe`, `cliente_cel_refe`, `cliente_empresa_laboral`, `cliente_cargo_laboral`, `cliente_tel_laboral`, `cliente_dir_laboral`, `cliente_refe_per_nombre`, `cliente_refe_per_cel`, `cliente_refe_per_dir`, `cliente_refe_fami_nombre`, `cliente_refe_fami_cel`, `cliente_refe_fami_dir`) VALUES
(6, 'MARYURI DE LOS ANGELES CABALLERO LOAISIGA', '2810210870007R', '+50586595453', 'Disponible', 'COSTADO SUR ERMITA DE DOLORES', NULL, 'ferchojoshua@gmail.com', '1', NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', ''),
(7, 'GLADYS ESPERANZA GARCIA PINEDA', '2810312620000T', '+50585812591', 'Disponible', 'VILLA 23 DE JULIO DEL PUENTE NUEVO 50 VRS SUR 50 VRS ESTE', NULL, 'ferchojoshua@gmail.com', '1', NULL, NULL, NULL, 'PENSIONADA', 'PENSIONADA', '+50523126370', '', 'ROGELIO HURTADO ESPINOZA', '+50584634170', 'RTPTOLE ANDRES ZAPATA 3RA CAL', 'VERONICA CABALLERO', '+50557211690', 'PALI GUADALUPE 20 VRS NORTE'),
(8, 'RAQUEL IVETTE HURTADO RUIZ', '0010901860015S', '+50578398319', 'con prestamo', 'PRADERAS NUEVA LEON 3RA CALLE CASA B1', NULL, 'ferchojoshua@gmail.com', '1', NULL, NULL, NULL, 'EN LINEA ONLINE', 'AGENTE DE VENTAS', '+50578398319', 'SU DOMICILIO', '', '', '', '', '', ''),
(9, 'DORYS GEORGINA CASTILLO MARTINEZ', '2812308830005U', '+50586677969', 'Disponible', 'ENTRADA PRINCIPAL OSCAR PEREZ 1C SUR 1 1/2 ESTE. TODO SERA MEJOR', NULL, 'martharuiz9211@gmail.com', '1', NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', ''),
(10, 'FATIMA GONZALEZ', '23456789', '+50586595453', 'DISPONIBLE', 'Leon-Nicaragua', NULL, 'ferchojoshua@gmail.com', '1', NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `clientes_rutas`
--

CREATE TABLE `clientes_rutas` (
  `cliente_ruta_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `ruta_id` int(11) NOT NULL,
  `orden_visita` int(11) DEFAULT 0 COMMENT 'Orden sugerido en la ruta',
  `direccion_especifica` text DEFAULT NULL COMMENT 'Dirección de cobro si es diferente',
  `observaciones` text DEFAULT NULL COMMENT 'Notas importantes para el cobro',
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_asignacion` datetime DEFAULT current_timestamp(),
  `usuario_asignacion` int(11) NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Relación entre clientes y rutas';

-- --------------------------------------------------------

--
-- Table structure for table `empresa`
--

CREATE TABLE `empresa` (
  `confi_id` int(11) NOT NULL,
  `confi_razon` varchar(255) DEFAULT NULL,
  `confi_ruc` varchar(40) DEFAULT NULL,
  `confi_direccion` varchar(255) DEFAULT NULL,
  `confi_correlativo` varchar(8) DEFAULT NULL,
  `config_correo` varchar(50) DEFAULT NULL,
  `config_celular` varchar(50) DEFAULT NULL,
  `config_moneda` varchar(3) DEFAULT NULL,
  `config_logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `empresa`
--

INSERT INTO `empresa` (`confi_id`, `confi_razon`, `confi_ruc`, `confi_direccion`, `confi_correlativo`, `config_correo`, `config_celular`, `config_moneda`, `config_logo`) VALUES
(1, 'CREDI-BIEN', '1020304050', 'Fundeci 2da ETAPA', '00000003', 'ferchojoshua@gmail.com', '922804671', 'C$', 'logo_empresa_1752213967.jpg'),
(2, 'CREDI-BI', '1020304050', 'Fundeci 2da ETAPA', NULL, NULL, '922804671', NULL, 'logo_empresa_1752213967.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `forma_pago`
--

CREATE TABLE `forma_pago` (
  `fpago_id` int(11) NOT NULL,
  `fpago_descripcion` varchar(255) DEFAULT NULL,
  `valor` char(10) DEFAULT NULL,
  `aplica_dias` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `forma_pago`
--

INSERT INTO `forma_pago` (`fpago_id`, `fpago_descripcion`, `valor`, `aplica_dias`) VALUES
(1, 'Diario', '1', '1'),
(2, 'Semanal', '7', '1'),
(3, 'Quincenal', '15', '1'),
(4, 'Mensual', '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `log_saldos_arrastrados`
--

CREATE TABLE `log_saldos_arrastrados` (
  `log_id` int(11) NOT NULL,
  `nro_prestamo` varchar(8) NOT NULL,
  `cuota_origen` int(11) NOT NULL,
  `cuota_destino` int(11) NOT NULL,
  `monto_arrastrado` decimal(10,2) NOT NULL,
  `fecha_movimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `modulo` varchar(255) DEFAULT NULL,
  `padre_id` int(11) DEFAULT NULL,
  `vista` varchar(50) DEFAULT NULL,
  `icon_menu` varchar(50) DEFAULT NULL,
  `orden` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `modulos`
--

INSERT INTO `modulos` (`id`, `modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) VALUES
(1, 'Tablero pincipal', 59, 'dashboard.php', 'fas fa-tachometer-alt', 1),
(10, 'Reportes', 0, '', 'fas fa-chart-line', 5),
(11, 'Empresa', 0, 'configuracion.php', 'fas fa-landmark', 9),
(12, 'Usuarios', 14, 'usuario.php', 'far fa-user', 13),
(13, 'Modulos y Perfiles', 14, 'modulos_perfiles.php', 'fas fa-tablet-alt', 14),
(14, 'Mantenimiento', 0, NULL, 'fas fa-cogs', 6),
(24, 'Clientes', 0, 'cliente.php', 'fas fa-id-card', 3),
(25, 'Moneda', 0, 'moneda.php', 'fas fa-dollar-sign', 10),
(29, 'Prestamos', 0, '', 'fas fa-landmark', 4),
(34, 'Solicitud/Prestamo', 29, 'prestamo.php', 'far fa-circle', 6),
(35, 'Listado Prestamos', 29, 'administrar_prestamos.php', 'far fa-circle', 7),
(36, 'Aprobar S/P', 29, 'aprobacion.php', 'far fa-circle', 8),
(37, 'Por Cliente', 10, 'reporte_cliente.php', 'far fa-circle', 16),
(38, 'Cuotas Pagadas', 10, 'reporte_cuotas_pagadas.php', 'far fa-circle', 17),
(39, 'Caja', 0, '', 'fas fa-cash-register', 2),
(40, 'Aperturar Caja', 39, 'caja.php', 'far fa-circle', 1),
(41, 'Ingresos / Egre', 39, 'ingresos.php', 'far fa-circle', 3),
(43, 'Pivot', 10, 'reportes.php', 'far fa-circle', 18),
(47, 'Backup', 0, 'index_backup.php', 'fas fa-database', 11),
(48, 'Notas de Débito', 0, 'notas_debito.php', 'fas fa-file-invoice', 8),
(49, 'Reporte Ingreso e E.', 10, 'reporte_diario.php', 'far fa-circle', 16),
(50, 'Estado de C. Cliente', 10, 'estado_cuenta_cliente.php', 'far fa-circle', 17),
(51, 'Reporte Mora', 10, 'reporte_mora.php', 'far fa-circle', 21),
(52, 'Reporte Cobro Diaria', 10, 'reporte_cobranza.php', 'far fa-circle', 22),
(53, 'Reporte C.Mora', 10, 'reporte_cuotas_atrasadas.php', 'far fa-circle', 23),
(54, 'Sucursales', 14, 'sucursales.php', 'fas fa-store', 15),
(55, 'Rutas', 14, 'rutas.php', 'fas fa-route', 16),
(58, 'Dashboard Cobradores', 59, 'dashboard_cobradores.php', 'fas fa-chart-pie', 2),
(59, 'Dashboards', NULL, NULL, 'fas fa-chart-pie', 0),
(61, 'Dashboards', 0, NULL, 'fas fa-chart-pie', 0),
(62, 'Grupos de Reportes', 14, 'grupos_reportes.php', 'fa fa-envelope', 15),
(65, 'Dashboard de Caja', 39, 'dashboard_caja.php', 'fas fa-tachometer-alt', 2),
(66, 'Configurar Sucursales', 39, 'configuracion_sucursales.php', 'fas fa-cogs', 4),
(76, 'Saldos Arrastrados', 10, 'reporte_saldos_arrastrados.php', 'far fa-circle', 25),
(77, 'Reporte Recuperación', 10, 'reporte_recuperacion.php', 'far fa-circle', 26),
(82, 'Reportes Financieros', 10, 'reportes_financieros.php', 'far fa-circle', 61),
(83, 'Dashboard Mejorado', 0, 'dashboard_mejorado.php', 'fas fa-chart-line', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modulos_backup_duplicados`
--

CREATE TABLE `modulos_backup_duplicados` (
  `id` int(11) NOT NULL DEFAULT 0,
  `modulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `padre_id` int(11) DEFAULT NULL,
  `vista` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `icon_menu` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `orden` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modulos_backup_duplicados`
--

INSERT INTO `modulos_backup_duplicados` (`id`, `modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) VALUES
(1, 'Dashboard Principal', 59, 'dashboard.php', 'fas fa-tachometer-alt', 1),
(10, 'Reportes', 0, '', 'fas fa-chart-line', 15),
(11, 'Empresa', 0, 'configuracion.php', 'fas fa-landmark', 9),
(12, 'Usuarios', 14, 'usuario.php', 'far fa-user', 13),
(13, 'Modulos y Perfiles', 14, 'modulos_perfiles.php', 'fas fa-tablet-alt', 14),
(14, 'Mantenimiento', 0, NULL, 'fas fa-cogs', 12),
(24, 'Clientes', 0, 'cliente.php', 'fas fa-id-card', 4),
(25, 'Moneda', 0, 'moneda.php', 'fas fa-dollar-sign', 10),
(29, 'Prestamos', 0, '', 'fas fa-landmark', 5),
(34, 'Solicitud/Prestamo', 29, 'prestamo.php', 'far fa-circle', 6),
(35, 'Listado Prestamos', 29, 'administrar_prestamos.php', 'far fa-circle', 7),
(36, 'Aprobar S/P', 29, 'aprobacion.php', 'far fa-circle', 8),
(37, 'Por Cliente', 10, 'reporte_cliente.php', 'far fa-circle', 16),
(38, 'Cuotas Pagadas', 10, 'reporte_cuotas_pagadas.php', 'far fa-circle', 17),
(39, 'Caja', 0, '', 'fas fa-cash-register', 1),
(40, 'Aperturar Caja', 39, 'caja.php', 'far fa-circle', 1),
(41, 'Ingresos / Egre', 39, 'ingresos.php', 'far fa-circle', 3),
(43, 'Pivot', 10, 'reportes.php', 'far fa-circle', 18),
(47, 'Backup', 0, 'index_backup.php', 'fas fa-database', 11),
(48, 'Notas de Débito', 0, 'notas_debito.php', 'fas fa-file-invoice', 8),
(49, 'Reporte Ingreso e E.', 10, 'reporte_diario.php', 'far fa-circle', 16),
(50, 'Estado de C. Cliente', 10, 'estado_cuenta_cliente.php', 'far fa-circle', 17),
(51, 'Reporte Mora', 10, 'reporte_mora.php', 'far fa-circle', 21),
(52, 'Reporte Cobro Diaria', 10, 'reporte_cobranza.php', 'far fa-circle', 22),
(53, 'Reporte C.Mora', 10, 'reporte_cuotas_atrasadas.php', 'far fa-circle', 23),
(54, 'Sucursales', 14, 'sucursales.php', 'fas fa-store', 15),
(55, 'Rutas', 14, 'rutas.php', 'fas fa-route', 16),
(56, 'Reportes Financieros', 10, 'reportes_financieros', 'far fa-circle', 19),
(58, 'Dashboard Cobradores', 59, 'dashboard_cobradores.php', 'fas fa-chart-pie', 2),
(59, 'Dashboards', NULL, NULL, 'fas fa-chart-pie', 0),
(60, 'Dashboard Cobradores', 0, 'dashboard_cobradores.php', 'fas fa-chart-pie', 1),
(61, 'Dashboards', 0, NULL, 'fas fa-chart-pie', 0),
(62, 'Grupos de Reportes', 14, 'grupos_reportes.php', 'fa fa-envelope', 15),
(65, 'Dashboard de Caja', 39, 'dashboard_caja.php', 'fas fa-tachometer-alt', 2),
(66, 'Configurar Sucursales', 39, 'configuracion_sucursales.php', 'fas fa-cogs', 4),
(67, 'Por Cliente', 10, 'reporte_cliente.php', 'far fa-circle', 16),
(68, 'Cuotas Pagadas', 10, 'reporte_cuotas_pagadas.php', 'far fa-circle', 17),
(69, 'Pivot', 10, 'reportes.php', 'far fa-circle', 18),
(70, 'Reporte Diario', 10, 'reporte_diario.php', 'far fa-circle', 19),
(71, 'Estado de C. Cliente', 10, 'estado_cuenta_cliente.php', 'far fa-circle', 20),
(72, 'Reporte Mora', 10, 'reporte_mora.php', 'far fa-circle', 21),
(73, 'Reporte Cobro Diaria', 10, 'reporte_cobranza.php', 'far fa-circle', 22),
(74, 'Reporte C.Mora', 10, 'reporte_cuotas_atrasadas.php', 'far fa-circle', 23),
(75, 'Reportes Financieros', 10, 'reportes_financieros.php', 'far fa-circle', 24),
(76, 'Saldos Arrastrados', 10, 'reporte_saldos_arrastrados.php', 'far fa-circle', 25),
(77, 'Reporte Recuperación', 10, 'reporte_recuperacion.php', 'far fa-circle', 26),
(78, 'Saldos Arrastrados', 10, 'reporte_saldos_arrastrados.php', 'far fa-circle', 24),
(79, 'Reporte Recuperación', 10, 'reporte_recuperacion.php', 'far fa-circle', 25),
(80, 'Reportes Financieros', 10, 'reportes_financieros.php', 'far fa-circle', 26),
(81, 'Grupos Reportes', 10, 'grupos_reportes.php', 'far fa-circle', 27);

-- --------------------------------------------------------

--
-- Table structure for table `moneda`
--

CREATE TABLE `moneda` (
  `moneda_id` int(11) NOT NULL,
  `moneda_nombre` varchar(10) DEFAULT NULL,
  `moneda_abrevia` varchar(10) DEFAULT NULL,
  `moneda_simbolo` varchar(10) DEFAULT NULL,
  `moneda_Descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `moneda`
--

INSERT INTO `moneda` (`moneda_id`, `moneda_nombre`, `moneda_abrevia`, `moneda_simbolo`, `moneda_Descripcion`) VALUES
(1, 'Cordobas', 'NIO', 'C$', 'Cordobas Nicaraguense'),
(2, 'Dolares', 'USD', '$', 'Dolar');

-- --------------------------------------------------------

--
-- Table structure for table `movimientos`
--

CREATE TABLE `movimientos` (
  `movimientos_id` int(11) NOT NULL,
  `movi_tipo` varchar(100) DEFAULT NULL,
  `movi_descripcion` varchar(255) DEFAULT NULL,
  `movi_monto` float DEFAULT NULL,
  `movi_fecha` datetime DEFAULT NULL,
  `movi_caja` varchar(100) DEFAULT NULL,
  `caja_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `movimientos`
--

INSERT INTO `movimientos` (`movimientos_id`, `movi_tipo`, `movi_descripcion`, `movi_monto`, `movi_fecha`, `movi_caja`, `caja_id`) VALUES
(2, 'INGRESO', 'ingreso extr', 2340, '2025-07-11 09:26:54', 'VIGENTE', 4),
(3, 'EGRESO', 'desembolso', 5000, '2025-07-11 09:27:23', 'VIGENTE', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notas_debito`
--

CREATE TABLE `notas_debito` (
  `id_nota_debito` int(11) NOT NULL,
  `nro_nota_debito` varchar(20) NOT NULL,
  `nro_prestamo` varchar(20) NOT NULL,
  `motivo` text NOT NULL,
  `interes_anterior` decimal(5,2) NOT NULL,
  `interes_nuevo` decimal(5,2) NOT NULL,
  `cuotas_anterior` int(11) NOT NULL,
  `cuotas_nuevas` int(11) NOT NULL,
  `cuota_anterior` decimal(10,2) NOT NULL,
  `cuota_nueva` decimal(10,2) NOT NULL,
  `saldo_capital` decimal(10,2) NOT NULL,
  `monto_interes_nuevo` decimal(10,2) NOT NULL,
  `monto_total_nuevo` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` varchar(20) DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perfiles`
--

CREATE TABLE `perfiles` (
  `id_perfil` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `perfiles`
--

INSERT INTO `perfiles` (`id_perfil`, `descripcion`, `estado`) VALUES
(1, 'Administrador', 1),
(2, 'Cobrador', 1),
(3, 'Caja', 1),
(4, 'Supervisor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `perfil_modulo`
--

CREATE TABLE `perfil_modulo` (
  `idperfil_modulo` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `vista_inicio` tinyint(4) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `perfil_modulo`
--

INSERT INTO `perfil_modulo` (`idperfil_modulo`, `id_perfil`, `id_modulo`, `vista_inicio`, `estado`) VALUES
(1, 1, 10, 0, 1),
(2, 1, 11, 0, 1),
(3, 1, 12, 0, 1),
(4, 1, 13, 0, 1),
(5, 1, 14, 0, 1),
(6, 1, 24, 0, 1),
(7, 1, 25, 0, 1),
(8, 1, 29, 0, 1),
(9, 1, 34, 0, 1),
(10, 1, 35, 0, 1),
(11, 1, 36, 0, 1),
(14, 1, 39, 0, 1),
(15, 1, 40, 0, 1),
(16, 1, 41, 0, 1),
(17, 1, 47, 0, 1),
(18, 1, 48, 0, 1),
(19, 1, 49, 0, 1),
(20, 1, 50, 0, 1),
(23, 1, 54, 0, 1),
(24, 1, 55, 0, 1),
(26, 1, 58, 0, 1),
(28, 2, 1, 0, 1),
(29, 2, 29, 0, 1),
(30, 2, 34, 1, 1),
(31, 2, 35, 0, 1),
(32, 2, 36, 0, 1),
(33, 2, 55, 0, 1),
(35, 2, 58, 0, 1),
(36, 2, 59, 0, 1),
(64, 1, 59, 1, 1),
(65, 1, 62, NULL, NULL),
(68, 1, 65, 0, 1),
(69, 2, 65, 0, 1),
(71, 1, 66, 0, NULL),
(72, 1, 37, 0, 1),
(73, 1, 38, 0, 1),
(74, 1, 43, 0, 1),
(77, 1, 51, 0, 1),
(78, 1, 52, 0, 1),
(79, 1, 53, 0, 1),
(81, 1, 76, 0, 1),
(82, 1, 77, 0, 1),
(87, 2, 49, 0, 1),
(88, 2, 50, 0, 1),
(89, 2, 37, 0, 1),
(90, 2, 38, 0, 1),
(139, 2, 52, 0, 1),
(157, 1, 1, 1, 1),
(166, 1, 82, 0, 1),
(167, 2, 82, 0, 1),
(168, 1, 83, 0, 1),
(169, 2, 83, 0, 1),
(170, 3, 39, 0, 1),
(171, 3, 40, 0, 1),
(172, 3, 65, 0, 1),
(173, 3, 41, 0, 1),
(174, 3, 66, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `perfil_modulo_backup_duplicados`
--

CREATE TABLE `perfil_modulo_backup_duplicados` (
  `idperfil_modulo` int(11) NOT NULL DEFAULT 0,
  `id_perfil` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `vista_inicio` tinyint(4) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perfil_modulo_backup_duplicados`
--

INSERT INTO `perfil_modulo_backup_duplicados` (`idperfil_modulo`, `id_perfil`, `id_modulo`, `vista_inicio`, `estado`) VALUES
(1, 1, 10, 0, 1),
(2, 1, 11, 0, 1),
(3, 1, 12, 0, 1),
(4, 1, 13, 0, 1),
(5, 1, 14, 0, 1),
(6, 1, 24, 0, 1),
(7, 1, 25, 0, 1),
(8, 1, 29, 0, 1),
(9, 1, 34, 0, 1),
(10, 1, 35, 0, 1),
(11, 1, 36, 0, 1),
(14, 1, 39, 0, 1),
(15, 1, 40, 0, 1),
(16, 1, 41, 0, 1),
(17, 1, 47, 0, 1),
(18, 1, 48, 0, 1),
(19, 1, 49, 0, 1),
(20, 1, 50, 0, 1),
(23, 1, 54, 0, 1),
(24, 1, 55, 0, 1),
(26, 1, 58, 0, 1),
(28, 2, 1, 0, 1),
(29, 2, 29, 0, 1),
(30, 2, 34, 1, 1),
(31, 2, 35, 0, 1),
(32, 2, 36, 0, 1),
(33, 2, 55, 0, 1),
(35, 2, 58, 0, 1),
(36, 2, 59, 0, 1),
(64, 1, 59, 1, 1),
(65, 1, 62, NULL, NULL),
(67, 1, 56, NULL, NULL),
(68, 1, 65, 0, 1),
(69, 2, 65, 0, 1),
(70, 1, 65, 0, NULL),
(71, 1, 66, 0, NULL),
(72, 1, 67, 0, 1),
(73, 1, 68, 0, 1),
(74, 1, 69, 0, 1),
(75, 1, 70, 0, 1),
(76, 1, 71, 0, 1),
(77, 1, 72, 0, 1),
(78, 1, 73, 0, 1),
(79, 1, 74, 0, 1),
(80, 1, 75, 0, 1),
(81, 1, 76, 0, 1),
(82, 1, 77, 0, 1),
(87, 2, 49, 0, 1),
(88, 2, 50, 0, 1),
(89, 2, 67, 0, 1),
(90, 2, 68, 0, 1),
(91, 2, 70, 0, 1),
(92, 2, 71, 0, 1),
(93, 2, 75, 0, 1),
(94, 1, 29, 0, 1),
(95, 1, 34, 0, 1),
(96, 1, 35, 0, 1),
(97, 1, 36, 0, 1),
(101, 1, 10, 0, 1),
(102, 1, 37, 0, 1),
(103, 1, 38, 0, 1),
(104, 1, 43, 0, 1),
(105, 1, 49, 0, 1),
(106, 1, 50, 0, 1),
(107, 1, 51, 0, 1),
(108, 1, 52, 0, 1),
(109, 1, 53, 0, 1),
(110, 1, 56, 0, 1),
(111, 1, 67, 0, 1),
(112, 1, 68, 0, 1),
(113, 1, 69, 0, 1),
(114, 1, 70, 0, 1),
(115, 1, 71, 0, 1),
(116, 1, 72, 0, 1),
(117, 1, 73, 0, 1),
(118, 1, 74, 0, 1),
(119, 1, 75, 0, 1),
(120, 1, 76, 0, 1),
(121, 1, 77, 0, 1),
(122, 1, 78, 0, 1),
(123, 1, 79, 0, 1),
(124, 1, 80, 0, 1),
(125, 1, 81, 0, 1),
(132, 2, 34, 0, 1),
(133, 2, 35, 0, 1),
(134, 2, 36, 0, 1),
(135, 2, 37, 0, 1),
(136, 2, 38, 0, 1),
(137, 2, 49, 0, 1),
(138, 2, 50, 0, 1),
(139, 2, 52, 0, 1),
(140, 2, 67, 0, 1),
(141, 2, 68, 0, 1),
(142, 2, 70, 0, 1),
(143, 2, 71, 0, 1),
(144, 2, 73, 0, 1),
(145, 2, 75, 0, 1),
(146, 2, 80, 0, 1),
(150, 1, 24, 0, 1),
(151, 1, 39, 0, 1),
(152, 1, 40, 0, 1),
(153, 1, 41, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `permisos_anulacion`
--

CREATE TABLE `permisos_anulacion` (
  `permiso_id` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL COMMENT 'ID del perfil de usuario',
  `tipo_documento` enum('pago','cuota','prestamo','contrato','nota_debito') NOT NULL,
  `puede_anular` tinyint(1) DEFAULT 0 COMMENT 'Si puede anular este tipo de documento',
  `requiere_justificacion` tinyint(1) DEFAULT 1 COMMENT 'Si requiere justificación',
  `limite_tiempo_horas` int(11) DEFAULT NULL COMMENT 'Límite de tiempo en horas para anular (NULL = sin límite)',
  `nivel_aprobacion` enum('propio','supervisor','administrador') DEFAULT 'propio' COMMENT 'Nivel de aprobación requerido',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Permisos de anulación por perfil de usuario';

--
-- Dumping data for table `permisos_anulacion`
--

INSERT INTO `permisos_anulacion` (`permiso_id`, `id_perfil`, `tipo_documento`, `puede_anular`, `requiere_justificacion`, `limite_tiempo_horas`, `nivel_aprobacion`, `fecha_creacion`, `activo`) VALUES
(1, 1, 'pago', 1, 1, NULL, 'propio', '2025-07-16 16:10:43', 1),
(2, 1, 'cuota', 1, 1, NULL, 'propio', '2025-07-16 16:10:43', 1),
(3, 1, 'prestamo', 1, 1, NULL, 'propio', '2025-07-16 16:10:43', 1),
(4, 1, 'contrato', 1, 1, NULL, 'propio', '2025-07-16 16:10:43', 1),
(5, 1, 'nota_debito', 1, 1, NULL, 'propio', '2025-07-16 16:10:43', 1),
(6, 2, 'pago', 0, 1, 24, 'administrador', '2025-07-16 16:10:43', 1),
(7, 2, 'cuota', 0, 1, 24, 'administrador', '2025-07-16 16:10:43', 1),
(8, 2, 'prestamo', 0, 1, 48, 'administrador', '2025-07-16 16:10:43', 1),
(9, 2, 'contrato', 0, 1, 48, 'administrador', '2025-07-16 16:10:43', 1),
(10, 2, 'nota_debito', 0, 1, NULL, 'administrador', '2025-07-16 16:10:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prestamo_cabecera`
--

CREATE TABLE `prestamo_cabecera` (
  `pres_id` int(11) NOT NULL,
  `nro_prestamo` varchar(8) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `pres_monto` float DEFAULT NULL,
  `pres_cuotas` char(10) DEFAULT NULL,
  `pres_interes` float DEFAULT NULL,
  `tipo_calculo_id` int(11) DEFAULT 1,
  `tipo_calculo` varchar(50) DEFAULT NULL COMMENT 'Tipo de cálculo del préstamo (ej. interes_sobre_saldo, cuota_fija)',
  `fpago_id` int(11) DEFAULT NULL,
  `moneda_id` int(11) DEFAULT NULL,
  `pres_f_emision` date DEFAULT NULL,
  `pres_monto_cuota` float DEFAULT NULL,
  `pres_monto_interes` float DEFAULT NULL,
  `pres_monto_total` float DEFAULT NULL,
  `pres_estado` varchar(255) DEFAULT NULL,
  `pres_estatus` char(10) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `pres_aprobacion` varchar(20) DEFAULT NULL,
  `pres_cuotas_pagadas` varchar(10) DEFAULT NULL,
  `pres_monto_restante` float DEFAULT NULL,
  `pres_cuotas_restante` varchar(10) DEFAULT NULL,
  `pres_fecha_registro` date DEFAULT NULL,
  `pres_estado_caja` varchar(50) DEFAULT NULL,
  `caja_id` int(11) DEFAULT NULL,
  `reimpreso_admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indica si el contrato ha sido reimpreso por un administrador (1=si, 0=no)',
  `sucursal_asignada_id` int(11) DEFAULT NULL COMMENT 'Sucursal asignada para cobranza',
  `ruta_asignada_id` int(11) DEFAULT NULL COMMENT 'Ruta asignada para cobranza',
  `cobrador_asignado_id` int(11) DEFAULT NULL COMMENT 'Usuario cobrador asignado',
  `fecha_asignacion` datetime DEFAULT NULL COMMENT 'Fecha de asignación de ruta y cobrador',
  `observaciones_asignacion` text DEFAULT NULL COMMENT 'Observaciones de la asignación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `prestamo_cabecera`
--

INSERT INTO `prestamo_cabecera` (`pres_id`, `nro_prestamo`, `cliente_id`, `pres_monto`, `pres_cuotas`, `pres_interes`, `tipo_calculo_id`, `tipo_calculo`, `fpago_id`, `moneda_id`, `pres_f_emision`, `pres_monto_cuota`, `pres_monto_interes`, `pres_monto_total`, `pres_estado`, `pres_estatus`, `id_usuario`, `pres_aprobacion`, `pres_cuotas_pagadas`, `pres_monto_restante`, `pres_cuotas_restante`, `pres_fecha_registro`, `pres_estado_caja`, `caja_id`, `reimpreso_admin`, `sucursal_asignada_id`, `ruta_asignada_id`, `cobrador_asignado_id`, `fecha_asignacion`, `observaciones_asignacion`) VALUES
(1, '00000001', 8, 20000, '12', 4.5, 1, 'FLAT', 4, 1, '2025-07-15', 1741.67, 900, 20900, 'Pendiente', '1', 1, 'aprobado', '2', 17417, '10', '2025-07-15', 'VIGENTE', 4, 0, 1, 1, 4, '2025-07-15 07:12:33', ''),
(2, '00000002', 10, 1245680, '12', 16, 1, 'FLAT', 2, 1, '2025-07-25', 107639, 45994.3, 1291670, 'Pendiente', '1', 1, 'aprobado', '1', 1184030, '11', '2025-07-18', 'VIGENTE', 4, 0, 1, 4, 4, '2025-07-18 11:25:37', ''),
(3, '00000003', 10, 10000, '6', 14, 1, 'FLAT', 3, 1, '2025-07-25', 1725, 350, 10350, 'Anulado', '1', 1, 'anulado', '0', NULL, '6', '2025-07-18', '', 4, 0, NULL, NULL, NULL, NULL, NULL);

--
-- Triggers `prestamo_cabecera`
--
DELIMITER $$
CREATE TRIGGER `tr_prestamo_anulado_audit` AFTER UPDATE ON `prestamo_cabecera` FOR EACH ROW BEGIN
    IF OLD.pres_aprobacion != 'anulado' AND NEW.pres_aprobacion = 'anulado' THEN
        -- Solo insertar si no existe ya un registro reciente (para evitar duplicados)
        IF NOT EXISTS (
            SELECT 1 FROM anulaciones_auditoria 
            WHERE tipo_documento = 'prestamo' 
            AND documento_id = NEW.nro_prestamo 
            AND fecha_anulacion >= DATE_SUB(NOW(), INTERVAL 1 MINUTE)
        ) THEN
            INSERT INTO anulaciones_auditoria (
                tipo_documento, documento_id, nro_prestamo, usuario_id, usuario_nombre,
                motivo_anulacion, datos_originales
            ) VALUES (
                'prestamo', NEW.nro_prestamo, NEW.nro_prestamo, 
                COALESCE(@USUARIO_ANULACION, 1), 
                COALESCE(@USUARIO_NOMBRE_ANULACION, 'Sistema'),
                COALESCE(@MOTIVO_ANULACION, 'Anulación desde sistema legacy'),
                JSON_OBJECT('monto_original', OLD.pres_monto, 'estado_anterior', OLD.pres_aprobacion)
            );
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `prestamo_detalle`
--

CREATE TABLE `prestamo_detalle` (
  `pdetalle_id` int(11) NOT NULL,
  `nro_prestamo` varchar(8) NOT NULL,
  `pdetalle_nro_cuota` varchar(8) NOT NULL,
  `pdetalle_monto_cuota` float DEFAULT NULL,
  `pdetalle_fecha` datetime DEFAULT NULL,
  `pdetalle_estado_cuota` varchar(100) DEFAULT NULL,
  `pdetalle_fecha_registro` timestamp NULL DEFAULT NULL,
  `pdetalle_saldo_cuota` float DEFAULT NULL,
  `pdetalle_cant_cuota_pagada` varchar(10) DEFAULT NULL,
  `pdetalle_liquidar` varchar(10) DEFAULT NULL,
  `pdetalle_monto_liquidar` varchar(10) DEFAULT NULL,
  `pdetalle_caja` varchar(255) DEFAULT NULL,
  `pdetalle_aprobacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `prestamo_detalle`
--

INSERT INTO `prestamo_detalle` (`pdetalle_id`, `nro_prestamo`, `pdetalle_nro_cuota`, `pdetalle_monto_cuota`, `pdetalle_fecha`, `pdetalle_estado_cuota`, `pdetalle_fecha_registro`, `pdetalle_saldo_cuota`, `pdetalle_cant_cuota_pagada`, `pdetalle_liquidar`, `pdetalle_monto_liquidar`, `pdetalle_caja`, `pdetalle_aprobacion`) VALUES
(1, '00000001', '1', 1741.67, '2025-07-15 00:00:00', 'pagada', '2025-07-15 13:13:04', 19158.3, '11', '0', NULL, 'VIGENTE', 'aprobado'),
(2, '00000001', '2', 1741.67, '2025-08-15 00:00:00', 'pagada', '2025-07-18 20:04:15', 17416.7, '10', '0', NULL, 'VIGENTE', 'aprobado'),
(3, '00000001', '3', 1741.67, '2025-09-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(4, '00000001', '4', 1741.67, '2025-10-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(5, '00000001', '5', 1741.67, '2025-11-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(6, '00000001', '6', 1741.67, '2025-12-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(7, '00000001', '7', 1741.67, '2026-01-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(8, '00000001', '8', 1741.67, '2026-02-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(9, '00000001', '9', 1741.67, '2026-03-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(10, '00000001', '10', 1741.67, '2026-04-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(11, '00000001', '11', 1741.67, '2026-05-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(12, '00000001', '12', 1741.67, '2026-06-15 00:00:00', 'pendiente', NULL, 1741.67, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(13, '00000002', '1', 107639, '2025-07-25 00:00:00', 'pagada', '2025-07-18 17:55:29', 1184030, '11', '0', NULL, 'VIGENTE', 'aprobado'),
(14, '00000002', '2', 107639, '2025-08-01 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(15, '00000002', '3', 107639, '2025-08-08 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(16, '00000002', '4', 107639, '2025-08-15 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(17, '00000002', '5', 107639, '2025-08-22 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(18, '00000002', '6', 107639, '2025-08-29 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(19, '00000002', '7', 107639, '2025-09-05 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(20, '00000002', '8', 107639, '2025-09-12 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(21, '00000002', '9', 107639, '2025-09-19 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(22, '00000002', '10', 107639, '2025-09-26 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(23, '00000002', '11', 107639, '2025-10-03 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(24, '00000002', '12', 107639, '2025-10-10 00:00:00', 'pendiente', NULL, 107639, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(25, '00000003', '1', 1725, '2025-07-25 00:00:00', 'Anulado', NULL, 1725, NULL, '0', NULL, '', 'anulado'),
(26, '00000003', '2', 1725, '2025-08-09 00:00:00', 'Anulado', NULL, 1725, NULL, '0', NULL, '', 'anulado'),
(27, '00000003', '3', 1725, '2025-08-24 00:00:00', 'Anulado', NULL, 1725, NULL, '0', NULL, '', 'anulado'),
(28, '00000003', '4', 1725, '2025-09-08 00:00:00', 'Anulado', NULL, 1725, NULL, '0', NULL, '', 'anulado'),
(29, '00000003', '5', 1725, '2025-09-23 00:00:00', 'Anulado', NULL, 1725, NULL, '0', NULL, '', 'anulado'),
(30, '00000003', '6', 1725, '2025-10-08 00:00:00', 'Anulado', NULL, 1725, NULL, '0', NULL, '', 'anulado');

--
-- Triggers `prestamo_detalle`
--
DELIMITER $$
CREATE TRIGGER `tg_can_cuotas_cabecera` AFTER UPDATE ON `prestamo_detalle` FOR EACH ROW BEGIN
DECLARE CUOTA INT;
SET CUOTA:=(select count(*) from prestamo_detalle where nro_prestamo = new.nro_prestamo and pdetalle_estado_cuota = 'pagada' );

        UPDATE prestamo_cabecera SET
	pres_cuotas_pagadas = CUOTA
	WHERE nro_prestamo = new.nro_prestamo;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_cuotas_restante` AFTER UPDATE ON `prestamo_detalle` FOR EACH ROW BEGIN
DECLARE CANTCUOTA INT;
SET CANTCUOTA:=(select count(pdetalle_estado_cuota) from prestamo_detalle where nro_prestamo = new.nro_prestamo AND pdetalle_estado_cuota = 'pagada');


  UPDATE prestamo_cabecera SET
	pres_cuotas_restante = pres_cuotas - CANTCUOTA
	WHERE nro_prestamo = new.nro_prestamo;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_monto_restante` AFTER UPDATE ON `prestamo_detalle` FOR EACH ROW BEGIN
DECLARE MONTO INT;
SET MONTO:=(select SUM(pdetalle_monto_cuota) from prestamo_detalle where nro_prestamo = new.nro_prestamo AND pdetalle_estado_cuota = 'pagada' );


  UPDATE prestamo_cabecera SET
	pres_monto_restante = pres_monto_total - MONTO
	WHERE nro_prestamo = new.nro_prestamo;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_montocuota_detalle` AFTER UPDATE ON `prestamo_detalle` FOR EACH ROW BEGIN

/*DECLARE MONTOTOTAL INT;
DECLARE MONTOCUOTA INT;

SET MONTOTOTAL:=(select pres_monto_total from prestamo_cabecera where nro_prestamo = prestamo);
SET MONTOCUOTA:=(select SUM(pdetalle_monto_cuota) from prestamo_detalle where nro_prestamo = prestamo AND pdetalle_estado_cuota = 'pagada' );


	UPDATE prestamo_detalle SET 
	 pdetalle_saldo_cuota = @MONTOTOTAL -  @MONTOCUOTA
	where nro_prestamo = new.nro_prestamo
  and pdetalle_nro_cuota = new.pdetalle_monto_cuota;*/


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `referencias`
--

CREATE TABLE `referencias` (
  `refe_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `refe_personal` varchar(255) DEFAULT NULL,
  `refe_cel_per` varchar(20) DEFAULT NULL,
  `refe_per_dir` varchar(255) DEFAULT NULL COMMENT 'Dirección de referencia personal',
  `refe_familiar` varchar(255) DEFAULT NULL,
  `refe_cel_fami` varchar(20) DEFAULT NULL,
  `refe_fami_dir` varchar(255) DEFAULT NULL COMMENT 'Dirección de referencia familiar',
  `refe_empresa_laboral` varchar(255) DEFAULT NULL,
  `refe_cargo_laboral` varchar(255) DEFAULT NULL,
  `refe_tel_laboral` varchar(50) DEFAULT NULL,
  `refe_dir_laboral` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `reporte_grupos`
--

CREATE TABLE `reporte_grupos` (
  `grupo_id` int(11) NOT NULL,
  `grupo_nombre` varchar(100) NOT NULL,
  `grupo_descripcion` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reporte_grupos`
--

INSERT INTO `reporte_grupos` (`grupo_id`, `grupo_nombre`, `grupo_descripcion`, `fecha_creacion`) VALUES
(1, 'Administración', 'Personal administrativo y gerencial', '2025-07-15 17:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `reporte_grupo_miembros`
--

CREATE TABLE `reporte_grupo_miembros` (
  `miembro_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `miembro_email` varchar(150) NOT NULL,
  `miembro_nombre` varchar(150) DEFAULT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reporte_grupo_miembros`
--

INSERT INTO `reporte_grupo_miembros` (`miembro_id`, `grupo_id`, `miembro_email`, `miembro_nombre`, `fecha_agregado`) VALUES
(1, 1, 'martharuiz9211@gmail.com', 'Martha Ruiz', '2025-07-15 17:55:03'),
(2, 1, 'ferchojoshua@gmail.com', 'Developer', '2025-07-15 17:55:23');

-- --------------------------------------------------------

--
-- Table structure for table `rol`
--

CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL,
  `nombre_rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `rol`
--

INSERT INTO `rol` (`rol_id`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Prestamista');

-- --------------------------------------------------------

--
-- Table structure for table `rutas`
--

CREATE TABLE `rutas` (
  `ruta_id` int(11) NOT NULL,
  `ruta_nombre` varchar(100) NOT NULL,
  `ruta_descripcion` text DEFAULT NULL,
  `ruta_codigo` varchar(20) NOT NULL,
  `ruta_color` varchar(7) DEFAULT '#3498db' COMMENT 'Color hexadecimal para identificar la ruta',
  `sucursal_id` int(11) NOT NULL,
  `ruta_estado` enum('activa','inactiva') DEFAULT 'activa',
  `ruta_orden` int(11) DEFAULT 0 COMMENT 'Orden de recorrido sugerido',
  `ruta_observaciones` text DEFAULT NULL,
  `usuario_creacion` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Tabla para gestionar rutas de cobranza';

--
-- Dumping data for table `rutas`
--

INSERT INTO `rutas` (`ruta_id`, `ruta_nombre`, `ruta_descripcion`, `ruta_codigo`, `ruta_color`, `sucursal_id`, `ruta_estado`, `ruta_orden`, `ruta_observaciones`, `usuario_creacion`, `fecha_creacion`, `usuario_modificacion`, `fecha_modificacion`) VALUES
(38, 'Barrio Zaragoza', 'Distrito Central', 'DC-ZARAGOZA', '#1e3a8a', 1, 'activa', 101, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(39, 'Barrio El Calvario', 'Distrito Central', 'DC-CALVARIO', '#1e40af', 1, 'activa', 102, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(40, 'Barrio El Coyolar', 'Distrito Central', 'DC-COYOLAR', '#1d4ed8', 1, 'activa', 103, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(41, 'Barrio El Laborío', 'Distrito Central', 'DC-LABORIO', '#2563eb', 1, 'activa', 104, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(42, 'Barrio El Sagrario', 'Distrito Central', 'DC-SAGRARIO', '#3b82f6', 1, 'activa', 105, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(43, 'Barrio San Juan', 'Distrito Central', 'DC-SANJUAN', '#60a5fa', 1, 'activa', 106, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(44, 'Barrio San Felipe', 'Distrito Central', 'DC-SANFELIPE', '#93c5fd', 1, 'activa', 107, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(45, 'Barrio San José', 'Distrito Central', 'DC-SANJOSE', '#bfdbfe', 1, 'activa', 108, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(46, 'Barrio San Sebastián', 'Distrito Central', 'DC-SEBASTIAN', '#dbeafe', 1, 'activa', 109, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(47, 'Colonia La de Mayo', 'Distrito Central', 'DC-LAMAYO', '#eff6ff', 1, 'activa', 110, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(48, 'Colonia La Fosforera', 'Distrito Central', 'DC-FOSFORERA', '#f8fafc', 1, 'activa', 111, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(49, 'Colonia Avellán', 'Distrito Central', 'DC-AVELLAN', '#f1f5f9', 1, 'activa', 112, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(50, 'H. y M. de Zaragoza', 'Distrito Central', 'DC-HMZARAGOZA', '#e2e8f0', 1, 'activa', 113, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(51, 'Colonia Santa Martha', 'Distrito Central', 'DC-SANTAMARTHA', '#cbd5e1', 1, 'activa', 114, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(52, 'H. y M. 26 de Abril', 'Distrito Central', 'DC-HM26ABRIL', '#94a3b8', 1, 'activa', 115, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(53, 'San Nicolás', 'Distrito Central', 'DC-SANNICOLAS', '#64748b', 1, 'activa', 116, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(54, 'Pedro José Avendaño', 'Distrito Central', 'DC-AVENDANO', '#475569', 1, 'activa', 117, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(55, 'Rogelio Santana', 'Distrito Central', 'DC-SANTANA', '#334155', 1, 'activa', 118, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(56, 'Andrés Zapata', 'Distrito Noreste', 'DN-ZAPATA', '#065f46', 1, 'activa', 201, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(57, 'Anexo Maritza López', 'Distrito Noreste', 'DN-MARITZA', '#047857', 1, 'activa', 202, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(58, 'Anexo Villa Soberana', 'Distrito Noreste', 'DN-VILLASOBERA', '#059669', 1, 'activa', 203, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(59, 'Aracely Pérez', 'Distrito Noreste', 'DN-ARACELY', '#0d9488', 1, 'activa', 204, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(60, 'Augusto César Sandino', 'Distrito Noreste', 'DN-SANDINO', '#0f766e', 1, 'activa', 205, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(61, 'Barrio Ermita de Dolores', 'Distrito Noreste', 'DN-ERMITA', '#115e59', 1, 'activa', 206, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(62, 'Bella Vista', 'Distrito Noreste', 'DN-BELLAVISTA', '#134e4a', 1, 'activa', 207, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(63, 'Benjamín Zeledón', 'Distrito Noreste', 'DN-ZELEDON', '#1f2937', 1, 'activa', 208, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(64, 'Colonia Farabundo Martí', 'Distrito Noreste', 'DN-FARABUNDO', '#166534', 1, 'activa', 209, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(65, 'Colonia Brenda Sofía', 'Distrito Noreste', 'DN-BRENDASOFIA', '#15803d', 1, 'activa', 210, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(66, 'El Platanal', 'Distrito Noreste', 'DN-PLATANAL', '#16a34a', 1, 'activa', 211, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(67, 'El Porvenir', 'Distrito Noreste', 'DN-PORVENIR', '#22c55e', 1, 'activa', 212, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(68, 'Enrique Lorente', 'Distrito Noreste', 'DN-LORENTE', '#4ade80', 1, 'activa', 213, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(69, 'Foyulesa', 'Distrito Noreste', 'DN-FOYULESA', '#6ade87', 1, 'activa', 214, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(70, 'Jericó', 'Distrito Noreste', 'DN-JERICO', '#86efac', 1, 'activa', 215, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(71, 'José Benito Escobar', 'Distrito Noreste', 'DN-ESCOBAR', '#a7f3d0', 1, 'activa', 216, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(72, 'José de la Cruz Mena', 'Distrito Noreste', 'DN-CRUZMENA', '#bbf7d0', 1, 'activa', 217, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(73, 'Linda Vista', 'Distrito Noreste', 'DN-LINDAVISTA', '#d1fae5', 1, 'activa', 218, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(74, 'Manolo Quezada', 'Distrito Noreste', 'DN-QUEZADA', '#ecfdf5', 1, 'activa', 219, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(75, 'Maritza López', 'Distrito Noreste', 'DN-MARITZALOP', '#f0fdf4', 1, 'activa', 220, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(76, '18 de Agosto', 'Distrito Sureste', 'DS-18AGOSTO', '#ea580c', 1, 'activa', 301, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(77, 'Alfonso Cortés', 'Distrito Sureste', 'DS-CORTES', '#dc2626', 1, 'activa', 302, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(78, 'Anexo Gustavo López', 'Distrito Sureste', 'DS-GUSTAVO', '#c2410c', 1, 'activa', 303, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(79, 'Arrocera I', 'Distrito Sureste', 'DS-ARROCERA1', '#b91c1c', 1, 'activa', 304, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(80, 'Arrocera II', 'Distrito Sureste', 'DS-ARROCERA2', '#991b1b', 1, 'activa', 305, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(81, 'Anexo Villa 23 de Julio', 'Distrito Sureste', 'DS-VILLA23', '#7f1d1d', 1, 'activa', 306, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(82, 'Azarías H. Pallais', 'Distrito Sureste', 'DS-PALLAIS', '#78716c', 1, 'activa', 307, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(83, 'Barrio Guadalupe', 'Distrito Sureste', 'DS-GUADALUPE', '#ef4444', 1, 'activa', 308, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(84, 'Benito Mauricio Lacayo', 'Distrito Sureste', 'DS-LACAYO', '#f87171', 1, 'activa', 309, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(85, 'Brisas de Acosasco', 'Distrito Sureste', 'DS-ACOSASCO', '#fca5a5', 1, 'activa', 310, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(86, 'Carlos Fonseca', 'Distrito Sureste', 'DS-FONSECA', '#fed7d7', 1, 'activa', 311, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(87, 'Candelaria', 'Distrito Sureste', 'DS-CANDELARIA', '#fee2e2', 1, 'activa', 312, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(88, 'Che Guevara', 'Distrito Sureste', 'DS-CHEGEVARA', '#fef2f2', 1, 'activa', 313, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(89, 'Colonia Universidad', 'Distrito Sureste', 'DS-UNIVERSIDAD', '#fb7185', 1, 'activa', 314, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(90, 'Concepción de María', 'Distrito Sureste', 'DS-CONCEPCION', '#f97316', 1, 'activa', 315, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(91, 'El Calvario', 'Distrito Sureste', 'DS-CALVARIO', '#fb923c', 1, 'activa', 316, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(92, 'El Cocal', 'Distrito Sureste', 'DS-COCAL', '#fdba74', 1, 'activa', 317, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(93, 'Emir Cabezas', 'Distrito Sureste', 'DS-CABEZAS', '#fed7aa', 1, 'activa', 318, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(94, 'Fundeci I', 'Distrito Sureste', 'DS-FUNDECI1', '#ffedd5', 1, 'activa', 319, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(95, 'Fundeci II', 'Distrito Sureste', 'DS-FUNDECI2', '#fff7ed', 1, 'activa', 320, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(96, 'Adiac I', 'Distrito Oeste', 'DO-ADIAC1', '#7c3aed', 1, 'activa', 401, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(97, 'Adiac II', 'Distrito Oeste', 'DO-ADIAC2', '#8b5cf6', 1, 'activa', 402, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(98, 'Adiac III', 'Distrito Oeste', 'DO-ADIAC3', '#a78bfa', 1, 'activa', 403, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(99, 'Anexo La Providencia', 'Distrito Oeste', 'DO-PROVIDENCIA', '#c4b5fd', 1, 'activa', 404, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(100, 'Barrio Sutiava', 'Distrito Oeste', 'DO-SUTIAVA', '#ddd6fe', 1, 'activa', 405, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(101, 'Belén', 'Distrito Oeste', 'DO-BELEN', '#ede9fe', 1, 'activa', 406, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(102, 'Bello Horizonte', 'Distrito Oeste', 'DO-HORIZONTE', '#f3f4f6', 1, 'activa', 407, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(103, 'Carlos Núñez', 'Distrito Oeste', 'DO-NUNEZ', '#6366f1', 1, 'activa', 408, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(104, 'Colonia Sonia Barrera', 'Distrito Oeste', 'DO-SONIABARRERA', '#818cf8', 1, 'activa', 409, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(105, 'Covista', 'Distrito Oeste', 'DO-COVISTA', '#a5b4fc', 1, 'activa', 410, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(106, 'Divino Niño', 'Distrito Oeste', 'DO-DIVININO', '#c7d2fe', 1, 'activa', 411, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(107, 'El Triángulo', 'Distrito Oeste', 'DO-TRIANGULO', '#e0e7ff', 1, 'activa', 412, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(108, 'Esfuerzo de la Comunidad', 'Distrito Oeste', 'DO-ESFUERZO', '#f0f4ff', 1, 'activa', 413, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(109, 'Fanor Urroz', 'Distrito Oeste', 'DO-URROZ', '#ec4899', 1, 'activa', 414, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(110, 'Félix Pedro Quiroz', 'Distrito Oeste', 'DO-QUIROZ', '#f472b6', 1, 'activa', 415, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(111, 'Felipe Santana', 'Distrito Oeste', 'DO-SANTANA', '#f9a8d4', 1, 'activa', 416, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(112, 'H. y M. Veracruz', 'Distrito Oeste', 'DO-VERACRUZ', '#fbbf24', 1, 'activa', 417, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(113, 'Hipólito Sánchez', 'Distrito Oeste', 'DO-SANCHEZ', '#f59e0b', 1, 'activa', 418, NULL, 1, '2025-07-18 12:56:17', NULL, NULL),
(114, 'Juan José Álvarez', 'Distrito Oeste', 'DO-ALVAREZ', '#d97706', 1, 'activa', 419, NULL, 1, '2025-07-18 12:56:17', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sucursales`
--

CREATE TABLE `sucursales` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL COMMENT 'Relación con empresa.confi_id',
  `nombre` varchar(100) NOT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  `codigo` varchar(20) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sucursales`
--

INSERT INTO `sucursales` (`id`, `empresa_id`, `nombre`, `direccion`, `telefono`, `estado`, `codigo`, `creado_por`, `fecha_creacion`) VALUES
(1, 1, 'Leon', 'ESA', '86595453', 'activa', 'LE001', 1, '2025-07-13 23:38:01'),
(2, 1, 'Chinandega', 'test', '86595453', 'activa', 'CH001', 1, '2025-07-16 21:48:07');

-- --------------------------------------------------------

--
-- Table structure for table `tipos_calculo_interes`
--

CREATE TABLE `tipos_calculo_interes` (
  `tipo_calculo_id` int(11) NOT NULL,
  `tipo_calculo_nombre` varchar(100) NOT NULL,
  `tipo_calculo_descripcion` text DEFAULT NULL,
  `tipo_calculo_estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tipos_calculo_interes`
--

INSERT INTO `tipos_calculo_interes` (`tipo_calculo_id`, `tipo_calculo_nombre`, `tipo_calculo_descripcion`, `tipo_calculo_estado`) VALUES
(1, 'ALEMAN', 'Sistema Alemán - Amortización fija con cuota e interés decreciente.', 0),
(2, 'AMERICANO', 'Sistema Americano - Pago de intereses y capital al final.', 0),
(3, 'FRANCES', 'Sistema Francés - Cuota fija con amortización creciente.', 1),
(4, 'SIMPLE', 'Sistema Simple - Cuota, amortización e interés fijos.', 0),
(5, 'COMPUESTO', 'Sistema Compuesto - Interés sobre interés con cuotas variables.', 0),
(6, 'FLAT', 'Sistema de amortización flat', 1),
(7, 'SOBRE SALDO', 'Sistema de amortización sobre saldo', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `sucursal_id` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(100) DEFAULT NULL,
  `apellido_usuario` varchar(100) DEFAULT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` text NOT NULL,
  `id_perfil_usuario` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `sucursal_id`, `nombre_usuario`, `apellido_usuario`, `usuario`, `clave`, `id_perfil_usuario`, `estado`) VALUES
(1, NULL, 'Gunner', 'User', 'Gunner', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 1, 1),
(2, 11, 'Eurania ', 'Alvarez', 'Eurania ', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 1, 1),
(3, 1, 'Pablo ', 'Mendoza', 'Pablo', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 2, 1),
(4, 1, 'Luis', 'Vanegas', 'Luis1', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_rutas`
--

CREATE TABLE `usuarios_rutas` (
  `usuario_ruta_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `ruta_id` int(11) NOT NULL,
  `tipo_asignacion` enum('responsable','apoyo') DEFAULT 'responsable',
  `fecha_asignacion` datetime DEFAULT current_timestamp(),
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `observaciones` text DEFAULT NULL,
  `usuario_asignacion` int(11) NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Asignación de usuarios (cobradores) a rutas';

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_anulaciones_auditoria_completa`
-- (See below for the actual view)
--
CREATE TABLE `v_anulaciones_auditoria_completa` (
`anulacion_id` int(11)
,`tipo_documento` enum('pago','cuota','prestamo','contrato','nota_debito')
,`documento_id` varchar(50)
,`nro_prestamo` varchar(8)
,`usuario_nombre` varchar(255)
,`motivo_anulacion` text
,`fecha_anulacion` datetime
,`estado` enum('activa','revertida')
,`sucursal_nombre` varchar(100)
,`sucursal_codigo` varchar(20)
,`perfil_usuario` varchar(45)
,`periodo_anulacion` varchar(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_auditoria_caja_detallada`
-- (See below for the actual view)
--
CREATE TABLE `v_auditoria_caja_detallada` (
`auditoria_id` int(11)
,`caja_id` int(11)
,`caja_descripcion` varchar(100)
,`accion` enum('APERTURA','CIERRE','MOVIMIENTO','CONSULTA','MODIFICACION','AUTORIZACION')
,`descripcion` text
,`nombre_usuario` varchar(100)
,`apellido_usuario` varchar(100)
,`monto_involucrado` decimal(15,2)
,`resultado` enum('EXITOSO','FALLIDO','PENDIENTE')
,`fecha_registro` datetime
,`ip_address` varchar(45)
,`sucursal_nombre` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_cobros_ruta`
-- (See below for the actual view)
--
CREATE TABLE `v_cobros_ruta` (
`ruta_id` int(11)
,`ruta_nombre` varchar(100)
,`ruta_codigo` varchar(20)
,`total_clientes` bigint(21)
,`prestamos_activos` bigint(21)
,`total_por_cobrar` double
,`cuotas_vencidas` bigint(21)
,`cobrador_asignado` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_estadisticas_barrios_leon`
-- (See below for the actual view)
--
CREATE TABLE `v_estadisticas_barrios_leon` (
`ruta_id` int(11)
,`ruta_codigo` varchar(20)
,`barrio_nombre` varchar(100)
,`ruta_descripcion` text
,`ruta_color` varchar(7)
,`distrito` varchar(7)
,`total_clientes` bigint(21)
,`cobradores_asignados` bigint(21)
,`sucursal_nombre` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_estado_cajas`
-- (See below for the actual view)
--
CREATE TABLE `v_estado_cajas` (
`caja_id` int(11)
,`caja_descripcion` varchar(100)
,`caja_estado` varchar(50)
,`caja_monto_inicial` float
,`caja_monto_total` float
,`caja_f_apertura` date
,`caja_f_cierre` date
,`caja_hora_apertura` time
,`caja_hora_cierre` time
,`usuario_apertura_nombre` varchar(100)
,`usuario_cierre_nombre` varchar(100)
,`nombre_caja_sucursal` varchar(100)
,`sucursal_nombre` varchar(100)
,`horas_abierta` bigint(21)
,`alertas_pendientes` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_permisos_anulacion_usuarios`
-- (See below for the actual view)
--
CREATE TABLE `v_permisos_anulacion_usuarios` (
`id_usuario` int(11)
,`nombre_usuario` varchar(100)
,`apellido_usuario` varchar(100)
,`perfil_nombre` varchar(45)
,`tipo_documento` enum('pago','cuota','prestamo','contrato','nota_debito')
,`puede_anular` tinyint(1)
,`requiere_justificacion` tinyint(1)
,`limite_tiempo_horas` int(11)
,`nivel_aprobacion` enum('propio','supervisor','administrador')
,`sucursal_nombre` varchar(100)
,`sucursal_codigo` varchar(20)
);

-- --------------------------------------------------------

--
-- Structure for view `v_anulaciones_auditoria_completa`
--
DROP TABLE IF EXISTS `v_anulaciones_auditoria_completa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_anulaciones_auditoria_completa`  AS SELECT `aa`.`anulacion_id` AS `anulacion_id`, `aa`.`tipo_documento` AS `tipo_documento`, `aa`.`documento_id` AS `documento_id`, `aa`.`nro_prestamo` AS `nro_prestamo`, `aa`.`usuario_nombre` AS `usuario_nombre`, `aa`.`motivo_anulacion` AS `motivo_anulacion`, `aa`.`fecha_anulacion` AS `fecha_anulacion`, `aa`.`estado` AS `estado`, `s`.`nombre` AS `sucursal_nombre`, `s`.`codigo` AS `sucursal_codigo`, `p`.`descripcion` AS `perfil_usuario`, CASE WHEN `aa`.`fecha_anulacion` >= current_timestamp() - interval 24 hour THEN 'Reciente' WHEN `aa`.`fecha_anulacion` >= current_timestamp() - interval 7 day THEN 'Esta semana' WHEN `aa`.`fecha_anulacion` >= current_timestamp() - interval 30 day THEN 'Este mes' ELSE 'Anterior' END AS `periodo_anulacion` FROM (((`anulaciones_auditoria` `aa` left join `sucursales` `s` on(`aa`.`sucursal_id` = `s`.`id`)) left join `usuarios` `u` on(`aa`.`usuario_id` = `u`.`id_usuario`)) left join `perfiles` `p` on(`u`.`id_perfil_usuario` = `p`.`id_perfil`)) ORDER BY `aa`.`fecha_anulacion` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_auditoria_caja_detallada`
--
DROP TABLE IF EXISTS `v_auditoria_caja_detallada`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_auditoria_caja_detallada`  AS SELECT `ca`.`auditoria_id` AS `auditoria_id`, `ca`.`caja_id` AS `caja_id`, `c`.`caja_descripcion` AS `caja_descripcion`, `ca`.`accion` AS `accion`, `ca`.`descripcion` AS `descripcion`, `u`.`nombre_usuario` AS `nombre_usuario`, `u`.`apellido_usuario` AS `apellido_usuario`, `ca`.`monto_involucrado` AS `monto_involucrado`, `ca`.`resultado` AS `resultado`, `ca`.`fecha_registro` AS `fecha_registro`, `ca`.`ip_address` AS `ip_address`, `s`.`nombre` AS `sucursal_nombre` FROM ((((`caja_auditoria` `ca` join `usuarios` `u` on(`ca`.`id_usuario` = `u`.`id_usuario`)) join `caja` `c` on(`ca`.`caja_id` = `c`.`caja_id`)) left join `cajas_sucursales` `cs` on(`c`.`caja_sucursal_id` = `cs`.`caja_sucursal_id`)) left join `sucursales` `s` on(`cs`.`sucursal_id` = `s`.`id`)) ORDER BY `ca`.`fecha_registro` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_cobros_ruta`
--
DROP TABLE IF EXISTS `v_cobros_ruta`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_cobros_ruta`  AS SELECT `r`.`ruta_id` AS `ruta_id`, `r`.`ruta_nombre` AS `ruta_nombre`, `r`.`ruta_codigo` AS `ruta_codigo`, count(distinct `cr`.`cliente_id`) AS `total_clientes`, count(distinct `pc`.`nro_prestamo`) AS `prestamos_activos`, sum(case when `pd`.`pdetalle_estado_cuota` = 'pendiente' then `pd`.`pdetalle_saldo_cuota` else 0 end) AS `total_por_cobrar`, count(case when `pd`.`pdetalle_estado_cuota` = 'pendiente' and `pd`.`pdetalle_fecha` < curdate() then 1 end) AS `cuotas_vencidas`, `u`.`usuario` AS `cobrador_asignado` FROM (((((`rutas` `r` left join `clientes_rutas` `cr` on(`r`.`ruta_id` = `cr`.`ruta_id`)) left join `usuarios_rutas` `ur` on(`r`.`ruta_id` = `ur`.`ruta_id` and `ur`.`estado` = 'activo')) left join `usuarios` `u` on(`ur`.`usuario_id` = `u`.`id_usuario`)) left join `prestamo_cabecera` `pc` on(`cr`.`cliente_id` = `pc`.`cliente_id` and `pc`.`pres_estado` = 'VIGENTE')) left join `prestamo_detalle` `pd` on(`pc`.`nro_prestamo` = `pd`.`nro_prestamo`)) GROUP BY `r`.`ruta_id`, `r`.`ruta_nombre`, `r`.`ruta_codigo`, `u`.`usuario` ;

-- --------------------------------------------------------

--
-- Structure for view `v_estadisticas_barrios_leon`
--
DROP TABLE IF EXISTS `v_estadisticas_barrios_leon`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_estadisticas_barrios_leon`  AS SELECT `r`.`ruta_id` AS `ruta_id`, `r`.`ruta_codigo` AS `ruta_codigo`, `r`.`ruta_nombre` AS `barrio_nombre`, `r`.`ruta_descripcion` AS `ruta_descripcion`, `r`.`ruta_color` AS `ruta_color`, CASE WHEN `r`.`ruta_codigo` like 'DC-%' THEN 'CENTRAL' WHEN `r`.`ruta_codigo` like 'DN-%' THEN 'NORESTE' WHEN `r`.`ruta_codigo` like 'DS-%' THEN 'SURESTE' WHEN `r`.`ruta_codigo` like 'DO-%' THEN 'OESTE' ELSE 'OTROS' END AS `distrito`, count(distinct `cr`.`cliente_id`) AS `total_clientes`, count(distinct `ur`.`usuario_id`) AS `cobradores_asignados`, `s`.`nombre` AS `sucursal_nombre` FROM (((`rutas` `r` join `sucursales` `s` on(`r`.`sucursal_id` = `s`.`id`)) left join `clientes_rutas` `cr` on(`r`.`ruta_id` = `cr`.`ruta_id` and `cr`.`estado` = 'activo')) left join `usuarios_rutas` `ur` on(`r`.`ruta_id` = `ur`.`ruta_id` and `ur`.`estado` = 'activo')) WHERE `r`.`ruta_codigo` like 'D%-%' GROUP BY `r`.`ruta_id`, `r`.`ruta_codigo`, `r`.`ruta_nombre`, `r`.`ruta_descripcion`, `r`.`ruta_color`, `s`.`nombre` ORDER BY `r`.`ruta_orden` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_estado_cajas`
--
DROP TABLE IF EXISTS `v_estado_cajas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_estado_cajas`  AS SELECT `c`.`caja_id` AS `caja_id`, `c`.`caja_descripcion` AS `caja_descripcion`, `c`.`caja_estado` AS `caja_estado`, `c`.`caja_monto_inicial` AS `caja_monto_inicial`, `c`.`caja_monto_total` AS `caja_monto_total`, `c`.`caja_f_apertura` AS `caja_f_apertura`, `c`.`caja_f_cierre` AS `caja_f_cierre`, `c`.`caja_hora_apertura` AS `caja_hora_apertura`, `c`.`caja_hora_cierre` AS `caja_hora_cierre`, `ua`.`nombre_usuario` AS `usuario_apertura_nombre`, `uc`.`nombre_usuario` AS `usuario_cierre_nombre`, `cs`.`nombre_caja` AS `nombre_caja_sucursal`, `s`.`nombre` AS `sucursal_nombre`, CASE WHEN `c`.`caja_estado` = 'VIGENTE' THEN timestampdiff(HOUR,timestamp(`c`.`caja_f_apertura`,`c`.`caja_hora_apertura`),current_timestamp()) ELSE NULL END AS `horas_abierta`, (select count(0) from `caja_alertas` `ca` where `ca`.`caja_id` = `c`.`caja_id` and `ca`.`estado` = 'pendiente') AS `alertas_pendientes` FROM ((((`caja` `c` left join `usuarios` `ua` on(`c`.`usuario_apertura` = `ua`.`id_usuario`)) left join `usuarios` `uc` on(`c`.`usuario_cierre` = `uc`.`id_usuario`)) left join `cajas_sucursales` `cs` on(`c`.`caja_sucursal_id` = `cs`.`caja_sucursal_id`)) left join `sucursales` `s` on(`cs`.`sucursal_id` = `s`.`id`)) ORDER BY `c`.`caja_f_apertura` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_permisos_anulacion_usuarios`
--
DROP TABLE IF EXISTS `v_permisos_anulacion_usuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_permisos_anulacion_usuarios`  AS SELECT `u`.`id_usuario` AS `id_usuario`, `u`.`nombre_usuario` AS `nombre_usuario`, `u`.`apellido_usuario` AS `apellido_usuario`, `p`.`descripcion` AS `perfil_nombre`, `pa`.`tipo_documento` AS `tipo_documento`, `pa`.`puede_anular` AS `puede_anular`, `pa`.`requiere_justificacion` AS `requiere_justificacion`, `pa`.`limite_tiempo_horas` AS `limite_tiempo_horas`, `pa`.`nivel_aprobacion` AS `nivel_aprobacion`, `s`.`nombre` AS `sucursal_nombre`, `s`.`codigo` AS `sucursal_codigo` FROM (((`usuarios` `u` join `perfiles` `p` on(`u`.`id_perfil_usuario` = `p`.`id_perfil`)) left join `permisos_anulacion` `pa` on(`p`.`id_perfil` = `pa`.`id_perfil` and `pa`.`activo` = 1)) left join `sucursales` `s` on(`u`.`sucursal_id` = `s`.`id`)) WHERE `u`.`estado` = 1 ORDER BY `u`.`nombre_usuario` ASC, `pa`.`tipo_documento` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anulaciones_auditoria`
--
ALTER TABLE `anulaciones_auditoria`
  ADD PRIMARY KEY (`anulacion_id`),
  ADD KEY `idx_tipo_documento` (`tipo_documento`),
  ADD KEY `idx_documento_id` (`documento_id`),
  ADD KEY `idx_prestamo` (`nro_prestamo`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_fecha` (`fecha_anulacion`),
  ADD KEY `idx_sucursal` (`sucursal_id`);

--
-- Indexes for table `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`caja_id`) USING BTREE,
  ADD KEY `idx_sucursal_caja` (`caja_sucursal_id`),
  ADD KEY `idx_usuario_apertura` (`usuario_apertura`),
  ADD KEY `idx_usuario_cierre` (`usuario_cierre`),
  ADD KEY `idx_estado_fecha` (`caja_estado`,`caja_f_apertura`);

--
-- Indexes for table `cajas_sucursales`
--
ALTER TABLE `cajas_sucursales`
  ADD PRIMARY KEY (`caja_sucursal_id`),
  ADD UNIQUE KEY `uk_codigo_caja` (`codigo_caja`),
  ADD UNIQUE KEY `uk_sucursal_nombre` (`sucursal_id`,`nombre_caja`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_tipo` (`tipo_caja`),
  ADD KEY `fk_caja_responsable` (`usuario_responsable`);

--
-- Indexes for table `caja_alertas`
--
ALTER TABLE `caja_alertas`
  ADD PRIMARY KEY (`alerta_id`),
  ADD KEY `idx_caja` (`caja_id`),
  ADD KEY `idx_tipo` (`tipo_alerta`),
  ADD KEY `idx_nivel` (`nivel_criticidad`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_fecha` (`fecha_generacion`),
  ADD KEY `fk_alerta_usuario` (`usuario_notificado`);

--
-- Indexes for table `caja_auditoria`
--
ALTER TABLE `caja_auditoria`
  ADD PRIMARY KEY (`auditoria_id`),
  ADD KEY `idx_caja` (`caja_id`),
  ADD KEY `idx_usuario` (`id_usuario`),
  ADD KEY `idx_accion` (`accion`),
  ADD KEY `idx_fecha` (`fecha_registro`),
  ADD KEY `idx_resultado` (`resultado`);

--
-- Indexes for table `caja_conteos_fisicos`
--
ALTER TABLE `caja_conteos_fisicos`
  ADD PRIMARY KEY (`conteo_id`),
  ADD KEY `idx_caja` (`caja_id`),
  ADD KEY `idx_usuario` (`usuario_conteo`),
  ADD KEY `idx_tipo` (`tipo_conteo`),
  ADD KEY `idx_fecha` (`fecha_conteo`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `fk_conteo_supervisor` (`supervisor_validacion`);

--
-- Indexes for table `caja_permisos`
--
ALTER TABLE `caja_permisos`
  ADD PRIMARY KEY (`permiso_id`),
  ADD UNIQUE KEY `uk_usuario_permisos` (`id_usuario`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indexes for table `caja_permisos_basico`
--
ALTER TABLE `caja_permisos_basico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuario` (`usuario_id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_id`) USING BTREE;

--
-- Indexes for table `clientes_rutas`
--
ALTER TABLE `clientes_rutas`
  ADD PRIMARY KEY (`cliente_ruta_id`),
  ADD UNIQUE KEY `uk_cliente_ruta` (`cliente_id`,`ruta_id`),
  ADD KEY `idx_ruta` (`ruta_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_orden` (`orden_visita`);

--
-- Indexes for table `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`confi_id`) USING BTREE;

--
-- Indexes for table `forma_pago`
--
ALTER TABLE `forma_pago`
  ADD PRIMARY KEY (`fpago_id`) USING BTREE;

--
-- Indexes for table `log_saldos_arrastrados`
--
ALTER TABLE `log_saldos_arrastrados`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `moneda`
--
ALTER TABLE `moneda`
  ADD PRIMARY KEY (`moneda_id`) USING BTREE;

--
-- Indexes for table `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`movimientos_id`) USING BTREE,
  ADD KEY `caja_id` (`caja_id`) USING BTREE;

--
-- Indexes for table `notas_debito`
--
ALTER TABLE `notas_debito`
  ADD PRIMARY KEY (`id_nota_debito`),
  ADD UNIQUE KEY `nro_nota_debito` (`nro_nota_debito`),
  ADD KEY `nro_prestamo` (`nro_prestamo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id_perfil`) USING BTREE;

--
-- Indexes for table `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  ADD PRIMARY KEY (`idperfil_modulo`) USING BTREE,
  ADD KEY `id_perfil` (`id_perfil`) USING BTREE,
  ADD KEY `id_modulo` (`id_modulo`) USING BTREE;

--
-- Indexes for table `permisos_anulacion`
--
ALTER TABLE `permisos_anulacion`
  ADD PRIMARY KEY (`permiso_id`),
  ADD UNIQUE KEY `uk_perfil_tipo` (`id_perfil`,`tipo_documento`),
  ADD KEY `idx_perfil` (`id_perfil`),
  ADD KEY `idx_tipo` (`tipo_documento`);

--
-- Indexes for table `prestamo_cabecera`
--
ALTER TABLE `prestamo_cabecera`
  ADD PRIMARY KEY (`pres_id`,`nro_prestamo`) USING BTREE,
  ADD KEY `cliente_id` (`cliente_id`) USING BTREE,
  ADD KEY `fpago_id` (`fpago_id`) USING BTREE,
  ADD KEY `moneda_id` (`moneda_id`) USING BTREE,
  ADD KEY `nro_prestamo` (`nro_prestamo`) USING BTREE,
  ADD KEY `caja_id` (`caja_id`) USING BTREE,
  ADD KEY `fk_tipo_calculo` (`tipo_calculo_id`),
  ADD KEY `idx_estado_cliente` (`cliente_id`,`pres_estado`),
  ADD KEY `idx_sucursal_asignada` (`sucursal_asignada_id`),
  ADD KEY `idx_ruta_asignada` (`ruta_asignada_id`),
  ADD KEY `idx_cobrador_asignado` (`cobrador_asignado_id`),
  ADD KEY `idx_fecha_asignacion` (`fecha_asignacion`);

--
-- Indexes for table `prestamo_detalle`
--
ALTER TABLE `prestamo_detalle`
  ADD PRIMARY KEY (`pdetalle_id`,`nro_prestamo`) USING BTREE,
  ADD KEY `nro_prestamo` (`nro_prestamo`) USING BTREE,
  ADD KEY `idx_estado_fecha` (`pdetalle_estado_cuota`,`pdetalle_fecha`);

--
-- Indexes for table `referencias`
--
ALTER TABLE `referencias`
  ADD PRIMARY KEY (`refe_id`) USING BTREE,
  ADD KEY `cliente_id` (`cliente_id`) USING BTREE;

--
-- Indexes for table `reporte_grupos`
--
ALTER TABLE `reporte_grupos`
  ADD PRIMARY KEY (`grupo_id`),
  ADD UNIQUE KEY `grupo_nombre` (`grupo_nombre`);

--
-- Indexes for table `reporte_grupo_miembros`
--
ALTER TABLE `reporte_grupo_miembros`
  ADD PRIMARY KEY (`miembro_id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- Indexes for table `rutas`
--
ALTER TABLE `rutas`
  ADD PRIMARY KEY (`ruta_id`),
  ADD UNIQUE KEY `uk_ruta_codigo_sucursal` (`ruta_codigo`,`sucursal_id`),
  ADD KEY `idx_sucursal` (`sucursal_id`),
  ADD KEY `idx_estado` (`ruta_estado`);

--
-- Indexes for table `sucursales`
--
ALTER TABLE `sucursales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `fk_sucursal_empresa` (`empresa_id`);

--
-- Indexes for table `tipos_calculo_interes`
--
ALTER TABLE `tipos_calculo_interes`
  ADD PRIMARY KEY (`tipo_calculo_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`) USING BTREE,
  ADD KEY `id_perfil_usuario` (`id_perfil_usuario`) USING BTREE,
  ADD KEY `fk_usuario_sucursal` (`sucursal_id`);

--
-- Indexes for table `usuarios_rutas`
--
ALTER TABLE `usuarios_rutas`
  ADD PRIMARY KEY (`usuario_ruta_id`),
  ADD UNIQUE KEY `uk_usuario_ruta_activo` (`usuario_id`,`ruta_id`,`estado`),
  ADD KEY `idx_ruta` (`ruta_id`),
  ADD KEY `idx_tipo` (`tipo_asignacion`),
  ADD KEY `idx_estado` (`estado`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anulaciones_auditoria`
--
ALTER TABLE `anulaciones_auditoria`
  MODIFY `anulacion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `caja`
--
ALTER TABLE `caja`
  MODIFY `caja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cajas_sucursales`
--
ALTER TABLE `cajas_sucursales`
  MODIFY `caja_sucursal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `caja_alertas`
--
ALTER TABLE `caja_alertas`
  MODIFY `alerta_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caja_auditoria`
--
ALTER TABLE `caja_auditoria`
  MODIFY `auditoria_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caja_conteos_fisicos`
--
ALTER TABLE `caja_conteos_fisicos`
  MODIFY `conteo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caja_permisos`
--
ALTER TABLE `caja_permisos`
  MODIFY `permiso_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `caja_permisos_basico`
--
ALTER TABLE `caja_permisos_basico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `clientes_rutas`
--
ALTER TABLE `clientes_rutas`
  MODIFY `cliente_ruta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `empresa`
--
ALTER TABLE `empresa`
  MODIFY `confi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forma_pago`
--
ALTER TABLE `forma_pago`
  MODIFY `fpago_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `log_saldos_arrastrados`
--
ALTER TABLE `log_saldos_arrastrados`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `moneda`
--
ALTER TABLE `moneda`
  MODIFY `moneda_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `movimientos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notas_debito`
--
ALTER TABLE `notas_debito`
  MODIFY `id_nota_debito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  MODIFY `idperfil_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `permisos_anulacion`
--
ALTER TABLE `permisos_anulacion`
  MODIFY `permiso_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `prestamo_cabecera`
--
ALTER TABLE `prestamo_cabecera`
  MODIFY `pres_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prestamo_detalle`
--
ALTER TABLE `prestamo_detalle`
  MODIFY `pdetalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `referencias`
--
ALTER TABLE `referencias`
  MODIFY `refe_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reporte_grupos`
--
ALTER TABLE `reporte_grupos`
  MODIFY `grupo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reporte_grupo_miembros`
--
ALTER TABLE `reporte_grupo_miembros`
  MODIFY `miembro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rutas`
--
ALTER TABLE `rutas`
  MODIFY `ruta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `sucursales`
--
ALTER TABLE `sucursales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tipos_calculo_interes`
--
ALTER TABLE `tipos_calculo_interes`
  MODIFY `tipo_calculo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuarios_rutas`
--
ALTER TABLE `usuarios_rutas`
  MODIFY `usuario_ruta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `caja`
--
ALTER TABLE `caja`
  ADD CONSTRAINT `fk_caja_sucursal_config` FOREIGN KEY (`caja_sucursal_id`) REFERENCES `cajas_sucursales` (`caja_sucursal_id`);

--
-- Constraints for table `cajas_sucursales`
--
ALTER TABLE `cajas_sucursales`
  ADD CONSTRAINT `fk_caja_responsable` FOREIGN KEY (`usuario_responsable`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_caja_sucursal` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`);

--
-- Constraints for table `caja_alertas`
--
ALTER TABLE `caja_alertas`
  ADD CONSTRAINT `fk_alerta_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`),
  ADD CONSTRAINT `fk_alerta_usuario` FOREIGN KEY (`usuario_notificado`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `caja_auditoria`
--
ALTER TABLE `caja_auditoria`
  ADD CONSTRAINT `fk_auditoria_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`),
  ADD CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `caja_conteos_fisicos`
--
ALTER TABLE `caja_conteos_fisicos`
  ADD CONSTRAINT `fk_conteo_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`),
  ADD CONSTRAINT `fk_conteo_supervisor` FOREIGN KEY (`supervisor_validacion`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_conteo_usuario` FOREIGN KEY (`usuario_conteo`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `caja_permisos`
--
ALTER TABLE `caja_permisos`
  ADD CONSTRAINT `fk_caja_permisos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `clientes_rutas`
--
ALTER TABLE `clientes_rutas`
  ADD CONSTRAINT `fk_clientes_rutas_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_clientes_rutas_ruta` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`ruta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`);

--
-- Constraints for table `notas_debito`
--
ALTER TABLE `notas_debito`
  ADD CONSTRAINT `fk_notas_debito_prestamo` FOREIGN KEY (`nro_prestamo`) REFERENCES `prestamo_cabecera` (`nro_prestamo`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notas_debito_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  ADD CONSTRAINT `perfil_modulo_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id_perfil`),
  ADD CONSTRAINT `perfil_modulo_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`);

--
-- Constraints for table `prestamo_cabecera`
--
ALTER TABLE `prestamo_cabecera`
  ADD CONSTRAINT `fk_tipo_calculo` FOREIGN KEY (`tipo_calculo_id`) REFERENCES `tipos_calculo_interes` (`tipo_calculo_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamo_cabecera_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`),
  ADD CONSTRAINT `prestamo_cabecera_ibfk_2` FOREIGN KEY (`fpago_id`) REFERENCES `forma_pago` (`fpago_id`),
  ADD CONSTRAINT `prestamo_cabecera_ibfk_3` FOREIGN KEY (`moneda_id`) REFERENCES `moneda` (`moneda_id`),
  ADD CONSTRAINT `prestamo_cabecera_ibfk_4` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`);

--
-- Constraints for table `prestamo_detalle`
--
ALTER TABLE `prestamo_detalle`
  ADD CONSTRAINT `prestamo_detalle_ibfk_1` FOREIGN KEY (`nro_prestamo`) REFERENCES `prestamo_cabecera` (`nro_prestamo`);

--
-- Constraints for table `referencias`
--
ALTER TABLE `referencias`
  ADD CONSTRAINT `referencias_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`);

--
-- Constraints for table `reporte_grupo_miembros`
--
ALTER TABLE `reporte_grupo_miembros`
  ADD CONSTRAINT `reporte_grupo_miembros_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `reporte_grupos` (`grupo_id`) ON DELETE CASCADE;

--
-- Constraints for table `rutas`
--
ALTER TABLE `rutas`
  ADD CONSTRAINT `fk_rutas_sucursal` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sucursales`
--
ALTER TABLE `sucursales`
  ADD CONSTRAINT `fk_sucursal_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`confi_id`) ON DELETE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_sucursal` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_perfil_usuario`) REFERENCES `perfiles` (`id_perfil`);

--
-- Constraints for table `usuarios_rutas`
--
ALTER TABLE `usuarios_rutas`
  ADD CONSTRAINT `fk_usuarios_rutas_ruta` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`ruta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_rutas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
