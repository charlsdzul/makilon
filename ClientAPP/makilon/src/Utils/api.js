import axios from "axios";
const API_URL = "http://localhost/makilon/API/public/api/v1/";

export default {
	post: async ({ url, formData }) => {
		return await axios({
			method: "post",
			url: API_URL + url,
			data: formData,
			headers: { "Content-Type": "multipart/form-data" },
		}).then((res) => res);
	},
	get: async (url, params, config) => {
		return await axios({
			method: "get",
			url: API_URL + url,
			params: params,
			headers: { "Access-Control-Allow-Origin": "*" },
		}).then((res) => res);
	},
};
