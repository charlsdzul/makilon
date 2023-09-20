export const rulesLogin = {
	correo: [
		{
			required: true,
			message: "Login.rules.ingresaCorreo",
		},
		{
			pattern: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
			message: "Login.rules.correoInvalido",
		},
		{
			max: 70,
			message: "Login.rules.correoInvalido",
		},
	],
	contrasena: [
		{
			required: true,
			message: "Login.rules.ingresaContrasena",
		},
		{
			max: 15,
			message: "Login.rules.contrasenaInvalida",
		},
		{
			min: 8,
			message: "Login.rules.contrasenaInvalida",
		},
	],
};
