<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UsuarioPreregistroModel;
use App\Models\UsuarioRecuperacionPasswordModel;
use App\Models\UsuarioModel;
use Error;
use CodeIgniter\I18n\Time;
use App\Libraries\PlpxLogger;
use Exception;
use ErrorException;
use CodeIgniter\HTTP\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\UnexpectedValueException;




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
	}

	private function logUsuario($dataLog)
	{
		$logger = new PlpxLogger();

		try {
			$logger = new PlpxLogger();
			$timeNow = new Time("now");
			$mensajeJson = is_object($dataLog["mensaje_objeto"] ?? null) ? json_encode($dataLog["mensaje_objeto"]) : null;

			$lUsuario = new \App\Entities\LogUsuario([
				"log_controller" => $this->controllerName,
				"log_method" => $this->router->methodName(),
				"log_request_ip" => $this->request->getIPAddress(),
				"log_inserted_at" => $timeNow,
			]);

			$lUsuario->log_usuario_id = $dataLog["usuario_id"] ?? ($this->session->usu_id ?? null);
			$lUsuario->log_usuario = $dataLog["usuario_usuario"] ?? ($this->session->usu_usuario ?? null);
			$lUsuario->log_usuario_correo = $dataLog["usuario_correo"] ?? ($this->session->usu_correo ?? null);
			$lUsuario->log_accion = $dataLog["accion"] ?? null;
			$lUsuario->log_mensaje = $dataLog["mensaje"] ?? null;
			$lUsuario->log_mensaje_json = $mensajeJson ?? null;
			$lUsuario->log_request_respond = $dataLog["request_respond"] ?? null;
			$lUsuario->log_linea = $dataLog["linea"] ?? null;
			$logger->loggerUsuario($lUsuario);
		} catch (Exception $e) {
			$logger->log("critical", $e, __LINE__);
		}
	}

	private function logSistema($dataLog)
	{
		$logger = new PlpxLogger();

		try {
			$logger = new PlpxLogger();
			$timeNow = new Time("now");
			$mensajeJson = is_object($dataLog["mensaje_objeto"]) ? json_encode($dataLog["mensaje_objeto"]) : $dataLog["mensaje_objeto"];

			$lSistema = new \App\Entities\LogSistema([
				"logsis_controller" => $this->controllerName,
				"logsis_method" => $this->router->methodName(),
				"logsis_request_ip" => $this->request->getIPAddress(),
				"logsis_inserted_at" => $timeNow,
				//"logsis_usuario_id" => $this->session->usu_id ?? null,
			]);

			$lSistema->logsis_tipo = $dataLog["tipo"] ?? null;
			$lSistema->logsis_accion = $dataLog["accion"] ?? null;
			$lSistema->logsis_mensaje = $dataLog["mensaje"] ?? null;
			$lSistema->logsis_mensaje_json = $mensajeJson;
			$lSistema->logsis_request_respond = $dataLog["request_respond"] ?? null;
			$lSistema->logsis_linea = $dataLog["linea"] ?? null;
			$logger->loggerSistema($lSistema);
		} catch (Exception $e) {
			$logger->log("critical", $e, __LINE__);
		}
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
					"mensaje" => getErrorResponseByCode(203,"","",true),
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request",
				];

				$this->logSistema(["tipo" => "notice", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
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
					"mensaje" => "Correo no existe: $correo",
					"mensaje_json" => null,
					"request_respond" => "invalid_request - " . lang("Usuario.correoContrasenaInvalida"),
				];

				$this->logSistema(["tipo" => "notice", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				$response = getErrorResponseByCode(1007);
				return $this->apiResponseError("invalid_request", [[...$response, "id" => "login1"]]);
			}

			if ($usuario->usu_sta == 0) {
				$dataLog = [
					"usuario_id" => $usuario->usu_id,
					"usuario_usuario" => $usuario->usu_usuario,
					"usuario_correo" => $usuario->usu_correo,
					"mensaje" => "Cuenta inactiva",
					"request_respond" => "invalid_request - " . lang("Usuario.cuentaInactiva"),
				];

				$this->logUsuario(["accion" => $logAccion, ...$dataLog]);
				$response = getErrorResponseByCode(1008);
				return $this->apiResponseError("invalid_request", [[...$response, "id" => "login"]]);
			}

			if (!password_verify($contrasena, $usuario->usu_password)) {
				$dataLog = [
					"usuario_id" => $usuario->usu_id,
					"usuario_usuario" => $usuario->usu_usuario,
					"usuario_correo" => $usuario->usu_correo,
					"mensaje" => "Password incorrecto",
					"request_respond" => "invalid_request - " . lang("Lang.login.correoContrasenaInvalida"),
				];

				$this->logUsuario(["accion" => $logAccion, ...$dataLog]);
				$response = getErrorResponseByCode(1007);
				return $this->apiResponseError("invalid_request", [[...$response, "id" => "login"]]);
			}

			$key = getenv("JWT_SECRET");
			$iat = time(); // current timestamp value
			$exp = $iat + 3600;
			$createdAt = $iat;
			$email = $usuario->usu_correo;
			$role = "admin";
			$user = $usuario->usu_usuario;
			$name = $usuario->usu_nombre;

			$payload = [
				"iat" => $iat, //Time the JWT issued at
				"exp" => $exp, // Expiration time of token
				"createdAt" => $createdAt,
				"email" => $correo,
				"role" => $role,
				"user" => $user,
				"name" => $name,
			];

			$token = JWT::encode($payload, $key, "HS256");

			$response = [
				"token" => $token

			];

			$dataLog = [
				"usuario_id" => $usuario->usu_id,
				"usuario_usuario" => $usuario->usu_usuario,
				"usuario_correo" => $usuario->usu_correo,
				"mensaje" => "Sesión iniciada",
				"request_respond" => "ok - " . lang("Usuario.sesionIniciada"),
			];

			$this->logUsuario(["accion" => $logAccion, ...$dataLog]);

			return $this->apiResponse("ok", [...$response]);
		} catch (Error $e) {

			$dataLog = [
				"mensaje" => lang("Common.solicitudNoProcesada"),
				"mensaje_objeto" => $e->getMessage(),
				"request_respond" => "server_error",
			];

			$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->apiResponseError("server_error", [getErrorResponseByCode(801, lang("Lang.login.errorLogin"))]);
		}
	}

}
