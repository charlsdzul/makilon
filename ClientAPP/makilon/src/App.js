import logo from "./logo.svg";
import "./App.css";
import React from "react";

import "bootstrap/dist/css/bootstrap.min.css";
import Home from "./Pages/Home";
import TopBar from "./Layouts/TopBar";
import AuthContext from "./Utils/AuthContext";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import { router } from "./Routes";

const session = { token: "123456", user: "charls" };

function App() {
	console.log("APP");
	// const router = createBrowserRouter([
	// 	{
	// 		path: "/",
	// 		children: [
	// 			{
	// 				path: "notaccess",
	// 				element: <NotAccess />,
	// 			},
	// 			{
	// 				path: "*",
	// 				element: <NotFound />,
	// 			},
	// 			{
	// 				index: true,
	// 				path: "inicio",
	// 				element: <Home />,
	// 			},
	// 			{
	// 				path: "login",
	// 				element: <Login />,
	// 			},
	// 			{
	// 				path: "registro",
	// 				element: <Register />,
	// 			},
	// 			{
	// 				path: "vacante/:vacanteId",
	// 				loader: (data) => {
	// 					console.log(data);
	// 					return data.params;
	// 				},
	// 				element: (
	// 					<React.Suspense fallback={<>...loading</>}>
	// 						<Vacant />
	// 					</React.Suspense>
	// 				),
	// 				errorElement: <ErrorBundary />,
	// 			},

	// 			{
	// 				path: "dashboard",
	// 				errorElement: <ErrorBundary />,
	// 				element: (
	// 					<ProtectedRoute session={session}>
	// 						<AuthContext.Consumer>{(auth) => <Dashboard props={{ ...auth }} />}</AuthContext.Consumer>
	// 					</ProtectedRoute>
	// 				),
	// 			},
	// 		],
	// 	},
	// ]);

	return (
		<>
			<AuthContext.Provider value={{ session }}>
				<>
					<TopBar session={session}></TopBar>
					<RouterProvider router={router}></RouterProvider>
				</>
			</AuthContext.Provider>
		</>
	);
}

export default App;
