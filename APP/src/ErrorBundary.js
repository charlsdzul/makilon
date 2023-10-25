import { useRouteError } from "react-router-dom";

const ErrorBundary = ({ auth, children }) => {
	const error = useRouteError();
	console.dir(error);
	return <h1>{error.data}</h1>;
};
export default ErrorBundary;
