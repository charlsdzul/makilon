import { Layout, Menu } from "antd";
import React, { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { NavLink, Outlet, useLoaderData } from "react-router-dom";
import styles from "./CSS/common.module.css";
import AuthService from "./Services/authservice.services";

import "./LayoutApp.css";

import AuthContext from "./Utils/AuthContext";

import {
	AreaChartOutlined,
	FormOutlined,
	HomeOutlined,
	LogoutOutlined,
	SettingOutlined,
	UnorderedListOutlined,
	UserOutlined,
} from "@ant-design/icons";
import { getUrlPathName } from "./Utils/utils";
const auth = new AuthService();

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

const AppWrapper = (props) => {
	const [collapsed, setCollapsed] = useState(false);
	const [opcionesMenu, setOpcionesMenu] = useState();
	const [currentPathName, setCurrentPathName] = useState("");
	const loaderData = useLoaderData();
	//console.log("loaderData AppWrapper", loaderData);
	const toggleCollapsed = () => {
		setCollapsed(!collapsed);
	};

	const { t } = useTranslation(["LayoutApp"]);

	const RightMenu = () => {
		// return (
		// 	<Menu mode="horizontal" style={{ height: "2.5rem" }}>
		// 		<Menu.Item key="mail">
		// 			<a href="">Signin</a>
		// 		</Menu.Item>
		// 		<Menu.Item key="app">
		// 			<a href="">Signup</a>
		// 		</Menu.Item>
		// 	</Menu>
		// );
	};

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
				defaultSelectedKeys={["2"]}
				items={new Array(3).fill(null).map((_, index) => {
					const key = index + 1;
					return {
						key,
						label: `nav ${key}`,
					};
				})}
			/>
		);
	};

	const iniciarPrograma = () => {
		setOpcionesMenu([
			getItem(<NavLink to={`/portal`}>{t("LayoutApp.lblMenuPortal")}</NavLink>, "/portal", <HomeOutlined />),
			getItem(<NavLink to={`/dashboard`}>{t("LayoutApp.lblMenuDashboard")}</NavLink>, "/dashboard", <AreaChartOutlined />),
			getItem(<NavLink to={`/vacante`}>{t("LayoutApp.lblMenuAgregarVacante")}</NavLink>, "/vacante", <FormOutlined />),
			getItem(<NavLink to={`/mis-vacantes`}>{t("LayoutApp.lblMenuMisVacantes")}</NavLink>, "/mis-vacantes", <UnorderedListOutlined />),
			getItem(<NavLink to={`/mi-cuenta`}>{t("LayoutApp.lblMenuMiCuenta")}</NavLink>, "/mi-cuenta", <UserOutlined />),
			getItem(t("LayoutApp.lblMenuConfiguraciones"), "sub1", <SettingOutlined />, [
				getItem("Option 5", "5"),
				getItem("Option 6", "6"),
				getItem("Option 7", "7"),
				getItem("Option 8", "8"),
			]),
			getItem(t("LayoutApp.lblMenuCerrarSesion"), "9", <LogoutOutlined />),
		]);

		const pathname = getUrlPathName();
		setCurrentPathName(pathname);
	};

	// eslint-disable-next-line react-hooks/exhaustive-deps
	useEffect(() => iniciarPrograma(), []);

	return (
		<AuthContext.Provider value={{ auth }}>
			<Layout className={styles.c_layoutapp}>
				{loaderData?.isAuthenticated && (
					<Sider
						breakpoint="lg"
						collapsedWidth="0"
						onBreakpoint={(broken) => {
							//	console.log(broken);
						}}
						onCollapse={(collapsed, type) => {
							//	console.log(collapsed, type);
						}}>
						<Menu theme="dark" mode="inline" selectedKeys={[currentPathName]} items={opcionesMenu} subMenuOpenDelay={5} />
					</Sider>
				)}

				<Layout>
					<Header style={{ height: "2.5rem" }}>
						<Layout className="menuCon">
							{/* <div className="logo">
								<a href="">logo</a>
							</div> */}

							{/* <div className="leftMenu">
									<TopMenu />
								</div> */}

							{/* <div className="rightMenu">
									<RightMenu />
								</div> */}
							{/* <Button className="barsMenu" type="primary" onClick={() => setVisible(true)}>
									<span className="barsBtn"></span>
								</Button> */}

							{/* <Drawer title="Basic Drawer" placement="right" closable={false} onClose={() => setVisible(false)} visible={visible} /> */}
						</Layout>
					</Header>

					<Content>
						<div style={{ background: "camel" }}>
							<Outlet />
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
		</AuthContext.Provider>
	);
};

export default AppWrapper;
