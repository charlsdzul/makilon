import React, { useState, useRef } from "react";
import AuthService from "../../Services/auth.services";
import CContainer from "../../Components/CContainer";
import { Divider, Form, Input } from "antd";
import { InfoCircleOutlined } from "@ant-design/icons";
import { Col, Row } from "antd";
import { EyeInvisibleOutlined, EyeTwoTone } from "@ant-design/icons";
import styles from "../../CSS/common.module.css";
import stylesLogin from "../../CSS/login.module.css";
import { Card } from "antd";
import CButton from "../../Components/CButton";
import { rulesLogin } from "./rulesLogin";
import { useTranslation } from "react-i18next";
import { asignarMensajeTranslation, showModal } from "../../Utils/util.";
import { StatusCodes } from "http-status-codes";
import { MODAL_TYPES } from "../../Utils/utilConst";

const Login = (props) => {
	const formRef = useRef(null);
	const [form] = Form.useForm();
	const [requesting, setRequesting] = useState(false);

	const { t } = useTranslation(["Login"]);
	const [rules] = useState(asignarMensajeTranslation({ t, rules: rulesLogin, production: true }));

	const handleSuccessForm = (e) => {
		setRequesting(true);
		iniciarSesion({ correo: e.correo, contrasena: e.contrasena });
	};

	const iniciarSesion = async ({ correo, contrasena }) => {
		const response = await AuthService.login(correo, contrasena);
		console.log(response);
		setRequesting(false);

		if (response.status === 200) {
			localStorage.setItem("token", response.data.accessToken);
			return;
		}

		let modalType = "";
		let modalTitulo = "";
		let modalMensaje = "";
		const errors = response?.data?.errors ?? [];

		if (errors.length > 0) {
			modalTitulo = errors[0].title ?? "";
			modalMensaje = errors[0].detail ?? "";
		}

		if (response.status === StatusCodes.BAD_REQUEST) {
			modalType = MODAL_TYPES.WARNING;
		} else {
			modalType = MODAL_TYPES.ERROR;
		}

		showModal({ type: modalType, title: modalTitulo, message: modalMensaje });
	};

	return (
		<CContainer className={stylesLogin.c_container}>
			<Row justify="center">
				<Col xs={16} sm={12} md={10} lg={8} xl={6} xxl={4}>
					<Card title={t("Login.labels.title")} size="default" type="inner" className={styles.c_shadow}>
						<Row justify="center">
							<Col span={24}>
								<Form form={form} layout="vertical" ref={formRef} onFinish={handleSuccessForm}>
									<Form.Item name="correo" required label={t("Login.labels.correo")} tooltip={t("Login.tooltips.correo")} rules={rules.correo}>
										<Input placeholder={t("Login.placeholders.correo")} size="large" disabled={requesting} />
									</Form.Item>
									<Form.Item
										name="contrasena"
										required
										label={t("Login.labels.contrasena")}
										tooltip={{ title: t("Login.tooltips.contrasena"), icon: <InfoCircleOutlined /> }}
										rules={rules.contrasena}>
										<Input.Password
											size="large"
											disabled={requesting}
											placeholder={t("Login.placeholders.contrasena")}
											iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
										/>
									</Form.Item>
									<Row justify="center" gutter={16}>
										<Col span={24}>
											<CButton htmlType="submit" size="large" block text={t("Login.labels.ingresar")} loading={requesting} />
										</Col>

										<Col>
											<CButton text={t("Login.labels.olvidasteContrasena")} type="link" disabled={requesting} />
										</Col>
									</Row>
								</Form>
							</Col>
						</Row>

						<Row justify="center">
							<Col span={24}>
								<Divider orientation="left" />
							</Col>

							<Col>
								<CButton
									type="primary"
									text={t("Login.labels.crearCuenta")}
									size="large"
									style={{ background: "#16ff3f", borderColor: "yellow" }}
									disabled={requesting}
								/>
							</Col>
						</Row>
					</Card>
				</Col>
			</Row>
		</CContainer>
	);
};

export default Login;
