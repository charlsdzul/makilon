import { Col, Row, Skeleton } from "antd";
import React from "react";
import CContainer from "../../Components/CContainer";

const SkeletonVacanteEdit = (props) => {
	const Tab1Vacante = () => {
		return (
			<CContainer style={{ backgroundColor: "" }}>
				<Row justify="left">
					<Col xs={24} sm={24} md={24} lg={12} xl={10} xxl={10}>
						<Skeleton.Input active={props.active} size={"default"} shape={"default"} block={false} />
					</Col>
					<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
						<Skeleton.Input active={props.active} size={"default"} shape={"default"} block={false} />
					</Col>
					<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
						<Skeleton.Input active={props.active} size={"default"} shape={"default"} block={false} />
					</Col>
				</Row>
				<Row justify="left">
					<Col xs={24} sm={24} md={24} lg={8} xl={10} xxl={10}>
						<Skeleton.Input active={props.active} size={"default"} shape={"default"} block={false} />
					</Col>
					<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
						<Skeleton.Input active={props.active} size={"default"} shape={"default"} block={false} />
					</Col>
					<Col xs={24} sm={24} md={24} lg={8} xl={7} xxl={7}>
						<Skeleton.Input active={props.active} size={"default"} shape={"default"} block={false} />
					</Col>
				</Row>
			</CContainer>
		);
	};

	return (
		<CContainer>
			<Row justify="center" style={{ marginTop: "2rem" }}>
				<Col xs={24} sm={24} md={24} lg={24} xl={24} xxl={24}>
					<Skeleton paragraph={{ rows: 4 }} active={props.active} size={"default"} shape={"default"} block={false} />
				</Col>
			</Row>
		</CContainer>
	);
};

export default SkeletonVacanteEdit;
