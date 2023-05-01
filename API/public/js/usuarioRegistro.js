import { validateEmail, validatePassword } from "./common/validations.js";
import { getErrorDataResponse, getSuccessDataResponse } from "./common/utils.js";

const btnCrearCuenta = document.querySelector("#btnCrearCuenta");

btnCrearCuenta.addEventListener("click", (e) => crearCuenta(e), false);

const crearCuenta = async (e) => {
	e.preventDefault();

	const txtCorreo = document.querySelector("#usuario_correo");
	const txtPwd1 = document.querySelector("#usuario_pwd1");
	const txtPwd2 = document.querySelector("#usuario_pwd2");

	//if (validations) {
	// PETICION API

	await axios
		.post("http://localhost/pulpox11/public/api/v1/usuario/registro", {
			usuario_correo: txtCorreo.value,
			usuario_pwd1: txtPwd1.value,
			usuario_pwd2: txtPwd2.value,
		})
		.then(function (response) {
			const resp = getSuccessDataResponse(response);
			console.log(resp);
		})
		.catch(function (error) {
			const { parametersErrors, generalErrors } = getErrorDataResponse(error);
			removeInvalidValidation();
			if (parametersErrors) {
				parametersErrors.forEach((error) => {
					validationParameter(error);
				});
			}
			console.log(generalErrors);

			if (generalErrors) {
				if (generalErrors[0].code === 1006) {
					//1006 Contrasenas no son iguales
					makeInvalidValidation("usuario_pwd1");
					makeInvalidValidation("usuario_pwd2");
					makeInvalidGeneralValidation("general_validation", generalErrors[0].title);
				}
			}
		});
	//}
};

const validationParameter = (error) => {
	const messageError = error?.title ?? "";
	const parameterId = error?.source?.parameter ?? null;

	const parameterControl = document.querySelector(`#${parameterId}`);
	const parameterValidation = document.querySelector(`#${parameterId}_validation`);

	parameterControl.classList.remove("is-valid");
	parameterControl.classList.add("is-invalid");
	parameterControl.setCustomValidity("invalid");
	parameterValidation.textContent = messageError;
};

const makeInvalidValidation = (fieldId) => {
	const fieldElement = document.querySelector(`#${fieldId}`);
	fieldElement.classList.remove("is-valid");
	fieldElement.classList.add("is-invalid");
	fieldElement.setCustomValidity("invalid");
};

const makeInvalidGeneralValidation = (fieldId, message) => {
	const fieldValidation = document.querySelector(`#${fieldId}`);
	console.log(fieldValidation);
	fieldValidation.textContent = message;
};

const removeInvalidValidation = () => {
	const fieldValidation = document.querySelector(`#general_validation`);
	const fields = document.querySelectorAll("[validatePlpx]");

	fieldValidation.textContent = "";

	fields.forEach((field) => {
		const fieldElement = document.querySelector(`#${field.id}`);
		const fieldValidation = document.querySelector(`#${field.id}_validation`);
		fieldElement.classList.remove("is-invalid");
		fieldElement.classList.add("is-valid");
		fieldElement.setCustomValidity("valid");
		fieldValidation.textContent = "";
	});
};

const validationsRegistro = (txtCorreo, txtPwd1, txtPwd2) => {
	const txtCorreoValidation = document.querySelector("#usuario_correo_validation");
	const txtPwd1Validation = document.querySelector("#usuario_pwd1_validation");
	const txtPwd2Validation = document.querySelector("#usuario_pwd2_validation");

	const txtCorreoValid = validateEmail(txtCorreo.value);
	const txtPwd1Valid = validatePassword(txtPwd1.value);
	const txtPwd2Valid = validatePassword(txtPwd2.value);

	txtCorreoValid.valid ? txtCorreo.classList.add("is-valid") : txtCorreo.classList.remove("is-valid");
	txtCorreoValid.valid ? txtCorreo.classList.remove("is-invalid") : txtCorreo.classList.add("is-invalid");
	txtCorreo.setCustomValidity(txtCorreoValid.valid ? "" : "invalid");
	txtCorreoValidation.textContent = txtCorreoValid.valid ? "" : txtCorreoValid.message;

	txtPwd1Valid.valid ? txtPwd1.classList.add("is-valid") : txtPwd1.classList.remove("is-valid");
	txtPwd1Valid.valid ? txtPwd1.classList.remove("is-invalid") : txtPwd1.classList.add("is-invalid");
	txtPwd1.setCustomValidity(txtPwd1Valid.valid ? "" : "invalid");
	txtPwd1Validation.textContent = txtPwd1Valid.valid ? "" : txtPwd1Valid.message;

	txtPwd2Valid.valid ? txtPwd2.classList.add("is-valid") : txtPwd2.classList.remove("is-valid");
	txtPwd2Valid.valid ? txtPwd2.classList.remove("is-invalid") : txtPwd2.classList.add("is-invalid");
	txtPwd2.setCustomValidity(txtPwd2Valid.valid ? "" : "invalid");
	txtPwd2Validation.textContent = txtPwd2Valid.valid ? "" : txtPwd2Valid.message;

	if (!txtCorreoValid.valid || !txtPwd1Valid.valid || !txtPwd2Valid.valid) return false;
	else return true;
};
