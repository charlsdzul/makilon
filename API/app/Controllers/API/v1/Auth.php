<?php

namespace App\Controllers\API\v1;

use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends ResourceController
{
    use ResponseTrait;
    public $router = null;
    public function __construct()
    {
        $this->router = \Config\Services::router();
        helper("/Validators/usuarioValidator");
        helper("/utils");
    }

    private function logSistema($args)
    {
        $args2 = [
            "log_controller" => "API/v1/Auth",
            "log_method" => $this->router->methodName(),
            "log_request_ip" => $this->request->getIPAddress(),
        ];

        logSistema([...$args, ...$args2]);
    }

    /**
     * Permite al usuario hacer login en el sistema.
     *
     * @return Response
     */
    public function login()
    {
        $logAccion = "Iniciar sesión";

        try {

            $requestBody = $this->request->getJsonVar(["correo", "contrasena"]);

            // var_dump($requestBody);

            if (!loginValidator($requestBody, $errors)) {

                //Se guarda LOG porque, en teoria, no deberia tener errores en el validator,
                //porque en el front ya estan la validaciones.
                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "warning", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "No pasó las validaciones del VALIDATOR.",
                    "log_request_respond" => "invalid_request",
                    "log_exception" => json_encode($errors),
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", $errors);
            }

            $correo = $requestBody["correo"];
            $contrasena = $requestBody["contrasena"];
            $usuarioModel = new UsuarioModel();

            $usuario = $usuarioModel
                ->select("usu_id,usu_usuario,usu_password,usu_correo,usu_nombre,usu_apellido,usu_tipo,usu_sta")
                ->where("usu_correo", $correo)
                ->first();

            if (is_null($usuario)) {

                $response = getErrorResponseByCode(["code" => 1007]);

                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "notice", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "El correo no está registrado.",
                    "log_request_respond" => "invalid_request",
                    "log_usuario_respuesta" => $response,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            if ($usuario->usu_sta == 0) {

                $response = getErrorResponseByCode(["code" => 1008]);

                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "notice", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "La cuenta está desactivada.",
                    "log_request_respond" => "invalid_request",
                    "log_usuario_respuesta" => $response,
                    "usuario_id" => $usuario->usu_id,
                    "usuario_usuario" => $usuario->usu_usuario,
                    "usuario_correo" => $usuario->usu_correo,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            if (!password_verify($contrasena, $usuario->usu_password)) {
                $response = getErrorResponseByCode(["code" => 1007]);

                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "notice", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "La contraseña ingresada es diferente a la del usuario",
                    "log_request_respond" => "invalid_request",
                    "log_usuario_respuesta" => $response,
                    "usuario_id" => $usuario->usu_id,
                    "usuario_usuario" => $usuario->usu_usuario,
                    "usuario_correo" => $usuario->usu_correo,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            $args = [
                "email" => $usuario->usu_correo,
                "user" => $usuario->usu_usuario,
                "name" => $usuario->usu_nombre,
                "role" => "admin",
                "id" => $usuario->usu_id,

            ];

            $token = crearToken($args);

            if ($token == null) {

                $response = getErrorResponseByCode(["code" => 201, "title" => lang("Lang.title.login")]);

                $dataLog = [
                    "log_origen" => "sistema", "log_tipo" => "critical", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "No se pudo generar el token, se generó como NULL.",
                    "log_request_respond" => "invalid_request",
                    "log_usuario_respuesta" => $response,
                    "usuario_id" => $usuario->usu_id,
                    "usuario_usuario" => $usuario->usu_usuario,
                    "usuario_correo" => $usuario->usu_correo,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            $response = [
                "token" => $token,
            ];

            $dataLog = [
                "log_origen" => "sistema", "log_tipo" => "info", "log_accion" => $logAccion, "log_linea" => __LINE__,
                "log_mensaje" => lang("Lang.detail.sesionIniciada"),
                "log_request_respond" => "ok",
                "usuario_id" => $usuario->usu_id,
                "usuario_usuario" => $usuario->usu_usuario,
                "usuario_correo" => $usuario->usu_correo,
            ];

            $this->logSistema($dataLog);
            return $this->apiResponse("ok", $response);
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            $response = getErrorResponseByCode(["code" => 210]);

            $dataLog = [
                "log_origen" => "usuario", "log_tipo" => "critical", "log_accion" => $logAccion, "log_linea" => __LINE__,
                "log_mensaje" => "ERROR CATCH. " . $mensaje,
                "log_request_respond" => "server_error",
                "log_exception" => $e,
                "log_usuario_respuesta" => $response,
            ];

            $this->logSistema($dataLog);
            $response = getErrorResponseByCode(["code" => 201, "title" => lang("Lang.title.errorLogin")]);
            return $this->apiResponseError("server_error", [$response]);
        }
    }

    /**
     * Verifica si el token de un usuario esta vigente
     *
     * @return Response
     */
    public function authenticated()
    {
        $logAccion = "Verificat Token";

        try {

            $requestBody = $this->request->getJsonVar(["token", "email"]);

            if (!authenticatedValidator($requestBody, $errors)) {
                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "warning", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => lang("Lang.detail.errorDatosValidacion"),
                    "log_request_respond" => "invalid_request",
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", $errors);
            }

            $token = $requestBody["token"];
            $email = $requestBody["email"];
            $jwt_key = getenv("JWT_SECRET");
            $jwt_algorithm = getenv("JWT_ALGORITHM");
            $payload = "";

            try {
                $payload = JWT::decode($token, new Key($jwt_key, $jwt_algorithm));
            } catch (\Exception $e) {
                $message = $e->getMessage();

                $response = [...getErrorResponseByCode(["code" => 2001]), "isValidToken" => false];

                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "critical", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "ERROR CATCH. " . $message . ". TOKEN: " . $token,
                    "log_request_respond" => "invalid_request",
                    "log_exception" => $e,
                    "log_usuario_respuesta" => $response,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", $response);
            }

            if ($payload->email == $email) {
                $response = [
                    "token" => $token,
                    "isValidToken" => true,
                    "tokenRenew" => null,
                ];

                return $this->apiResponse("ok", $response);

            } else {

                $response = [...getErrorResponseByCode(["code" => 2001]), "isValidToken" => false];

                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "critical", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "Email del token ($payload->email), es diferente del enviado en peticion ($email). TOKEN: " . $token,
                    "log_request_respond" => "invalid_request",
                    "log_usuario_respuesta" => $response,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", $response);
            };
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            $response = getErrorResponseByCode(["code" => 201, "title" => lang("Lang.title.auth")]);

            $dataLog = [
                "log_origen" => "usuario", "log_tipo" => "critical", "log_accion" => $logAccion, "log_linea" => __LINE__,
                "log_mensaje" => "ERROR CATCH. " . $mensaje,
                "log_request_respond" => "server_error",
                "log_exception" => $e,
                "log_usuario_respuesta" => $response,
            ];

            $this->logSistema($dataLog);
            return $this->apiResponseError("server_error", $response);
        }
    }
}
