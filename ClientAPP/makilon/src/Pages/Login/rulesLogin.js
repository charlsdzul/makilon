export const rulesLogin = {
	correo: [
		{
			required: true,
			message: "Login.rules.correoEnBlanco",
		},
		{
			pattern: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
			message: "Login.rules.correoInvalido",
		},
		{
			max: 70,
			message: "Login.rules.longitudDatoIncorreto",
		},
	],
	contrasena: [
		{
			required: true,
			message: "Login.rules.contrasenaEnBlanco",
		},
		{
			max: 15,
			message: "Login.rules.contrasenaMaxima",
		},
	],
};
