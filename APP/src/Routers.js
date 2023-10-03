import { lazy } from "@loadable/component";
import React, { Suspense } from "react";
import { Route, Routes, Link } from "react-router-dom";
//import Home from "./Pages/Home";
import Register from "./Pages/Register";
import Login from "./Pages/Login";

const Home = lazy(() => import("./Pages/Home"));
//const ACATG001 = lazy(() => import("../ACatalogos/ACATG001"));
//const ACATG002 = lazy(() => import("../ACatalogos/ACATG002"));

const Routers = () => {
	return (
		<>
			<Routes>
				<Route
					path="inicio"
					element={
						<React.Suspense fallback={"Cargando..."}>
							<Home />
						</React.Suspense>
					}
				/>
				{/* <Route path="login" element={<Login />} />
				<Route path="registro" element={<Register />} /> */}
			</Routes>
		</>
	);
};

export default Routers;
