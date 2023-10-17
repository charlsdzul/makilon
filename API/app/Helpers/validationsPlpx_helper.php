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

function validateRequestData($requestBody = null, &$error = "")
{
    $isValid = false;

    if (is_null($requestBody) || !$requestBody) {
        $error = getErrorResponseByCode(["code" => 200]);
        $isValid = true;
    }

    return $isValid;
}

function validateEmailLogin($correo = null, &$error = [])
{
    $isValid = true;
    // var_dump($correo);

    if (isset($correo)) {
        if ($correo == "" || $correo == "undefined" || $correo == "null" || $correo == "NULL" || $correo == "NULL") {
            $error = getErrorResponseByCode(["code" => 1000]);
            $isValid = false;
        } elseif (!is_string($correo)) {
            $error = getErrorResponseByCode(["code" => 1010]);
            $isValid = false;
        } elseif (strlen($correo) > 70) {
            $error = getErrorResponseByCode(["code" => 1011]);
            $isValid = false;
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error = getErrorResponseByCode(["code" => 1001]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 1012]);
        $isValid = false;
    }

    if (!$isValid) {
        $error = [...$error, "id" => "correo"];
    }

    return $isValid;
}

function validateTituloVacante($titulo = null, &$error = [])
{
    $isValid = true;

    if (isset($titulo)) {
        if ($titulo == "" || $titulo == "undefined" || $titulo == "null" || $titulo == "NULL") {
            $error = getErrorResponseByCode(["code" => 2110]);
            $isValid = false;
        } elseif (!is_string($titulo)) {
            $error = getErrorResponseByCode(["code" => 2111]);
            $isValid = false;
        } elseif (strlen($titulo) <= 15) {
            $error = getErrorResponseByCode(["code" => 2112]);
            $isValid = false;
        } elseif (strlen($titulo) > 60) {
            $error = getErrorResponseByCode(["code" => 2112]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 2110]);
        $isValid = false;
    }

    return $isValid;
}

function validatePuestoVacante($puesto = null, &$error = [])
{
    $isValid = true;

    if (isset($puesto)) {
        if ($puesto == "" || $puesto == "undefined" || $puesto == "null" || $puesto == "NULL") {
            $error = getErrorResponseByCode(["code" => 2120]);
            $isValid = false;
        } elseif (!is_string($puesto)) {
            $error = getErrorResponseByCode(["code" => 2121]);
            $isValid = false;
        } elseif (strlen($puesto) > 20) {
            $error = getErrorResponseByCode(["code" => 2122]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 2120]);
        $isValid = false;
    }

    return $isValid;
}

function validatePuestoOtroVacante($puesto = null, $puestoOtro = null, &$error = [])
{
    $isValid = true;

    if ($puesto != "otro") {
        return $isValid;
    }

    if (isset($puestoOtro)) {
        if ($puestoOtro == "" || $puestoOtro == "undefined" || $puestoOtro == "null" || $puestoOtro == "NULL") {
            $error = getErrorResponseByCode(["code" => 2140]);
            $isValid = false;
        } elseif (!is_string($puestoOtro)) {
            $error = getErrorResponseByCode(["code" => 2141]);
            $isValid = false;
        } elseif (strlen($puestoOtro) > 50) {
            $error = getErrorResponseByCode(["code" => 2142]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 2140]);
        $isValid = false;
    }

    return $isValid;
}

function validatePuestoEspecificoVacante($puestoEspecifico = null, &$error = [])
{
    $isValid = true;

    if (isset($puestoEspecifico)) {
        if ($puestoEspecifico == "" || $puestoEspecifico == "undefined" || $puestoEspecifico == "null" || $puestoEspecifico == "NULL") {
            $error = getErrorResponseByCode(["code" => 2130]);
            $isValid = false;
        } elseif (!is_string($puestoEspecifico)) {
            $error = getErrorResponseByCode(["code" => 2131]);
            $isValid = false;
        } elseif (strlen($puestoEspecifico) > 20) {
            $error = getErrorResponseByCode(["code" => 2132]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 2130]);
        $isValid = false;
    }

    return $isValid;
}

function validatePuestoEspecificoOtroVacante($puestoEspecifico = null, $puestoEspecificoOtro = null, &$error = [])
{
    $isValid = true;

    if ($puestoEspecifico != "otro") {
        return $isValid;
    }

    if (isset($puestoEspecificoOtro)) {
        if ($puestoEspecificoOtro == "" || $puestoEspecificoOtro == "undefined" || $puestoEspecificoOtro == "null" || $puestoEspecificoOtro == "NULL") {
            $error = getErrorResponseByCode(["code" => 2150]);
            $isValid = false;
        } elseif (!is_string($puestoEspecificoOtro)) {
            $error = getErrorResponseByCode(["code" => 2151]);
            $isValid = false;
        } elseif (strlen($puestoEspecificoOtro) > 50) {
            $error = getErrorResponseByCode(["code" => 2152]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 2150]);
        $isValid = false;
    }

    return $isValid;
}

function validateJwt($jwt = null, &$error = [])
{
    $isValid = true;

    if (isset($jwt)) {
        if ($jwt == "" || $jwt == "undefined" || $jwt == "null" || $jwt == "NULL") {

            $error = getErrorResponseByCode(["code" => 100]);
            $isValid = false;
        }
    } else {
        $error = getErrorResponseByCode(["code" => 100]);
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
        if ($psw == "" || $psw == "undefined" || $psw == "null" || $psw == "NULL") {
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
        $error = getErrorResponseByCode(["code" => 1013]);
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
