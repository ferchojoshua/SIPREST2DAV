<?php

require_once "../controladores/cliente_controlador.php";
require_once "../modelos/cliente_modelo.php";

class AjaxCliente
{

    public $cliente_dni;

    /*=========================================================*/
    //LISTAR SELECT EN COMBO
    /*=========================================================*/
    public function ListarSelectClientes()
    {
        $cliente = ClienteControlador::ctrListarSelectClientes();
        echo json_encode($cliente, JSON_UNESCAPED_UNICODE);
    }


    /*=========================================================*/
    //LISTAR CLIENTE EN DATATABLE 
    /*=========================================================*/
    public function  ListarClientes()
    {
        $cliente = ClienteControlador::ctrListarClientes();
        echo json_encode($cliente);
    }




    /*=========================================================*/
    //REGISTRAR CLIENTE
    /*=========================================================*/
    public function ajaxRegistrarCliente()
    {
        $cliente = ClienteControlador::ctrRegistrarcliente(
            $this->cliente_nombres,
            $this->cliente_dni,
            $this->cliente_cel,
            $this->cliente_direccion,
            $this->cliente_correo,
            // Nuevos campos de Información Laboral
            $_POST['cliente_empresa_laboral'] ?? null,
            $_POST['cliente_cargo_laboral'] ?? null,
            $_POST['cliente_tel_laboral'] ?? null,
            $_POST['cliente_dir_laboral'] ?? null,
            // Nuevos campos de Referencia Personal
            $_POST['cliente_refe_per_nombre'] ?? null,
            $_POST['cliente_refe_per_cel'] ?? null,
            $_POST['cliente_refe_per_dir'] ?? null,
            // Nuevos campos de Referencia Familiar
            $_POST['cliente_refe_fami_nombre'] ?? null,
            $_POST['cliente_refe_fami_cel'] ?? null,
            $_POST['cliente_refe_fami_dir'] ?? null
        );
        echo json_encode($cliente);
    }



    /*=========================================================*/
    //ACTUALIZAR CLIENTE
    /*=========================================================*/
    public function ajaxActualizarCliente($data)
    {
        // $respuesta = ClienteControlador::ctrActualizarCliente(
        //     $this->cliente_id,
        //     $this->cliente_nombres,
        //     $this->cliente_dni,
        //     $this->cliente_cel,
        //     $this->cliente_direccion,
        //     $this->cliente_correo,
        //     $this->refe_personal,
        //     $this->refe_cel_per,
        //     $this->refe_familiar,
        //     $this->refe_cel_fami

        // );
        //echo json_encode($respuesta);
        $table = "clientes"; //TABLA
        $id = $_POST["cliente_id"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "cliente_id"; //CAMPO DE LA BASE

        $respuesta = ClienteControlador::ctrActualizarCliente($table, $data, $id, $nameId);

        echo json_encode($respuesta);
    }



    /*=========================================================*/
    //ELIMINAR CLIENTE
    /*=========================================================*/
    public function ajaxEliminarCliente()
    {
        $table = "clientes"; //TABLA
        $id = $_POST["cliente_id"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "cliente_id"; //CAMPO DE LA BASE 
        $respuesta = ClienteControlador::ctrEliminarCliente($table, $id, $nameId);

        echo json_encode($respuesta);
    }



    /*=========================================================*/
    //VERIFICAR SI EL DOCUMENTO YA SE ENCUENTRA REGISTRADO
    /*=========================================================*/
    public function ajaxVerificarDuplicadoDocument()
    {
        $respuesta = ClienteControlador::ctrVerificarDuplicadoDocument($this->cliente_dni);
        echo json_encode($respuesta);
        // var_dump($respuesta);
    }


    /*=========================================================*/
    //Traer datos al texbox
    /*=========================================================*/
    public function ajaxObtenerDataClienteTexbox()
    {
        $cliente = ClienteControlador::ctrObtenerDataClienteTexbox($this->cliente_dni);
        echo json_encode($cliente);
        //var_dump($cliente);
    }


    /*=========================================================*/
    //LISTAR CLIENTE EN DATATABLE 
    /*=========================================================*/
    public function  ListarClientesPrestamo()
    {
        $cliente = ClienteControlador::ctrListarClientesPrestamo();
        echo json_encode($cliente);
    }

    /*=========================================================*/
    //REGISTRAR LAS REFERENCIAS DEL CLIENTE
    /*=========================================================*/
    public function ajaxRegistrarReferencias()
    {
        $regReferencia = ClienteControlador::ctrRegistrarReferencias(
            $this->cliente_id,
            $this->refe_personal,
            $this->refe_cel_per,
            $this->refe_familiar,
            $this->refe_cel_fami,

        );
        echo json_encode($regReferencia);
    }


    /*=========================================================*/
    //Traer REFERENCIAS AL EDITAR
    /*=========================================================*/
    public function ajaxObtenerDataReferencias()
    {
        $TraerRefe = ClienteControlador::ctrTraerRefe($this->cliente_id);
        echo json_encode($TraerRefe);
        //var_dump($cliente);
    }
}








if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR CLIENTE EN DATATABLE DE CLIENTE
    $cliente = new AjaxCliente();
    $cliente->ListarClientes();


} else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //PARA REGISTRAR EL CLIENTE
    $registroCliente = new AjaxCliente();
    $registroCliente->cliente_nombres = $_POST["cliente_nombres"];
    $registroCliente->cliente_dni = $_POST["cliente_dni"];
    $registroCliente->cliente_cel = $_POST["cliente_cel"];
    $registroCliente->cliente_direccion = $_POST["cliente_direccion"];
    $registroCliente->cliente_correo = $_POST["cliente_correo"];
    $registroCliente->cliente_refe = $_POST["cliente_refe"];
    $registroCliente->cliente_cel_refe = $_POST["cliente_cel_refe"];
    $registroCliente->ajaxRegistrarCliente();



} else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //ACTUALIZAR CLIENTE
    $actualizarCliente = new AjaxCliente();
    $data = array(
        // campo de tabla y la variable definida en el registrar
        "cliente_nombres" => $_POST["cliente_nombres"],
        "cliente_dni" => $_POST["cliente_dni"],
        "cliente_cel" => $_POST["cliente_cel"],
        "cliente_direccion" => $_POST["cliente_direccion"],
        "cliente_correo" => $_POST["cliente_correo"],
        "cliente_refe" => $_POST["cliente_refe"],
        "cliente_cel_refe" => $_POST["cliente_cel_refe"],
        // Nuevos campos de Información Laboral
        "cliente_empresa_laboral" => $_POST['cliente_empresa_laboral'] ?? null,
        "cliente_cargo_laboral" => $_POST['cliente_cargo_laboral'] ?? null,
        "cliente_tel_laboral" => $_POST['cliente_tel_laboral'] ?? null,
        "cliente_dir_laboral" => $_POST['cliente_dir_laboral'] ?? null,
        // Nuevos campos de Referencia Personal
        "cliente_refe_per_nombre" => $_POST['cliente_refe_per_nombre'] ?? null,
        "cliente_refe_per_cel" => $_POST['cliente_refe_per_cel'] ?? null,
        "cliente_refe_per_dir" => $_POST['cliente_refe_per_dir'] ?? null,
        // Nuevos campos de Referencia Familiar
        "cliente_refe_fami_nombre" => $_POST['cliente_refe_fami_nombre'] ?? null,
        "cliente_refe_fami_cel" => $_POST['cliente_refe_fami_cel'] ?? null,
        "cliente_refe_fami_dir" => $_POST['cliente_refe_fami_dir'] ?? null
    );
    $actualizarCliente->ajaxActualizarCliente($data);


} else if (isset($_POST['accion']) && $_POST['accion'] == 4) { //ELIMINAR UN CLIENTE
    $eliminarCliente = new AjaxCliente();
    $eliminarCliente->ajaxEliminarCliente();


} else if (isset($_POST['accion']) && $_POST['accion'] == 5) {   //VERIFICA SI YA ESTA REGISTRADO
    $verificaDoc = new AjaxCliente();
    $verificaDoc->cliente_dni = $_POST["cliente_dni"];
    $verificaDoc->ajaxVerificarDuplicadoDocument();


} else if (isset($_POST['accion']) && $_POST['accion'] == 6) { //OBTENER DATA DEL CLIENTE EN TEXBOX
    //OBTENER DATOS DE UN PRODUCTO POR SU CODIGO
    $TraerDatosCliente = new AjaxCliente();
    $TraerDatosCliente->cliente_dni = $_POST["cliente_dni"]; //definimos arriba  y jalamos la variable que envia desde ventas
    $TraerDatosCliente->ajaxObtenerDataClienteTexbox();


} else if (isset($_POST['accion']) && $_POST['accion'] == 7) { //LISTAR CLIENTE EN DATATABLE DE CLIENTE PARA PRESTAR
    $cliente = new AjaxCliente();
    $cliente->ListarClientesPrestamo();


} else if (isset($_POST['accion']) && $_POST['accion'] == 8) { //PARA REGISTRAR LAS REFERENCIAS
    $regReferencia = new AjaxCliente();
    $regReferencia->cliente_id = $_POST["cliente_id"];
    $regReferencia->refe_personal = $_POST["refe_personal"];
    $regReferencia->refe_cel_per = $_POST["refe_cel_per"];
    $regReferencia->refe_familiar = $_POST["refe_familiar"];
    $regReferencia->refe_cel_fami = $_POST["refe_cel_fami"];
    $regReferencia->ajaxRegistrarReferencias();


} else if (isset($_POST['accion']) && $_POST['accion'] == 9) {  //OBTENEMOS LAS REFERENCIAS DEL CLIENTE EN TEXBOX
    $TraerRefe = new AjaxCliente();
    $TraerRefe->cliente_id = $_POST["cliente_id"];
    $TraerRefe->ajaxObtenerDataReferencias();


} else {
    $cliente = new AjaxCliente(); // SELECT EN COMBO
    $cliente->ListarSelectClientes();
}
