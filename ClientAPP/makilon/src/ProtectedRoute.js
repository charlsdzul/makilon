import { Navigate } from "react-router-dom";

const ProtectedRoute = ({ auth, children }) => {
	const redirectPath = "/notaccess";
	if (!auth) {
		return <Navigate to={redirectPath} replace />;
	}

	return children;
};
export default ProtectedRoute;
