import { StatusCodes } from "http-status-codes";
import { useEffect, useState } from "react";

const ProtectedRoute = ({ children, auth, redirectPath = "/login" }) => {
	//PENDIENTE REDIRECCIONER AL LOGIN CUANDO SE ELINA EL TOKEN, Y SE SELECCIONA OTRA OPCION DEL SIDER
	const [validDone, setValidDone] = useState(false);
	const [isValid, setIsValid] = useState(false);

	const validarToken = async () => {
		const existsToken = auth.existsToken();
		if (!existsToken) {
			setValidDone(true);
			return;
		}

		const response = await auth.isAuthenticated();
		if (response === null) {
			setValidDone(true);
			return;
		}

		if (response.status === StatusCodes.OK) {
			setIsValid(true);
		}

		setValidDone(true);
	};

	useEffect(() => {
		validarToken();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	useEffect(() => {
		if (validDone && !isValid) {
			window.location.href = "/login";
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [validDone]);

	//console.log("validDone", validDone);

	return validDone && isValid ? children : <></>;
};

export default ProtectedRoute;
