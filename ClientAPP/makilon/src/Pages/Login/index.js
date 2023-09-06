import React,{useState,useRef} from "react";
import api from "../../Utils/api";
import AuthService from "../../Services/auth.services";
import CContainer from "../../Components/CContainer";
import { Button, Form, Input, Radio } from 'antd';
import { InfoCircleOutlined } from '@ant-design/icons';
import { Col, Divider, Row } from 'antd';
import { EyeInvisibleOutlined, EyeTwoTone } from '@ant-design/icons';


const cardStyle = { 
    //width: "360px", 
    //height: "192px", 
    borderRadius: "5px", 
    //marginRight: "24px", 
	backgroundColor:"white",
    boxShadow: "rgba(208, 216, 243, 0.6) 0 4px 8px 5px" ,
	padding:"1rem"
 }

const Login = (props) => {
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

	  

	// const [requiredMark, setRequiredMarkType] = useState('optional');
	const onRequiredTypeChange = ( e) => {
		console.log(e)
		//setRequiredMarkType(requiredMarkValue);
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
		
		<CContainer>

<Row justify="center">
      <Col span={5}>

		<div style={cardStyle}>


		<Row justify="center">

		<Form
		
      form={form}
      layout="vertical"
	  ref={formRef}
    //   initialValues={{ requiredMarkValue: requiredMark }}
      onValuesChange={onRequiredTypeChange}
	  onFinish={onFinish}
	  onFinishFailed={onFinishFailed}


    //   requiredMark={requiredMark}
    >
      
      <Form.Item label="Usuario"  name="usuario" required tooltip="This is a required field"  rules={usuarioRules}>
        <Input placeholder="input placeholder" />
      </Form.Item>
      <Form.Item
        label="Contraseña"
		name="contrasena" 
		rules={contrasenaRules}
        tooltip={{ title: 'Tooltip with customize icon', icon: <InfoCircleOutlined /> }}
      >
        <Input.Password placeholder="input placeholder"          iconRender={(visible) => (visible ? <EyeTwoTone /> : <EyeInvisibleOutlined />)}
/>
      </Form.Item>


      <Form.Item>
	  <Row justify="center" gutter={16}>
	  <Col span={24} >

        <Button type="primary" htmlType="submit" block >Iniciar sesion</Button>

		</Col>

		<Col >


<Button type="link">¿Olvidaste tu contraseña?</Button>


</Col>

		</Row>
      </Form.Item>
    </Form>		

	</Row>



	<Row justify="center" gutter={16}>
	<Button type="primary"  style={{ background: "#16ff3f", borderColor: "yellow" }}>Crear cuenta</Button>
	</Row>



	</div>



	</Col>
    </Row>


	

		</CContainer>
			
		
	);
};

export default Login;
