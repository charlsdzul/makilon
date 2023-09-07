import React,{useState,useRef} from "react";
import api from "../../Utils/api";
import AuthService from "../../Services/auth.services";
import CContainer from "../../Components/CContainer";
import { Button, Form, Input, Radio } from 'antd';
import { InfoCircleOutlined } from '@ant-design/icons';
import { Col, Divider, Row } from 'antd';
import { EyeInvisibleOutlined, EyeTwoTone } from '@ant-design/icons';
import styles from '../../CSS/common.module.css'
import stylesLogin from '../../CSS/login.module.css'
import { Card, Space } from 'antd';
import CButton from "../../Components/CButton";
import { rulesLogin } from "./rulesLogin";
import { useTranslation } from "react-i18next";


const Login = (props) => {
	const { t } = useTranslation(["charls"]);

	console.log(styles)
	const formRef = useRef(null);

	const [form] = Form.useForm();
	const nameValue = Form.useWatch('usuario', form);

	console.log(t("demo"))
	

	const onRequiredTypeChange = ( e) => {
		console.log(e)
	  };

	const iniciarSesion = async ({usuario, contrasena}) => {
		const response = await AuthService.login(usuario, contrasena);
		console.log(response);

		if (response.status===200) {
			localStorage.setItem("token", response.data.accessToken);
		}else{
			//alert("hay error")
		}
	};

	const onClick = (e) =>{
console.log(e)
	}

	const onFinish = (e) =>{
		console.log("onFinish")
		console.log(e)

		iniciarSesion({usuario:e.usuario, contrasena:e.contrasena})
			}
		
			const onFinishFailed = (e) =>{
				console.log("onFinishFailed")
				console.log(e)
					}
				
	console.log("render")


	return (
		
		<CContainer className={stylesLogin.c_container}>

<Row justify="center">
      <Col  xs={16} sm={12}  md={10}  lg={8}  xl={6}  xxl={4}>

	  <Card title="Iniciar Sesion" size="default" type="inner" className={styles.c_shadow}>
		<Row justify="center">

		<Form
		
      form={form}
      layout="vertical"
	  ref={formRef}
      onValuesChange={onRequiredTypeChange}
	  onFinish={onFinish}
	  onFinishFailed={onFinishFailed}


    >
      
      <Form.Item label="Usuario"  name="usuario" required tooltip="This is a required field"  rules={rulesLogin.usuario}>
        <Input placeholder="input placeholder"  size="large" />
      </Form.Item>

      <Form.Item
        label="Contraseña"
		name="contrasena" 
		rules={rulesLogin.contrasena}
        tooltip={{ title: 'Tooltip with customize icon', icon: <InfoCircleOutlined /> }}
      >
        <Input.Password  size="large" placeholder="input placeholder"          iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
/>
      </Form.Item>


	  <Row justify="center" gutter={16}>
	  <Col span={24} >

		<CButton htmlType="submit" size="large"    block text="Ingresar" />

		</Col>

		<Col >



<CButton text="¿Olvidaste tu contraseña?" type="link"  />


</Col>

		</Row>
    </Form>		

	</Row>



	<Row justify="center" gutter={16}>
	<Button type="primary"  size="large"  style={{ background: "#16ff3f", borderColor: "yellow" }}>Crear cuenta</Button>
	</Row>



	

    </Card>


	</Col>
    </Row>

		</CContainer>
			
		
	);
};

export default Login;
