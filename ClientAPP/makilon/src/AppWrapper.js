import App from "./App";
import AuthContext from "./Utils/AuthContext";

const session = { token: "123456", user: "charls" };

function AppWrapper() {
	return (
		<>
			<AuthContext.Provider value={{ session }}>
				<>
					{/* <TopBar session={session}></TopBar>
					<RouterProvider router={router}></RouterProvider> */}
					<App />
				</>
			</AuthContext.Provider>
		</>
	);
}

export default AppWrapper;
