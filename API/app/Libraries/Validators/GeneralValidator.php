<?php
namespace App\Libraries\Validators;

use App\Libraries\Utils\FieldsLength\VacanteFieldsLength;

class GeneralValidator
{
    public static function validateTitulo($titulo): array
    {
        $error = [];
        $titulo = $titulo ?? "";

        if (empty($titulo)) {
            $error = getErrorResponseByCode(["code" => 2110]);
        } else if (!is_string($titulo)) {
            $error = getErrorResponseByCode(["code" => 2111]);
        } else if (strlen($titulo) <= VacanteFieldsLength::$TITULO_MIN || strlen($titulo) > VacanteFieldsLength::$TITULO_MAX) {
            $error = getErrorResponseByCode(["code" => 2112]);
        }

        return $error;
    }

    public static function validatePuesto($puesto): array
    {
        $error = [];
        $puesto = $puesto ?? "";

        if (empty($titulo)) {
            $error = getErrorResponseByCode(["code" => 2120]);
        } else if (!is_string($puesto)) {
            $error = getErrorResponseByCode(["code" => 2121]);
        } else if (strlen($puesto) > VacanteFieldsLength::$PUESTO_MAX) {
            $error = getErrorResponseByCode(["code" => 2122]);
        }

        return $error;
    }

    public static function validatePuestoOtro($puesto, $puestoOtro): array
    {
        $error = [];
        $puesto = $puesto ?? "";
        $puestoOtro = $puestoOtro ?? "";

        if (empty($titulo)) {
            $error = getErrorResponseByCode(["code" => 2140]);
        } else if (!is_string($puestoOtro)) {
            $error = getErrorResponseByCode(["code" => 2141]);
        } else if (strlen($puestoOtro) > VacanteFieldsLength::$PUESTO_OTRO_MAX) {
            $error = getErrorResponseByCode(["code" => 2142]);
        }

        return $error;
    }

    public static function validatePuestoEspecifico($puestoEspecifico): array
    {
        $error = [];
        $puestoEspecifico = $puestoEspecifico ?? "";

        if (empty($titulo)) {
            $error = getErrorResponseByCode(["code" => 2130]);
        } else if (!is_string($puestoEspecifico)) {
            $error = getErrorResponseByCode(["code" => 2131]);
        } else if (strlen($puestoEspecifico) > VacanteFieldsLength::$PUESTO_ESP_MAX) {
            $error = getErrorResponseByCode(["code" => 2132]);
        }

        return $error;
    }

    public static function validatePuestoEspecificoOtro($puestoEspecifico, $puestoEspecificoOtro): array
    {
        $error = [];
        $puestoEspecifico = $puestoEspecifico ?? "";
        $puestoEspecificoOtro = $puestoEspecificoOtro ?? "";

        if (empty($titulo)) {
            $error = getErrorResponseByCode(["code" => 2150]);
        } else if (!is_string($puestoEspecificoOtro)) {
            $error = getErrorResponseByCode(["code" => 2151]);
        } else if (strlen($puestoEspecificoOtro) > VacanteFieldsLength::$PUESTO_ESP_OTRO_MAX) {
            $error = getErrorResponseByCode(["code" => 2152]);
        }

        return $error;
    }
}
