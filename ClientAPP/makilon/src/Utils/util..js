export const asignarMensajeTranslation = (t, rules) => {
	for (const field in rules) {
		rules[field].forEach((props) => {
			props.message = t(props.message);
		});
	}

	return rules;
};
