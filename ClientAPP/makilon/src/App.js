import "./App.css";
import React from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import TopBar from "./Layouts/TopBar";
import AuthContext from "./Utils/AuthContext";
import { RouterProvider } from "react-router-dom";
import { router } from "./Routes";

const session = { token: "123456", user: "charls" };

function App() {
	console.log("App");

	return (
		<AuthContext.Provider value={{ session }}>
			<>
				<TopBar session={session}></TopBar>
				<RouterProvider router={router}></RouterProvider>
			</>
		</AuthContext.Provider>
	);
}

export default App;
