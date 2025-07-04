<?php

class ClienteControlador
{


    /*===================================================================*/
    //LISTAR EN COMBO
    /*===================================================================*/
    static public function ctrListarSelectClientes()
    {
        $cliente = ClienteModelo::mdlListarSelectClientes();
        return $cliente;
    }


    /*===================================================================*/
    //LISTAR CLIENTES CON PROCEDURE  EN DATATABLE
    /*===================================================================*/
    static public function ctrListarClientes()
    {
        $cliente = ClienteModelo::mdlListarClientes();
        return $cliente;
    }



    /*===================================================================*/
    //REGISTRAR CLIENTES
    /*===================================================================*/
    static public function ctrRegistrarcliente($cliente_nombres, $cliente_dni, $cliente_cel, $cliente_direccion, $cliente_correo, 
    $cliente_empresa_laboral, $cliente_cargo_laboral, $cliente_tel_laboral, $cliente_dir_laboral, 
    $cliente_refe_per_nombre, $cliente_refe_per_cel, $cliente_refe_per_dir, 
    $cliente_refe_fami_nombre, $cliente_refe_fami_cel, $cliente_refe_fami_dir)
    {
        $registrocliente = ClienteModelo::mdlRegistrarCliente(
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
    static public function ctrRegistrarReferencias($cliente_id, $refe_personal, $refe_cel_per, $refe_familiar,$refe_cel_fami)
    {
        $regReferencia = ClienteModelo::mdlRegistrarReferencias($cliente_id, $refe_personal, $refe_cel_per, $refe_familiar, $refe_cel_fami);
        return $regReferencia;
        var_dump($regReferencia);
    }



    /*===================================================================*/
     //Traer REFERENCIAS AL EDITAR
     /*===================================================================*/
    static public function ctrTraerRefe($cliente_id)
    {
        $TraerRefe = ClienteModelo::mdlTraerRefe($cliente_id);
        return $TraerRefe;
    }
}
