import { CloseSquareFilled } from "@ant-design/icons";
import { Col, Form, Input, Row, Select, Tabs } from "antd";
import QueueAnim from "rc-queue-anim";
import { default as React, useEffect, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { useLoaderData } from "react-router-dom";
import CContainer from "../../Components/CContainer";

import TextArea from "antd/es/input/TextArea";
import { StatusCodes } from "http-status-codes";
import NotFound from "../../NotFound";
import { get, post } from "../../Utils/api";
import { API_ACTIONS_TYPES } from "../../Utils/utilConst";
import { asignarMensajeTranslation } from "../../Utils/utils";
import { obtenerCatalogo } from "../../Utils/utilsRequest";
import SkeletonVacanteEdit from "./SkeletonVacanteEdit";
import { rulesVacante } from "./rulesVacante";

// const initialValuesAgregarVacante = {
// 	titulo: "",
// 	puesto: "",
// 	puestoEspecifico: "",
// 	puestoOtro: "",
// 	puestoEspecificoOtro: "",
// };

const VacanteEdit = (props) => {
	console.log(props);
	const urlParams = useLoaderData();

	const { t } = useTranslation(["VacanteEdit", "Common"]);
	//const navigate = useNavigate();

	const formAgregarVacanteRef = useRef(null);
	const [formeEditarVacante] = Form.useForm();

	const puestoWatch = Form.useWatch("puesto", formeEditarVacante);
	const puestoOtroWatch = Form.useWatch("puesto_otro", formeEditarVacante);
	const puestoEspecificoWatch = Form.useWatch("puestoEspecifico", formeEditarVacante);
	const puestoEspecificoOtroWatch = Form.useWatch("puesto_especifico_otro", formeEditarVacante);

	const [sourcePuestos, setSourcePuestos] = useState([]);
	const [sourcePuestosEspecificos, setSourcePuestosEspecificos] = useState([]);
	const [vacanteValida, setVacanteValida] = useState(false);
	const [finalizaValidacionesIniciales, setFinalizaValidacionesIniciales] = useState(false);

	// const [respuestaAgregar, setRespuestaAgregar] = useState({
	// 	exitoso: false,
	// 	mensaje: "",
	// });

	const [rules] = useState(asignarMensajeTranslation({ t, rules: rulesVacante, production: false }));

	const handleSuccessFormNuevaVacante = async (e) => {
		// const json = {
		// 	titulo: e.titulo,
		// 	puesto: e.puesto,
		// 	puestoOtro: e.puestoOtro,
		// 	puestoEspecifico: e.puestoEspecifico,
		// 	puestoEspecificoOtro: e.puestoEspecificoOtro,
		// };
		// const response = await post({ url: "vacante", json });
		// console.log(response);
		// //const modalTitulo = t("VacanteEdit.lblAgregarVacante");
		// if (!response) {
		// 	showModal({
		// 		type: MODAL_TYPES.ERROR,
		// 		//title: modalTitulo,
		// 		content: t("Common:Common.messages.noPudimosProcesar"),
		// 	});
		// 	return;
		// }
		// if (response.status === StatusCodes.OK) {
		// 	setRespuestaAgregar({
		// 		exitoso: true,
		// 		mensaje: response.data?.detail,
		// 		idVacante: response.data?.idVacante,
		// 	});
		// 	return;
		// }
		// const errors = response?.errors ?? [];
		// let modalMensaje = "";
		// if (errors.length > 0) {
		// 	modalMensaje = getErrorMessages({ errors });
		// }
		// const modalType = response.status === StatusCodes.BAD_REQUEST ? MODAL_TYPES.WARNING : MODAL_TYPES.ERROR;
		//showModal({ type: modalType, title: modalTitulo, content: modalMensaje });
	};

	const vacantePerteneceAlUsuario = async (vacanteId) => {
		const json = {
			action: API_ACTIONS_TYPES.VACANTE_PERTENECE_USUARIO,
		};
		const url = `vacantes/${vacanteId}/actions`;
		const response = await post({ url, json });
		console.log(response);
		return response?.status === StatusCodes.OK;
	};

	const obtenerVacante = async (vacanteId) => {
		const url = `vacantes/${vacanteId}`;
		const response = await get({ url });
		console.log(response);
		if (response?.status !== StatusCodes.OK) return null;
		return response?.data;
	};

	const iniciarPrograma = async () => {
		const vacanteId = urlParams.vacanteId;

		const pertenece = await vacantePerteneceAlUsuario(vacanteId);
		if (!pertenece) {
			setFinalizaValidacionesIniciales(true);
			return;
		}

		const vacante = await obtenerVacante(vacanteId);
		if (!vacante) {
			setFinalizaValidacionesIniciales(true);
			return;
		}

		const { data: puestos } = await obtenerCatalogo({ catalogo: "puestos" });
		setSourcePuestos(puestos);

		const { data: puestosEspecificos } = await obtenerCatalogo({ catalogo: "puestosEspecificos" });
		setSourcePuestosEspecificos(puestosEspecificos);

		setVacanteValida(true);
		setFinalizaValidacionesIniciales(true);

		formeEditarVacante.setFieldsValue({
			titulo: vacante.titulo,
			puesto: vacante.puesto,
			puesto_otro: vacante.puesto_otro,
			puesto_especifico: vacante.puesto_especifico,
			puesto_especifico_otro: vacante.puesto_especifico_otro,
		});
	};

	useEffect(() => {
		iniciarPrograma();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	// const handleClickAgregar = () => {
	// 	setRespuestaAgregar({ exitoso: false, mensaje: "", respuestaAgregar: 0 });
	// 	//formAgregarVacanteEdit.resetFields();
	// };

	// const handleClickMisVcantes = () => {
	// 	navigate(URLS_PORTAL.MIS_VACANTES);
	// };

	const Tab1Vacante = () => {
		return (
			<CContainer>
				<Form
					form={formeEditarVacante}
					layout="vertical"
					ref={formAgregarVacanteRef}
					onFinish={handleSuccessFormNuevaVacante}
					wrapperCol={{ span: 23 }}>
					<Row justify="left">
						<Col xs={24} sm={24} md={24} lg={12} xl={10} xxl={10}>
							<Form.Item name="titulo" required label={t("VacanteEdit.lblTitulo")} tooltip={t("VacanteEdit.ttTitulo")}>
								<Input placeholder={t("VacanteEdit.phTitulo")} showCount maxLength={60} />
							</Form.Item>
						</Col>
						<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
							<Form.Item name="puesto" required label={t("VacanteEdit.lblPuesto")} tooltip={t("VacanteEdit.ttPuesto")}>
								<Select
									showSearch
									placeholder={t("VacanteEdit.phPuesto")}
									optionFilterProp="children"
									filterOption={(input, option) => (option?.label ?? "").includes(input)}
									filterSort={(optionA, optionB) => (optionA?.label ?? "").toLowerCase().localeCompare((optionB?.label ?? "").toLowerCase())}
									options={sourcePuestos}
									fieldNames={{ label: "descripcion", value: "sigla" }}
									onChange={() => formeEditarVacante.resetFields(["puesto_otro"])}
								/>
							</Form.Item>
						</Col>
						<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
							<Form.Item
								name="puesto_otro"
								label={t("VacanteEdit.lblOtro")}
								tooltip={t("VacanteEdit.ttPuesto")}
								rules={puestoWatch === "otro" ? rules.puestoOtro : []}>
								<Input
									maxLength={50}
									showCount
									disabled={puestoWatch !== "otro"}
									options={sourcePuestosEspecificos}
									fieldNames={{ label: "descripcion", value: "sigla" }}
									filterOption={(inputValue, option) => option.descripcion.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
									placeholder={t("VacanteEdit.phPuestoOtro")}
									allowClear={{
										clearIcon: <CloseSquareFilled />,
									}}
								/>
							</Form.Item>
						</Col>
					</Row>
					<Row justify="left">
						<Col xs={24} sm={24} md={24} lg={8} xl={10} xxl={10}>
							<Form.Item name="descripcion" required label={t("VacanteEdit.lblDescripcion")} tooltip={t("VacanteEdit.ttDescripcion")}>
								<TextArea rows={6} placeholder={t("VacanteEdit.phDescripcion")} maxLength={200} />
							</Form.Item>
						</Col>
						<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
							<Form.Item
								name="puesto_especifico"
								required
								label={t("VacanteEdit.lblPuestoEspecifico")}
								tooltip={t("VacanteEdit.ttPuestoEspecifico")}
								rules={rules.puestoEspecifico}>
								<Select
									showSearch
									placeholder={t("VacanteEdit.phPuestoEspecifico")}
									optionFilterProp="children"
									filterOption={(input, option) => (option?.label ?? "").includes(input)}
									filterSort={(optionA, optionB) => (optionA?.label ?? "").toLowerCase().localeCompare((optionB?.label ?? "").toLowerCase())}
									options={sourcePuestosEspecificos}
									fieldNames={{ label: "descripcion", value: "sigla" }}
									onChange={() => formeEditarVacante.resetFields(["puesto_especifico_otro"])}
								/>
							</Form.Item>
						</Col>
						<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
							<Form.Item
								name="puesto_especifico_otro"
								def
								label={t("VacanteEdit.lblOtro")}
								tooltip={t("VacanteEdit.ttPuestoEspecifico")}
								rules={puestoEspecificoWatch === "otro" ? rules.puestoEspecificoOtro : []}>
								<Input
									maxLength={50}
									showCount
									disabled={puestoEspecificoWatch !== "otro"}
									options={sourcePuestosEspecificos}
									fieldNames={{ label: "descripcion", value: "sigla" }}
									filterOption={(inputValue, option) => option.descripcion.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
									placeholder={t("VacanteEdit.phPuestoEspecifico")}
									allowClear={{
										clearIcon: <CloseSquareFilled />,
									}}
								/>
							</Form.Item>
						</Col>
					</Row>
				</Form>
			</CContainer>
		);
	};

	const Tab2QueOfrecemos = () => {
		return (
			<CContainer style={{ backgroundColor: "" }}>
				<Form
					form={formeEditarVacante}
					layout="vertical"
					ref={formAgregarVacanteRef}
					onFinish={handleSuccessFormNuevaVacante}
					wrapperCol={{ span: 23 }}>
					<Row justify="left">
						<Col xs={24} sm={24} md={24} lg={12} xl={10} xxl={10}>
							<Form.Item name="titulo" required label={t("VacanteEdit.lblTitulo")} tooltip={t("VacanteEdit.ttTitulo")}>
								<Input placeholder={t("Login.phCorreo")} showCount maxLength={60} />
							</Form.Item>
						</Col>
						<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
							<Form.Item name="puesto" required label={t("VacanteEdit.lblPuesto")} tooltip={t("VacanteEdit.ttPuesto")}>
								<Select
									showSearch
									placeholder={t("Vacante.phPuesto")}
									optionFilterProp="children"
									filterOption={(input, option) => (option?.label ?? "").includes(input)}
									filterSort={(optionA, optionB) => (optionA?.label ?? "").toLowerCase().localeCompare((optionB?.label ?? "").toLowerCase())}
									options={sourcePuestos}
									fieldNames={{ label: "descripcion", value: "sigla" }}
									onChange={() => formeEditarVacante.resetFields(["puesto_otro"])}
								/>
							</Form.Item>
						</Col>
						<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
							<Form.Item
								name="puesto_otro"
								label={t("VacanteEdit.lblOtro")}
								tooltip={t("VacanteEdit.ttPuesto")}
								rules={puestoWatch === "otro" ? rules.puestoOtro : []}>
								<Input
									maxLength={50}
									showCount
									disabled={puestoWatch !== "otro"}
									options={sourcePuestosEspecificos}
									fieldNames={{ label: "descripcion", value: "sigla" }}
									filterOption={(inputValue, option) => option.descripcion.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
									placeholder={t("VacanteEdit.phPuestoOtro")}
									allowClear={{
										clearIcon: <CloseSquareFilled />,
									}}
								/>
							</Form.Item>
						</Col>
					</Row>
					<Row justify="left">
						<Col xs={24} sm={24} md={24} lg={8} xl={10} xxl={10}>
							<Form.Item name="descripcion" required label={t("VacanteEdit.lblDescripcion")} tooltip={t("VacanteEdit.ttDescripcion")}>
								<TextArea rows={6} placeholder="maxLength is 6" maxLength={200} />
							</Form.Item>
						</Col>
						<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
							<Form.Item
								name="puesto_especifico"
								required
								label={t("VacanteEdit.lblPuestoEspecifico")}
								tooltip={t("VacanteEdit.ttPuestoEspecifico")}
								rules={rules.puestoEspecifico}>
								<Select
									showSearch
									placeholder={t("Vacante.phPuestoEspecifico")}
									optionFilterProp="children"
									filterOption={(input, option) => (option?.label ?? "").includes(input)}
									filterSort={(optionA, optionB) => (optionA?.label ?? "").toLowerCase().localeCompare((optionB?.label ?? "").toLowerCase())}
									options={sourcePuestosEspecificos}
									fieldNames={{ label: "descripcion", value: "sigla" }}
									onChange={() => formeEditarVacante.resetFields(["puesto_especifico_otro"])}
								/>
							</Form.Item>
						</Col>
						<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
							<Form.Item
								name="puesto_especifico_otro"
								def
								label={t("VacanteEdit.lblOtro")}
								tooltip={t("VacanteEdit.ttPuestoEspecifico")}
								rules={puestoEspecificoWatch === "otro" ? rules.puestoEspecificoOtro : []}>
								<Input
									maxLength={50}
									showCount
									disabled={puestoEspecificoWatch !== "otro"}
									options={sourcePuestosEspecificos}
									fieldNames={{ label: "descripcion", value: "sigla" }}
									filterOption={(inputValue, option) => option.descripcion.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
									placeholder={t("VacanteEdit.phPuestoEspecifico")}
									allowClear={{
										clearIcon: <CloseSquareFilled />,
									}}
								/>
							</Form.Item>
						</Col>
					</Row>
				</Form>
			</CContainer>
		);
	};
	if (!finalizaValidacionesIniciales) return <SkeletonVacanteEdit active />;

	if (!vacanteValida) return <NotFound />;

	return (
		<CContainer title={t("VacanteEdit.lblEditarVacante")}>
			<Row justify="center" style={{ marginTop: "2rem" }}>
				<Col xs={24} sm={24} md={24} lg={24} xl={24} xxl={24}>
					<QueueAnim type="scale">
						<Tabs
							//	onChange={onChange}
							type="card"
							items={[
								{
									label: t("VacanteEdit.lblVacante"),
									key: "1",
									children: <Tab1Vacante />,
								},
								{
									label: t("VacanteEdit.lblQueOfrecemos"),
									key: "2",
									//disabled: true,
									children: <Tab2QueOfrecemos />,
								},
								{
									label: t("VacanteEdit.lblQueRequiere"),
									key: "3",
									children: "Tab 3",
									disabled: true,
								},
								{
									label: t("VacanteEdit.lblContratacion"),
									key: "4",
									children: "Tab 4",
									disabled: true,
								},
								{
									label: t("VacanteEdit.btnFAQ"),
									key: "5",
									children: "Tab 5",
									disabled: true,
								},
							]}
						/>
					</QueueAnim>
				</Col>
			</Row>
		</CContainer>
	);
};

export default VacanteEdit;
