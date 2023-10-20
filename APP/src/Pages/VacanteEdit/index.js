import { CloseSquareFilled } from "@ant-design/icons";
import { Col, Form, Input, Result, Row, Select, Tooltip } from "antd";
import Alert from "antd/es/alert/Alert";
import Card from "antd/es/card/Card";
import { StatusCodes } from "http-status-codes";
import QueueAnim from "rc-queue-anim";
import { default as React, useEffect, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { useLoaderData, useNavigate } from "react-router-dom";
import styles from "../../CSS/common.module.css";
import CButton from "../../Components/CButton";
import CContainer from "../../Components/CContainer";
import { post } from "../../Utils/api";
import { MODAL_TYPES } from "../../Utils/utilConst";
import { URLS_PORTAL } from "../../Utils/utilUrl";
import { Radio, Tabs } from "antd";

import {
  asignarMensajeTranslation,
  getErrorMessages,
  showModal,
} from "../../Utils/utils";
import { obtenerCatalogo } from "../../Utils/utilsRequest";
import { rulesVacante } from "./rulesVacante";

const initialValuesAgregarVacante = {
  titulo: "",
  puesto: "",
  puestoEspecifico: "",
  puestoOtro: "",
  puestoEspecificoOtro: "",
};

const VacanteEdit = (props) => {
  console.log(props);
  const albums = useLoaderData();
  console.log(albums);

  const { t } = useTranslation(["VacanteEdit", "Common"]);
  const navigate = useNavigate();

  const formAgregarVacanteRef = useRef(null);
  const [formAgregarVacante] = Form.useForm();

  const puestoWatch = Form.useWatch("puesto", formAgregarVacante);
  const puestoOtroWatch = Form.useWatch("puestoOtro", formAgregarVacante);
  const puestoEspecificoWatch = Form.useWatch(
    "puestoEspecifico",
    formAgregarVacante,
  );
  const puestoEspecificoOtroWatch = Form.useWatch(
    "puestoEspecificoOtro",
    formAgregarVacante,
  );

  const [sourcePuestos, setSourcePuestos] = useState([]);
  const [sourcePuestosEspecificos, setSourcePuestosEspecificos] = useState([]);

  const [respuestaAgregar, setRespuestaAgregar] = useState({
    exitoso: false,
    mensaje: "",
  });

  const [rules] = useState(
    asignarMensajeTranslation({ t, rules: rulesVacante, production: false }),
  );

  const handleSuccessFormNuevaVacante = async (e) => {
    const json = {
      titulo: e.titulo,
      puesto: e.puesto,
      puestoOtro: e.puestoOtro,
      puestoEspecifico: e.puestoEspecifico,
      puestoEspecificoOtro: e.puestoEspecificoOtro,
    };

    const response = await post({ url: "vacante", json });

    console.log(response);

    const modalTitulo = t("Vacante.lblAgregarVacante");

    if (!response) {
      showModal({
        type: MODAL_TYPES.ERROR,
        title: modalTitulo,
        content: t("Common:Common.messages.noPudimosProcesar"),
      });
      return;
    }

    if (response.status === StatusCodes.OK) {
      setRespuestaAgregar({
        exitoso: true,
        mensaje: response.data?.detail,
        idVacante: response.data?.idVacante,
      });
      return;
    }

    const errors = response?.errors ?? [];
    let modalMensaje = "";

    if (errors.length > 0) {
      modalMensaje = getErrorMessages({ errors });
    }

    const modalType =
      response.status === StatusCodes.BAD_REQUEST
        ? MODAL_TYPES.WARNING
        : MODAL_TYPES.ERROR;
    showModal({ type: modalType, title: modalTitulo, content: modalMensaje });
  };

  const iniciarPrograma = async () => {
    const { data: puestos } = await obtenerCatalogo({ catalogo: "puestos" });
    setSourcePuestos(puestos);

    const { data: puestosEspecificos } = await obtenerCatalogo({
      catalogo: "puestosEspecificos",
    });
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
    <CContainer title={t("VacanteEdit.lblAgregarVacante")}>
      <Row justify="center" style={{ marginTop: "5rem" }}>
        <Col xs={24} sm={24} md={17} lg={16} xl={14} xxl={14}>
          <QueueAnim type="scale">
            <Tabs
              defaultActiveKey="1"
              type="card"
              //size={size}
              items={new Array(3).fill(null).map((_, i) => {
                const id = String(i + 1);
                return {
                  label: `Card Tab ${id}`,
                  key: id,
                  children: `Content of card tab ${id}`,
                };
              })}
            />
          </QueueAnim>
        </Col>
      </Row>
    </CContainer>
  );
};

export default VacanteEdit;
