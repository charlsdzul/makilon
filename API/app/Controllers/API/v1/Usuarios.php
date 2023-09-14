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




class Usuarios extends ResourceController
{
	use ResponseTrait;
	public $session = null;
	public $router = null;
	public $lUsario = null;
	public $controllerName = "API/v1/Usuarios";

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
					"mensaje" => lang("Common.errorDatosValidacion"),
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
				$response = getErrorsUsuario(1007);
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
				$response = getErrorsUsuario(1008);
				return $this->apiResponseError("invalid_request", [[...$response, "id" => "login"]]);
			}

			if (!password_verify($contrasena, $usuario->usu_password)) {
				$dataLog = [
					"usuario_id" => $usuario->usu_id,
					"usuario_usuario" => $usuario->usu_usuario,
					"usuario_correo" => $usuario->usu_correo,
					"mensaje" => "Password incorrecto",
					"request_respond" => "invalid_request - " . lang("Usuario.correoContrasenaInvalida"),
				];

				$this->logUsuario(["accion" => $logAccion, ...$dataLog]);
				$response = getErrorsUsuario(1007);
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
			return $this->apiResponseError("server_error", [getErrorsCommon(801, lang("Usuario.errorLogin"))]);
		}
	}

	/**
	 * Permite al usuario hacer logout en el sistema.
	 *
	 * @return Response
	 */

	public function logout()
	{
		$logAccion = "Finalizar sesión";

		try {
			if (!usuarioEstaLogueado()) {
				$dataLog = [
					"mensaje" => "Se intentó finalizar sesión sin estar logueado",
					"request_respond" => "unauthorized - " . lang("Common.api.accesoNoPermitido"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("unauthorized", "Common.api.accesoNoPermitido", false);
			}

			$dataLog = [
				"usuario_id" => $this->session->usu_id,
				"usuario_usuario" => $this->session->usu_usuario,
				"usuario_correo" => $this->session->usu_correo,
				"mensaje" => "Sesióm finalizada",
				"request_respond" => "ok - " . lang("Usuario.sesionFinalizada"),
			];

			$this->logUsuario(["accion" => $logAccion, ...$dataLog]);

			$this->session->destroy();
			return $this->respondPlpx("ok", "Usuario.sesionFinalizada", true);
		} catch (Error $e) {
			$dataLog = [
				"mensaje" => "Error general",
				"mensaje_objeto" => $e,
				"request_respond" => "server_error - " . lang("Common.solicitudNoProcesada"),
			];

			$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->respondPlpx("server_error", "Common.solicitudNoProcesada", false);
		}
	}

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
				$response = getErrorsjwt(2000,$message);
				return $this->apiResponseError("invalid_request", [$response]);
			}


			if ($payload->email == $email) {
				$response = [
					"token" => "asasas"
	
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

	/**
	 * @desc Permite confirmer una cuenta nueva el sistema, según un enlace
	 * que se le envió previamente al usuario por correo.
	 *
	 * @param string $correo, email del usuario
	 * @param string $codigo, codigo de confirmacion
	 * @return Response
	 */
	public function confirmacion($correo = "", $codigo = "")
	{
		$logAccion = "Confirmación cuenta nueva";

		try {
			if (!confirmacionValidator($correo, $codigo, $errors)) {
				$dataLog = [
					"mensaje" => "Formato de correo o codigo de confirmacion invalidos: $correo  -  $codigo",
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request",
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", $errors, false);
			}

			$usuarioPreregistroModel = new UsuarioPreregistroModel();

			//Valida si ya existe un correo registrado y activo en Pre-registro.
			$usuarioPreregistro = $usuarioPreregistroModel
				->where("usupr_correo", $correo)
				->where("usupr_sta", 1)
				->first();

			if (!is_null($usuarioPreregistro)) {
				//Si ya existe...

				$dataLog = [
					"mensaje" => "Cuenta ya está existía en pre-registro y activa.",
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request - " . lang("Usuario.correoYaEstaRegistrado"),
				];

				$this->logSistema(["tipo" => "warning", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.correoYaEstaRegistrado", false);
			}

			$usuarioPreregistro = $usuarioPreregistroModel
				->select("usupr_id,usupr_correo,usupr_password")
				->where("usupr_correo", $correo)
				->where("usupr_codigo_activacion", $codigo)
				->first();

			if (is_null($usuarioPreregistro)) {
				//Combinación de corroe y código no existe en bdd.

				$dataLog = [
					"mensaje" => "Combinacion de correo y código recibidos no existe en pre-registro: $correo -  $codigo",
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request - " . lang("Usuario.correoCodigoNoExisten"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.correoCodigoNoExisten", false);
			}

			// Actualiza el registro para confirmar el correo
			$timeNow = new Time("now");

			$dataUpd = [
				"usupr_updated_at" => $timeNow,
				"usupr_confirmed_at" => $timeNow,
				"usupr_sta" => 1,
			];

			$updated = $usuarioPreregistroModel->update($usuarioPreregistro->usupr_id, $dataUpd);

			if (!$updated) {
				$dataLog = [
					"mensaje" => "No se pudo actualizar el estatus a activo en la confirmacion de registro:  $correo  -  $codigo",
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request - " . lang("Usuario.errorCrearCuenta"),
				];

				$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.errorCrearCuenta", false);
			}

			//Crear registro de usuario en tabla usuarios
			$usuarioModel = new UsuarioModel();
			$usuario = $usuarioModel->where("usu_correo", $correo)->first();

			if (!is_null($usuario)) {
				$dataLog = [
					"mensaje" => "Correo ya pertenece a otro usuario. Correo en pre-registro se marcó como activo, pero el correo ya existía en usuarios: $correo",
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request - " . lang("Usuario.errorCrearCuenta"),
				];

				$this->logSistema(["tipo" => "warning", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.errorCrearCuenta", false);
			}

			$timeNow = new Time("now");

			$dataIns = [
				"usu_correo" => $usuarioPreregistro->usupr_correo,
				"usu_tipo" => 1,
				"usu_password" => $usuarioPreregistro->usupr_password,
				"usu_sta" => 1,
				"usu_created_at" => $timeNow,
				"usu_id_preregistro" => $usuarioPreregistro->usupr_id,
			];

			$idInserted = $usuarioModel->insert($dataIns);

			//VERIFICA SI HAY ERRORES SEGUN EL MODELO
			if (count($usuarioModel->errors()) > 0) {
				$errores = "";

				foreach ($usuarioModel->errors() as $error) {
					$errores = $correo . " - " . $errores . " | " . $error;
				}

				//Si el insert en Usuario no es exitoso, marca el campo sta en 0 del preregistro, para permitirle volver a confirmar.
				$updated = $usuarioPreregistroModel->update($usuarioPreregistro->usupr_id, ["usupr_updated_at" => $timeNow, "usupr_sta" => 0]);

				$dataLog = [
					"mensaje" => "Correo no se pudo insertar en Usuarios. No pasó las validaciones del modelo Usuario: $correo",
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request - " . lang("Usuario.errorCrearCuenta"),
				];



				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.errorCrearCuenta", false);
			}













			if ($idInserted == 0) {
				//Si el insert en Usuario no es exitoso, marca el campo sta en 0 del preregistro, para permitirle volver a confirmar.
				$updated = $usuarioPreregistroModel->update($usuarioPreregistro->usupr_id, ["usupr_updated_at" => $timeNow, "usupr_sta" => 0]);





				$dataLog = [
					"mensaje" => "No se pudo realizar el registro de Usuario: $correo",
					"request_respond" => "invalid_request - " . lang("Usuario.errorCrearCuenta"),
				];



				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.errorCrearCuenta", false);
			}

			$dataLog = [
				"mensaje" => "Usuario confirmado y registrado",
				"request_respond" => "ok - " . lang("Usuario.registroExitoso"),
			];




			$this->logSistema(["tipo" => "info", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->respondPlpx("ok", "Usuario.registroExitoso", true);
		} catch (Error $e) {
			$dataLog = [
				"mensaje" => "Error general",
				"mensaje_objeto" => $e,
				"request_respond" => "server_error - " . lang("Common.solicitudNoProcesada"),
			];

			$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->respondPlpx("server_error", "Common.solicitudNoProcesada", false);
		}
	}

	/**
	 * @desc Permite registrar una cuenta nueva
	 *
	 * @return Response
	 */
	public function registro()
	{
		$logAccion = "Registro de usuario";

		//PENDIENTE DEFINIR LA RESPUESTA DE ERROR QUE NO ES REQUESTTTTTT

		try {
			if (!registroValidator($this->request->getJSON(), $errors)) {
				//var_dump($errors);

				$dataLog = [
					"mensaje" => lang("Common.errorDatosValidacion"),
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request",
				];

				$this->logSistema(["tipo" => "warning", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);

				// $aditionalData = [
				// 	"validatedDate" => lang("Common.errorDatosValidacion"),
				// 	"mensaje_objeto" => $errors,
				// 	"request_respond" => "invalid_request",
				// ];

				return $this->respondPlpx("invalid_request", $errors);
			}

			$correo = $this->request->getJsonVar("usuario_correo");
			$psw = $this->request->getJsonVar("usuario_psw");

			//CONSULTA SI YA EXISTE EL USUARIO
			$usuarioModel = new UsuarioModel();
			$usuario = $usuarioModel->where("usu_correo", $correo)->first();

			if (!is_null($usuario)) {
				$dataLog = [
					"mensaje" => "Correo ya está registrado y activo: " . $correo,
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request",
				];

				$this->logSistema(["tipo" => "warning", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.correoNoDisponible", false);
			}

			//CONSULTA SI YA EXISTE EL USUARIO EN PREREGISTRO
			$usuarioPreregistroModel = new UsuarioPreregistroModel();
			$usuarioPreregistro = $usuarioPreregistroModel
				->where("usupr_correo", $correo)
				->where("usupr_sta", 1)
				->first();

			if (!is_null($usuarioPreregistro)) {
				$dataLog = [
					"mensaje" => "Correo ya está en preregistro y activo: " . $correo,
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request",
				];

				$this->logSistema(["tipo" => "warning", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.correoNoDisponible", false);
			}

			//INSERTA PRE REGISTRO
			/**Mientras no exista un correo con sta 1, hara un insert nuevo. */
			$timeNow = new Time("now");
			$codigo = hash("sha256", $correo . $timeNow);
			$pwdHash = password_hash($psw, PASSWORD_DEFAULT);

			$dataIns = [
				"usupr_correo" => $correo,
				"usupr_password" => $pwdHash,
				"usupr_codigo_activacion" => $codigo,
				"usupr_created_at" => $timeNow,
				"usupr_sta" => 0,
			];

			$idInserted = $usuarioPreregistroModel->insert($dataIns);

			//VERIFICA SI HAY ERRORES SEGUN EL MODELO
			if (count($usuarioPreregistroModel->errors()) > 0) {
				$errores = "";

				foreach ($usuarioPreregistroModel->errors() as $error) {
					$errores = $correo . " - " . $errores . " | " . $error;
				}

				$dataLog = [
					"mensaje" => "Correo no se pudo insertar en Pre-registro. No pasó las validaciones del modelo: $correo",
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request - " . lang("Usuario.errorCrearCuenta"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.errorCrearCuenta", false);
			}

			//ENVIA EMAIL DE CONFIRMACION
			$correoEnlace = anchor(base_url() . URL_API_REGISTRO_CONFIRMACION . $correo . "/" . $codigo, "Confirmar");
			$correoEnlace = anchor(base_url() . URL_API_CONFIRMACION_RECUPERACION . $codigo, lang("Email.recuperacionPassword.reestablecerMiContra"));

			$email = \Config\Services::email();
			$email->setFrom(EMAIL_FROM_GENERAL, lang("Email.generalFrom"));
			$email->setTo($correo);
			$email->setSubject(lang("Usuario.email.emailAsunto"));
			$email->setMessage(lang("Usuario.email.emailCuerpo", [$correo, $correoEnlace]));

			if ($email->send()) {
				$timeNow = new Time("now");
				$updated = $usuarioPreregistroModel->update($idInserted, ["usupr_email_sended_at" => $timeNow]);

				if (!$updated) {
					$dataLog = [
						"mensaje" => "Se envió correo para confirmación, pero no se pudo actualizar el campo usupr_email_sended_at",
					];

					$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				}

				$dataLog = [
					"mensaje" => "Se envió correo de confirmación de registro",
					"request_respond" => "ok - " . lang("Usuario.email.preregistroExitoso"),
				];

				$this->logSistema(["tipo" => "info", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("ok", "Usuario.email.preregistroExitoso", true);
			} else {
				$data = $email->printDebugger(["headers"]);

				$dataLog = [
					"mensaje" => "No se pudo enviar correo para confirmación de registro: $correo",
					"mensaje_objeto" => $data,
					"request_respond" => "invalid_request - " . lang("Usuario.errorCrearCuenta"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.errorCrearCuenta", false);
			}
		} catch (Error $e) {
			var_dump($e);
			$dataLog = [
				"mensaje" => "Error general",
				"mensaje_objeto" => $e,
				"request_respond" => "server_error - " . lang("Common.solicitudNoProcesada"),
			];

			$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->respondPlpx("server_error", "Common.solicitudNoProcesada", false);
		}
	}

	/**
	 * @desc Permite actualizar datos del usuario
	 *
	 * @return Response
	 */
	public function actualizaDatos()
	{
		$logAccion = "Actualizar datos de usuario";

		try {
			if (!usuarioEstaLogueado()) {
				$dataLog = [
					"mensaje" => "Se intentó actualizar datos de usuario sin estar logueado",
					"request_respond" => "unauthorized - " . lang("Common.api.accesoNoPermitido"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("unauthorized", "Common.api.accesoNoPermitido", false);
			}

			if (actualizaDatosValidator($this->request->getJSON(), $errors)) {
				$dataLog = [
					"mensaje" => lang("Common.errorDatosValidacion"),
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request",
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", $errors, false);
			}

			$usuarioModel = new UsuarioModel();
			$usuario = $this->request->getJsonVar("usuario_usuario");
			$nombre = $this->request->getJsonVar("usuario_nombre");
			$nombre = $this->request->getJsonVar("usuario_apellido");
			$timeNow = new Time("now");

			$dataUpd = [
				"usu_updated_at" => $timeNow,
				"usu_usuario" => $usuario,
				"usu_nombre" => $nombre,
				"usu_apellido" => $nombre,
			];

			$updated = $usuarioModel->update($this->session->usu_id, $dataUpd);

			if (count($usuarioModel->errors()) > 0) {
				$errores = "";

				foreach ($usuarioModel->errors() as $error) {
					$errores = $errores . " | " . $error;
				}

				$dataLog = [
					"mensaje" => "No pasó las validaciones del modelo Usuario: " . $errores,
					"mensaje_objeto" => $dataUpd,
					"request_respond" => "invalid_request - " . lang("Usuario.datosNoActualizados"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.datosNoActualizados", false);
			}

			if ($updated) {
				$dataLog = [
					"mensaje" => "Datos actualizados",
					"request_respond" => "ok - " . lang("Usuario.datosActualizados"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("ok", "Usuario.datosActualizados", true);
			} else {
				$dataLog = [
					"mensaje" => "Datos no actualizados",
					"request_respond" => "invalid_request - " . lang("Usuario.datosNoActualizados"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.datosNoActualizados", false);
			}
		} catch (Error $e) {
			$dataLog = [
				"mensaje" => "Error general",
				"mensaje_objeto" => $e,
				"request_respond" => "server_error - " . lang("Common.solicitudNoProcesada"),
			];

			$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->respondPlpx("server_error", "Common.solicitudNoProcesada", false);
		}
	}

	/**
	 * @desc Permite actualizar el correo del usuario
	 *
	 * @return Response
	 */
	public function actualizaCorreo()
	{
		$logAccion = "Actualizar correo de usuario";

		try {
			if (!usuarioEstaLogueado()) {
				$dataLog = [
					"mensaje" => "Se intentó actualizar correo de usuario sin estar logueado",
					"request_respond" => "unauthorized - " . lang("Common.api.accesoNoPermitido"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("unauthorized", "Common.api.accesoNoPermitido", false);
			}

			if (actualizaCorreoValidator($this->request->getJSON(), $errors)) {
				$dataLog = [
					"mensaje" => lang("Common.errorDatosValidacion"),
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request",
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", $errors, false);
			}

			$usuarioModel = new UsuarioModel();
			$correo = $this->request->getJsonVar("usuario_correo");
			$timeNow = new Time("now");

			$dataUpd = [
				"usu_updated_at" => $timeNow,
				"usu_correo" => $correo,
			];

			$updated = $usuarioModel->update($this->session->usu_id, $dataUpd);

			if (count($usuarioModel->errors()) > 0) {
				$errores = "";

				foreach ($usuarioModel->errors() as $error) {
					$errores = $errores . " | " . $error;
				}

				$dataLog = [
					"mensaje" => "No pasó las validaciones del modelo Usuario: " . $errores,
					"mensaje_objeto" => $dataUpd,
					"request_respond" => "invalid_request - " . lang("Usuario.correoNoActualizado"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.correoNoActualizado", false);
			}

			if ($updated) {
				$dataLog = [
					"mensaje" => "Datos actualizados",
					"mensaje_objeto" => $dataUpd,
					"request_respond" => "ok - " . lang("Usuario.correoActualizado"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("ok", "Usuario.correoActualizado", true);
			} else {
				$dataLog = [
					"mensaje" => "Datos no actualizados",
					"mensaje_objeto" => $dataUpd,
					"request_respond" => "invalid_request - " . lang("Usuario.correoNoActualizado"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.correoNoActualizado", false);
			}
		} catch (Error $e) {
			$dataLog = [
				"mensaje" => "Error general",
				"mensaje_objeto" => $e,
				"request_respond" => "server_error - " . lang("Common.solicitudNoProcesada"),
			];

			$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->respondPlpx("server_error", "Common.solicitudNoProcesada", false);
		}
	}

	/**
	 * Actualiza la contraseña del usuario logueado.
	 *
	 * @return Response
	 */
	public function actualizaPassword()
	{
		$logAccion = "Actualizar contraseña de usuario";

		try {
			if (!usuarioEstaLogueado()) {
				$dataLog = [
					"mensaje" => "Se intentó actualizar contraseña de usuario sin estar logueado",
					"request_respond" => "unauthorized - " . lang("Common.api.accesoNoPermitido"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("unauthorized", "Common.api.accesoNoPermitido", false);
			}

			if (!actualizaPasswordValidator($this->request->getJSON(), $errors)) {
				$dataLog = [
					"mensaje" => lang("Common.errorDatosValidacion"),
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request",
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", $errors, false);
			}

			$usuarioModel = new UsuarioModel();
			$psw = $this->request->getJsonVar("usuario_psw");
			$timeNow = new Time("now");
			$pwdHash = password_hash($psw, PASSWORD_DEFAULT);

			$dataUpd = [
				"usu_updated_at" => $timeNow,
				"usu_password" => $pwdHash,
			];

			$updated = $usuarioModel->update($this->session->usu_id, $dataUpd);

			if (count($usuarioModel->errors()) > 0) {
				$errores = "";

				foreach ($usuarioModel->errors() as $error) {
					$errores = $errores . " | " . $error;
				}

				$dataLog = [
					"mensaje" => "No pasó las validaciones del modelo Usuario: " . $errores,
					"mensaje_objeto" => $dataUpd,
					"request_respond" => "invalid_request - " . lang("Usuario.passwordNoActualizado"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request - ", "Usuario.passwordNoActualizado", false);
			}

			if ($updated) {
				$dataLog = [
					"mensaje" => "Password actualizado",
					"mensaje_objeto" => $dataUpd,
					"request_respond" => "ok - " . lang("Usuario.passwordActualizado"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("ok", "Usuario.actualizarDatos.datosActualizados", true);
			} else {
				$dataLog = [
					"mensaje" => "Password no actualizado",
					"mensaje_objeto" => $dataUpd,
					"request_respond" => "invalid_request - " . lang("Usuario.passwordNoActualizado"),
				];

				$this->logUsuario(["accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request - ", "Usuario.passwordNoActualizado", false);
			}
		} catch (Error $e) {
			$dataLog = [
				"mensaje" => "Error general",
				"mensaje_objeto" => $e,
				"request_respond" => "server_error - " . lang("Common.solicitudNoProcesada"),
			];

			$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->respondPlpx("server_error", "Common.solicitudNoProcesada", false);
		}
	}

	/**
	 * Permite recuperar una contraseña de un usuario que no está logueado.
	 * Envia correo al usuario.
	 * Si no se cumplen ciertas condiciones para recuperar password,
	 * al solicitatente se le regresa una respuesta general.
	 *
	 * @return Response
	 */
	public function recuperarPassword()
	{
		$logAccion = "Recuperar contraseña";

		try {
			if (usuarioEstaLogueado()) {
				$dataLog = [
					"mensaje" => "Se intentó recuperar contraseña de usuario estando logueado",
					"request_respond" => "unauthorized - " . lang("Common.api.accesoNoPermitido"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("unauthorized", "Common.api.accesoNoPermitido", false);
			}

			if (!recuperarPasswordValidator($this->request->getJSON(), $errors)) {
				$dataLog = [
					"mensaje" => lang("Common.errorDatosValidacion"),
					"mensaje_objeto" => $errors,
					"request_respond" => "invalid_request - " . lang("Common.api.accesoNoPermitido"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", $errors, false);
			}

			$usuarioModel = new UsuarioModel();
			$correo = $this->request->getJsonVar("usuario_correo");

			//CONSULTA SI EXISTE EL USUARIO.
			$usuario = $usuarioModel
				->select("usu_id,usu_correo,usu_sta")
				->where("usu_correo", $correo)
				->first();

			if (is_null($usuario)) {
				$dataLog = [
					"mensaje" => "Correo ingresado para recuperar contrasena no existe: " . $correo,
					"request_respond" => "ok",
				];

				$this->logSistema(["tipo" => "warning", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("ok", "Usuario.respuestaRecuperarPassword", false);
			}

			if ($usuario->usu_sta != 1) {
				$dataLog = [
					"mensaje" => "Correo ingresado para recuperar es cuenta inactiva: " . $correo,
					"request_respond" => "ok",
				];

				$this->logSistema(["tipo" => "warning", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("ok", "Usuario.respuestaRecuperarPassword", false);
			}

			//INSERTA REGISTRO DE RECUPERACIÓN
			$usuarioRecuperacionPassword = new UsuarioRecuperacionPasswordModel();

			$timeNow = new Time("now");
			$timeExpiry = $timeNow->addHours(DURACION_ENLACE_RECUPERACION_PASSWORD_HRS);
			$codigo = hash("sha256", $correo . $timeNow);

			$dataIns = [
				"usurecp_usu_id" => $usuario->usu_id,
				"usurecp_usu_correo" => $correo,
				"usurecp_codigo_recuperacion" => $codigo,
				"usurecp_expiry" => $timeExpiry,
				"usurecp_created_at" => $timeNow,
				"usurecp_sta" => 0,
			];

			$idInserted = $usuarioRecuperacionPassword->insert($dataIns);

			//VERIFICA SI HAY ERRORES SEGUN EL MODELO
			if (count($usuarioRecuperacionPassword->errors()) > 0) {
				$errores = "";

				foreach ($usuarioRecuperacionPassword->errors() as $error) {
					$errores = $errores . " | " . $error;
				}

				$dataLog = [
					"mensaje" => "Error al guardar registro de recuperación de password del correo $correo. No pasó las validaciones del modelo: $errores",
					"request_respond" => "invalid_request - " . lang("Usuario.errorRecuperarPassword"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.errorRecuperarPassword", false);
			}

			//ENVIA EMAIL DE RECUPERACIÓN
			$correoEnlace = anchor(base_url() . URL_API_CONFIRMACION_RECUPERACION . $codigo, lang("Email.recuperacionPassword.reestablecerMiContra"));
			$email = \Config\Services::email();
			$email->setFrom(EMAIL_FROM_GENERAL, lang("Email.generalFrom"));
			$email->setTo($correo);
			$email->setSubject(lang("Email.recuperacionPassword.asunto"));
			$email->setMessage(lang("Email.recuperacionPassword.cuerpo", [$correoEnlace]));

			if ($email->send()) {
				$timeNow = new Time("now");
				$updated = $usuarioRecuperacionPassword->update($idInserted, ["usurecp_email_sended_at" => $timeNow]);

				if (!$updated) {
					$dataLog = [
						"mensaje" => "Se envió correo para confirmación, pero no se pudo actualizar el campo usurecp_email_sended_at",
					];

					$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				}

				$dataLog = [
					"mensaje" => "Se envió correo de recuperación de password",
					"request_respond" => "ok - " . lang("Usuario.respuestaRecuperarPassword"),
				];

				$this->logSistema(["tipo" => "info", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("ok", "Usuario.respuestaRecuperarPassword", true);
			} else {
				$data = $email->printDebugger(["headers"]);

				$dataLog = [
					"mensaje" => "No se pudo enviar correo de recuperación de password: $correo",
					"mensaje_objeto" => $data,
					"request_respond" => "invalid_request - " . lang("Usuario.errorRecuperarPassword"),
				];

				$this->logSistema(["tipo" => "alert", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
				return $this->respondPlpx("invalid_request", "Usuario.errorRecuperarPassword", false);
			}
		} catch (Error $e) {
			$dataLog = [
				"mensaje" => "Error general",
				"mensaje_objeto" => $e,
				"request_respond" => "server_error - " . lang("Common.solicitudNoProcesada"),
			];

			$this->logSistema(["tipo" => "critical", "accion" => $logAccion, "linea" => __LINE__, ...$dataLog]);
			return $this->respondPlpx("server_error", "Common.solicitudNoProcesada", false);
		}
	}
}
