<?php
use App\Models\UsuarioModel;

helper("validationsPlpx");

function loginValidator($datos = null, &$errors = null)
{
	$errors = new stdClass();
	$errorsValidation = [];
	$correo = $datos["correo"] ?? "";
	$contrasena = $datos["contrasena"] ?? "";

	if (validateRequestData($datos, $errorsValidate)) {
		$errorsValidation += ["datos" => $errorsValidate];
	} else {
		if (!validateEmail($correo, $errorsEmail)) {
			array_push($errorsValidation, $errorsEmail);
		}

		if (!validatePassword($contrasena, $errorsContrasena)) {
			array_push($errorsValidation, $errorsContrasena);
		}
	}

	$errors = $errorsValidation;
	return count($errorsValidation) == 0;
}

function authenticatedValidator($datos = null, &$errors = null)
{
	$errors = new stdClass();
	$errorsValidation = [];
	$jwt = $datos["jwt"] ?? "";
	$email = $datos["email"] ?? "";

	if (validateRequestData($datos, $errorsValidate)) {
		$errorsValidation += ["datos" => $errorsValidate];
	} else {
		if (!validateEmail($email, $errorsEmail)) {
			array_push($errorsValidation, $errorsEmail);
		}

		if (!validateJwt($jwt, $errorsJwt)) {
			array_push($errorsValidation, $errorsJwt);
		}
	}

	$errors = $errorsValidation;
	return count($errorsValidation) == 0;
}

function registroValidator($datos = null, &$errors = null)
{
	$errors = [];
	$pwd1 = $datos->usuario_pwd1 ?? null;
	$pwd2 = $datos->usuario_pwd2 ?? null;
	$correo = $datos->usuario_correo ?? null;

	if (validateRequestNullOrEmpty($datos, $errorValidate)) {
		$errors = [$errorValidate];
	} else {
		if (!validateEmail($correo, $errorValidate)) {
			$error = [...$errorValidate, "source" => ["parameter" => "usuario_correo"]];
			$errors = [$error];
		}

		if (!validatePassword($pwd1, $errorValidate)) {
			$error = [...$errorValidate, "source" => ["parameter" => "usuario_pwd1"]];
			$errors = [...$errors, $error];
		}

		if (!validatePassword($pwd2, $errorValidate)) {
			$error = [...$errorValidate, "source" => ["parameter" => "usuario_pwd2"]];
			$errors = [...$errors, $error];
		}

		if (!validatePasswords($pwd1, $pwd2, $errorValidate)) {
			$errors = [...$errors, $errorValidate];
		}
	}

	return count($errors) == 0;
}

function confirmacionValidator($correo = "", $codigo = "", &$errors = null)
{
	$errors = new stdClass();
	$errorsValidation = [];

	if (!validateEmail($correo, $mensaje)) {
		$errorsValidation += ["correo" => $mensaje];
	}

	if (!validateRequestCodigoConfirmacion($codigo, $mensaje)) {
		$errorsValidation += ["codigo" => $mensaje];
	}

	$errors->errorsValidation = $errorsValidation;
	return count($errorsValidation) == 0;
}

function actualizaDatosValidator($datos = null, &$errors = null)
{
	$errors = new stdClass();
	$errorsValidation = [];
	$usuario = $datos->usuario_usuario ?? null;
	$nombre = $datos->usuario_nombre ?? null;
	$apellido = $datos->usuario_apellido ?? null;

	if (validateRequestNullOrEmpty($datos, $mensaje)) {
		$errorsValidation += ["datos" => $mensaje];
	} else {
		if (!validateRequestUsuario($usuario, $mensaje)) {
			$errorsValidation += ["usuario_usuario" => $mensaje];
		}

		if (!validateRequestNombreUsuario($nombre, $mensaje)) {
			$errorsValidation += ["usuario_nombre" => $mensaje];
		}

		if (!validateRequestApellidoUsuario($apellido, $mensaje)) {
			$errorsValidation += ["usuario_apellido" => $mensaje];
		}
	}

	$errors->errorsValidation = $errorsValidation;
	return count($errorsValidation) == 0;
}

function actualizaCorreoValidator($datos = null, &$errors = null)
{
	$errors = new stdClass();
	$errorsValidation = [];
	$correo = $datos->usuario_correo ?? null;

	if (validateRequestNullOrEmpty($datos, $mensaje)) {
		$errorsValidation += ["datos" => $mensaje];
	} else {
		if (!validateEmail($correo, $mensaje)) {
			$errorsValidation += ["usuario_correo" => $mensaje];
		} else {
			$usuarioModel = new UsuarioModel();
			$usuario = $usuarioModel->where("usu_correo", $correo)->first();

			if (!is_null($usuario)) {
				$errorsValidation += ["usuario_correo" => lang("Usuario.correoNoDisponible")];
			}
		}
	}

	$errors->errorsValidation = $errorsValidation;
	return count($errorsValidation) == 0;
}

function actualizaPasswordValidator($datos = null, &$errors = null)
{
	$errors = new stdClass();
	$errorsValidation = [];
	$psw = $datos->usuario_psw ?? null;
	$psw2 = $datos->usuario_psw2 ?? null;

	if (validateRequestNullOrEmpty($datos, $mensaje)) {
		$errorsValidation += ["datos" => $mensaje];
	} else {
		if (!validatePassword($psw, $mensaje)) {
			$errorsValidation += ["usuario_psw" => $mensaje];
		}

		if (!validatePassword($psw2, $mensaje)) {
			$errorsValidation += ["usuario_psw2" => $mensaje];
		}

		if (!is_null($psw) && !is_null($psw2) && $psw != $psw2) {
			$errorsValidation += ["psws" => lang("Usuario.contrasenasNoIguales")];
		}
	}

	$errors->errorsValidation = $errorsValidation;
	return count($errorsValidation) == 0;
}

function recuperarPasswordValidator($datos = null, &$errors = null)
{
	try {
		$errors = new stdClass();
		$errorsValidation = [];

		if (validateRequestNullOrEmpty($datos, $mensaje)) {
			echo "sdsd";
			$errorsValidation += ["datos" => $mensaje];
		} else {
			if (!validateEmail($datos->usuario_correo ?? null, $mensaje)) {
				$errorsValidation += ["usuario_correo" => $mensaje];
			}
		}

		$errors->errorsValidation = $errorsValidation;
		return count($errorsValidation) == 0;
	} catch (Error $er) {
		//return false;
	}
}
