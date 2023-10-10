import { Typography } from "antd";
import PropTypes from "prop-types";
import styles from "../CSS/common.module.css";
const { Title } = Typography;

const CContainer = ({ children, className, style, title }) => {
	return (
		<div className={`${styles.c_container} ${className}`} style={{ ...style, padding: "1rem" }}>
			{title !== "" && (
				<Title level={3} style={{ margin: 0 }}>
					{title}
				</Title>
			)}

			{children}
		</div>
	);
};

CContainer.propTypes = {
	title: PropTypes.string,
	style: PropTypes.object,
	className: PropTypes.string,
};

CContainer.defaultProps = {
	title: "",
	style: {},
	className: "",
	children: <></>,
};

export default CContainer;
