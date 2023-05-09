<?php
helper("codesPlpx");

function usuarioEstaLogueado()
{
	$isValid = false;
	$session = session();

	if (!is_null($session->usu_id)) {
		$isValid = true;
	}

	return $isValid;
}

function validateRequestNullOrEmpty($datos = null, &$mensaje = "")
{
	$isValid = false;

	if (is_null($datos) || !$datos) {
		$mensaje = lang("Common.datosNoRecibidos");
		$isValid = true;
	}

	return $isValid;
}

function validateRequestData($datos = null, &$error = "")
{
	$isValid = false;

	if (is_null($datos) || !$datos) {
		$error = getErrorsCommon(200);
		$isValid = true;
	}

	return $isValid;
}

function validateEmail($correo = null, &$error = [])
{
	$isValid = true;

	if (isset($correo)) {
		if ($correo == "") {
			$error = getErrorsUsuario(1000);
			$isValid = false;
		} elseif (!is_string($correo)) {
			$error = getErrorsCommon(100);
			$isValid = false;
		} elseif (strlen($correo) > 70) {
			$error = getErrorsCommon(101);
			$isValid = false;
		} elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
			$error = getErrorsUsuario(1001);
			$isValid = false;
		}
	} else {
		$error = getErrorsCommon(200);
		$isValid = false;
	}

	$error = [...$error, "id" => "correo"];
	return $isValid;
}

function validatePassword($psw = null, &$errors = [])
{
	$isValid = true;

	if (isset($psw)) {
		if ($psw == "") {
			$errors = getErrorsUsuario(1002);
			$isValid = false;
		} elseif (strlen($psw) < 8) {
			$errors = getErrorsUsuario(1003);
			$isValid = false;
		} elseif (strlen($psw) > 15) {
			$errors = getErrorsUsuario(1004);
			$isValid = false;
		}
	} else {
		$errors = getErrorsCommon(200);
		$isValid = false;
	}

	$errors = [...$errors, "id" => "contrasena"];
	return $isValid;
}

function validatePasswords($pwd1 = "", $pwd2 = "", &$errors = [])
{
	$isValid = true;

	if ($pwd1 != $pwd2) {
		$errors = getErrorsUsuario(1006);
		$isValid = false;
	}

	return $isValid;
}

function validateRequestCodigoConfirmacion($codigoConfirmacion = null, &$mensaje = "")
{
	$isValid = true;

	if (isset($codigoConfirmacion)) {
		if ($codigoConfirmacion == "") {
			$mensaje = lang("Usuario.confirmacion.correoCodigoInvalido");
			$isValid = false;
		} elseif (!preg_match("/^([a-f0-9]{64})$/", $codigoConfirmacion)) {
			$mensaje = lang("Usuario.confirmacion.correoCodigoInvalido");
			$isValid = false;
		}
	} else {
		$mensaje = lang("Common.datosNoRecibidos");
		$isValid = false;
	}

	return $isValid;
}

function validateRequestUsuario($usuario = null, &$mensaje = "")
{
	$isValid = true;

	if (isset($usuario)) {
		if (!is_string($usuario)) {
			$mensaje = lang("Common.tipoDatoIncorreto");
			$isValid = false;
		}

		if (!ctype_alnum($usuario)) {
			$mensaje = lang("Common.datoContieneEspaciosOCaracteresInv");
		}

		if (strlen($usuario) > 20) {
			$mensaje = lang("Common.longitudDatoIncorreto");
		}
	} else {
		$mensaje = lang("Common.datosNoRecibidos");
		$isValid = false;
	}

	return $isValid;
}

function validateRequestNombreUsuario($nombre = null, &$mensaje = "")
{
	$isValid = true;

	if (isset($nombre)) {
		if (!is_string($nombre)) {
			$mensaje = lang("Common.tipoDatoIncorreto");
			$isValid = false;
		}

		if (strlen($nombre) > 50) {
			$mensaje = lang("Common.longitudDatoIncorreto");
		}

		if (!preg_match(REGEX_ALPHA_NUM_SPA, $nombre)) {
			$mensaje = lang("Common.datoContieneEspaciosOCaracteresInv");
		}
	} else {
		$mensaje = lang("Common.datosNoRecibidos");
		$isValid = false;
	}

	return $isValid;
}

function validateRequestApellidoUsuario($apellido = null, &$mensaje = "")
{
	$isValid = true;

	if (isset($apellido)) {
		if (!is_string($apellido)) {
			$mensaje = lang("Common.tipoDatoIncorreto");
			$isValid = false;
		}

		if (strlen($apellido) > 50) {
			$mensaje = lang("Common.longitudDatoIncorreto");
		}

		if (!preg_match(REGEX_ALPHA_NUM_SPA, $apellido)) {
			$mensaje = lang("Common.datoContieneEspaciosOCaracteresInv");
		}
	} else {
		$mensaje = lang("Common.datosNoRecibidos");
		$isValid = false;
	}

	return $isValid;
}
