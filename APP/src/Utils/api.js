import axios from "axios";

const APIURL = "http://localhost/makilon/API/public/api/v1/";

export const API_URL = APIURL;

export default {
	post: async ({ url, formData, json }) => {
		return await axios({
			method: "post",
			url: API_URL + url,
			data: json ?? formData,
			headers: { "Content-Type": json ? "application/json" : "multipart/form-data" },
		}).then((res) => res);
	},
	get: async ({ url, params, config, external = false }) => {
		return await axios({
			method: "get",
			url: external ? url : API_URL + url,
			//params: qs.stringify(params),
		}).then((res) => res);
	},
};
