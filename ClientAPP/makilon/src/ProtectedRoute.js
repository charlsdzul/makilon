import AuthContext from "./Utils/AuthContext";
import { Route, Redirect } from "react-router-dom";

const ProtectedRoute = ({ session, children: Component }) => {
	return (
		<AuthContext.Consumer>
			{(auth) => (
				<Route
					render={(props) => {
						return <Component auth={auth} {...props} />;
					}}
				/>
			)}
		</AuthContext.Consumer>
	);
};
export default ProtectedRoute;
