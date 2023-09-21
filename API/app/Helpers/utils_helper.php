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
        $lUsuario = new \App\Entities\LogUsuario();
        $lUsuario->log_inserted_at = $timeNow;
        $lUsuario->log_controller = $args["controller"];
        $lUsuario->log_method = $args["method"];
        $lUsuario->log_request_ip = $args["request_ip"];
        $lUsuario->log_usuario_id = $args["usuario_id"];
        $lUsuario->log_usuario = $args["usuario_usuario"];
        $lUsuario->log_usuario_correo = $args["usuario_correo"];
        $lUsuario->log_accion = $args["accion"] ?? null;
        $lUsuario->log_mensaje = $args["mensaje"] ?? null;
        $lUsuario->log_exception = $args["exception"];
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
        $lSistema->logsis_inserted_at = $timeNow;
        $lSistema->logsis_controller = $args["controller"] ?? null;
        $lSistema->logsis_method = $args["method"] ?? null;
        $lSistema->logsis_request_ip = $args["request_ip"] ?? null;
        $lSistema->logsis_tipo = $args["tipo"] ?? null;
        $lSistema->logsis_accion = $args["accion"] ?? null;
        $lSistema->logsis_mensaje = $args["mensaje"] ?? null;
        $lSistema->logsis_exception = $args["exception"] ?? null;
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
            'logsis_controller' => "utils_helper.php",
            "accion" => "Crear Token",
        ];

        logSistema(["tipo" => "critical", "linea" => __LINE__, ...$dataLog]);
        $logger->log("critical", $e, __LINE__);
        return null;
    }
}
