import Typography from "antd/es/typography/Typography";
import styles from "../CSS/common.module.css";

const CContainer = ({ children, className, style, title }) => {
	return (
		<div className={`${styles.c_container} ${className}`} style={{ ...style, padding: "1rem" }}>
			{title && (
				<Typography.Title level={3} style={{ margin: 0 }}>
					{title}
				</Typography.Title>
			)}

			{children}
		</div>
	);
};

export default CContainer;
