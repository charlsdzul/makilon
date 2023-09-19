<?php

function getErrorResponseByCode($code = null, $title = "", $detail = "",$onlyDetail=false)
{

	
	$title_ = $title == "" ? lang("Common.errorDatos") : $title;

	$codes = [
	//100
	100 => ["code" => 100, "title" => $title_, "detail" => lang("Lang.tipoDatoIncorreto")],
	101 => ["code" => 101, "title" => $title_, "detail" => lang("Lang.longitudDatoIncorreto")],
	102 => ["code" => 102, "title" => $title_, "detail" => lang("Lang.datoInvalido")],
	103 => ["code" => 103, "title" => $title_, "detail" => lang("Lang.datoContieneEspaciosOCaracteresInv")],

	//200
	200 => ["code" => 200, "title" => $title_, "detail" => lang("Lang.datosNoRecibidos")],
	201 => ["code" => 201, "title" => $title_, "detail" => lang("Lang.datosActualizados")],
	202 => ["code" => 202, "title" => $title_, "detail" => lang("Lang.datoNoActualizados")],
	203 => ["code" => 203, "title" => $title_, "detail" => lang("Lang.errorDatosValidacion")],

	//800
	800 => ["code" => 800, "title" => $title_, "detail" => lang("Lang.intentoNoLogueado")],
	801 => ["code" => 801, "title" => $title_, "detail" => lang("Lang.solicitudNoProcesada")],
	802 => ["code" => 802, "title" => $title_, "detail" => lang("Lang.idInvalido")],
	803 => ["code" => 803, "title" => $title_, "detail" => lang("Lang.queryParamsInvalidos")],

		//1000
		1000 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.correo"), "detail" => lang("Lang.errorCorreo"), "action" => lang("Lang.ingresaCorreo")],
		1001 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.correo"), "detail" => lang("Lang.errorCorreo"),"action" => lang("Lang.ingresaCorreoValido")],
		1002 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.contrasena"), "detail" => lang("Lang.errorContrasena"), "action" => lang("Lang.contrasenaEnBlanco")],
		1003 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.contrasena"), "detail" => lang("Lang.errorContrasena"), "action" => lang("Lang.contrasenaMinima")],
		1004 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.contrasena"), "detail" => lang("Lang.errorContrasena"),"action" => lang("Lang.contrasenaMaxima")],
		1005 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.contrasena"), "detail" => lang("Lang.errorContrasena"),"action" => lang("Lang.contrasenaInvalida")],
		1006 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.contrasena"), "detail" => lang("Lang.errorContrasena"),"action" => lang("Lang.contrasenasNoIguales")],
		1007 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.contrasena"), "detail" => lang("Lang.login.correoContrasenaInvalida"),"action" => lang("")],
		1008 => ["code" => $code, "title" => lang("Lang.login.errorLogin"), "label" => lang("Lang.labels.contrasena"), "detail" => lang("Lang.login.cuentaInactiva"),"action" => lang("")],

			//2000
			//2000 => ["code" => $code, "title" => lang("Usuario.jwt.jwtInvalido"), "detail" => $detail],
			//2001 => ["code" => $code, "title" => lang("Usuario.jwt.jwtInvalido"), "detail" => lang("Usuario.jwt.jwtInvalido")],

			//9000	
			//9000 => ["code" => 1000, "status" => 401, "title" => lang("API.accesoNoPermitido"), "detail" => ""],

	];

	$error = $codes[$code];

	if($onlyDetail){
		return $error["detail"];
	}
	return $error;
}

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
		1000 => ["code" => $code, "title" => lang("Usuario.correoEnBlanco"), "detail" => lang("Usuario.correoEnBlanco")],
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

function getErrorsjwt($code = null, $message = "")
{
	//echo $message;
	$codes = [
		//2000
		2000 => ["code" => $code, "title" => lang("Usuario.jwt.jwtInvalido"), "detail" => $message],
		2001 => ["code" => $code, "title" => lang("Usuario.jwt.jwtInvalido"), "detail" => lang("Usuario.jwt.jwtInvalido")],

		
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
