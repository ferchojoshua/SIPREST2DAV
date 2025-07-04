-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-06-2025 a las 00:18:49
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbprestamo`
--

DELIMITER $$
--
-- Procedimientos
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CAMBIAR_ESTADO_CABECERA` (IN `prestamo` VARCHAR(8))   BEGIN
DECLARE ESTADO INT;
DECLARE CLIENTE INT;
SET @ESTADO:=(select count(*) from prestamo_detalle pd where pd.nro_prestamo = prestamo and pd.pdetalle_estado_cuota like '%pendiente%' );
SET @CLIENTE:=(select cliente_id from prestamo_cabecera where nro_prestamo = prestamo);

 IF  @ESTADO = 0 THEN 
        UPDATE prestamo_cabecera SET
	pres_aprobacion = 'finalizado',
	pres_estado = 'Finalizado',
	pres_estado_caja = 'CERRADO_CAJA'
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
		pc.cliente_id,
		c.cliente_dni,
		c.cliente_nombres,
		pd.nro_prestamo,
		pd.pdetalle_nro_cuota,
		DATE_FORMAT(pd.pdetalle_fecha,'%d/%m/%Y') as fecha,
		pd.pdetalle_monto_cuota,
		pc.id_usuario,
		u.nombre_usuario,
		CURDATE(),
		DATE(pd.pdetalle_fecha),
		(CURDATE() - DATE(pd.pdetalle_fecha) ) as resta
		
	FROM
		prestamo_cabecera pc
		INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
		INNER JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo 
		INNER JOIN usuarios u on pc.id_usuario =  u.id_usuario
	WHERE
		CURDATE() >= DATE(pd.pdetalle_fecha) and
		
		pc.pres_aprobacion = 'aprobado' 
		and pd.pdetalle_estado_cuota = 'pendiente'
		ORDER BY 	DATE(pd.pdetalle_fecha) ASC;
	-- 	LIMIT 10;
		

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CLIENTES_PRESTAMO` ()   SELECT
	cliente_id, 
	cliente_nombres, 
	cliente_dni, 
	cliente_estado_prestamo, 
	cliente_estatus

FROM
	clientes$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CLIENTES_TABLE` ()   SELECT
	cliente_id, 
	cliente_nombres, 
	cliente_dni, 
	cliente_cel, 
	cliente_estado_prestamo, 
	cliente_estatus,
	cliente_direccion,
	cliente_correo,
	'' as opciones,
	cliente_refe,
	cliente_cel_refe
FROM
	clientes$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_REFERENCIAS_EN_CLIENTE_EDIT` (IN `ID` INT)   SELECT
cliente_id,
refe_personal,
refe_cel_per,
refe_familiar,
refe_cel_fami
	
FROM
	referencias
	where cliente_id = ID$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_USUARIOS` ()   SELECT
	usuarios.id_usuario,
	usuarios.nombre_usuario,
	usuarios.apellido_usuario,
	-- CONCAT_WS(' ', usuarios.nombre_usuario,usuarios.apellido_usuario) as nombre, 
	usuarios.usuario, 
	usuarios.clave,
	usuarios.id_perfil_usuario, 
	perfiles.descripcion, 
	usuarios.estado,
	'' as opciones
FROM
	usuarios
	INNER JOIN
	perfiles
	ON 
		usuarios.id_perfil_usuario = perfiles.id_perfil
		
		ORDER BY usuarios.id_usuario ASC$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_CAJA_CIERRE` (IN `MONTO_INGRESO` FLOAT, IN `MONTO_PRES` FLOAT, IN `MONTO_EGRES` FLOAT, IN `MONTO_TOTAL` FLOAT, IN `CANT_PRESTA` VARCHAR(100), IN `CANT_INGRES` VARCHAR(100), IN `CANT_EGRESO` VARCHAR(100), IN `INTERES` FLOAT)   BEGIN 
	
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





END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_REFERENCIAS` (IN `ID_CLI` INT, IN `REFE_PER` VARCHAR(255), IN `CEL_PER` VARCHAR(20), IN `REFE_FAM` VARCHAR(255), IN `CEL_FAM` VARCHAR(20))   BEGIN
INSERT INTO referencias (cliente_id, refe_personal, refe_cel_per, refe_familiar, refe_cel_fami) values(ID_CLI, REFE_PER, CEL_PER, REFE_FAM, CEL_FAM);

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_POR_CLIENTE` (IN `id` INT)   select pc.pres_id ,
				pc.nro_prestamo,
				pc.cliente_id,
				c.cliente_nombres,
				pc.pres_monto,
				DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha,
				pc.pres_monto_total,
				pc.pres_monto_cuota,
			  pc.pres_cuotas,
				pc.fpago_id,
				fp.fpago_descripcion,
				-- pc.id_usuario,
		  	-- 	u.usuario,						
				pc.pres_aprobacion as estado,
				'' as opciones	,
				pc.pres_interes	,
				pc.pres_monto_interes,
				pc.pres_cuotas_pagadas,
				DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') as femision
				 from prestamo_cabecera pc
				 INNER JOIN clientes c on
				 pc.cliente_id = c.cliente_id
				 INNER JOIN forma_pago fp on 
				 pc.fpago_id = fp.fpago_id
				 INNER JOIN usuarios u on
				 pc.id_usuario = u.id_usuario
				 WHERE pc.cliente_id = id$$

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
-- Estructura de tabla para la tabla `caja`
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
  `caja_interes` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`caja_id`, `caja_descripcion`, `caja_monto_inicial`, `caja_monto_ingreso`, `caja_prestamo`, `caja_f_apertura`, `caja_f_cierre`, `caja__monto_egreso`, `caja_monto_total`, `caja_estado`, `caja_hora_apertura`, `caja_hora_cierre`, `caja_count_prestamo`, `caja_count_ingreso`, `caja_count_egreso`, `caja_correo`, `caja_interes`) VALUES
(4, 'Apertura de Caja', 78000, NULL, NULL, '2025-06-25', NULL, NULL, NULL, 'VIGENTE', '07:31:22', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Disparadores `caja`
--
DELIMITER $$
CREATE TRIGGER `TG_CERRAR_MOVI_INGRESO` BEFORE UPDATE ON `caja` FOR EACH ROW BEGIN

UPDATE movimientos SET
movi_caja= 'CERRADO'
where movi_caja='VIGENTE';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
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
  `cliente_cel_refe` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cliente_id`, `cliente_nombres`, `cliente_dni`, `cliente_cel`, `cliente_estado_prestamo`, `cliente_direccion`, `cliente_obs`, `cliente_correo`, `cliente_estatus`, `cliente_cant_prestamo`, `cliente_refe`, `cliente_cel_refe`) VALUES
(1, 'Gunner', '2810512850003D', '86595453', 'DISPONIBLE', 'Leon-Nicaragua', NULL, 'ferchojoshua@gmail.com', '1', NULL, '', ''),
(6, 'Eliut', '281051283000f', '86595453', 'con prestamo', 'del chinchunte hacia abajo', NULL, 'prueba@yahoo,com', '1', NULL, 'pablo cortez', '98765432'),
(7, 'Ricardo', '23445670909', '45678990', 'con prestamo', 'Leon-Nicaragua', NULL, 'joshua@gmail.com', '1', NULL, 'Edwin', '989879878');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `confi_id` int(11) NOT NULL,
  `confi_razon` varchar(255) DEFAULT NULL,
  `confi_ruc` varchar(40) DEFAULT NULL,
  `confi_direccion` varchar(255) DEFAULT NULL,
  `confi_correlativo` varchar(8) DEFAULT NULL,
  `config_correo` varchar(50) DEFAULT NULL,
  `config_celular` varchar(50) DEFAULT NULL,
  `config_moneda` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`confi_id`, `confi_razon`, `confi_ruc`, `confi_direccion`, `confi_correlativo`, `config_correo`, `config_celular`, `config_moneda`) VALUES
(1, 'PRESTAMOS NICA', '1020304050', 'Leon-Nicaragua', '00000003', 'ferchojoshua@gmail.com', '922804671', 'C$');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forma_pago`
--

CREATE TABLE `forma_pago` (
  `fpago_id` int(11) NOT NULL,
  `fpago_descripcion` varchar(255) DEFAULT NULL,
  `valor` char(10) DEFAULT NULL,
  `aplica_dias` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `forma_pago`
--

INSERT INTO `forma_pago` (`fpago_id`, `fpago_descripcion`, `valor`, `aplica_dias`) VALUES
(1, 'Diario', '1', '1'),
(2, 'Semanal', '7', '1'),
(3, 'Quincenal', '15', '1'),
(4, 'Mensual', '1', '0'),
(5, 'Bimestral', '2', '0'),
(6, 'Semestrual', '6', '0'),
(7, 'Anual', '1', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
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
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) VALUES
(1, 'DashBoard', 0, 'dashboard.php', 'fas fa-tachometer-alt', 0),
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
(40, 'Aperturar Caja', 39, 'caja.php', 'far fa-circle', 2),
(41, 'Ingresos / Egre', 39, 'ingresos.php', 'far fa-circle', 3),
(43, 'Pivot', 10, 'reportes.php', 'far fa-circle', 18),
(47, 'Backup', 0, 'index_backup.php', 'fas fa-database', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

CREATE TABLE `moneda` (
  `moneda_id` int(11) NOT NULL,
  `moneda_nombre` varchar(10) DEFAULT NULL,
  `moneda_abrevia` varchar(10) DEFAULT NULL,
  `moneda_simbolo` varchar(10) DEFAULT NULL,
  `moneda_Descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `moneda`
--

INSERT INTO `moneda` (`moneda_id`, `moneda_nombre`, `moneda_abrevia`, `moneda_simbolo`, `moneda_Descripcion`) VALUES
(1, 'Cordoba', 'NIO', 'C$', 'Cordoba Nicaraguense'),
(2, 'Dolar amer', 'USD', '$', 'Dolar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
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
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`movimientos_id`, `movi_tipo`, `movi_descripcion`, `movi_monto`, `movi_fecha`, `movi_caja`, `caja_id`) VALUES
(2, 'INGRESO', 'CANCELACIONES', 2000, '2025-06-25 07:54:22', 'VIGENTE', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id_perfil` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id_perfil`, `descripcion`, `estado`) VALUES
(1, 'Administrador', 1),
(2, 'Colector', 1),
(3, 'Caja', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_modulo`
--

CREATE TABLE `perfil_modulo` (
  `idperfil_modulo` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `vista_inicio` tinyint(4) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `perfil_modulo`
--

INSERT INTO `perfil_modulo` (`idperfil_modulo`, `id_perfil`, `id_modulo`, `vista_inicio`, `estado`) VALUES
(174, 1, 13, 0, 1),
(412, 1, 1, 1, 1),
(413, 1, 40, 0, 1),
(414, 1, 39, 0, 1),
(415, 1, 41, 0, 1),
(416, 1, 24, 0, 1),
(417, 1, 34, 0, 1),
(418, 1, 29, 0, 1),
(419, 1, 35, 0, 1),
(420, 1, 36, 0, 1),
(421, 1, 11, 0, 1),
(422, 1, 25, 0, 1),
(423, 1, 12, 0, 1),
(424, 1, 14, 0, 1),
(425, 1, 37, 0, 1),
(426, 1, 10, 0, 1),
(427, 1, 38, 0, 1),
(428, 1, 43, 0, 1),
(429, 1, 47, 0, 1),
(442, 3, 39, 0, 1),
(443, 3, 40, 1, 1),
(444, 3, 41, 0, 1),
(445, 3, 24, 0, 1),
(446, 2, 24, 1, 1),
(447, 2, 29, 0, 1),
(448, 2, 34, 0, 1),
(449, 2, 35, 0, 1),
(450, 2, 36, 0, 1),
(451, 2, 37, 0, 1),
(452, 2, 10, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo_cabecera`
--

CREATE TABLE `prestamo_cabecera` (
  `pres_id` int(11) NOT NULL,
  `nro_prestamo` varchar(8) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `pres_monto` float DEFAULT NULL,
  `pres_cuotas` char(10) DEFAULT NULL,
  `pres_interes` float DEFAULT NULL,
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
  `caja_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `prestamo_cabecera`
--

INSERT INTO `prestamo_cabecera` (`pres_id`, `nro_prestamo`, `cliente_id`, `pres_monto`, `pres_cuotas`, `pres_interes`, `fpago_id`, `moneda_id`, `pres_f_emision`, `pres_monto_cuota`, `pres_monto_interes`, `pres_monto_total`, `pres_estado`, `pres_estatus`, `id_usuario`, `pres_aprobacion`, `pres_cuotas_pagadas`, `pres_monto_restante`, `pres_cuotas_restante`, `pres_fecha_registro`, `pres_estado_caja`, `caja_id`) VALUES
(9, '00000002', 6, 17000, '12', 15, 2, 1, '2025-06-26', 1629.17, 2550, 19550, 'Pendiente', '1', 1, 'aprobado', '1', 17921, '11', '2025-06-25', 'VIGENTE', 4),
(10, '00000003', 7, 2000, '24', 10, 4, 2, '2025-07-26', 91.67, 200, 2200, 'Pendiente', '1', 1, 'aprobado', '0', NULL, '24', '2025-06-26', 'VIGENTE', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo_detalle`
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
-- Volcado de datos para la tabla `prestamo_detalle`
--

INSERT INTO `prestamo_detalle` (`pdetalle_id`, `nro_prestamo`, `pdetalle_nro_cuota`, `pdetalle_monto_cuota`, `pdetalle_fecha`, `pdetalle_estado_cuota`, `pdetalle_fecha_registro`, `pdetalle_saldo_cuota`, `pdetalle_cant_cuota_pagada`, `pdetalle_liquidar`, `pdetalle_monto_liquidar`, `pdetalle_caja`, `pdetalle_aprobacion`) VALUES
(29, '00000002', '1', 1629.17, '2025-06-26 00:00:00', 'pagada', '2025-06-25 13:55:55', 17920.8, '11', '0', NULL, 'VIGENTE', 'aprobado'),
(30, '00000002', '2', 1629.17, '2025-07-03 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(31, '00000002', '3', 1629.17, '2025-07-10 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(32, '00000002', '4', 1629.17, '2025-07-17 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(33, '00000002', '5', 1629.17, '2025-07-24 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(34, '00000002', '6', 1629.17, '2025-07-31 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(35, '00000002', '7', 1629.17, '2025-08-07 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(36, '00000002', '8', 1629.17, '2025-08-14 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(37, '00000002', '9', 1629.17, '2025-08-21 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(38, '00000002', '10', 1629.17, '2025-08-28 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(39, '00000002', '11', 1629.17, '2025-09-04 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(40, '00000002', '12', 1629.17, '2025-09-11 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(41, '00000003', '1', 91.67, '2025-07-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(42, '00000003', '2', 91.67, '2025-08-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(43, '00000003', '3', 91.67, '2025-09-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(44, '00000003', '4', 91.67, '2025-10-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(45, '00000003', '5', 91.67, '2025-11-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(46, '00000003', '6', 91.67, '2025-12-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(47, '00000003', '7', 91.67, '2026-01-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(48, '00000003', '8', 91.67, '2026-02-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(49, '00000003', '9', 91.67, '2026-03-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(50, '00000003', '10', 91.67, '2026-04-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(51, '00000003', '11', 91.67, '2026-05-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(52, '00000003', '12', 91.67, '2026-06-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(53, '00000003', '13', 91.67, '2026-07-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(54, '00000003', '14', 91.67, '2026-08-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(55, '00000003', '15', 91.67, '2026-09-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(56, '00000003', '16', 91.67, '2026-10-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(57, '00000003', '17', 91.67, '2026-11-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(58, '00000003', '18', 91.67, '2026-12-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(59, '00000003', '19', 91.67, '2027-01-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(60, '00000003', '20', 91.67, '2027-02-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(61, '00000003', '21', 91.67, '2027-03-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(62, '00000003', '22', 91.67, '2027-04-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(63, '00000003', '23', 91.67, '2027-05-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado'),
(64, '00000003', '24', 91.67, '2027-06-26 00:00:00', 'pendiente', NULL, NULL, NULL, '0', NULL, 'VIGENTE', 'aprobado');

--
-- Disparadores `prestamo_detalle`
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
-- Estructura de tabla para la tabla `referencias`
--

CREATE TABLE `referencias` (
  `refe_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `refe_personal` varchar(255) DEFAULT NULL,
  `refe_cel_per` varchar(20) DEFAULT NULL,
  `refe_familiar` varchar(255) DEFAULT NULL,
  `refe_cel_fami` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL,
  `nombre_rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rol_id`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Prestamista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) DEFAULT NULL,
  `apellido_usuario` varchar(100) DEFAULT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` text NOT NULL,
  `id_perfil_usuario` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `apellido_usuario`, `usuario`, `clave`, `id_perfil_usuario`, `estado`) VALUES
(1, 'Gunner', 'Bento', 'Gunner', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 1, 1),
(2, 'Colector', 'numero uno', 'Cuno', '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', 2, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`caja_id`) USING BTREE;

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_id`) USING BTREE;

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`confi_id`) USING BTREE;

--
-- Indices de la tabla `forma_pago`
--
ALTER TABLE `forma_pago`
  ADD PRIMARY KEY (`fpago_id`) USING BTREE;

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `moneda`
--
ALTER TABLE `moneda`
  ADD PRIMARY KEY (`moneda_id`) USING BTREE;

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`movimientos_id`) USING BTREE,
  ADD KEY `caja_id` (`caja_id`) USING BTREE;

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id_perfil`) USING BTREE;

--
-- Indices de la tabla `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  ADD PRIMARY KEY (`idperfil_modulo`) USING BTREE,
  ADD KEY `id_perfil` (`id_perfil`) USING BTREE,
  ADD KEY `id_modulo` (`id_modulo`) USING BTREE;

--
-- Indices de la tabla `prestamo_cabecera`
--
ALTER TABLE `prestamo_cabecera`
  ADD PRIMARY KEY (`pres_id`,`nro_prestamo`) USING BTREE,
  ADD KEY `cliente_id` (`cliente_id`) USING BTREE,
  ADD KEY `fpago_id` (`fpago_id`) USING BTREE,
  ADD KEY `moneda_id` (`moneda_id`) USING BTREE,
  ADD KEY `nro_prestamo` (`nro_prestamo`) USING BTREE,
  ADD KEY `caja_id` (`caja_id`) USING BTREE;

--
-- Indices de la tabla `prestamo_detalle`
--
ALTER TABLE `prestamo_detalle`
  ADD PRIMARY KEY (`pdetalle_id`,`nro_prestamo`) USING BTREE,
  ADD KEY `nro_prestamo` (`nro_prestamo`) USING BTREE;

--
-- Indices de la tabla `referencias`
--
ALTER TABLE `referencias`
  ADD PRIMARY KEY (`refe_id`) USING BTREE,
  ADD KEY `cliente_id` (`cliente_id`) USING BTREE;

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`) USING BTREE,
  ADD KEY `id_perfil_usuario` (`id_perfil_usuario`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `caja_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `confi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `forma_pago`
--
ALTER TABLE `forma_pago`
  MODIFY `fpago_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `moneda`
--
ALTER TABLE `moneda`
  MODIFY `moneda_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `movimientos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  MODIFY `idperfil_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=453;

--
-- AUTO_INCREMENT de la tabla `prestamo_cabecera`
--
ALTER TABLE `prestamo_cabecera`
  MODIFY `pres_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `prestamo_detalle`
--
ALTER TABLE `prestamo_detalle`
  MODIFY `pdetalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `referencias`
--
ALTER TABLE `referencias`
  MODIFY `refe_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`);

--
-- Filtros para la tabla `perfil_modulo`
--
ALTER TABLE `perfil_modulo`
  ADD CONSTRAINT `perfil_modulo_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id_perfil`),
  ADD CONSTRAINT `perfil_modulo_ibfk_2` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`);

--
-- Filtros para la tabla `prestamo_cabecera`
--
ALTER TABLE `prestamo_cabecera`
  ADD CONSTRAINT `prestamo_cabecera_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`),
  ADD CONSTRAINT `prestamo_cabecera_ibfk_2` FOREIGN KEY (`fpago_id`) REFERENCES `forma_pago` (`fpago_id`),
  ADD CONSTRAINT `prestamo_cabecera_ibfk_3` FOREIGN KEY (`moneda_id`) REFERENCES `moneda` (`moneda_id`),
  ADD CONSTRAINT `prestamo_cabecera_ibfk_4` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`);

--
-- Filtros para la tabla `prestamo_detalle`
--
ALTER TABLE `prestamo_detalle`
  ADD CONSTRAINT `prestamo_detalle_ibfk_1` FOREIGN KEY (`nro_prestamo`) REFERENCES `prestamo_cabecera` (`nro_prestamo`);

--
-- Filtros para la tabla `referencias`
--
ALTER TABLE `referencias`
  ADD CONSTRAINT `referencias_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_perfil_usuario`) REFERENCES `perfiles` (`id_perfil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
