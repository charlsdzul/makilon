import { Navigate } from "react-router-dom";

const ProtectedRoute = ({ session, children }) => {
	console.log("ProtectedRoute", session);
	const redirectPath = "/notaccess";
	// if (!auth) {
	// 	return <Navigate to={redirectPath} replace />;
	// }

	return children;
};
export default ProtectedRoute;
