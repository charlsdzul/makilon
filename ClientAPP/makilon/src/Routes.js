import React from "react";
import { createBrowserRouter } from "react-router-dom";
import RecuperarContrasena from "./Pages/RecuperarContrasena";
import ProtectedRoute from "./ProtectedRoute";
import AuthContext from "./Utils/AuthContext";

const Register = React.lazy(() => import("./Pages/Register"));
const Vacante = React.lazy(() => import("./Pages/Vacante"));
const Dashboard = React.lazy(() => import("./Pages/Dashboard.js"));
const Login = React.lazy(() => import("./Pages/Login"));
const ErrorBundary = React.lazy(() => import("./ErrorBundary"));
const NotAccess = React.lazy(() => import("./NotAccess"));
const NotFound = React.lazy(() => import("./NotFound"));
const Home = React.lazy(() => import("./Pages/Home"));

export const router = createBrowserRouter([
	{
		path: "/",
		children: [
			{
				path: "notaccess",
				element: <NotAccess />,
			},
			// {
			// 	path: "*",
			// 	element: <NotFound />,
			// },
			{
				index: true,
				path: "inicio",
				element: <Home />,
			},
			{
				path: "login",
				element: <AuthContext.Consumer>{({ auth }) => <Login auth={auth} />}</AuthContext.Consumer>,
			},
			{
				path: "login/recuperar",
				element: <RecuperarContrasena />,
			},
			{
				path: "registro",
				element: <Register />,
			},
			{
				path: "vacante/",
				//				path: "vacante/:vacanteId",

				loader: (data) => {
					console.log(data);
					return data.params;
				},
				element: (
					
								<Vacante  />
							
				),
				errorElement: <ErrorBundary />,
			},

			{
				path: "dashboard",
				errorElement: <ErrorBundary />,
				loader: () => {
					console.log("111111111111");
					return null;
				},
				element: (
					<AuthContext.Consumer>
						{({ auth }) => (
							<ProtectedRoute auth={auth}>
								<Dashboard auth={auth} />
							</ProtectedRoute>
						)}
					</AuthContext.Consumer>
				),
			},
		],
	},
]);
