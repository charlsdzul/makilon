import logo from "./logo.svg";
import "./App.css";
import React from "react";

import "bootstrap/dist/css/bootstrap.min.css";
import Home from "./Pages/Home";
import TopBar from "./Layouts/TopBar";
import AuthContext from "./Utils/AuthContext";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import ProtectedRoute from "./ProtectedRoute";

const Register = React.lazy(() => import("./Pages/Register"));
const Vacant = React.lazy(() => import("./Pages/Vacant"));
const Dashboard = React.lazy(() => import("./Pages/Dashboard.js"));
const Login = React.lazy(() => import("./Pages/Login"));
const ErrorBundary = React.lazy(() => import("./ErrorBundary"));
const NotAccess = React.lazy(() => import("./NotAccess"));
const NotFound = React.lazy(() => import("./NotFound"));

function App() {
	const router = createBrowserRouter([
		{
			path: "/",
			children: [
				{
					path: "notaccess",
					element: <NotAccess />,
				},
				{
					path: "*",
					element: <NotFound />,
				},
				{
					index: true,
					path: "inicio",
					element: <Home />,
				},
				{
					path: "login",
					element: <Login />,
				},
				{
					path: "registro",
					element: <Register />,
				},
				{
					path: "vacante/:vacanteId",
					loader: (data) => {
						console.log(data);
						return data.params;
					},
					element: (
						<React.Suspense fallback={<>...loading</>}>
							<Vacant />
						</React.Suspense>
					),
					errorElement: <ErrorBundary />,
				},
				{
					path: "dashboard",
					errorElement: <ErrorBundary />,
					element: (
						<ProtectedRoute auth={false}>
							<Dashboard />
						</ProtectedRoute>
					),
				},
			],
		},
	]);

	return (
		<>
			<AuthContext.Consumer>
				{(auth) => (
					<>
						<TopBar auth={"auth"}></TopBar>
						<RouterProvider router={router}></RouterProvider>
					</>
				)}
			</AuthContext.Consumer>
		</>
	);
}

export default App;
