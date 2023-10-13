import Cookies from "js-cookie";
import jwt_decode from "jwt-decode";
import api from "../Utils/api";

let _id = null;
let _accessToken = null;
let _scopes = null;
let _expiresAt = null;
let _user = null;

export default class AuthService {
	// constructor(history) {
	// 	this.history = history;
	// }

	authorize = async (correo, contrasena) => {
		// delete api.defaults.headers.common["Authorization"];
		// delete axios.defaults.headers.common["Authorization"];
		// api
		// 	.post("/Account/authenticate", { userCode: user, password: password })
		// 	.then((response) => {
		// 		this.handleAuthentication(response.data, null);
		// 	})
		// 	.catch((error) => {
		// 		this.handleAuthentication(null, error);
		// 	});

		const formData = new FormData();
		formData.append("correo", correo);
		formData.append("contrasena", contrasena);

		const response = await api
			.post({ url: "auth/login", formData })
			.then((response) => response.data)
			.catch((error) => error.response);

		if (response?.status === 200) {
			const token = response?.data?.token;
			this.setSession({ token });
		}

		return response;
	};

	isAuthenticated = async () => {
		const dataToken = this.getDataFromToken();
		if (!dataToken) return null;

		const formData = new FormData();
		formData.append("token", dataToken?.token);
		formData.append("email", dataToken?.email);

		const response = await api
			.post({ url: "auth/authenticated", formData })
			.then((response) => response.data)
			.catch((error) => error.response);

		return response;
	};

	validateTokenRouteLoader = async () => {
		const existsToken = this.existsToken();
		if (!existsToken) return { isAuthenticated: existsToken };

		const response = await this.isAuthenticated();
		if (response === null) return { isAuthenticated: false };

		const isAuthenticated = response?.data?.isValidToken ?? false;
		return { isAuthenticated: isAuthenticated };
	};

	existsToken = () => {
		const exists = Cookies.get("token");
		return exists !== undefined && exists !== null && exists !== "";
	};

	getTokenFromCokie = () => {
		const token = Cookies.get("token");
		return token;
	};

	cleanToken = () => {
		Cookies.remove("token");
	};

	// login = (user, password) => {
	// 	let location = this.history.location;
	// 	if (location.pathname === "/login") location = "/";
	// 	Cookies.remove("token");
	// 	localStorage.setItem(REDIRECT_ON_LOGIN, JSON.stringify(location));
	// 	this.authorize(user, password);
	// };

	getDataFromToken = () => {
		try {
			const token = this.getTokenFromCokie();
			const decoded = jwt_decode(token);
			return { expires: decoded.exp, id: decoded.uid, token: token, userName: decoded.sub, email: decoded.email, demo: decoded.demo };
		} catch (error) {
			return null;
		}
	};

	// getAuthInfo = () => {
	// 	return jwt_decode(_accessToken);
	// };

	// handleAuthentication = (authResult, err) => {
	// 	if (authResult && authResult.token) {
	// 		const data = this.getSessionDataFromToken(authResult.token);
	// 		Cookies.set("token", authResult.token);
	// 		this.setSession(data);
	// 		const redirectLocation = localStorage.getItem(REDIRECT_ON_LOGIN) === "undefined" ? "/" : JSON.parse(localStorage.getItem(REDIRECT_ON_LOGIN));
	// 		this.history.push(redirectLocation);
	// 	} else if (err) {
	// 		//notify("The combination of user/password is incorrect.", "error", 5000);
	// 		console.log(err);
	// 	}
	// 	localStorage.removeItem(REDIRECT_ON_LOGIN);
	// };

	setSession = ({ token }) => {
		//console.log("authResult", authResult);
		Cookies.set("token", token);
		// set the time that the access token will expire
		//_expiresAt = authResult.expires;
		// If there is a value on the `scope` param from the authResult,
		// use it to set scopes in the session for the user. Otherwise
		// use the scopes as requested. If no scopes were requested,
		// set it to nothing
		//_scopes = authResult.scope || this.requestedScopes || "";
		_accessToken = token;
		//_id = authResult.id;
		//_user = authResult.userName;
		// se crean default para las creaciones de api subsecuentes
		//axios.defaults.headers.common["Authorization"] = "bearer " + authResult.token;
		//axios.defaults.headers.common["Accept-Language"] = i18n.language;
		// api aun no trae los default, se agregan para esta instancia
		//api.defaults.headers.common["Authorization"] = "bearer " + authResult.token;
		//api.defaults.headers.common["Accept-Language"] = i18n.language;
		//this.scheduleTokenRenewal();
	};

	// logout = () => {
	// 	Cookies.remove("token");
	// 	_id = null;
	// 	_accessToken = null;
	// 	_scopes = null;
	// 	_expiresAt = null;
	// 	_user = null;
	// 	this.history.push("/");
	// };

	// userData = () => {
	// 	return { userId: _id, userName: _user };
	// };

	// getAccessToken = () => {
	// 	if (!_accessToken) {
	// 		throw new Error("No access token found.");
	// 	}
	// 	return _accessToken;
	// };

	// userHasScopes(scopes) {
	// 	const grantedScopes = (_scopes || "").split(" ");
	// 	return scopes.every((scope) => grantedScopes.includes(scope));
	// }

	// silentToken() {
	// 	Cookies.remove("token");
	// 	console.log("silentToken: ");
	// }

	// renewToken(cb) {
	// 	console.log("renewToken: " + JSON.stringify(cb));
	// 	const token = Cookies.get("token");
	// 	if (token) {
	// 		const data = this.getSessionDataFromToken(token);
	// 		this.setSession(data);
	// 	}
	// 	// this.auth0.checkSession({}, (err, result) => {
	// 	//   if (err) {
	// 	//     console.log(`Error: ${err.error} - ${err.error_description}.`);
	// 	//   } else {
	// 	//     this.setSession(result);
	// 	//   }
	// 	if (cb) cb(null, null);
	// 	// });
	// }

	// implementar silent renew cuando pase el timer...
	// scheduleTokenRenewal() {
	// 	const delay = _expiresAt * 1000 - Date.now();
	// 	if (delay > 0) setTimeout(() => this.silentToken(), delay);
	// }
}
