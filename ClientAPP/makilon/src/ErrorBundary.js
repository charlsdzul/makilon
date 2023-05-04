import { useRouteError } from "react-router-dom";

const ErrorBundary = ({ auth, children }) => {
	let error = useRouteError();
	console.dir(error);
	return <h1>ErrorBundary</h1>;
};
export default ErrorBundary;
