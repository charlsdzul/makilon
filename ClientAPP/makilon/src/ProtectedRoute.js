import { StatusCodes } from "http-status-codes";
import { useEffect, useState } from "react";
import { Navigate } from "react-router-dom";

const ProtectedRoute = ({ children, auth, redirectPath = "/login" }) => {
	const [validDone, setValidDone] = useState(false);
	const [isValid, setIsValid] = useState(false);

	const validarToken = async () => {
		const exists = auth.existsToken();
		if (!exists) {
			setValidDone(true);
			return;
		}

		const response = await auth.isAuthenticated();
		console.log(response);
		if (response.status === StatusCodes.OK) {
			setIsValid(true);
		}

		setValidDone(true);
	};

	useEffect(() => {
		if (!validDone) validarToken();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	if (!validDone) return <></>;

	if (isValid) {
		return children;
	} else {
		return <Navigate to={redirectPath} replace />;
	}
};

export default ProtectedRoute;
