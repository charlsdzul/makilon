import { ConfigProvider, Layout, Menu,Icon, Drawer, Button } from "antd";
import React,{ useState } from "react";
import { RouterProvider } from "react-router-dom";
import styles from "./CSS/common.module.css";
//import "antd/dist/antd.css";
import "./LayoutApp.css"
import { UploadOutlined, UserOutlined, VideoCameraOutlined } from '@ant-design/icons';

import { router } from "./Routes";
import AuthContext from "./Utils/AuthContext";

import {
	AppstoreOutlined,
	ContainerOutlined,
	DesktopOutlined,
	MailOutlined,
	MenuFoldOutlined,
	MenuUnfoldOutlined,
	PieChartOutlined,
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
  const items = [
	getItem('Option 1', '1', <PieChartOutlined />),
	getItem('Option 2', '2', <DesktopOutlined />),
	getItem('Option 3', '3', <ContainerOutlined />),
	getItem('Navigation One', 'sub1', <MailOutlined />, [
	  getItem('Option 5', '5'),
	  getItem('Option 6', '6'),
	  getItem('Option 7', '7'),
	  getItem('Option 8', '8'),
	]),
	getItem('Navigation Two', 'sub2', <AppstoreOutlined />, [
	  getItem('Option 9', '9'),
	  getItem('Option 10', '10'),
	  getItem('Submenu', 'sub3', null, [getItem('Option 11', '11'), getItem('Option 12', '12')]),
	]),
  ];

const LayoutApp = () => {
	console.log("LayoutApp");

	const [collapsed, setCollapsed] = useState(false);
	const toggleCollapsed = () => {
	  setCollapsed(!collapsed);
	};

	const [visible, setVisible] = useState(false)

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
			defaultOpenKeys={['sub1']}
			mode="inline"
			///theme="dark"
			inlineCollapsed={false}
			items={items}
		  	/>
      </Sider>


		  </>
			);
		
	  }
	  
	

	const LeftMenu = () => {
		
		  return (
		 <Menu mode="inline">
			 <Menu.Item key="mail">
				<a href="">Home</a>
			  </Menu.Item>
			  <SubMenu title={<span>Blogs</span>}>
				<MenuItemGroup title="Item 1">
				  <Menu.Item key="setting:1">Option 1</Menu.Item>
				  <Menu.Item key="setting:2">Option 2</Menu.Item>
				</MenuItemGroup>
				<MenuItemGroup title="Item 2">
				  <Menu.Item key="setting:3">Option 3</Menu.Item>
				  <Menu.Item key="setting:4">Option 4</Menu.Item>
				</MenuItemGroup>
			  </SubMenu>
			  <Menu.Item key="alipay">
				<a href="">Contact Us</a>
			  </Menu.Item>
			</Menu>
		  );
		
	  }

	return (
		<AuthContext.Consumer>
			{({ auth }) => (
				<Layout className={styles.c_layoutapp}>
					{/* <TopBar auth={auth}></TopBar> */}

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
						{/* <div className="demo-logo-vertical" /> */}
						<Menu
						theme="dark"
						mode="inline"
						defaultSelectedKeys={['1']}
						items ={items}
					
						/>
					</Sider>

	  				<Layout>
							
	  <Header className="menuBar" style={{ display: "flex", alignItems: "center" }}>
 
          <div className="logo">
            <a href="">logo</a>
          </div>
          <div className="menuCon">
            <div className="leftMenu">
              <LeftMenu />
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
