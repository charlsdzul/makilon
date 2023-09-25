import { ConfigProvider, Layout, Menu,Icon, Drawer, Button } from "antd";
import React,{ useEffect, useRef, useState } from "react";
import { RouterProvider } from "react-router-dom";
import styles from "./CSS/common.module.css";
//import "antd/dist/antd.css";
import { useTranslation } from "react-i18next";

import "./LayoutApp.css"
import { UploadOutlined,  VideoCameraOutlined } from '@ant-design/icons';

import { router } from "./Routes";
import AuthContext from "./Utils/AuthContext";

import {
	AppstoreOutlined,
	ContainerOutlined,FormOutlined,
	DesktopOutlined,
	LogoutOutlined,AreaChartOutlined,
	MailOutlined,
	MenuFoldOutlined,
	MenuUnfoldOutlined,
	PieChartOutlined,
	SettingOutlined,
	UserOutlined,UnorderedListOutlined,HomeOutlined
  } from '@ant-design/icons'

const SubMenu = Menu.SubMenu;
const MenuItemGroup = Menu.ItemGroup;
const { Header, Content, Footer, Sider } = Layout;

function getItem(label, key, icon, children, type) {
	return {
	  key,
	  icon,
	  children,
	  label,
	  type,
	};
  }


const LayoutApp = () => {
	console.log("LayoutApp");

	const [collapsed, setCollapsed] = useState(false);
	const toggleCollapsed = () => {
	  setCollapsed(!collapsed);
	};

	const [visible, setVisible] = useState(false)
	const { t } = useTranslation(["LayoutApp"]);

	const [opcionesMenu,setOpcionesMenu] = useState(
	
)

	const RightMenu =()=> {
		
		  return (
			// <Menu mode="horizontal">
			//   <Menu.Item key="mail">
			// 	<a href="">Signin</a>
			//   </Menu.Item>
			//   <Menu.Item key="app">
			// 	<a href="">Signup</a>
			//   </Menu.Item>
			// </Menu>

			<>
			{/* <Button
			type="primary"
			onClick={toggleCollapsed}
			style={{
			  marginBottom: 16,
			}}
		  />
		   */}
		         <Sider trigger={null} collapsible collapsed={collapsed}>

			<Menu
			className="rightMenu"
			defaultSelectedKeys={['1']}
			//defaultOpenKeys={['sub1']}
			mode="inline"
			///theme="dark"
			inlineCollapsed={false}
			items={opcionesMenu}
		  	/>
      </Sider>


		  </>
			);
		
	  }
	  
	

	const TopMenu = () => {
		
		  return (
		//  <Menu mode="horizontal">
		// 	 <Menu.Item key="mail">
		// 		<a href="">Home</a>
		// 	  </Menu.Item>
		// 	  {/* <SubMenu title={<span>Blogs</span>}> */}
		// 		{/* <MenuItemGroup title="Item 1"> */}
		// 		  <Menu.Item key="setting:1">Crear Cuenta</Menu.Item>
		// 		  <Menu.Item key="setting:2">Iniciar Sesión</Menu.Item>
		// 		{/* </MenuItemGroup>
		// 		<MenuItemGroup title="Item 2">
		// 		  <Menu.Item key="setting:3">Option 3</Menu.Item>
		// 		  <Menu.Item key="setting:4">Option 4</Menu.Item>
		// 		</MenuItemGroup> */}
		// 	  {/* </SubMenu> */}
			 
		// 	</Menu>

<Menu
theme="dark"
mode="horizontal"
defaultSelectedKeys={['2']}
items={new Array(3).fill(null).map((_, index) => {
  const key = index + 1;
  return {
	key,
	label: `nav ${key}`,
  };
})}
/>
		  );
		
	  }


	  const iniciarPrograma = () =>{

		setOpcionesMenu(	[
			getItem(t("LayoutApp.lblMenuPortal"), '1', <HomeOutlined />),
			getItem(t("LayoutApp.lblMenuDashboard"), '2',<AreaChartOutlined />),
			getItem(t("LayoutApp.lblMenuAgregarVacante"), '3', <FormOutlined />),
			getItem(t("LayoutApp.lblMenuMisVacantes"), '4', <UnorderedListOutlined />),
			getItem(t("LayoutApp.lblMenuMiCuenta"), '5', <UserOutlined />),
			getItem(t("LayoutApp.lblMenuConfiguraciones"), 'sub1', <SettingOutlined />, [
			  getItem('Option 5', '5'),
			  getItem('Option 6', '6'),
			  getItem('Option 7', '7'),
			  getItem('Option 8', '8'),
			]),	
			getItem(t("LayoutApp.lblMenuCerrarSesion"), '6',<LogoutOutlined />),
			
		  ])
	  }

	  useEffect(()=> iniciarPrograma(),[])

	return (
		<AuthContext.Consumer>
			{({ auth }) => (
				<Layout className={styles.c_layoutapp}>

					<Sider
						breakpoint="lg"
						collapsedWidth="0"
						onBreakpoint={(broken) => {
						console.log(broken);
						}}
						onCollapse={(collapsed, type) => {
						console.log(collapsed, type);
						}}
					>
						<Menu
						theme="dark"
						mode="inline"
						defaultSelectedKeys={['3']}
						items ={opcionesMenu}
						subMenuOpenDelay={5}
					
						/>
					</Sider>

	  				<Layout>
							
	  <Header className="menuBar" style={{ display: "flex", alignItems: "center" }}>
 
          <div className="logo">
            <a href="">logo</a>
          </div>
		  
          <div className="menuCon">
            <div className="leftMenu">
              <TopMenu />
            </div>       
            <Button className="barsMenu" type="primary" onClick={()=>setVisible(true)}>
              <span className="barsBtn"></span>
            </Button>


            <Drawer
              title="Basic Drawer"
              placement="right"
              closable={false}
              onClose={()=>setVisible(false)}
              visible={visible}
            />
           
			</div>
    
		</Header>	

			<Content>
					<div
							// className="site-layout-content"
							style={{
								background: "camel",
							}}>
							<RouterProvider router={router} />
						</div>
					</Content>
						
				

					</Layout>


				

				
						

             

					
					

					{/* <Footer
        style={{
          textAlign: 'center',
        }}
      >
        Ant Design ©2023 Created by Ant UED
      </Footer>        */}
				</Layout>
			)}
		</AuthContext.Consumer>
	);
};

export default LayoutApp;
