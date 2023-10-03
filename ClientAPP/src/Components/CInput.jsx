import React from "react";
import PropTypes from "prop-types";
import { Input } from "antd";

const CInput = (props) => {
	return <Input placeholder={props.placeholder} size={props.size} disabled={props.disabled}></Input>;
};

CInput.propTypes = {
	placeholder: PropTypes.string,
	size: PropTypes.string,
	disabled: PropTypes.bool,
	//htmlType: PropTypes.string,
	//size: PropTypes.string,
	//block: PropTypes.bool,
	//style: PropTypes.object,
	//loading: PropTypes.bool,
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

CInput.defaultProps = {
	placeholder: "",
	size: "",
	disabled: false,
	// htmlType: "",
	// size: "default",
	// block: false,
	// style: null,
	// loading: false,
};

export default CInput;
