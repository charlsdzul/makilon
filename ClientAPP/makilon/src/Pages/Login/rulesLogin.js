export const rulesLogin = {
	usuario: [
		{
			required: true,
			message: "Usuario.correoEnBlanco",
		},
		{
			pattern: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
			message: "Usuario:correoInvalido",
		},
		{
			max: 70,
			message: "Common.longitudDatoIncorreto",
		},
	],
	contrasena: [
		{
			required: true,
			message: "Usuario.contrasenaEnBlanco",
		},
		{
			max: 15,
			message: "Usuario.contrasenaMaxima",
		},
	],
};

export const getRules = () => {};
