<?php

class ControladorGruposReportes {

    /*=============================================
    CREAR GRUPO
    =============================================*/
    static public function ctrCrearGrupo() {
        if (isset($_POST["nuevoNombre"])) {
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"])) {
                
                $tabla = "reporte_grupos";
                $datos = array("nombre" => $_POST["nuevoNombre"], "descripcion" => $_POST["nuevaDescripcion"]);
                
                $respuesta = ModeloGruposReportes::mdlCrearGrupo($tabla, $datos);
                
                if ($respuesta == "ok") {
                    echo '<script>
                        swal({
                            type: "success",
                            title: "¡El grupo ha sido guardado correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "grupos-reportes";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡El nombre no puede ir vacío o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "grupos-reportes";
                        }
                    });
                </script>';
            }
        }
    }

    /*=============================================
    MOSTRAR GRUPOS
    =============================================*/
    static public function ctrMostrarGrupos($item, $valor) {
        $tabla = "reporte_grupos";
        return ModeloGruposReportes::mdlMostrarGrupos($tabla, $item, $valor);
    }

    /*=============================================
    EDITAR GRUPO
    =============================================*/
    static public function ctrEditarGrupo() {
        if (isset($_POST["editarNombre"])) {
            if (preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])) {
                
                $tabla = "reporte_grupos";
                $datos = array(
                    "id" => $_POST["idGrupo"],
                    "nombre" => $_POST["editarNombre"],
                    "descripcion" => $_POST["editarDescripcion"]
                );
                
                $respuesta = ModeloGruposReportes::mdlEditarGrupo($tabla, $datos);
                
                if ($respuesta == "ok") {
                    echo '<script>
                        swal({
                            type: "success",
                            title: "¡El grupo ha sido modificado correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "grupos-reportes";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal({
                        type: "error",
                        title: "¡El nombre no puede ir vacío o llevar caracteres especiales!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "grupos-reportes";
                        }
                    });
                </script>';
            }
        }
    }

    /*=============================================
    BORRAR GRUPO
    =============================================*/
    static public function ctrBorrarGrupo() {
        if (isset($_GET["idGrupo"])) {
            $tabla = "reporte_grupos";
            $datos = $_GET["idGrupo"];
            
            $respuesta = ModeloGruposReportes::mdlBorrarGrupo($tabla, $datos);
            
            if ($respuesta == "ok") {
                echo '<script>
                    swal({
                        type: "success",
                        title: "El grupo ha sido borrado correctamente",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if(result.value){
                            window.location = "grupos-reportes";
                        }
                    });
                </script>';
            }
        }
    }

    /*=============================================
    MOSTRAR MIEMBROS
    =============================================*/
    static public function ctrMostrarMiembros($item, $valor) {
        $tabla = "reporte_grupo_miembros";
        return ModeloGruposReportes::mdlMostrarMiembros($tabla, $item, $valor);
    }
} 