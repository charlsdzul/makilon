<?php
use App\Libraries\PlpxLogger;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function logUsuario($args)
{
    $logger = new PlpxLogger();

    try {
        // $logger = new PlpxLogger();
        // $timeNow = new Time("now");
        // $lUsuario = new \App\Entities\LogUsuario();
        // $lUsuario->log_inserted_at = $timeNow;
        // $lUsuario->log_controller = $args["controller"];
        // $lUsuario->log_method = $args["method"];
        // $lUsuario->log_request_ip = $args["request_ip"];
        // $lUsuario->log_usuario_id = $args["usuario_id"];
        // $lUsuario->log_usuario = $args["usuario_usuario"];
        // $lUsuario->log_usuario_correo = $args["usuario_correo"];
        // $lUsuario->log_accion = $args["accion"] ?? null;
        // $lUsuario->log_mensaje = $args["mensaje"] ?? null;
        // $lUsuario->log_exception = $args["exception"];
        // $lUsuario->log_request_respond = $args["request_respond"] ?? null;
        // $lUsuario->log_linea = $args["linea"] ?? null;
        // $logger->loggerUsuario($lUsuario);
    } catch (Exception $e) {
        $logger->log("critical", $e, __LINE__);
    }
}

function logSistema($args)
{
    $logger = new PlpxLogger();

    try {
        $logger = new PlpxLogger();
        $lSistema = new \App\Entities\Log();
        $lSistema->log_tipo = $args["log_tipo"] ?? null;
        $lSistema->log_accion = $args["log_accion"] ?? null;
        $lSistema->log_linea = $args["log_linea"] ?? null;
        $lSistema->log_controller = $args["log_controller"] ?? null;
        $lSistema->log_method = $args["log_method"] ?? null;
        $lSistema->log_mensaje = $args["log_mensaje"] ?? null;
        $lSistema->log_exception = $args["log_exception"] ?? null;
        $lSistema->log_request_respond = $args["log_request_respond"] ?? null;
        $lSistema->log_origen = $args["log_origen"] ?? null;
        $lSistema->log_usuario = $args["log_usuario"] ?? null;
        $lSistema->log_usuario_id = $args["log_usuario_id"] ?? null;
        $lSistema->log_usuario_correo = $args["log_usuario_correo"] ?? null;
        $lSistema->log_request_ip = $args["log_request_ip"] ?? null;
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
        $jwt_key = getenv("JWT_SECRET");
        $jwt_algorithm = getenv("JWT_ALGORITHM");
        $iat = time(); // current timestamp value
        $exp = $iat + 3600;
        $createdAt = $iat;
        $email = $args["email"];
        $role = $args["role"];
        $user = $args["user"];
        $name = $args["name"];
        $id = $args["id"];

        $payload = [
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "createdAt" => $createdAt,
            "email" => $email,
            "role" => $role,
            "user" => $user,
            "name" => $name,
            "id" => $id,

        ];

        $token = JWT::encode($payload, $jwt_key, $jwt_algorithm);
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

function getDataTokenFromRequest($request)
{
    try {
        $token = null;
        $header = $request->header("Authorization");

        if (empty($header)) {
            return null;
        } else {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        if (is_null($token) || empty($token)) {
            return null;
        }

        $jwt_key = getenv("JWT_SECRET");
        $jwt_algorithm = getenv("JWT_ALGORITHM");

        $decoded = JWT::decode($token, new Key($jwt_key, $jwt_algorithm));
        return $decoded;
    } catch (Exception $ex) {
        // echo $ex->getMessage();
        //var_dump($ex);
        return null;
    }

}
