import { Button } from "antd";
import PropTypes from "prop-types";
import React from "react";

const CButton = (props) => {
	return (
		<Button
			type={props.type}
			size={props.size}
			htmlType={props.htmlType}
			block={props.block}
			style={props.style}
			loading={props.loading}
			onClick={props.onClick}
			key={props.key}>
			{props.text}
		</Button>
	);
};

CButton.propTypes = {
	id: PropTypes.string,
	type: PropTypes.string,
	text: PropTypes.string,
	htmlType: PropTypes.string,
	size: PropTypes.string,
	block: PropTypes.bool,
	style: PropTypes.object,
	loading: PropTypes.bool,
	onClick: PropTypes.func,
};

CButton.defaultProps = {
	id: "",
	text: "",
	type: "primary",
	htmlType: "button",
	size: "default",
	block: false,
	style: {},
	loading: false,
	onClick: null,
	key: null,
};

export default CButton;
