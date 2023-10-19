import axios from "axios";
import { getToken } from "./utils";

const APIURL = "http://localhost/makilon/API/public/api/v1/";

export const API_URL = APIURL;

export const post = async ({ url, formData, json, useToken = true }) => {
	let headers = { "Content-Type": formData ? "multipart/form-data" : "text/plain" };

	if (useToken) {
		const token = getToken();
		headers = {
			...headers,
			Authorization: `Bearer ${token}`,
			//"Access-Control-Allow-Credentials": true,
		};
	}

	const axiosConfig = {
		method: "post",
		url: API_URL + url,
		data: json ?? formData,
		headers,
	};

	//console.log(axiosConfig);

	const request = await axios(axiosConfig)
		.then((response) => {
			//console.log(response);
			return {
				status: response?.status,
				statusText: response?.statusText,
				data: response?.data?.data,
			};
		})
		.catch((response) => {
			//console.log(response);
			return {
				status: response?.response?.status,
				statusText: response?.response?.statusText,
				errors: response?.response?.data?.errors,
			};
		});

	return request;
};

export const get = async ({ url, params, useToken = true }) => {
	let headers = { "Content-Type": "text/plain" };

	if (useToken) {
		const token = getToken();
		headers = {
			Authorization: `Bearer ${token}`,
			//"Access-Control-Allow-Credentials": true,
		};
	}

	const axiosConfig = {
		method: "get",
		url: API_URL + url,
		headers,
		params: params,
	};
	//console.log(axiosConfig);

	const request = await axios(axiosConfig)
		.then((response) => {
			//console.log(response);
			return {
				status: response?.status,
				statusText: response?.statusText,
				data: response?.data?.data,
			};
		})
		.catch((response) => {
			//console.log(response);
			return {
				status: response?.response?.status,
				statusText: response?.response?.statusText,
				errors: response?.response?.data?.errors,
			};
		});
	return request;
};

// export default {
// 	post: async ({ url, formData, json, useToken = true }) => {
// 		let headers = { "Content-Type": json ? "text/plain" : "multipart/form-data" };

// 		if (useToken) {
// 			const token = getToken();
// 			headers = {
// 				"Content-Type": json ? "text/plain" : "multipart/form-data",
// 				Authorization: `Bearer ${token}`,
// 				"Access-Control-Allow-Credentials": true,
// 			};
// 		}

// 		return await axios({
// 			method: "post",
// 			url: API_URL + url,
// 			data: json ?? formData,
// 			headers,
// 		});
// 		// 	.then((res) => {
// 		// 		console.log(res);

// 		// 		return { status: result.response.status, statusText: result.response.statusText, data: result.response.data };
// 		// 	})
// 		// 	.catch((error) => {
// 		// 		console.log(error);
// 		// 		return {};
// 		// 	});

// 		// console.log(result);

// 		// if (result?.response.status === 401) {
// 		// 	console.error(result?.response?.status, result?.response?.data);
// 		// }

// 		// return {
// 		// 	status: result.response.status,
// 		// 	statusText: result.response.statusText,

// 		// 	data: result.response.data,
// 		// };
// 	},
// 	get: async ({ url, params, external = false }) => {
// 		return await axios({
// 			method: "get",
// 			url: external ? url : API_URL + url,
// 			//params: qs.stringify(params),
// 		}).then((res) => res);
// 	},
// };
