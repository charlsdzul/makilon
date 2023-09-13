import { useRouteError } from "react-router-dom";

const ErrorBundary = ({ auth, children }) => {
	let error = useRouteError();
	console.dir(error);
	return <h1>ErrorBundary Custom!</h1>;
};
export default ErrorBundary;
