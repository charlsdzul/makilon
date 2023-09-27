import { useLoaderData } from "react-router-dom";
import App from "./App";
import AuthService from "./Services/authservice.services";
import AuthContext from "./Utils/AuthContext.js";
const auth = new AuthService();
function AppWrapper() {
	const albums = useLoaderData();

	console.log("AppWrapper", albums);

	return (
		<AuthContext.Provider value={{ auth }}>
			<App />
		</AuthContext.Provider>
	);
}

export default AppWrapper;

//TODO ELIMINAR
