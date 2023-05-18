import api from "../Utils/api";

// const register = (username, email, password) => {
// 	return axios.post(API_URL + "signup", {
// 		username,
// 		email,
// 		password,
// 	});
// };

const login = async (correo, contrasena) => {
	const formData = new FormData();
	formData.append("correo", correo);
	formData.append("contrasena", contrasena);

	const response = await api
		.post("usuario/login", formData)
		.then((response) => response.data)
		.catch((error) => error.response.data);

	return response;
};

// const logout = () => {
// 	localStorage.removeItem("user");
// };

// const getCurrentUser = () => {
// 	return JSON.parse(localStorage.getItem("user"));
// };

const AuthService = {
	//register,
	login,
	//logout,
	//getCurrentUser,
};

export default AuthService;
