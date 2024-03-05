import { VacanteFieldsLength } from "../../Utils/fieldsLength";

export const rulesVacante = {
	titulo: [
		{
			required: true,
			message: "Vacante.rules.ingresaTitulo",
		},
		{
			min: VacanteFieldsLength.TITULO_MIN,
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
	puestoOtro: [
		{
			required: true,
			message: "Vacante.rules.ingresaPuestoOtro",
		},
	],

	puestoEspecifico: [
		{
			required: true,
			message: "Vacante.rules.ingresaPuestoEspecifico",
		},
	],
	puestoEspecificoOtro: [
		{
			required: true,
			message: "Vacante.rules.ingresaPuestoEspecificoOtro",
		},
	],
};
