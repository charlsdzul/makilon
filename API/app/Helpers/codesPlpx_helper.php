<?php

function getErrorResponseByCode($args)
{

    try {

        $code = $args["code"];
        $title = $args["title"] ?? "";
        $detail = $args["detail"] ?? "";
        $onlyDetail = $args["onlyDetail"] ?? false;
        $useDetail = $args["useDetail"] ?? true;
        $useAction = $args["useAction"] ?? true;

        //$title_ = $title == "" ? lang("Common.errorDatos") : $title;

        $codes = [
            //100
            100 => ["code" => 100, "title" => $title, "detail" => lang("Lang.detail.tipoDatoIncorreto")],
            101 => ["code" => 101, "title" => $title, "detail" => lang("Lang.detail.longitudDatoIncorreto")],
            102 => ["code" => 102, "title" => $title, "detail" => lang("Lang.detail.datoInvalido")],
            103 => ["code" => 103, "title" => $title, "detail" => lang("Lang.detail.datoContieneEspaciosOCaracteresInv")],

            //200
            200 => ["code" => 200, "title" => $title, "detail" => lang("Lang.detail.datosNoRecibidos")],
            201 => ["code" => 201, "title" => $title, "detail" => lang("Lang.detail.datosActualizados")],
            202 => ["code" => 202, "title" => $title, "detail" => lang("Lang.detail.datoNoActualizados")],
            203 => ["code" => 203, "title" => $title, "detail" => lang("Lang.detail.errorDatosValidacion")],

            //800
            800 => ["code" => 800, "title" => $title, "detail" => lang("Lang.detail.intentoNoLogueado")],
            801 => ["code" => 801, "title" => $title, "detail" => lang("Lang.detail.solicitudNoProcesada")],
            802 => ["code" => 802, "title" => $title, "detail" => lang("Lang.detail.idInvalido")],
            803 => ["code" => 803, "title" => $title, "detail" => lang("Lang.detail.queryParamsInvalidos")],

            //1000
            1000 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.correo"), "detail" => lang("Lang.detail.correoInvalido"), "action" => lang("Lang.detail.ingresaCorreo")],
            1001 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.correo"), "detail" => lang("Lang.detail.correoInvalido"), "action" => lang("Lang.detail.ingresaCorreoValido")],
            1002 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.contrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.ingresaContrasena")],
            1003 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.contrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.contrasenaMinima")],
            1004 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.contrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.contrasenaMaxima")],
            1005 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.contrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.contrasenaInvalida")],
            1006 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.contrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.contrasenasNoIguales")],
            1007 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang(""), "detail" => lang("Lang.detail.login.correoContrasenaInvalida"), "action" => lang("")],
            1008 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.contrasena"), "detail" => lang("Lang.detail.login.cuentaInactiva"), "action" => lang("")],
            1009 => ["code" => $code, "title" => lang("Lang.title.errorLogin"), "label" => lang("Lang.label.contrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("")],

            //2000
            //2000 => ["code" => $code, "title" => lang("Usuario.jwt.jwtInvalido"), "detail" => $detail],
            //2001 => ["code" => $code, "title" => lang("Usuario.jwt.jwtInvalido"), "detail" => lang("Usuario.jwt.jwtInvalido")],

            //9000
            //9000 => ["code" => 1000, "status" => 401, "title" => lang("API.accesoNoPermitido"), "detail" => ""],

        ];

        $error = $codes[$code];

        if (!$useDetail) {
            $error["detail"] = "";
        }

        if (!$useAction) {
            $error["action"] = "";
        }

        if ($onlyDetail) {
            return $error["detail"];
        }
        return $error;

    } catch (Exception $ex) {
        return ["code" => $code, "title" => lang(""), "label" => lang(""), "detail" => lang(""), "action" => lang("")];
    }

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
        1002 => ["code" => $code, "title" => lang("Usuario.contrasenaInvalida"), "detail" => lang("Usuario.ingresaContrasena")],
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
