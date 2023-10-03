import { useLoaderData } from "react-router-dom";
import AuthService from "./Services/authservice.services";
import AuthContext from "./Utils/AuthContext.js";
import App from "./respaldo_App";
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
