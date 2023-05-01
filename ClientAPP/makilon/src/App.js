import logo from "./logo.svg";
import "./App.css";
import "bootstrap/dist/css/bootstrap.min.css";
import Home from "./Pages/Home";
import TopBar from "./Layouts/TopBar";
import Login from "./Pages/Login";

function App() {
	return (
		<>
			<TopBar></TopBar>
			<Login></Login>
		</>
	);
}

export default App;
