<?php

require_once "../../modelos/conexion.php";


$query = "SELECT
                vd.id,
                vd.nro_boleta,
                vd.codigo_producto,
               -- c.nombre_categoria,
                p.descripcion_producto,
                vd.cantidad AS cantidad,
                ROUND( vd.total_venta, 2 ) AS total_venta 
             FROM
                venta_detalle vd
             INNER JOIN productos p ON vd.codigo_producto = p.codigo_producto
             INNER JOIN categorias c ON c.id_categoria = p.id_categoria_producto 
             WHERE
                nro_boleta = " .$_POST['nro_boleta']. "
                ORDER BY vd.id";


$statement = Conexion::conectar()->prepare($query);
$statement ->execute();

$numero_filas_filtradas = $statement->rowCount();
$result = $statement->fetchAll();


$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $numero_filas_filtradas,
    'recordsFiltered' => $numero_filas_filtradas,
    'data'=> $result
);


echo json_encode($output);




























