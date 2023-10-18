import { StatusCodes } from "http-status-codes";
import { get } from "./api";

export const obtenerCatalogo = async ({ catalogo, useValueLabel }) => {
	let url = "";

	if (catalogo === "puestos") {
		url = "catalogos/puestos";
	} else if (catalogo === "puestosEspecificos") {
		url = "catalogos/puestosEspecificos";
	}

	const response = await get({ url });

	if (!response) {
		return { data: [], status: response?.status };
	}

	if (!response || response?.status !== StatusCodes.OK) {
		return { data: [], status: response?.status };
	}

	let data = [];
	if (useValueLabel) {
		data = createObjectWithValueLabel([...response?.data]);
	} else {
		data = response?.data;
	}

	return { data: data, status: response.status };
};

const createObjectWithValueLabel = (data) => {
	const newData = data.map(function (obj) {
		return { value: obj["sigla"], label: obj["descripcion"] };
	});

	return newData;
};
