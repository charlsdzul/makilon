import React from "react";
import Container from "react-bootstrap/esm/Container";

const Dashboard = (props) => {
	console.log("dashboard", props);
	return (
		<Container fluid>
			<h1>Dashboard</h1>
		</Container>
	);
};

export default Dashboard;
