import { CloseSquareFilled } from "@ant-design/icons";
import { AutoComplete, Col, Form, Input, Row, Select, Tabs } from "antd";
import TextArea from "antd/es/input/TextArea";
import { default as React, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { useLoaderData } from "react-router-dom";
import stylesLogin from "../../CSS/login.module.css";
import CContainer from "../../Components/CContainer";

const { Option } = Select;

const Vacante = (props) => {
	const formRef = useRef(null);
	const [form] = Form.useForm();
	const albums = useLoaderData();
	const [anotherOptions, setAnotherOptions] = useState([]);

	const { t } = useTranslation(["Vacante"]);
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
				<Form form={form} layout="horizontal" ref={formRef} onFinish={handleSuccessForm} labelWrap labelCol={{ span: 7 }} wrapperCol={{ span: 17 }}>
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
		<CContainer>
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
		</CContainer>
	);
};

export default Vacante;
