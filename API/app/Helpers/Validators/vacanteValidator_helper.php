<?php
use App\Libraries\DTO\VacanteDTO;
use App\Models\UsuarioModel;

helper("validationsPlpx");

function vacanteValidatorRES($requestBody = null, &$errors = null)
{
    $errors = new stdClass();
    $errorsValidation = [];

    $titulo = $requestBody["titulo"];
    $puesto = $requestBody["puesto"];
    $puestoOtro = $requestBody["puestoOtro"];
    $puestoEspecifico = $requestBody["puestoEspecifico"];
    $puestoEspecificoOtro = $requestBody["puestoEspecificoOtro"];

    if (!validateTituloVacante($titulo, $errorsVacante)) {
        array_push($errorsValidation, $errorsVacante);
    }

    if (!validatePuestoVacante($puesto, $errorsVacante)) {
        array_push($errorsValidation, $errorsVacante);
    } else {
        if (!validatePuestoOtroVacante($puesto, $puestoOtro, $errorsVacante)) {
            array_push($errorsValidation, $errorsVacante);
        }
    }

    if (!validatePuestoEspecificoVacante($puestoEspecifico, $errorsVacante)) {
        array_push($errorsValidation, $errorsVacante);
    } else {
        if (!validatePuestoEspecificoOtroVacante($puestoEspecifico, $puestoEspecificoOtro, $errorsVacante)) {
            array_push($errorsValidation, $errorsVacante);
        }
    }

    $errors = $errorsValidation;
    return count($errorsValidation) == 0;
}

function vacanteValidator(VacanteDTO $vacante): array
{
    $errorsValidation = [];

    $tituloError = validateTituloVacante($vacante->titulo);
    if (count($tituloError) > 0) {
        array_push($errorsValidation, $tituloError);
    }

    // if (!validateTituloVacante($vacante->titulo, $errorsVacante)) {
    //     array_push($errorsValidation, $errorsVacante);
    // }

    if (!validatePuestoVacante($vacante->puesto, $errorsVacante)) {
        array_push($errorsValidation, $errorsVacante);
    } else {
        if (!validatePuestoOtroVacante($vacante->puesto, $vacante->puestoOtro, $errorsVacante)) {
            array_push($errorsValidation, $errorsVacante);
        }
    }

    if (!validatePuestoEspecificoVacante($vacante->puestoEspecifico, $errorsVacante)) {
        array_push($errorsValidation, $errorsVacante);
    } else {
        if (!validatePuestoEspecificoOtroVacante($vacante->puestoEspecifico, $vacante->puestoEspecificoOtro, $errorsVacante)) {
            array_push($errorsValidation, $errorsVacante);
        }
    }

    return $errorsValidation;
}

function authenticatedValidator($datos = null, &$errors = null)
{
    $errors = new stdClass();
    $errorsValidation = [];
    $token = $datos["token"] ?? "";
    $email = $datos["email"] ?? "";

    if (validateRequestData($datos, $errorsValidate)) {
        $errorsValidation += ["datos" => $errorsValidate];
    } else {
        if (!validateEmailLogin($email, $errorsEmail)) {
            array_push($errorsValidation, $errorsEmail);
        }

        if (!validateJwt($token, $errorsJwt)) {
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
        if (!validateEmailLogin($correo, $errorValidate)) {
            $error = [...$errorValidate, "source" => ["parameter" => "usuario_correo"]];
            $errors = [$error];
        }

        if (!validatePasswordLogin($pwd1, $errorValidate)) {
            $error = [...$errorValidate, "source" => ["parameter" => "usuario_pwd1"]];
            $errors = [...$errors, $error];
        }

        if (!validateEmailLogin($pwd2, $errorValidate)) {
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

    if (!validateEmailLogin($correo, $mensaje)) {
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
        if (!validateEmailLogin($correo, $mensaje)) {
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
            if (!validateEmailLogin($datos->usuario_correo ?? null, $mensaje)) {
                $errorsValidation += ["usuario_correo" => $mensaje];
            }
        }

        $errors->errorsValidation = $errorsValidation;
        return count($errorsValidation) == 0;
    } catch (Error $er) {
        //return false;
    }
}
