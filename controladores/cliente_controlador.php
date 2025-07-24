<?php

class ClienteControlador
{


    /*===================================================================*/
    //LISTAR EN COMBO
    /*===================================================================*/
    static public function ctrListarSelectClientes($busqueda = '')
    {
        try {
            $cliente = ClienteModelo::mdlListarSelectClientes($busqueda);
            return $cliente;
        } catch (Exception $e) {
            error_log("Error en ctrListarSelectClientes: " . $e->getMessage());
            return false;
        }
    }


    /*===================================================================*/
    //LISTAR CLIENTES CON PROCEDURE  EN DATATABLE
    /*===================================================================*/
    static public function ctrListarClientes($sucursal_id)
    {
        $cliente = ClienteModelo::mdlListarClientes($sucursal_id);
        return $cliente;
    }

    /*===================================================================*/
    //LISTAR CLIENTES CON PROCEDURE EN DATATABLE (ASOCIATIVO)
    /*===================================================================*/
    static public function ctrListarClientesForDataTableAssoc($sucursal_id)
    {
        $cliente = ClienteModelo::mdlListarClientesForDataTableAssoc($sucursal_id);
        return $cliente;
    }


    /*===================================================================*/
    //REGISTRAR CLIENTES
    /*===================================================================*/
    static public function ctrRegistrarcliente($sucursal_id, $cliente_nombres, $cliente_dni, $cliente_cel, $cliente_direccion, $cliente_correo, 
    $cliente_empresa_laboral, $cliente_cargo_laboral, $cliente_tel_laboral, $cliente_dir_laboral, 
    $cliente_refe_per_nombre, $cliente_refe_per_cel, $cliente_refe_per_dir, 
    $cliente_refe_fami_nombre, $cliente_refe_fami_cel, $cliente_refe_fami_dir)
    {
        $registrocliente = ClienteModelo::mdlRegistrarCliente(
            $sucursal_id,
            $cliente_nombres, 
            $cliente_dni, 
            $cliente_cel, 
            $cliente_direccion, 
            $cliente_correo, 
            $cliente_empresa_laboral, 
            $cliente_cargo_laboral, 
            $cliente_tel_laboral, 
            $cliente_dir_laboral, 
            $cliente_refe_per_nombre, 
            $cliente_refe_per_cel, 
            $cliente_refe_per_dir, 
            $cliente_refe_fami_nombre, 
            $cliente_refe_fami_cel, 
            $cliente_refe_fami_dir
        );
        return $registrocliente;
    }



    /*===================================================================*/
    //ACTUALIZAR CLIENTES
    /*===================================================================*/
    static public function ctrActualizarCliente($table, $data, $id, $nameId)
    {
        $respuesta = ClienteModelo::mdlActualizarCliente($table, $data, $id, $nameId);
        return $respuesta;
    }


    /*===================================================================*/
    //ELIMINAR CLIENTES
    /*===================================================================*/
    static public function ctrEliminarCliente($table, $id, $nameId)
    {
        $respuesta = ClienteModelo::mdlEliminarCliente($table, $id, $nameId);
        return $respuesta;
    }



    /*===================================================================*/
    //VERIFICAR SI EL DOCUMENTO YA SE ENCUENTRA REGISTRADO
    /*===================================================================*/
    static public function ctrVerificarDuplicadoDocument($cliente_dni)
    {
        $respuesta = ClienteModelo::mdlVerificarDuplicadoDocument($cliente_dni);
        return $respuesta;
    }


    /*===================================================================*/
    //DATOS A TEXBOX
    /*===================================================================*/
    static public function ctrObtenerDataClienteTexbox($cliente_dni)
    {
        $cliente = ClienteModelo::mdlObtenerDataClienteTexbox($cliente_dni);
        return $cliente;
    }



    /*===================================================================*/
    //LISTAR CLIENTES CON PROCEDURE  EN DATATABLE - BUSCAR CLIENTE
    /*===================================================================*/
    static public function ctrListarClientesPrestamo()
    {
        $cliente = ClienteModelo::mdlListarClientesPrestamo();
        return $cliente;
    }



    /*===================================================================*/
    ////REGISTRAR LAS REFERENCIAS DEL CLIENTE
    /*===================================================================*/
    static public function ctrRegistrarReferencias($cliente_id, $refe_personal, $refe_cel_per, $refe_familiar, $refe_cel_fami, $refe_empresa_laboral, $refe_cargo_laboral, $refe_tel_laboral, $refe_dir_laboral)
    {
        $respuesta = ClienteModelo::mdlRegistrarReferencias($cliente_id, $refe_personal, $refe_cel_per, $refe_familiar, $refe_cel_fami, $refe_empresa_laboral, $refe_cargo_laboral, $refe_tel_laboral, $refe_dir_laboral);
        return $respuesta;
    }



    /*===================================================================*/
     //Traer REFERENCIAS AL EDITAR
     /*===================================================================*/
    static public function ctrTraerRefe($cliente_id)
    {
        $TraerRefe = ClienteModelo::mdlTraerRefe($cliente_id);
        return $TraerRefe;
    }

    /*===================================================================*/
    //BUSCAR CLIENTES PARA SELECT2
    /*===================================================================*/
    static public function ctrBuscarClientesParaSelect($busqueda)
    {
        try {
            $clientes = ClienteModelo::mdlBuscarClientesParaSelect($busqueda);
            return $clientes;
        } catch (Exception $e) {
            error_log("Error en ctrBuscarClientesParaSelect: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    //BUSCAR SOLO CLIENTES DISPONIBLES (SIN PRÉSTAMOS ACTIVOS)
    /*===================================================================*/
    static public function ctrBuscarClientesDisponibles($busqueda)
    {
        try {
            $clientes = ClienteModelo::mdlBuscarClientesDisponibles($busqueda);
            return $clientes;
        } catch (Exception $e) {
            error_log("Error en ctrBuscarClientesDisponibles: " . $e->getMessage());
            return false;
        }
    }
}
