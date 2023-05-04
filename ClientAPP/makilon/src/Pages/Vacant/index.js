import React from "react";
import Container from "react-bootstrap/esm/Container";
import { useLoaderData } from "react-router-dom";

const Vacant = (props) => {
	const albums = useLoaderData();

	console.log("Vacant", albums);
	return (
		<>
			<Container fluid>
				<h1>Vacant</h1>
			</Container>
		</>
	);
};

export default Vacant;
