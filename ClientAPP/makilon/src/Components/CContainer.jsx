import styles from "../CSS/common.module.css";

const CContainer = ({ children, className, style }) => {
	return (
		<div className={`${styles.c_container} ${className}`} style={{ ...style }}>
			{children}
		</div>
	);
};

export default CContainer;
