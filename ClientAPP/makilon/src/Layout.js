import { RouterProvider } from "react-router-dom";
import AuthContext from "./Utils/AuthContext";
import TopBar from "./Layouts/TopBar";
import { router } from "./Routes";

const Layout = () => {



  return (
    <AuthContext.Consumer>
      {(auth) => (
        <>
         <TopBar auth={auth}></TopBar>
      <RouterProvider router={router}></RouterProvider>
       </>
      )}
    </AuthContext.Consumer>
  );
};

export default Layout;
