import React, { useState, useRef } from "react";
import api from "../../Utils/api";
import AuthService from "../../Services/auth.services";
import CContainer from "../../Components/CContainer";
import { Button, Form, Input, Radio } from "antd";
import { InfoCircleOutlined } from "@ant-design/icons";
import { Col, Divider, Row } from "antd";
import { EyeInvisibleOutlined, EyeTwoTone } from "@ant-design/icons";
import styles from "../../CSS/common.module.css";
import stylesLogin from "../../CSS/login.module.css";
import { Card, Space } from "antd";
import CButton from "../../Components/CButton";

const cardStyle = {
	//width: "360px",
	//height: "192px",
	borderRadius: "5px",
	//marginRight: "24px",
	backgroundColor: "white",
	boxShadow: "rgba(208, 216, 243, 0.6) 0 4px 8px 5px",
	padding: "1rem",
};

const RecuperarContrasena = (props) => {
	const formRef = useRef(null);

	const [form] = Form.useForm();
	const nameValue = Form.useWatch("usuario", form);

	const usuarioRules = [
		{
			required: true,
			message: "Ingresa usuario",
		},
	];

	const contrasenaRules = [
		{
			required: true,
			message: "Ingresa contrasena",
		},
	];

	const onRequiredTypeChange = (e) => {
		console.log(e);
	};

	const iniciarSesion = async ({ usuario, contrasena }) => {
		const response = await AuthService.login(usuario, contrasena);
		console.log(response);

		if (response.status === 200) {
			localStorage.setItem("token", response.data.accessToken);
		} else {
			//alert("hay error")
		}
	};

	const onFinish = (e) => {
		console.log("onFinish");
		console.log(e);

		iniciarSesion({ usuario: e.usuario, contrasena: e.contrasena });
	};

	const onFinishFailed = (e) => {
		console.log("onFinishFailed");
		console.log(e);
	};

	return (
		<CContainer className={stylesLogin.c_container}>
			<Row justify="center">
				<Col xs={16} sm={12} md={10} lg={8} xl={6} xxl={4}>
					<Card title="Recuperar Constraseña" size="default" type="inner" className={styles.c_shadow}>
						<Row justify="center">
							<Col span={24}>
								<Form
									form={form}
									layout="vertical"
									ref={formRef}
									onValuesChange={onRequiredTypeChange}
									onFinish={onFinish}
									onFinishFailed={onFinishFailed}>
									<Form.Item label="Usuario" name="usuario" required tooltip="This is a required field" rules={usuarioRules}>
										<Input size="large" placeholder="input placeholder" />
									</Form.Item>
								</Form>
							</Col>
						</Row>

						<Row justify="center" gutter={16}>
							<Col span={24}>
								<CButton htmlType="submit" size="large" block text="Recuperar contraseña" />
							</Col>
						</Row>
					</Card>
				</Col>
			</Row>
		</CContainer>
	);
};

export default RecuperarContrasena;
