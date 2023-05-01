<?php

function getResponseCommon($code = null)
{
	$codes = [
		//100
		100 => ["code" => 100, "status" => 400, "title" => lang("Common.tipoDatoIncorreto"), "detail" => ""],
		101 => ["code" => 101, "status" => 400, "title" => lang("Common.longitudDatoIncorreto"), "detail" => ""],
		102 => ["code" => 102, "status" => 400, "title" => lang("Common.datoInvalido"), "detail" => ""],
		103 => ["code" => 103, "status" => 400, "title" => lang("Common.datoContieneEspaciosOCaracteresInv"), "detail" => ""],

		//200
		200 => ["code" => 200, "status" => 400, "title" => lang("Common.datosNoRecibidos"), "detail" => ""],
		201 => ["code" => 201, "status" => 400, "title" => lang("Common.datosActualizados"), "detail" => ""],
		202 => ["code" => 202, "status" => 400, "title" => lang("Common.datoNoActualizados"), "detail" => ""],
		203 => ["code" => 203, "status" => 400, "title" => lang("Common.errorDatosValidacion"), "detail" => ""],

		//800
		800 => ["code" => 800, "status" => 400, "title" => lang("Common.intentoNoLogueado"), "detail" => ""],
		801 => ["code" => 801, "status" => 400, "title" => lang("Common.solicitudNoProcesada"), "detail" => ""],
		802 => ["code" => 802, "status" => 400, "title" => lang("Common.idInvalido"), "detail" => ""],
		803 => ["code" => 803, "status" => 400, "title" => lang("Common.queryParamsInvalidos"), "detail" => ""],
	];

	$error = $codes[$code];
	return $error;
}

function getResponseUsuario($code = null)
{
	$codes = [
		//1000
		1000 => ["code" => 1000, "status" => 400, "title" => lang("Usuario.correoEnBlanco"), "detail" => ""],
		1001 => ["code" => 1001, "status" => 400, "title" => lang("Usuario.correoInvalido"), "detail" => ""],
		1002 => ["code" => 1002, "status" => 400, "title" => lang("Usuario.contrasenaEnBlanco"), "detail" => ""],
		1003 => ["code" => 1003, "status" => 400, "title" => lang("Usuario.contrasenaMinima"), "detail" => ""],
		1004 => ["code" => 1004, "status" => 400, "title" => lang("Usuario.contrasenaMaxima"), "detail" => ""],
		1005 => ["code" => 1005, "status" => 400, "title" => lang("Usuario.contrasenaInvalida"), "detail" => ""],
		1006 => ["code" => 1006, "status" => 400, "title" => lang("Usuario.contrasenasNoIguales"), "detail" => ""],
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
