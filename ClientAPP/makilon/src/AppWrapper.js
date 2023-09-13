import App from "./App";
import AuthContext from "./Utils/AuthContext.js";
import AuthService from "./Services/authservice.services";
const auth = new AuthService();
function AppWrapper() {
	console.log("AppWrapper");

	return (
		<AuthContext.Provider value={{ auth }}>
			<App />
		</AuthContext.Provider>
	);
}

export default AppWrapper;
