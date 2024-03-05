<?php
namespace App\Libraries\Validators;

use App\Libraries\DTO\VacanteDTO;

class VacanteValidator
{
    public static function validateNuevaVacante(VacanteDTO $vacante): array
    {
        $errorsValidation = [];

        $tituloErrors = GeneralValidator::validateTitulo($vacante->titulo);

        if (count($tituloErrors) > 0) {
            array_push($errorsValidation, $tituloErrors);
        }

        $puestoErrors = GeneralValidator::validatePuesto($vacante->puesto);

        if (count($puestoErrors) > 0) {
            array_push($errorsValidation, $puestoErrors);
        } else {
            if ($vacante->puesto == "otro") {
                $puestoOtroErrors = GeneralValidator::validatePuestoOtro($vacante->puesto, $vacante->puestoOtro);
                if (count($puestoOtroErrors) > 0) {
                    array_push($errorsValidation, $puestoOtroErrors);
                }
            }
        }

        $puestoEspecificoErrors = GeneralValidator::validatePuestoEspecifico($vacante->puestoEspecifico);

        if (count($puestoEspecificoErrors) > 0) {
            array_push($errorsValidation, $puestoEspecificoErrors);
        } else {
            if ($vacante->puestoEspecifico == "otro") {

                $puestoeEspecificoOtroErrors = GeneralValidator::validatePuestoEspecificoOtro($vacante->puestoEspecifico, $vacante->puestoEspecificoOtro);
                if (count($puestoeEspecificoOtroErrors) > 0) {
                    array_push($errorsValidation, $puestoeEspecificoOtroErrors);
                }
            }
        }

        return $errorsValidation;
    }
}
