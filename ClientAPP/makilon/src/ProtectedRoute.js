import { useEffect, useState } from "react";
import { Navigate } from "react-router-dom";

const ProtectedRoute = ({ children, auth, redirectPath = "/login" }) => {
	console.log("ProtectedRoute");
	const [validDone, setValidDone] = useState(false);
	const [isValid, setIsValid] = useState(false);

	const validarToken = async () => {
		const exists = auth.existsToken();
		if (!exists) {
			setIsValid(false);
			setValidDone(true);
			return;
		}

		const isAuthenticated = await auth.authenticated();
		console.log(isAuthenticated);
	};

	useEffect(() => {
		console.log("assasassas");
		validarToken();
	}, []);

	if (!validDone) return <></>;

	if (isValid) {
		return children;
	} else {
		// return <Navigate to={redirectPath} replace />;
		return children;
	}
};

export default ProtectedRoute;
