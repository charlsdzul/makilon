import App from "./App";
import AuthContext from "./Utils/AuthContext.js";

const session = { token: "123456", user: "charls" };

function AppWrapper() {
	return (
		<AuthContext.Provider value={{ session }}>
			<App />
		</AuthContext.Provider>
	);
}

export default AppWrapper;
