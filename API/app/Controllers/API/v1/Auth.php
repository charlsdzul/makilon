<?php

namespace App\Controllers\API\v1;

use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Auth extends ResourceController
{
    use ResponseTrait;
    public $session = null;
    public $router = null;
    public $lUsario = null;
    public $controllerName = "API/v1/Auth";

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->router = \Config\Services::router();
        helper("/Validators/usuarioValidator");
        helper("/utils");

    }

    // private function logUsuario($dataLog)
    // {
    //     $logger = new PlpxLogger();

    //     try {
    //         $logger = new PlpxLogger();
    //         $timeNow = new Time("now");
    //         $mensajeJson = is_object($dataLog["mensaje_objeto"] ?? null) ? json_encode($dataLog["mensaje_objeto"]) : null;

    //         $lUsuario = new \App\Entities\LogUsuario([
    //             "log_controller" => $this->controllerName,
    //             "log_method" => $this->router->methodName(),
    //             "log_request_ip" => $this->request->getIPAddress(),
    //             "log_inserted_at" => $timeNow,
    //         ]);

    //         $lUsuario->log_usuario_id = $dataLog["usuario_id"] ?? ($this->session->usu_id ?? null);
    //         $lUsuario->log_usuario = $dataLog["usuario_usuario"] ?? ($this->session->usu_usuario ?? null);
    //         $lUsuario->log_usuario_correo = $dataLog["usuario_correo"] ?? ($this->session->usu_correo ?? null);
    //         $lUsuario->log_accion = $dataLog["accion"] ?? null;
    //         $lUsuario->log_mensaje = $dataLog["mensaje"] ?? null;
    //         $lUsuario->log_mensaje_json = $mensajeJson ?? null;
    //         $lUsuario->log_request_respond = $dataLog["request_respond"] ?? null;
    //         $lUsuario->log_linea = $dataLog["linea"] ?? null;
    //         $logger->loggerUsuario($lUsuario);
    //     } catch (Exception $e) {
    //         $logger->log("critical", $e, __LINE__);
    //     }
    // }

    // private function logSistema($dataLog)
    // {
    //     $logger = new PlpxLogger();

    //     try {
    //         $logger = new PlpxLogger();
    //         $timeNow = new Time("now");
    //         $mensajeJson = is_object($dataLog["mensaje_objeto"]) ? json_encode($dataLog["mensaje_objeto"]) : $dataLog["mensaje_objeto"];

    //         $lSistema = new \App\Entities\LogSistema([
    //             "logsis_controller" => $this->controllerName,
    //             "logsis_method" => $this->router->methodName(),
    //             "logsis_request_ip" => $this->request->getIPAddress(),
    //             "logsis_inserted_at" => $timeNow,
    //             //"logsis_usuario_id" => $this->session->usu_id ?? null,
    //         ]);

    //         $lSistema->logsis_tipo = $dataLog["tipo"] ?? null;
    //         $lSistema->logsis_accion = $dataLog["accion"] ?? null;
    //         $lSistema->logsis_mensaje = $dataLog["mensaje"] ?? null;
    //         $lSistema->logsis_mensaje_json = $mensajeJson;
    //         $lSistema->logsis_request_respond = $dataLog["request_respond"] ?? null;
    //         $lSistema->logsis_linea = $dataLog["linea"] ?? null;
    //         $logger->loggerSistema($lSistema);
    //     } catch (Exception $e) {
    //         $logger->log("critical", $e, __LINE__);
    //     }
    // }

    private function logSistema($args)
    {
        $args2 = [
            "logsis_controller" => $this->controllerName,
            "logsis_method" => $this->router->methodName(),
            "logsis_request_ip" => $this->request->getIPAddress(),
        ];

        logSistema([...$args, ...$args2]);
    }

    private function logUsuario($args)
    {
        $args2 = [
            "logsis_controller" => $this->controllerName,
            "logsis_method" => $this->router->methodName(),
            "logsis_request_ip" => $this->request->getIPAddress(),
        ];

        logUsuario([...$args, ...$args2]);
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
            if (!loginValidator($this->request->getPost(), $errors)) {
                $dataLog = [
                    "tipo" => "notice",
                    "accion" => $logAccion,
                    "linea" => __LINE__,
                    "mensaje_objeto" => $errors,
                    "mensaje" => "No pasó las validaciones. " . getErrorResponseByCode(["code" => 203, "onlyDetail" => true]),
                    "request_respond" => "invalid_request",
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", $errors);
            }

            $correo = $this->request->getPost("correo");
            $contrasena = $this->request->getPost("contrasena");
            $usuarioModel = new UsuarioModel();

            $usuario = $usuarioModel
                ->select("usu_id,usu_usuario,usu_password,usu_correo,usu_nombre,usu_apellido,usu_tipo,usu_sta")
                ->where("usu_correo", $correo)
                ->first();

            if (is_null($usuario)) {
                $dataLog = [
                    "tipo" => "notice",
                    "accion" => $logAccion,
                    "linea" => __LINE__,
                    "mensaje" => "El correo no está registrado. " . getErrorResponseByCode(["code" => 1007, "onlyDetail" => true]),
                    "mensaje_json" => null,
                    "request_respond" => "invalid_request",
                ];

                $this->logSistema($dataLog);
                $response = getErrorResponseByCode(["code" => 1007]);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            if ($usuario->usu_sta == 0) {
                $dataLog = [
                    "accion" => $logAccion,
                    "usuario_id" => $usuario->usu_id,
                    "usuario_usuario" => $usuario->usu_usuario,
                    "usuario_correo" => $usuario->usu_correo,
                    "mensaje" => "La cuenta está desactivada." . getErrorResponseByCode(["code" => 1008, "onlyDetail" => true]),
                    "request_respond" => "invalid_request",
                ];

                $this->logUsuario($dataLog);
                $response = getErrorResponseByCode(["code" => 1008]);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            if (!password_verify($contrasena, $usuario->usu_password)) {
                $dataLog = [
                    "accion" => $logAccion,
                    "usuario_id" => $usuario->usu_id,
                    "usuario_usuario" => $usuario->usu_usuario,
                    "usuario_correo" => $usuario->usu_correo,
                    "mensaje" => "La contraseña ingresada es diferente. " . getErrorResponseByCode(["code" => 1007, "onlyDetail" => true]),
                    "request_respond" => "invalid_request",
                ];

                $this->logUsuario($dataLog);
                $response = getErrorResponseByCode(["code" => 1007]);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            $args = [
                "email" => $usuario->usu_correo,
                "user" => $usuario->usu_usuario,
                "name" => $usuario->usu_nombre,
                "role" => "admin",
            ];

            $token = crearToken($args);

            if ($token == null) {
                $dataLog = [
                    "accion" => $logAccion,
                    "usuario_id" => $usuario->usu_id,
                    "usuario_usuario" => $usuario->usu_usuario,
                    "usuario_correo" => $usuario->usu_correo,
                    "mensaje" => "No se pudo generar el token, se generó como NULL. " . getErrorResponseByCode(["code" => 801, "onlyDetail" => true]),
                    "request_respond" => "invalid_request",
                ];

                $this->logUsuario($dataLog);
                $response = getErrorResponseByCode(["code" => 801, "title" => lang("Lang.title.errorLogin")]);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            $response = [
                "token" => $token,
            ];

            $dataLog = [
                "accion" => $logAccion,
                "usuario_id" => $usuario->usu_id,
                "usuario_usuario" => $usuario->usu_usuario,
                "usuario_correo" => $usuario->usu_correo,
                "mensaje" => getErrorResponseByCode(["code" => 801, "onlyDetail" => true]),
                "request_respond" => "ok - " . lang("Lang.detail.sesionIniciada"),
            ];

            $this->logUsuario($dataLog);
            return $this->apiResponse("ok", $response);
        } catch (\Exception $e) {
            $dataLog = [
                "tipo" => "critical",
                "accion" => $logAccion,
                "linea" => __LINE__,
                "mensaje" => "Error catch. " . $e->getMessage() . ". " . lang("Lang.detail.solicitudNoProcesada"),
                "mensaje_objeto" => $e->getMessage(),
                "request_respond" => "server_error",
            ];

            $this->logSistema($dataLog);
            $response = getErrorResponseByCode(["code" => 801, "title" => lang("Lang.title.errorLogin")]);
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
        $logAccion = "Verificat JWT";

        try {

            $requestValues = $this->request->getPost();

            if (!authenticatedValidator($requestValues, $errors)) {
                $dataLog = [
                    "mensaje" => lang("Common.errorDatosValidacion"),
                    "mensaje_objeto" => $errors,
                    "request_respond" => "invalid_request",
                ];

                $this->logSistema(["tipo" => "notice", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
                return $this->apiResponseError("invalid_request", $errors);
            }

            $jwt = $requestValues["jwt"];
            $email = $requestValues["email"];
            $key = getenv("JWT_SECRET");
            $payload = "";

            try {
                $payload = JWT::decode($jwt, new Key($key, 'HS256'));
            } catch (Exception $e) {
                $message = $e->getMessage();
                $response = getErrorsjwt(2000, $message);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            if ($payload->email == $email) {
                $response = [
                    "token" => "asasas",

                ];
                return $this->apiResponse("ok", [$response]);

            } else {
                $res = array("status" => false, "Error" => "Invalid Token or Token Exipred, So Please login Again!");
                return $res;
            };
        } catch (Exception $e) {
            $dataLog = [
                "mensaje" => lang("Common.solicitudNoProcesada"),
                "mensaje_objeto" => $e->getMessage(),
                "request_respond" => "server_error",
            ];

            $this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
            return $this->apiResponseError("server_error", [getErrorsCommon(801, lang("Usuario.errorLogin"))]);
        }
    }

}
