import React from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import Layout from "./Layout";

const session = { token: "123456", user: "charls" };

function App() {
	console.log("App");

	return (
		<Layout/>		 
	);
}

export default App;
