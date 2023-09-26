import { Modal } from "antd";
import { MODAL_TYPES } from "./utilConst";

export const asignarMensajeTranslation = ({ t, rules, production = true }) => {
	if (production) {
		for (const field in rules) {
			rules[field].forEach((props) => {
				props.message = t(props.message);
			});
		}

		return rules;
	} else {
		const rules = [];

		for (const field in rules) {
			rules.push([field]);
		}

		return rules;
	}
};

export const showModal = ({ type, title, content }) => {
	if (type === MODAL_TYPES.INFO) {
		Modal.info({ title: title, content: content });
	} else if (type === MODAL_TYPES.WARNING) {
		Modal.warning({
			title: title,
			content: <>{content}</>,
		});
	} else if (type === MODAL_TYPES.ERROR) {
		Modal.error({ title: title, content: content });
	}
};

export const getErrorMessages = ({ errors, useLabel = true, useDetail = true, useAction = true, separator = <br /> }) => {
	if (errors.length === 1) {
		const error = errors[0];
		const mensaje = generateErrorMensaje({ error, useLabel, useDetail, useAction });

		return <span>{mensaje}</span>;
	} else if (errors.length > 1) {
		let mensajes = <></>;

		for (const index in errors) {
			const error = errors[index];
			const mensaje = generateErrorMensaje({ error, useLabel, useDetail, useAction });
			mensajes = (
				<>
					{mensajes}
					<span>{mensaje}</span>
					{separator}
				</>
			);
		}

		return <>{mensajes}</>;
	}
};

const generateErrorMensaje = ({ error, useLabel, useDetail, useAction }) => {
	let mensaje = "";

	if (useLabel && error.label) {
		mensaje = `${error.label}:`;
	}

	if (useDetail && error.detail) {
		if (mensaje) {
			mensaje = `${mensaje} ${error.detail}.`;
		} else {
			mensaje = `${error.detail}.`;
		}
	}

	if (useAction && error.action) {
		if (mensaje) {
			mensaje = `${mensaje} ${error.action}.`;
		} else {
			mensaje = `${error.action}.`;
		}
	}

	console.log(mensaje);

	return mensaje;
};

export const getUrlPathName = () => {
	return window.location.pathname;
};
