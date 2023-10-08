<?php

namespace App\Controllers\API\v1;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Vacante extends ResourceController
{
    use ResponseTrait;
    public $session = null;
    public $router = null;
    public $lUsario = null;
    public $controllerName = "API/v1/Vacante";

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->router = \Config\Services::router();
        helper("/Validators/usuarioValidator");
        helper("/utils");

    }

    private function logSistema($args)
    {
        $args2 = [
            "controller" => $this->controllerName,
            "method" => $this->router->methodName(),
            "request_ip" => $this->request->getIPAddress(),
        ];

        logSistema([...$args, ...$args2]);
    }

    private function logUsuario($args)
    {
        $args2 = [
            "controller" => $this->controllerName,
            "method" => $this->router->methodName(),
            "request_ip" => $this->request->getIPAddress(),
        ];

        logUsuario([...$args, ...$args2]);
    }

    /**
     * Permite al usuario hacer login en el sistema.
     *
     * @return Response
     */
    public function crear()
    {
        $logAccion = "Crear Vacante";

        try {
            $titulo = $this->request->getJsonVar("titulo");
            $puesto = $this->request->getJsonVar("puesto");
            $puestoOtro = $this->request->getJsonVar("puestoOtro");
            $puestoEspecifico = $this->request->getJsonVar("puestoEspecifico");
            $puestoEspecificoOtro = $this->request->getJsonVar("puestoEspecificoOtro");

            $data = [
                "vac_titulo" => $titulo,
                "vac_puesto" => $puesto,
                "vac_puestoOtro" => $puestoOtro,
                "vac_puestoEspecifico" => $puestoEspecifico,
                "puestoEspecificoOtro" => $puestoEspecificoOtro,
                "vac_sta" => 1,
            ];

            $vacante = new VacanteModel();
            $idInserted = $vacante->insert($data);

            // if (!loginValidator($this->request->getPost(), $errors)) {
            //     $dataLog = [
            //         "tipo" => "notice",
            //         "accion" => $logAccion,
            //         "linea" => __LINE__,
            //         "exception" => $errors,
            //         "mensaje" => "No pasó las validaciones. " . getErrorResponseByCode(["code" => 203, "onlyDetail" => true]),
            //         "request_respond" => "invalid_request",
            //     ];

            //     $this->logSistema($dataLog);
            //     return $this->apiResponseError("invalid_request", $errors);
            // }

            // $correo = $this->request->getPost("correo");
            // $contrasena = $this->request->getPost("contrasena");
            // $usuarioModel = new UsuarioModel();

            // $usuario = $usuarioModel
            //     ->select("usu_id,usu_usuario,usu_password,usu_correo,usu_nombre,usu_apellido,usu_tipo,usu_sta")
            //     ->where("usu_correo", $correo)
            //     ->first();

            // if (is_null($usuario)) {
            //     $dataLog = [
            //         "tipo" => "notice",
            //         "accion" => $logAccion,
            //         "linea" => __LINE__,
            //         "mensaje" => "El correo no está registrado. " . getErrorResponseByCode(["code" => 1007, "onlyDetail" => true]),
            //         "mensaje_json" => null,
            //         "request_respond" => "invalid_request",
            //     ];

            //     $this->logSistema($dataLog);
            //     $response = getErrorResponseByCode(["code" => 1007]);
            //     return $this->apiResponseError("invalid_request", [$response]);
            // }

            // if ($usuario->usu_sta == 0) {
            //     $dataLog = [
            //         "accion" => $logAccion,
            //         "usuario_id" => $usuario->usu_id,
            //         "usuario_usuario" => $usuario->usu_usuario,
            //         "usuario_correo" => $usuario->usu_correo,
            //         "mensaje" => "La cuenta está desactivada." . getErrorResponseByCode(["code" => 1008, "onlyDetail" => true]),
            //         "request_respond" => "invalid_request",
            //     ];

            //     $this->logUsuario($dataLog);
            //     $response = getErrorResponseByCode(["code" => 1008]);
            //     return $this->apiResponseError("invalid_request", [$response]);
            // }

            // if (!password_verify($contrasena, $usuario->usu_password)) {
            //     $dataLog = [
            //         "accion" => $logAccion,
            //         "usuario_id" => $usuario->usu_id,
            //         "usuario_usuario" => $usuario->usu_usuario,
            //         "usuario_correo" => $usuario->usu_correo,
            //         "mensaje" => "La contraseña ingresada es diferente. " . getErrorResponseByCode(["code" => 1007, "onlyDetail" => true]),
            //         "request_respond" => "invalid_request",
            //     ];

            //     $this->logUsuario($dataLog);
            //     $response = getErrorResponseByCode(["code" => 1007]);
            //     return $this->apiResponseError("invalid_request", [$response]);
            // }

            // $args = [
            //     "email" => $usuario->usu_correo,
            //     "user" => $usuario->usu_usuario,
            //     "name" => $usuario->usu_nombre,
            //     "role" => "admin",
            // ];

            // $token = crearToken($args);

            // if ($token == null) {
            //     $dataLog = [
            //         "accion" => $logAccion,
            //         "usuario_id" => $usuario->usu_id,
            //         "usuario_usuario" => $usuario->usu_usuario,
            //         "usuario_correo" => $usuario->usu_correo,
            //         "mensaje" => "No se pudo generar el token, se generó como NULL. " . getErrorResponseByCode(["code" => 801, "onlyDetail" => true]),
            //         "request_respond" => "invalid_request",
            //     ];

            //     $this->logUsuario($dataLog);
            //     $response = getErrorResponseByCode(["code" => 801, "title" => lang("Lang.title.errorLogin")]);
            //     return $this->apiResponseError("invalid_request", [$response]);
            // }

            $response = [
                "response" => 111111,
            ];

            // $dataLog = [
            //     "accion" => $logAccion,
            //     "usuario_id" => $usuario->usu_id,
            //     "usuario_usuario" => $usuario->usu_usuario,
            //     "usuario_correo" => $usuario->usu_correo,
            //     "mensaje" => getErrorResponseByCode(["code" => 801, "onlyDetail" => true]),
            //     "request_respond" => "ok - " . lang("Lang.detail.sesionIniciada"),
            // ];

            // $this->logUsuario($dataLog);
            return $this->apiResponse("ok", $response);
        } catch (\Exception $e) {
            var_dump($e);
            // $dataLog = [
            //     "tipo" => "critical",
            //     "accion" => $logAccion,
            //     "linea" => __LINE__,
            //     "mensaje" => "Error catch. " . $e->getMessage() . ". " . lang("Lang.detail.solicitudNoProcesada"),
            //     "exception" => $e,
            //     "request_respond" => "server_error",
            // ];

            // $this->logSistema($dataLog);
            // $response = getErrorResponseByCode(["code" => 801, "title" => lang("Lang.title.errorLogin")]);
            // return $this->apiResponseError("server_error", [$response]);
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

            $requestValues = $this->request->getPost();

            if (!authenticatedValidator($requestValues, $errors)) {
                $dataLog = [
                    "mensaje" => lang("Lang.detail.errorDatosValidacion"),
                    "exception" => $errors,
                    "request_respond" => "invalid_request",
                ];

                $this->logSistema(["tipo" => "notice", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
                return $this->apiResponseError("invalid_request", $errors);
            }

            $token = $requestValues["token"];
            $email = $requestValues["email"];
            $key = getenv("JWT_SECRET");
            $payload = "";

            try {
                $payload = JWT::decode($token, new Key($key, 'HS256'));
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $dataLog = [
                    "tipo" => "notice",
                    "accion" => $logAccion,
                    "mensaje" => "Error catch. " . $message . ". TOKEN: " . $token,
                    "exception" => $e,
                    "request_respond" => "invalid_request",
                    "linea" => __LINE__,
                ];

                $this->logSistema($dataLog);
                $response = [...getErrorResponseByCode(["code" => 2001]), "isValidToken" => false];
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

                $dataLog = [
                    "tipo" => "critical",
                    "accion" => $logAccion,
                    "mensaje" => "Email del token ($payload->email), es diferente del enviado en peticion ($email). TOKEN: " . $token,
                    "request_respond" => "invalid_request",
                    "linea" => __LINE__,
                ];

                $this->logSistema($dataLog);
                $response = [...getErrorResponseByCode(["code" => 2001]), "isValidToken" => false];
                return $this->apiResponseError("invalid_request", $response);
            };
        } catch (\Exception $e) {
            $dataLog = [
                "mensaje" => lang("Common.solicitudNoProcesada"),
                "exception" => $e,
                "request_respond" => "server_error",
            ];

            $this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
            return $this->apiResponseError("server_error", [getErrorsCommon(801, lang("Usuario.errorLogin"))]);
        }
    }

}
