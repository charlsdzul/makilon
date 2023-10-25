<?php

function getErrorResponseByCode($args)
{

    try {

        $code = $args["code"];
        $title = $args["title"] ?? "";
        $detail = $args["detail"] ?? "";
        $field = $args["field"] ?? "";
        $onlyDetail = $args["onlyDetail"] ?? false;
        $useDetail = $args["useDetail"] ?? true;
        $useAction = $args["useAction"] ?? true;

        //$title_ = $title == "" ? lang("Common.errorDatos") : $title;

        $codes = [
            //100
            100 => ["code" => $code, "title" => lang("Lang.title.auth"), "field" => "", "detail" => lang("Lang.detail.datoNoRecibido")],

            //200
            200 => ["code" => $code, "title" => $title, "field" => "", "detail" => lang("Lang.detail.datosNoRecibidos")],
            201 => ["code" => $code, "title" => $title, "field" => "", "detail" => lang("Lang.detail.solicitudNoProcesada")],

            //LOGIN
            1000 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginCorreo"), "detail" => lang("Lang.detail.correoInvalido"), "action" => lang("Lang.detail.ingresaCorreo")],
            1001 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginCorreo"), "detail" => lang("Lang.detail.correoInvalido"), "action" => lang("Lang.detail.ingresaCorreoValido")],
            //1002 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginContrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.ingresaContrasena")],
            //1003 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginContrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.contrasenaMinima")],
            //1004 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginContrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.contrasenaMaxima")],
            //1005 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginContrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.contrasenaInvalida")],
            1006 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginContrasena"), "detail" => lang("Lang.detail.contrasenaInvalida"), "action" => lang("Lang.detail.contrasenasNoIguales")],

            1007 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => "", "detail" => lang("Lang.detail.loginCorreoContrasenaInvalida"), "action" => lang("")],
            1008 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => "", "detail" => lang("Lang.detail.loginCuentaInactiva"), "action" => ""],
            1009 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginContrasena"), "detail" => lang("Lang.loginContrasenaInvalida"), "action" => lang("")],
            1010 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginCorreo"), "detail" => lang("Lang.detail.tipoDatoIncorreto")],
            1011 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginCorreo"), "detail" => lang("Lang.detail.longitudDatoIncorreto")],
            1012 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => "", "detail" => lang("Lang.detail.datosNoRecibidos")],
            1013 => ["code" => $code, "title" => lang("Lang.title.login"), "field" => lang("Lang.field.loginContrasena"), "detail" => lang("Lang.detail.datosNoRecibidos")],

            //2000
            2001 => ["code" => $code, "title" => lang("Lang.title.auth"), "field" => "", "detail" => lang("Lang.detail.tokenInvalido")],

            //2100 VACANTE
            2100 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => "", "detail" => lang("Lang.detail.vacanteNoPudoAgregar")],
            2101 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => "", "detail" => lang("Lang.detail.vacanteAgregada")],
            2110 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacanteTitulo"), "detail" => lang("Lang.detail.datoNoRecibido")],
            2111 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacanteTitulo"), "detail" => lang("Lang.detail.datoTipoIncorreto")],
            2112 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacanteTitulo"), "detail" => lang("Lang.detail.datoLongitudIncorrecta")],
            2120 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuesto"), "detail" => lang("Lang.detail.datoNoRecibido")],
            2121 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuesto"), "detail" => lang("Lang.detail.datoTipoIncorreto")],
            2122 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuesto"), "detail" => lang("Lang.detail.datoLongitudIncorrecta")],
            2130 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoEspecifico"), "detail" => lang("Lang.detail.datoNoRecibido")],
            2131 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoEspecifico"), "detail" => lang("Lang.detail.datoTipoIncorreto")],
            2132 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoEspecifico"), "detail" => lang("Lang.detail.datoLongitudIncorrecta")],
            2140 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoOtro"), "detail" => lang("Lang.detail.datoNoRecibido")],
            2141 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoOtro"), "detail" => lang("Lang.detail.datoTipoIncorreto")],
            2142 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoOtro"), "detail" => lang("Lang.detail.datoLongitudIncorrecta")],
            2150 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoEspecificoOtro"), "detail" => lang("Lang.detail.datoNoRecibido")],
            2151 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoEspecificoOtro"), "detail" => lang("Lang.detail.datoTipoIncorreto")],
            2152 => ["code" => $code, "title" => lang("Lang.title.agregarVacante"), "field" => lang("Lang.field.vacantePuestoEspecificoOtro"), "detail" => lang("Lang.detail.datoLongitudIncorrecta")],

            //2200 EDITAR VACANTE
            2200 => ["code" => $code, "title" => lang("Lang.title.editarVacante"), "field" => "", "detail" => lang("Lang.detail.vacanteNoSePudoVerificarUsuario")],
            2201 => ["code" => $code, "title" => lang("Lang.title.editarVacante"), "field" => "", "detail" => lang("Lang.detail.vacanteNoPerteneceAUsuario")],
            2202 => ["code" => $code, "title" => lang("Lang.title.editarVacante"), "field" => "", "detail" => lang("Lang.detail.vacantePerteneceAUsuario")],

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
