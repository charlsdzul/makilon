import React ,{Suspense}from "react";
import { createBrowserRouter } from "react-router-dom";
import AppWrapper from "./AppWrapper";
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
		element: <AppWrapper />,

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
				//index: true,
				path: "portal",
				element: <Suspense fallback={<>...</>}><Home />	</Suspense>,
			},
			{
				//index: true,
				path: "mi-cuenta",
				element: <Home />,
			},
			{
				//index: true,
				path: "mis-vacantes",
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
				path: "vacante",
				//				path: "vacante/:vacanteId",

				loader: (data) => {
					console.log(data);
					return data.params;
				},
				element:							<Suspense fallback={<>...</>}>
				<Vacante />	</Suspense>,
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
							<Suspense fallback={<>...</>}>
							<ProtectedRoute auth={auth}>
								<Dashboard auth={auth} />
							</ProtectedRoute>
							</Suspense>
						)}
					</AuthContext.Consumer>
				),
			},
		],
	},
]);
