import React from "react";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import ProtectedRoute from "./ProtectedRoute";
import AuthContext from "./Utils/AuthContext";
import RecuperarContrasena from "./Pages/RecuperarContrasena";

const Register = React.lazy(() => import("./Pages/Register"));
const Vacant = React.lazy(() => import("./Pages/Vacant"));
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
				element: <Login />,
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
