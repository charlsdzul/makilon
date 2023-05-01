import { validateEmailRegex } from "./utils.js";
import { commonMessages, usuarioMessages } from "./plpxTranslate.js";

export function validateEmail(email) {
	if (email === "") return { valid: false, message: usuarioMessages[1000] };
	if (!typeof email === "string") return { valid: false, message: commonMessages[100] };
	if (email.length > 70) return { valid: false, message: commonMessages[101] };
	if (!validateEmailRegex(email)) return { valid: false, message: usuarioMessages[1001] };
	return { valid: true, message: "" };
}

export function validatePassword(pwd) {
	if (pwd === "") return { valid: false, message: usuarioMessages[1002] };
	if (pwd.length < 8) return { valid: false, message: usuarioMessages[1003] };
	if (pwd.length > 15) return { valid: false, message: usuarioMessages[1004] };
	return { valid: true, message: "" };
}
