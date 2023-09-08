import { RouterProvider } from "react-router-dom";
import AuthContext from "./Utils/AuthContext";
import TopBar from "./Layouts/TopBar";
import { router } from "./Routes";
import { Layout, Menu, theme } from "antd";
import styles from "./CSS/common.module.css";

const { Header, Content, Footer } = Layout;

const LayoutApp = () => {
	return (
		<AuthContext.Consumer>
			{(auth) => (
				<Layout className={styles.c_layoutapp}>
					{/* <TopBar auth={auth}></TopBar> */}
					<Header style={{ display: "flex", alignItems: "center" }}>
						<Menu
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
						/>
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
