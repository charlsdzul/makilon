<?php
use App\Libraries\PlpxLogger;
use CodeIgniter\I18n\Time;

function logUsuario($args)
{
    $logger = new PlpxLogger();

    try {
        $logger = new PlpxLogger();
        $timeNow = new Time("now");
        $mensajeJson = is_object($args["mensaje_objeto"] ?? null) ? json_encode($args["mensaje_objeto"]) : null;

        $lUsuario = new \App\Entities\LogUsuario([
            "logsis_controller" => $args["logsis_controller"],
            "logsis_method" => $args["logsis_method"],
            "logsis_request_ip" => $args["logsis_request_ip"],
            "log_inserted_at" => $timeNow,
        ]);

        $lUsuario->log_usuario_id = $args["usuario_id"];
        $lUsuario->log_usuario = $args["usuario_usuario"];
        $lUsuario->log_usuario_correo = $args["usuario_correo"];
        $lUsuario->log_accion = $args["accion"] ?? null;
        $lUsuario->log_mensaje = $args["mensaje"] ?? null;
        $lUsuario->log_mensaje_json = $mensajeJson ?? null;
        $lUsuario->log_request_respond = $args["request_respond"] ?? null;
        $lUsuario->log_linea = $args["linea"] ?? null;
        $logger->loggerUsuario($lUsuario);
    } catch (Exception $e) {
        $logger->log("critical", $e, __LINE__);
    }
}

function logSistema($args)
{
    $logger = new PlpxLogger();

    try {
        $logger = new PlpxLogger();
        $timeNow = new Time("now");
        $mensajeJson = is_object($args["mensaje_objeto"]) ? json_encode($args["mensaje_objeto"]) : $args["mensaje_objeto"];

        $lSistema = new \App\Entities\LogSistema([
            "logsis_controller" => $args["logsis_controller"],
            "logsis_method" => $args["logsis_method"],
            "logsis_request_ip" => $args["logsis_request_ip"],
            "logsis_inserted_at" => $timeNow,
            //"logsis_usuario_id" => $this->session->usu_id ?? null,
        ]);

        $lSistema->logsis_tipo = $args["tipo"] ?? null;
        $lSistema->logsis_accion = $args["accion"] ?? null;
        $lSistema->logsis_mensaje = $args["mensaje"] ?? null;
        $lSistema->logsis_mensaje_json = $mensajeJson;
        $lSistema->logsis_request_respond = $args["request_respond"] ?? null;
        $lSistema->logsis_linea = $args["linea"] ?? null;
        $logger->loggerSistema($lSistema);
    } catch (Exception $e) {
        $logger->log("critical", $e, __LINE__);
    }
}
