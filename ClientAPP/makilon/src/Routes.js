import React, { Suspense } from "react";
import { createBrowserRouter } from "react-router-dom";
import LayoutApp from "./LayoutApp";
import ProtectedRoute from "./ProtectedRoute";
import AuthService from "./Services/authservice.services";
import AuthContext from "./Utils/AuthContext";

const auth = new AuthService();

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
		loader: async () => {
			const existsToken = auth.existsToken();
			if (!existsToken) return { isAuthenticated: existsToken };

			const response = await auth.isAuthenticated();
			if (response === null) return { isAuthenticated: false };

			const isAuthenticated = response?.data?.isValidToken ?? false;
			return { isAuthenticated: isAuthenticated };
		},
		element: (
			<Suspense fallback={<>...</>}>
				<LayoutApp />
			</Suspense>
		),

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
				element: (
					<Suspense fallback={<>...</>}>
						<Home />{" "}
					</Suspense>
				),
			},
			{
				path: "mi-cuenta",
				element: <Home />,
			},

			{
				path: "login",
				element: <AuthContext.Consumer>{({ auth }) => <Login auth={auth} />}</AuthContext.Consumer>,
			},
			// {
			// 	path: "login/recuperar",
			// 	element: <RecuperarContrasena />,
			// },
			{
				path: "registro",
				element: <Register />,
			},
			{
				path: "vacante", //path: "vacante/:vacanteId",
				// loader: (data) => {	return data.params; },
				element: (
					<AuthContext.Consumer>
						{({ auth }) => (
							<Suspense fallback={<>...</>}>
								<ProtectedRoute auth={auth}>
									<Vacante auth={auth} />
								</ProtectedRoute>
							</Suspense>
						)}
					</AuthContext.Consumer>
				),
				errorElement: <ErrorBundary />,
			},
			{
				path: "mis-vacantes",
				element: (
					<AuthContext.Consumer>
						{({ auth }) => (
							<Suspense fallback={<>...</>}>
								<ProtectedRoute auth={auth}>
									<Home auth={auth} />
								</ProtectedRoute>
							</Suspense>
						)}
					</AuthContext.Consumer>
				),
			},

			{
				path: "dashboard",
				errorElement: <ErrorBundary />,
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
