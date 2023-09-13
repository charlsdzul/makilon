import { MODAL_TYPES } from "./utilConst";
import { Modal } from "antd";

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
		Modal.warning({ title: title, content: content });
	} else if (type === MODAL_TYPES.ERROR) {
		Modal.error({ title: title, content: content });
	}
};
