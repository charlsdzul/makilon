import { CloseSquareFilled } from "@ant-design/icons";
import { AutoComplete, Col, Form, Input, Row, Select, Tabs, Typography } from "antd";
import Alert from "antd/es/alert/Alert";
import Card from "antd/es/card/Card";
import TextArea from "antd/es/input/TextArea";
import { default as React, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { useLoaderData } from "react-router-dom";
import styles from "../../CSS/common.module.css";
import CButton from "../../Components/CButton";
import CContainer from "../../Components/CContainer";
import { asignarMensajeTranslation } from "../../Utils/utils";
import { rulesVacante } from "./rulesVacante";

const { Option } = Select;

const Vacante = (props) => {
	const formAgregarVacanteRef = useRef(null);
	const formEditarVacanteRef = useRef(null);

	const [form] = Form.useForm();
	const albums = useLoaderData();
	const [anotherOptions, setAnotherOptions] = useState([]);
	const [esNuevaVacante, setEsNuevaVacante] = useState(true);
	const { t } = useTranslation(["Vacante"]);

	const [rules] = useState(asignarMensajeTranslation({ t, rules: rulesVacante, production: true }));

	//const [rules] = useState(asignarMensajeTranslation({ t, rules: rulesLogin, production: true }));

	const handleSuccessForm = (e) => {
		//setRequesting(true);
		//login({ correo: e.correo, contrasena: e.contrasena });
	};

	const onChange = (key) => {
		console.log(key);
	};

	const options = [
		{
			value: "Produccion",
		},
		{
			value: "Materiales",
		},
		{
			value: "Wall Street",
		},
	];

	console.log("Vacant", albums);

	const Tab1Vacante = () => {
		return (
			<CContainer style={{ backgroundColor: "" }}>
				<Form form={form} layout="vertical" ref={formAgregarVacanteRef} onFinish={handleSuccessForm} wrapperCol={{ span: 23 }}>
					<Row justify="left">
						<Col xs={24} sm={24} md={24} lg={12} xl={8} xxl={16}>
							<Form.Item name="titulo" required label={t("Vacante.lblTitulo")} tooltip={t("Vacante.ttTitulo")}>
								<Input placeholder={t("Login.phCorreo")} showCount maxLength={30} />
							</Form.Item>
						</Col>

						<Col xs={24} sm={24} md={24} lg={8} xl={6} xxl={6}>
							<Form.Item name="puesto" required label={t("Vacante.lblPuesto")} tooltip={t("Vacante.ttPuesto")}>
								<Select
									showSearch
									placeholder="Search to Select"
									optionFilterProp="children"
									filterOption={(input, option) => (option?.label ?? "").includes(input)}
									filterSort={(optionA, optionB) => (optionA?.label ?? "").toLowerCase().localeCompare((optionB?.label ?? "").toLowerCase())}
									options={[
										{
											value: "1",
											label: "Otro",
										},
										{
											value: "2",
											label: "Operador",
										},
									]}
								/>
							</Form.Item>
						</Col>

						<Col xs={24} sm={24} md={24} lg={8} xl={6} xxl={6}>
							<Form.Item name="puestoEspecifico" required label={t("Vacante.lblPuestoEspecifico")} tooltip={t("Vacante.ttPuestoEspecifico")}>
								<AutoComplete
									options={options}
									filterOption={(inputValue, option) => option.value.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
									placeholder="Customized clear icon"
									allowClear={{
										clearIcon: <CloseSquareFilled />,
									}}
								/>
							</Form.Item>
						</Col>
					</Row>
					<Row justify="left">
						<Col xs={24} sm={24} md={24} lg={8} xl={8} xxl={8}>
							<Form.Item name="descripcion" required label={t("Vacante.lblDescripcion")} tooltip={t("Vacante.ttDescripcion")}>
								<TextArea rows={6} placeholder="maxLength is 6" maxLength={200} />
							</Form.Item>
						</Col>
					</Row>
				</Form>
			</CContainer>
		);
	};

	return (
		<CContainer title={esNuevaVacante ? t("Vacante.lblAgregarVacante") : t("Vacante.lblEditarVacante")}>
			{!esNuevaVacante && (
				<Typography.Title level={3} style={{ margin: 0 }}>
					{t("Vacante.lblID")}
				</Typography.Title>
			)}

			{esNuevaVacante ? (
				<Row justify="center" style={{ marginTop: "5rem" }}>
					<Col xs={24} sm={24} md={24} lg={24} xl={12} xxl={12}>
						<Card title={t("Vacante.lblNuevaVacante")} size="default" type="inner" className={styles.c_shadow}>
							<Form form={form} layout="vertical" ref={formAgregarVacanteRef} onFinish={handleSuccessForm} wrapperCol={{ span: 23 }}>
								<Row justify="center">
									<Col xs={24} sm={24} md={24} lg={12} xl={22} xxl={24}>
										<Form.Item name="titulo" required label={t("Vacante.lblTitulo")} tooltip={t("Vacante.ttTitulo")} rules={rules.titulo}>
											<Input placeholder={t("Vacante.phTitulo")} showCount maxLength={60} />
										</Form.Item>
									</Col>
								</Row>

								<Row justify="center">
									<Col xs={24} sm={24} md={24} lg={10} xl={11} xxl={6}>
										<Form.Item name="puesto" required label={t("Vacante.lblPuesto")} tooltip={t("Vacante.ttPuesto")} rules={rules.puesto}>
											<Select
												showSearch
												placeholder={t("Vacante.phPuesto")}
												optionFilterProp="children"
												filterOption={(input, option) => (option?.label ?? "").includes(input)}
												filterSort={(optionA, optionB) => (optionA?.label ?? "").toLowerCase().localeCompare((optionB?.label ?? "").toLowerCase())}
												options={[
													{
														value: "1",
														label: "Otro",
													},
													{
														value: "2",
														label: "Operador",
													},
												]}
											/>
										</Form.Item>
									</Col>

									<Col xs={24} sm={24} md={24} lg={10} xl={11} xxl={6}>
										<Form.Item
											name="puestoEspecifico"
											required
											label={t("Vacante.lblPuestoEspecifico")}
											tooltip={t("Vacante.ttPuestoEspecifico")}
											rules={rules.puestoEspecifico}>
											<AutoComplete
												options={options}
												filterOption={(inputValue, option) => option.value.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
												placeholder={t("Vacante.phPuestoEspecifico")}
												allowClear={{
													clearIcon: <CloseSquareFilled />,
												}}
											/>
										</Form.Item>
									</Col>
								</Row>
								<Row justify="center" style={{ marginBottom: ".5rem" }}>
									<Col xs={24} sm={24} md={24} lg={8} xl={22} xxl={6}>
										<Alert showIcon message={t("Vacante.messages.infoEditarInfoMasAdelante")} type="info" />
									</Col>
								</Row>
								<Row justify="center">
									<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={6}>
										<CButton type="primary" text={t("Vacante.lblCrear")} size="large" block />
									</Col>
								</Row>
							</Form>
						</Card>
					</Col>
				</Row>
			) : (
				<Row justify="center">
					<Col xs={24} sm={24} md={24} lg={24} xl={24} xxl={24}>
						<Tabs
							onChange={onChange}
							type="card"
							items={[
								{
									label: t("Vacante.lblVacante"),
									key: "1",
									children: <Tab1Vacante />,
								},
								{
									label: t("Vacante.lblQueOfrecemos"),

									key: "2",
									children: "Tab 2",
									disabled: true,
								},
								{
									label: t("Vacante.lblQueRequiere"),
									key: "3",
									children: "Tab 3",
									disabled: true,
								},
								{
									label: t("Vacante.lblContratacion"),
									key: "4",
									children: "Tab 4",
									disabled: true,
								},
								{
									label: t("Vacante.btnFAQ"),
									key: "5",
									children: "Tab 5",
									disabled: true,
								},
							]}
						/>
					</Col>
				</Row>
			)}
		</CContainer>
	);
};

export default Vacante;
