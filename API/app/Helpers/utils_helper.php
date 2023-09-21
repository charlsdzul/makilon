<?php
use App\Libraries\PlpxLogger;
use CodeIgniter\I18n\Time;
use Firebase\JWT\JWT;

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
        $lSistema = new \App\Entities\LogSistema();

        $mensaje_objeto = $args["mensaje_objeto"] ?? "";
        $mensajeJson = is_object($mensaje_objeto) ? json_encode($mensaje_objeto) : $mensaje_objeto;
        $lSistema->logsis_inserted_at = $timeNow;
        $lSistema->logsis_controller = $args["logsis_controller"] ?? null;
        $lSistema->logsis_method = $args["logsis_method"] ?? null;
        $lSistema->logsis_request_ip = $args["logsis_request_ip"] ?? null;
        $lSistema->logsis_tipo = $args["tipo"] ?? null;
        $lSistema->logsis_accion = $args["accion"] ?? null;
        $lSistema->logsis_mensaje = $args["mensaje"] ?? null;
        $lSistema->logsis_mensaje_json = $mensajeJson;
        $lSistema->logsis_request_respond = $args["request_respond"] ?? null;
        $lSistema->logsis_linea = $args["linea"] ?? null;
        $logger->loggerSistema($lSistema);

    } catch (Exception $e) {
        echo $e->getMessage();
        $logger->log("critical", $e, __LINE__);
    }
}

function crearToken($args)
{
    $logger = new PlpxLogger();

    try {
        $key = getenv("JWT_SECRET");
        $iat = time(); // current timestamp value
        $exp = $iat + 3600;
        $createdAt = $iat;
        $email = $args["email"];
        $role = $args["role"];
        $user = $args["user"];
        $name = $args["name"];

        $payload = [
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "createdAt" => $createdAt,
            "email" => $email,
            "role" => $role,
            "user" => $user,
            "name" => $name,
        ];

        $token = JWT::encode($payload, $key, "HS256");
        return $token;

    } catch (Exception $e) {
        $dataLog = [
            "mensaje" => "Error catch. " . $e->getMessage(),
            "mensaje_objeto" => $e,
            "logsis_method" => "crearToken",
            "accion" => "Crear Token",
        ];

        logSistema(["tipo" => "critical", "linea" => __LINE__, ...$dataLog]);
        $logger->log("critical", $e, __LINE__);
        return null;
    }
}
