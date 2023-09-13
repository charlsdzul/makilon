<?php

function getErrorsCommon($code = null, $title = "", $detail = "")
{
	$title_ = $title == "" ? lang("Common.errorDatos") : $title;

	$codes = [
		//100
		100 => ["code" => 100, "title" => $title_, "detail" => lang("Common.tipoDatoIncorreto")],
		101 => ["code" => 101, "title" => $title_, "detail" => lang("Common.longitudDatoIncorreto")],
		102 => ["code" => 102, "title" => $title_, "detail" => lang("Common.datoInvalido")],
		103 => ["code" => 103, "title" => $title_, "detail" => lang("Common.datoContieneEspaciosOCaracteresInv")],

		//200
		200 => ["code" => 200, "title" => $title_, "detail" => lang("Common.datosNoRecibidos")],
		201 => ["code" => 201, "title" => $title_, "detail" => lang("Common.datosActualizados")],
		202 => ["code" => 202, "title" => $title_, "detail" => lang("Common.datoNoActualizados")],
		203 => ["code" => 203, "title" => $title_, "detail" => lang("Common.errorDatosValidacion")],

		//800
		800 => ["code" => 800, "title" => $title_, "detail" => lang("Common.intentoNoLogueado")],
		801 => ["code" => 801, "title" => $title_, "detail" => lang("Common.solicitudNoProcesada")],
		802 => ["code" => 802, "title" => $title_, "detail" => lang("Common.idInvalido")],
		803 => ["code" => 803, "title" => $title_, "detail" => lang("Common.queryParamsInvalidos")],
	];

	$error = $codes[$code];
	return $error;
}

function getErrorsUsuario($code = null)
{
	$codes = [
		//1000
		1000 => ["code" => $code, "title" => lang("Usuario.correoInvalido"), "detail" => lang("Usuario.correoEnBlanco")],
		1001 => ["code" => $code, "title" => lang("Usuario.correoInvalido"), "detail" => lang("Usuario.correoInvalido")],
		1002 => ["code" => $code, "title" => lang("Usuario.contrasenaInvalida"), "detail" => lang("Usuario.contrasenaEnBlanco")],
		1003 => ["code" => $code, "title" => lang("Usuario.contrasenaInvalida"), "detail" => lang("Usuario.contrasenaMinima")],
		1004 => ["code" => $code, "title" => lang("Usuario.contrasenaInvalida"), "detail" => lang("Usuario.contrasenaMaxima")],
		1005 => ["code" => $code, "title" => lang("Usuario.contrasenaInvalida"), "detail" => lang("Usuario.contrasenaInvalida")],
		1006 => ["code" => $code, "title" => lang("Usuario.contrasenaInvalida"), "detail" => lang("Usuario.contrasenasNoIguales")],
		1007 => ["code" => $code, "title" => lang("Usuario.errorLogin"), "detail" => lang("Usuario.correoContrasenaInvalida")],
		1008 => ["code" => $code, "title" => lang("Usuario.errorLogin"), "detail" => lang("Usuario.cuentaInactiva")],
	];

	$error = $codes[$code];
	return $error;
}

function getErrorsjwt($code = null)
{
	$codes = [
		//2000
		2000 => ["code" => $code, "title" => lang("Usuario.jwt.jwtInvalido"), "detail" => lang("Usuario.jwt.jwtInvalido")],
		
	];

	$error = $codes[$code];
	return $error;
}

function getResponseAPI($code = null)
{
	$codes = [
		9000 => ["code" => 1000, "status" => 401, "title" => lang("API.accesoNoPermitido"), "detail" => ""],
	];

	$error = $codes[$code];
	return $error;
}
