import axios from "axios";
import { getToken } from "./utils";

const APIURL = "http://localhost/makilon/API/public/api/v1/";

export const API_URL = APIURL;

export default {
	post: async ({ url, formData, json, useToken = true }) => {
		let headers = { "Content-Type": json ? "text/plain" : "multipart/form-data" };

		if (useToken) {
			const token = getToken();
			headers = {
				"Content-Type": json ? "text/plain" : "multipart/form-data",
				Authorization: `Bearer ${token}`,
				"Access-Control-Allow-Credentials": true,
			};
		}

		const result = await axios({
			method: "post",
			url: API_URL + url,
			data: json ?? formData,
			headers,
		})
			.then((res) => {	status: result.response.status,
				statusText: result.response.statusText,	
				data: result.response.data,})
			.catch((error) => error);

		console.log(result);

		if (result?.response.status === 401) {
			console.error(result?.response?.status, result?.response?.data);
		}

		return {
			status: result.response.status,
			statusText: result.response.statusText,

			data: result.response.data,
		};
	},
	get: async ({ url, params, external = false }) => {
		return await axios({
			method: "get",
			url: external ? url : API_URL + url,
			//params: qs.stringify(params),
		}).then((res) => res);
	},
};
