import { RouterProvider } from "react-router-dom";
import AuthContext from "./Utils/AuthContext";
import TopBar from "./Layouts/TopBar";
import { router } from "./Routes";
import { Layout, Menu, theme } from "antd";
import styles from "./CSS/common.module.css";
import "./LayoutApp.css"

const { Header, Content, Footer } = Layout;

const LayoutApp = () => {
	console.log("LayoutApp");

	const RightMenu =()=> {
		
		  return (
			<Menu mode="horizontal">
			  <Menu.Item key="mail">
				<a href="">Signin</a>
			  </Menu.Item>
			  <Menu.Item key="app">
				<a href="">Signup</a>
			  </Menu.Item>
			</Menu>
		  );
		
	  }
	

	const LeftMenu = () => {
		
		  return (
		 <Menu mode="horizontal">
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
					<Header style={{ display: "flex", alignItems: "center" }}>

						 <nav className="menuBar">
          <div className="logo">
            <a href="">logo</a>
          </div>
          <div className="menuCon">
            <div className="leftMenu">
              <LeftMenu />
            </div>
            <div className="rightMenu">
                <RightMenu />
            </div>
            <Button className="barsMenu" type="primary" onClick={this.showDrawer}>
              <span className="barsBtn"></span>
            </Button>
            <Drawer
              title="Basic Drawer"
              placement="right"
              closable={false}
              onClose={this.onClose}
              visible={this.state.visible}
            >
              <LeftMenu />
              <RightMenu />
            </Drawer>
</div>
        </nav>

						{/* <Menu
							theme="dark"
							mode="horizontal"
							defaultSelectedKeys={["2"]}
							style={{ flex: "auto", minWidth: 0 }}
							items={new Array(15).fill(null).map((_, index) => {
								const key = index + 1;
								return {
									key,
									label: `nav ${key}`,
								};
							})}
						/> */}
					</Header>

					<Content
						style={{
							padding: "1rem",
						}}>
						<div
							// className="site-layout-content"
							style={{
								background: "camel",
							}}>
							<RouterProvider router={router} />
						</div>
					</Content>

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
