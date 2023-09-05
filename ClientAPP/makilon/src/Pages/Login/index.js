import React from "react";
import Container from "react-bootstrap/esm/Container";
import Form from "react-bootstrap/Form";
import Button from "react-bootstrap/esm/Button";
import api from "../../Utils/api";
import AuthService from "../../Services/auth.services";

const Login = (props) => {
	const iniciarSesion = async () => {
		const response = await AuthService.login("c.dzul@hotmail.com", "12345678");
		console.log(response);

		if (response.status===200) {
			localStorage.setItem("token", response.data.accessToken);
		}else{
			//alert("hay error")
		}
	};

	return (
		<>
			<Container fluid mt={5}>
				<Form>
					<Form.Group className="mb-3" controlId="formBasicEmail">
						<Form.Label>Email address</Form.Label>
						<Form.Control type="email" placeholder="Enter email" />
						<Form.Text className="text-muted">We'll never share your email with anyone else.</Form.Text>
					</Form.Group>

					<Form.Group className="mb-3" controlId="formBasicPassword">
						<Form.Label>Password</Form.Label>																																										
						<Form.Control type="password" placeholder="Password" />
					</Form.Group>
					<Form.Group className="mb-3" controlId="formBasicCheckbox">
						<Form.Check type="checkbox" label="Check me out" />
					</Form.Group>
					<Button variant="primary" type="button" onClick={iniciarSesion}>
						Submit
					</Button>
				</Form>
			</Container>
		</>
	);
};

export default Login;
