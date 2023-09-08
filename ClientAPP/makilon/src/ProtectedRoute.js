import AuthContext from "./Utils/AuthContext";
import { Route, Redirect, Outlet, Navigate } from "react-router-dom";

const ProtectedRoute = ({ children, auth, redirectPath = "/login" }) => {
	console.log(auth.session);
	if (auth.session.user) {
		return children;
	} else {
		return <Navigate to={redirectPath} replace />;
	}
};

export default ProtectedRoute;
