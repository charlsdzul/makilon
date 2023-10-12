import { CloseSquareFilled } from "@ant-design/icons";
import { Col, Form, Input, Result, Row, Select, Tooltip } from "antd";
import Alert from "antd/es/alert/Alert";
import Card from "antd/es/card/Card";
import { StatusCodes } from "http-status-codes";
import QueueAnim from "rc-queue-anim";
import { default as React, useEffect, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { useNavigate } from "react-router-dom";
import styles from "../../CSS/common.module.css";
import CButton from "../../Components/CButton";
import CContainer from "../../Components/CContainer";
import api from "../../Utils/api";
import { MODAL_TYPES } from "../../Utils/utilConst";
import { URLS_PORTAL } from "../../Utils/utilUrl";
import { asignarMensajeTranslation, getErrorMessages, showModal } from "../../Utils/utils";
import { obtenerCatalogo } from "../../Utils/utilsRequest";
import { rulesVacante } from "./rulesVacante";

const initialValuesAgregarVacante = {
	titulo: "",
	puesto: "",
	puestoEspecifico: "",
	puestoOtro: "",
	puestoEspecificoOtro: "",
};

const Vacante = (props) => {
	const { t } = useTranslation(["Vacante"]);
	const navigate = useNavigate();

	const formAgregarVacanteRef = useRef(null);
	const [formAgregarVacante] = Form.useForm();

	const puestoWatch = Form.useWatch("puesto", formAgregarVacante);
	const puestoOtroWatch = Form.useWatch("puestoOtro", formAgregarVacante);
	const puestoEspecificoWatch = Form.useWatch("puestoEspecifico", formAgregarVacante);
	const puestoEspecificoOtroWatch = Form.useWatch("puestoEspecificoOtro", formAgregarVacante);

	const [sourcePuestos, setSourcePuestos] = useState([]);
	const [sourcePuestosEspecificos, setSourcePuestosEspecificos] = useState([]);

	const [respuestaAgregar, setRespuestaAgregar] = useState({ exitoso: false, mensaje: "" });

	const [rules] = useState(asignarMensajeTranslation({ t, rules: rulesVacante, production: true }));

	const handleSuccessFormNuevaVacante = async (e) => {
		const json = {
			titulo: e.titulo,
			puesto: e.puesto,
			puestoOtro: e.puestoOtro,
			puestoEspecifico: e.puestoEspecifico,
			puestoEspecificoOtro: e.puestoEspecificoOtro,
		};

		const response = await api
			.post({ url: "vacante", json })
			.then((response) => response.data)
			.catch((error) => error.response);
		console.log(response);
		if (response.status === 200) {
			setRespuestaAgregar({ exitoso: true, mensaje: response.data?.detail, idVacante: response.data?.idVacante });
			return;
		}

		const errors = response?.data?.errors ?? [];
		const modalTitulo = t("Vacante.lblAgregarVacante");
		let modalMensaje = "";

		if (errors.length > 0) {
			modalMensaje = getErrorMessages({ errors });
		}

		const modalType = response.status === StatusCodes.BAD_REQUEST ? MODAL_TYPES.WARNING : MODAL_TYPES.ERROR;
		showModal({ type: modalType, title: modalTitulo, content: modalMensaje });
	};

	const iniciarPrograma = async () => {
		const { data: puestos } = await obtenerCatalogo({ catalogo: "puestos" });
		setSourcePuestos(puestos);

		const { data: puestosEspecificos } = await obtenerCatalogo({ catalogo: "puestosEspecificos" });
		setSourcePuestosEspecificos(puestosEspecificos);
	};

	useEffect(() => {
		iniciarPrograma();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	const handleClickAgregar = () => {
		setRespuestaAgregar({ exitoso: false, mensaje: "", respuestaAgregar: 0 });
		formAgregarVacante.resetFields();
	};

	const handleClickMisVcantes = () => {
		navigate(URLS_PORTAL.MIS_VACANTES);
	};

	return (
		<CContainer title={t("Vacante.lblAgregarVacante")}>
			{respuestaAgregar.exitoso && (
				<Row justify="center" style={{ marginTop: "5rem" }}>
					<Col xs={24} sm={24} md={17} lg={16} xl={14} xxl={14}>
						<QueueAnim type="scale">
							<div key="Result">
								<Result
									status="success"
									title={respuestaAgregar.mensaje}
									subTitle={t("Vacante.lblIDVacante", { id: respuestaAgregar.idVacante })}
									extra={[
										<CButton type="primary" text={t("Vacante.lblAcompletarVacante")} />,
										<CButton type="default" onClick={handleClickAgregar} text={t("Vacante.lblAgregarOtraVacante")} />,
										<CButton type="default" onClick={handleClickMisVcantes} text={t("Vacante.lblIrMisVacante")} />,
									]}
								/>
							</div>
						</QueueAnim>
					</Col>
				</Row>
			)}
			{!respuestaAgregar.exitoso && (
				<Row justify="center" style={{ marginTop: "4rem" }}>
					<Col xs={24} sm={24} md={17} lg={16} xl={14} xxl={14}>
						<QueueAnim type="scale">
							<div key="Card">
								<Card title={t("Vacante.lblNuevaVacante")} size="default" type="inner" className={styles.c_shadow}>
									<Form
										form={formAgregarVacante}
										layout="vertical"
										initialValues={initialValuesAgregarVacante}
										ref={formAgregarVacanteRef}
										onFinish={handleSuccessFormNuevaVacante}
										wrapperCol={{ span: 23 }}>
										<Row justify="center">
											<Col xs={24} sm={24} md={22} lg={22} xl={22} xxl={22}>
												<Form.Item name="titulo" required label={t("Vacante.lblTitulo")} tooltip={t("Vacante.ttTitulo")} rules={rules.titulo}>
													<Input placeholder={t("Vacante.phTitulo")} showCount maxLength={60} />
												</Form.Item>
											</Col>
										</Row>

										<Row justify="center">
											<Col xs={24} sm={24} md={11} lg={11} xl={11} xxl={11}>
												<Form.Item name="puesto" required label={t("Vacante.lblPuesto")} tooltip={t("Vacante.ttPuesto")} rules={rules.puesto}>
													<Select
														showSearch
														placeholder={t("Vacante.phPuesto")}
														optionFilterProp="children"
														filterOption={(input, option) => (option?.label ?? "").includes(input)}
														filterSort={(optionA, optionB) =>
															(optionA?.label ?? "").toLowerCase().localeCompare((optionB?.label ?? "").toLowerCase())
														}
														options={sourcePuestos}
														fieldNames={{ label: "descripcion", value: "sigla" }}
														onChange={() => formAgregarVacante.resetFields(["puestoOtro"])}
													/>
												</Form.Item>
											</Col>

											<Col xs={24} sm={24} md={11} lg={11} xl={11} xxl={11}>
												<Tooltip title={t("Vacante.tt2PuestoOtro")} color="#108ee9" open={puestoWatch === "otro" && !puestoOtroWatch}>
													<Form.Item
														name="puestoOtro"
														label={t("Vacante.lblOtro")}
														tooltip={t("Vacante.ttPuesto")}
														rules={puestoWatch === "otro" ? rules.puestoOtro : []}>
														<Input
															maxLength={50}
															showCount
															disabled={puestoWatch !== "otro"}
															options={sourcePuestosEspecificos}
															fieldNames={{ label: "descripcion", value: "sigla" }}
															filterOption={(inputValue, option) => option.descripcion.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
															placeholder={t("Vacante.phPuestoOtro")}
															allowClear={{
																clearIcon: <CloseSquareFilled />,
															}}
														/>
													</Form.Item>
												</Tooltip>
											</Col>
										</Row>

										<Row justify="center">
											<Col xs={24} sm={24} md={11} lg={11} xl={11} xxl={11}>
												<Form.Item
													name="puestoEspecifico"
													required
													label={t("Vacante.lblPuestoEspecifico")}
													tooltip={t("Vacante.ttPuestoEspecifico")}
													rules={rules.puestoEspecifico}>
													<Select
														showSearch
														placeholder={t("Vacante.phPuestoEspecifico")}
														optionFilterProp="children"
														filterOption={(input, option) => (option?.label ?? "").includes(input)}
														filterSort={(optionA, optionB) =>
															(optionA?.label ?? "").toLowerCase().localeCompare((optionB?.label ?? "").toLowerCase())
														}
														options={sourcePuestosEspecificos}
														fieldNames={{ label: "descripcion", value: "sigla" }}
														onChange={() => formAgregarVacante.resetFields(["puestoEspecificoOtro"])}
													/>
												</Form.Item>
											</Col>

											<Col xs={24} sm={24} md={11} lg={11} xl={11} xxl={11}>
												<Tooltip
													title={t("Vacante.tt2PuestoEspecificoOtro")}
													color="#108ee9"
													open={puestoEspecificoWatch === "otro" && !puestoEspecificoOtroWatch}>
													<Form.Item
														name="puestoEspecificoOtro"
														def
														label={t("Vacante.lblOtro")}
														tooltip={t("Vacante.ttPuestoEspecifico")}
														rules={puestoEspecificoWatch === "otro" ? rules.puestoEspecificoOtro : []}>
														<Input
															maxLength={50}
															showCount
															disabled={puestoEspecificoWatch !== "otro"}
															options={sourcePuestosEspecificos}
															fieldNames={{ label: "descripcion", value: "sigla" }}
															filterOption={(inputValue, option) => option.descripcion.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
															placeholder={t("Vacante.phPuestoEspecifico")}
															allowClear={{
																clearIcon: <CloseSquareFilled />,
															}}
														/>
													</Form.Item>
												</Tooltip>
											</Col>
										</Row>

										<Row justify="center" style={{ marginBottom: "1rem" }}>
											<Col xs={24} sm={24} md={22} lg={22} xl={22} xxl={22}>
												<Alert showIcon message={t("Vacante.messages.infoEditarInfoMasAdelante")} type="info" />
											</Col>
										</Row>

										<Row justify="center">
											<Col xs={24} sm={24} md={7} lg={7} xl={7} xxl={7}>
												<CButton type="primary" htmlType="submit" text={t("Vacante.lblCrear")} size="large" block />
											</Col>
										</Row>
									</Form>
								</Card>
							</div>
						</QueueAnim>
					</Col>
				</Row>
			)}
		</CContainer>
	);
};

export default Vacante;
