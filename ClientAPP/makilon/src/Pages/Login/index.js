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


const Login = (props) => {
	console.log(styles)
	const formRef = useRef(null);

	const [form] = Form.useForm();
	const nameValue = Form.useWatch('usuario', form);

	const usuarioRules = [
        {
          required: true,
          message: 'Ingresa usuario',
        },
      ]

	  const contrasenaRules = [
        {
          required: true,
          message: 'Ingresa contrasena',
        },
      ]  

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
      
      <Form.Item label="Usuario"  name="usuario" required tooltip="This is a required field"  rules={usuarioRules}>
        <Input placeholder="input placeholder"  size="large" />
      </Form.Item>

      <Form.Item
        label="Contraseña"
		name="contrasena" 
		rules={contrasenaRules}
        tooltip={{ title: 'Tooltip with customize icon', icon: <InfoCircleOutlined /> }}
      >
        <Input.Password  size="large" placeholder="input placeholder"          iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
/>
      </Form.Item>


	  <Row justify="center" gutter={16}>
	  <Col span={24} >

        <Button type="primary"  size="large" htmlType="submit" block >Ingresar</Button>

		</Col>

		<Col >


<Button type="link">¿Olvidaste tu contraseña?</Button>

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
