import React from "react";
import PropTypes from "prop-types";
import { Button } from "antd";

const CButton = (props) => {
	return (
		<Button type={props.type} size={props.size} htmlType={props.htmlType} block={props.block} style={props.style} loading={props.loading}>
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

	// value: PropTypes.oneOfType([PropTypes.instanceOf(Date), PropTypes.number, PropTypes.string]),
	// displayFormat: PropTypes.string,
	// useMaskBehavior: PropTypes.bool,
	// hint: PropTypes.string,
	// onValueChanged: PropTypes.func,
	// className: PropTypes.string,
	// disabled: PropTypes.bool,
	// inputAttr: PropTypes.object,
	// min: PropTypes.oneOfType([PropTypes.instanceOf(Date), PropTypes.number, PropTypes.string]),
	// style: PropTypes.string,
	// invalidDateMessage: PropTypes.string,
	// type: PropTypes.string,
	// maxLenght: PropTypes.number,
	// readOnly: PropTypes.bool,
	// pickerType: PropTypes.string,
	// placeholder: PropTypes.string,
	// showDropDownButton: PropTypes.bool,
	// onEnterKey: PropTypes.func,
	// onFocusIn: PropTypes.func,
	// onFocusOut: PropTypes.func,
	// onChange: PropTypes.func,
	// acceptCustomValue: PropTypes.bool,
	// visible: PropTypes.bool,
	// emptyBox: PropTypes.bool,
	// showClearButton: PropTypes.bool,
};

CButton.defaultProps = {
	id: "",
	text: "",
	type: "primary",
	htmlType: "",
	size: "default",
	block: false,
	style: null,
	loading: false,
};

export default CButton;
