import { EyeInvisibleOutlined, EyeTwoTone, InfoCircleOutlined } from "@ant-design/icons";
import { Card, Col, Divider, Form, Input, Row } from "antd";
import { StatusCodes } from "http-status-codes";
import React, { useEffect, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { useLoaderData, useNavigate } from "react-router-dom";
import styles from "../../CSS/common.module.css";
import stylesLogin from "../../CSS/login.module.css";
import CButton from "../../Components/CButton";
import CContainer from "../../Components/CContainer";
import AuthService from "../../Services/authservice.services";
import { MODAL_TYPES } from "../../Utils/utilConst";
import { URLS_PORTAL } from "../../Utils/utilUrl";
import { asignarMensajeTranslation, getErrorMessages, showModal } from "../../Utils/utils";
import { rulesLogin } from "./rulesLogin";

const Login = (props) => {
	console.log("Login", props);
	const formRef = useRef(null);
	const [form] = Form.useForm();
	const [requesting, setRequesting] = useState(false);
	const [existValidToken, setExistValidToken] = useState(false);
	const [showLogin, setShowLogin] = useState(false);
	const loaderData = useLoaderData();

	const navigate = useNavigate();

	const { t } = useTranslation(["Login"]);
	const [rules] = useState(asignarMensajeTranslation({ t, rules: rulesLogin, production: false }));

	const handleSuccessForm = (e) => {
		setRequesting(true);
		login({ correo: e.correo, contrasena: e.contrasena });
	};

	const login = async ({ correo, contrasena }) => {
		const auth = new AuthService();
		const response = await auth.authorize(correo, contrasena);
		console.log(response);
		setRequesting(false);

		if (!response) {
			showModal({ type: MODAL_TYPES.WARNING, title: t("Login.lblTitleLogin"), content: t("Login.messages.errorInicioSesion") });
			return;
		}

		if (response.status === StatusCodes.OK) {
			const token = response?.data?.token;
			iniciarSesion(token);
			return;
		}

		let modalTitulo = "";
		let modalMensaje = "";
		const errors = response?.errors ?? [];

		if (errors.length > 0) {
			modalTitulo = errors[0].title ?? "";
			modalMensaje = getErrorMessages({ errors });
		}

		const modalType = response.status === StatusCodes.BAD_REQUEST ? MODAL_TYPES.WARNING : MODAL_TYPES.ERROR;
		showModal({ type: modalType, title: modalTitulo, content: modalMensaje });
	};

	const iniciarSesion = (token) => {
		if (token) {
			window.location.href = URLS_PORTAL.DASHBOARD;
		} else {
			const mensaje = `${t("Login.messages.errorInicioSesion")} ${t("Login.messages.errorInicioSesionNotificacion")}`;
			showModal({ type: MODAL_TYPES.ERROR, title: t("Login.lblTitleLogin"), content: mensaje });
			//PENDIENTE ENVIAR NOTIFICACION DE ERROR
		}
	};

	useEffect(() => {
		const iniciarPrograma = async () => {
			console.log("loaderData Login", loaderData);

			if (loaderData?.isAuthenticated) {
				window.location.href = URLS_PORTAL.DASHBOARD;
			} else {
				props.auth.cleanToken();
				setShowLogin(true);
			}
		};

		iniciarPrograma();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	if (!showLogin) return <></>;

	return (
		<CContainer className={stylesLogin.c_container}>
			<Row justify="center">
				<Col xs={16} sm={12} md={10} lg={8} xl={6} xxl={4}>
					<Card title={t("Login.lblTitleLogin")} size="default" type="inner" className={styles.c_shadow}>
						<Row justify="center">
							<Col span={24}>
								<Form form={form} layout="vertical" ref={formRef} onFinish={handleSuccessForm}>
									<Form.Item name="correo" required label={t("Login.lblCorreo")} tooltip={t("Login.ttCorreo")} rules={rules.correo}>
										<Input placeholder={t("Login.phCorreo")} size="large" disabled={requesting} />
									</Form.Item>
									<Form.Item
										name="contrasena"
										required
										label={t("Login.lblContrasena")}
										tooltip={{ title: t("Login.ttContrasena"), icon: <InfoCircleOutlined /> }}
										rules={rules.contrasena}>
										<Input.Password
											size="large"
											disabled={requesting}
											placeholder={t("Login.phContrasena")}
											iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
										/>
									</Form.Item>
									<Row justify="center" gutter={16}>
										<Col span={24}>
											<CButton htmlType="submit" size="large" block text={t("Login.btnIngresar")} loading={requesting} />
										</Col>

										<Col>
											<CButton text={t("Login.btnOlvidasteContrasena")} type="link" disabled={requesting} />
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
									text={t("Login.btnCrearCuenta")}
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
