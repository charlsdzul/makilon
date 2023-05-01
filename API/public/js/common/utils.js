export function validateEmailRegex(mail) {
	return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail);
}

export function getSuccessDataResponse(response) {
	//
	try {
		return response?.response?.data ?? null;
	} catch (error) {
		console.log(error);
	}
}

export function getErrorDataResponse(response) {
	let dataResponse = null;
	let allErrors = null;
	let generalErrors = null;
	let parametersErrors = null;
	let codeError = null;
	let codeDescription = null;

	try {
		dataResponse = response.response?.data ?? null;
		allErrors = response.response?.data.errors ?? null;

		if (dataResponse) {
			codeError = dataResponse.code;
			codeDescription = dataResponse.codeDescription;

			if (allErrors) {
				//Obtiene los errores que son de parametros
				parametersErrors = allErrors.filter((error) => {
					if (error?.source?.parameter) return error;
				});

				if (parametersErrors.length === 0) parametersErrors = null;

				//Obtiene los errores que son generales
				generalErrors = allErrors.filter((error) => {
					if (!error?.source?.parameter) return error;
				});

				if (generalErrors.length === 0) generalErrors = null;
			}
		}

		//return { allErrors, generalErrors, parametersErrors, dataResponse,codeError,codeDescription };
	} catch (error) {
		console.log(error);
		//	return { allErrors, generalErrors, parametersErrors, dataResponse };
	}

	return { allErrors, generalErrors, parametersErrors, dataResponse, codeError, codeDescription };
}
