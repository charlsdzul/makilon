export const rulesVacante = {
	titulo: [
		{
			required: true,
			message: "Vacante.rules.ingresaTitulo",
		},
		{
			min: 15,
			message: "Vacante.rules.ingresaTituloMinimo",
		},
		{
			min: 25,
			message: "Vacante.rules.ingresaTituloDescriptivo",
			warningOnly: true,
		},
	],
	puesto: [
		{
			required: true,
			message: "Vacante.rules.ingresaPuesto",
		},
	],

	puestoEspecifico: [
		{
			required: true,
			message: "Vacante.rules.ingresaPuestoEspecifico",
		},
	],
};
