export const commonMessages = {
	//100
	100: translateCommon("tipoDatoIncorreto"),
	101: translateCommon("longitudDatoIncorreto"),
	102: translateCommon("datoInvalido"),
	103: translateCommon("datoContieneEspaciosOCaracteresInv"),
	//200
	200: translateCommon("datosNoRecibidos"),
	201: translateCommon("datosActualizados"),
	202: translateCommon("datoNoActualizados"),
	203: translateCommon("errorDatosValidacion"),
	//800
	800: translateCommon("intentoNoLogueado"),
	801: translateCommon("solicitudNoProcesada"),
	802: translateCommon("idInvalido"),
	803: translateCommon("queryParamsInvalidos"),
};

export const usuarioMessages = {
	1000: translateUsuario("correoEnBlanco"),
	1001: translateUsuario("correoInvalido"),
	1002: translateUsuario("contrasenaEnBlanco"),
	1003: translateUsuario("contrasenaMinima"),
	1004: translateUsuario("contrasenaMaxima"),
	1005: translateUsuario("contrasenaInvalida"),
	1006: translateUsuario("contrasenasNoIguales"),
};

export const apiMessages = {
	9000: translateUsuario("accesoNoPermitido"),
};

export function translateCommon(type) {
	var messages = {
		tipoDatoIncorreto: "Tipo de dato incorrecto",
		longitudDatoIncorreto: "Longitud de dato incorrecto",
		datoInvalido: "Dato inválido",
		datoContieneEspaciosOCaracteresInv: "Dato contiene espacios en blanco o caracteres inválidos",
		datosNoRecibidos: "No recibimos tus datos",
		datosActualizados: "Tus datos se actualizaron con éxito",
		datoNoActualizados: "No pudimos actualizar tus datos",
		errorDatosValidacion: "Datos no pasaron validación",
		intentoNoLogueado: "Intento de accion sin estar logueado",
		solicitudNoProcesada: "Su solicitud no se pudo procesar.",
		idInvalido: "ID inválido",
		queryParamsInvalidos: "Parámetro {0} de URL inválido.",
	};
	return messages[type];
}

export function translateUsuario(type) {
	var messages = {
		correoEnBlanco: "Ingresa un correo",
		correoInvalido: "Correo inválido",
		contrasenaEnBlanco: "Ingresa una contraseña",
		contrasenaMinima: "Contraseña debe tener mínimo 8 caracteres",
		contrasenaMaxima: "Contraseña debe tener máximo 15 caracteres",
		contrasenaInvalida: "Contraseña inválida",
		contrasenasNoIguales: "Contraseñas ingresadas deben ser iguales",
	};
	return messages[type];
}

export function translateAPI(type) {
	var messages = {
		accesoNoPermitido: "Acceso no permitido",
	};
	return messages[type];
}
