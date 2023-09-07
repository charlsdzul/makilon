import styles from '../CSS/common.module.css'



const CContainer = ({children, className}) => {
	return <div className={`${styles.c_container} ${className}`}>{children}</div>;
};

export default CContainer;
