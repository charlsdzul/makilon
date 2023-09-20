<?php
helper("codesPlpx");
use Firebase\JWT\Key;
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
        $error = getErrorResponseByCode(["code" => 200]);
        $isValid = true;
    }

    return $isValid;
}

function validateEmailLogin($correo = null, &$error = [])
{
    $isValid = true;

    if (isset($correo)) {
        if ($correo == "" || $correo == "undefined" || $correo == "null") {
            $error = getErrorResponseByCode(["code" => 1000, "useDetail" => false]);
            $isValid = false;
        } elseif (!is_string($correo)) {
            $error = getErrorResponseByCode(["code" => 100, "useDetail" => false]);
            $isValid = false;
        } elseif (strlen($correo) > 70) {
            $error = getErrorResponseByCode(["code" => 101, "useDetail" => false]);
            $isValid = false;
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error = getErrorResponseByCode(["code" => 1001, "useDetail" => false]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 200]);
        $isValid = false;
    }

    if (!$isValid) {
        $error = [...$error, "id" => "correo"];
    }

    return $isValid;
}

function validateJwt($jwt = null, &$error = [])
{
    $isValid = true;

    if (isset($jwt)) {
        if ($jwt == "" || $jwt == "undefined" || $jwt == "null") {

            $error = getErrorResponseByCode(["code" => 1002]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 200]);
        $isValid = false;
    }

    if (!$isValid) {
        $error = [...$error, "id" => "jwt"];
    }

    return $isValid;
}

function validatePasswordLogin($psw = null, &$error = [])
{
    $isValid = true;

    if (isset($psw)) {
        if ($psw == "" || $psw == "undefined" || $psw == "null") {
            $error = getErrorResponseByCode(["code" => 1009]);
            $isValid = false;
        } elseif (strlen($psw) < 8) {
            $error = getErrorResponseByCode(["code" => 1009]);
            $isValid = false;
        } elseif (strlen($psw) > 15) {
            $error = getErrorResponseByCode(["code" => 1009]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 200]);
        $isValid = false;
    }

    if (!$isValid) {
        $error = [...$error, "id" => "contrasena"];
    }

    return $isValid;
}

function validatePasswords($pwd1 = "", $pwd2 = "", &$errors = [])
{
    $isValid = true;

    if ($pwd1 != $pwd2) {
        $errors = getErrorResponseByCode(["code" => 1006]);
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
