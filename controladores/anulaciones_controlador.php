<?php

class AnulacionesControlador
{
    /**
     * Verificar permisos de anulación
     */
    static public function ctrVerificarPermisosAnulacion($usuario_id, $tipo_documento, $fecha_documento = null)
    {
        require_once "../modelos/anulaciones_modelo.php";
        return AnulacionesModelo::mdlVerificarPermisosAnulacion($usuario_id, $tipo_documento, $fecha_documento);
    }

    /**
     * Anular pago de cuota con justificación
     */
    static public function ctrAnularPago($nro_prestamo, $nro_cuota, $motivo)
    {
        require_once "../modelos/anulaciones_modelo.php";
        
        // Validar entrada
        if (empty($motivo) || strlen(trim($motivo)) < 10) {
            return [
                'estado' => 'error',
                'mensaje' => 'La justificación debe tener al menos 10 caracteres'
            ];
        }

        // Obtener usuario actual
        $usuario_actual = AnulacionesModelo::mdlObtenerUsuarioActual();
        if (!$usuario_actual) {
            return [
                'estado' => 'error',
                'mensaje' => 'Usuario no válido'
            ];
        }

        // Verificar permisos
        $permisos = AnulacionesModelo::mdlVerificarPermisosAnulacion(
            $usuario_actual['id_usuario'], 
            'pago'
        );
        
        if (!$permisos['puede_anular']) {
            return [
                'estado' => 'error',
                'mensaje' => 'Sin permisos para anular pagos: ' . $permisos['mensaje']
            ];
        }

        // Ejecutar anulación
        $resultado = AnulacionesModelo::mdlAnularPago(
            $nro_prestamo, 
            $nro_cuota, 
            $usuario_actual['id_usuario'], 
            trim($motivo), 
            $usuario_actual['sucursal_id']
        );

        if ($resultado === 'ok') {
            return [
                'estado' => 'ok',
                'mensaje' => 'Pago anulado correctamente'
            ];
        } else {
            return [
                'estado' => 'error',
                'mensaje' => self::interpretarErrorAnulacion($resultado)
            ];
        }
    }

    /**
     * Anular préstamo/contrato con justificación
     */
    static public function ctrAnularPrestamo($nro_prestamo, $motivo)
    {
        require_once "../modelos/anulaciones_modelo.php";
        
        // Validar entrada
        if (empty($motivo) || strlen(trim($motivo)) < 20) {
            return [
                'estado' => 'error',
                'mensaje' => 'La justificación para anular préstamos debe tener al menos 20 caracteres'
            ];
        }

        // Obtener usuario actual
        $usuario_actual = AnulacionesModelo::mdlObtenerUsuarioActual();
        if (!$usuario_actual) {
            return [
                'estado' => 'error',
                'mensaje' => 'Usuario no válido'
            ];
        }

        // Verificar permisos
        $permisos = AnulacionesModelo::mdlVerificarPermisosAnulacion(
            $usuario_actual['id_usuario'], 
            'prestamo'
        );
        
        if (!$permisos['puede_anular']) {
            return [
                'estado' => 'error',
                'mensaje' => 'Sin permisos para anular préstamos: ' . $permisos['mensaje']
            ];
        }

        // Ejecutar anulación
        $resultado = AnulacionesModelo::mdlAnularPrestamoConJustificacion(
            $nro_prestamo, 
            $usuario_actual['id_usuario'], 
            trim($motivo), 
            $usuario_actual['sucursal_id']
        );

        if ($resultado === 'ok') {
            return [
                'estado' => 'ok',
                'mensaje' => 'Préstamo anulado correctamente'
            ];
        } else {
            return [
                'estado' => 'error',
                'mensaje' => self::interpretarErrorAnulacion($resultado)
            ];
        }
    }

    /**
     * Obtener historial de anulaciones
     */
    static public function ctrObtenerHistorialAnulaciones($filtros = [])
    {
        require_once "../modelos/anulaciones_modelo.php";
        return AnulacionesModelo::mdlObtenerHistorialAnulaciones($filtros);
    }

    /**
     * Verificar si el usuario puede anular
     */
    static public function ctrPuedeAnular($tipo_documento, $usuario_id = null)
    {
        require_once "../modelos/anulaciones_modelo.php";
        
        if ($usuario_id === null) {
            $usuario_actual = AnulacionesModelo::mdlObtenerUsuarioActual();
            if (!$usuario_actual) {
                return false;
            }
            $usuario_id = $usuario_actual['id_usuario'];
        }

        $permisos = AnulacionesModelo::mdlVerificarPermisosAnulacion($usuario_id, $tipo_documento);
        return $permisos['puede_anular'];
    }

    /**
     * Interpretar códigos de error
     */
    private static function interpretarErrorAnulacion($codigo_error)
    {
        $errores = [
            'error_pago_no_encontrado' => 'El pago especificado no existe',
            'error_pago_no_pagado' => 'La cuota no está en estado pagado',
            'error_prestamo_no_encontrado' => 'El préstamo especificado no existe',
            'error_prestamo_ya_anulado' => 'El préstamo ya está anulado',
            'error_justificacion_insuficiente' => 'La justificación es insuficiente para un préstamo con pagos realizados',
            'error_auditoria' => 'Error al registrar la auditoría de la anulación',
            'error_base_datos' => 'Error en la base de datos',
            'error_procedimiento_anulacion' => 'Error en el procedimiento de anulación'
        ];

        // Si el error contiene "error_sin_permisos:", extraer el mensaje
        if (strpos($codigo_error, 'error_sin_permisos:') === 0) {
            return str_replace('error_sin_permisos:', '', $codigo_error);
        }

        return $errores[$codigo_error] ?? $codigo_error;
    }
}
?> 