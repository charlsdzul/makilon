import { RouterProvider } from "react-router-dom";
import AuthContext from "./Utils/AuthContext";
import TopBar from "./Layouts/TopBar";
import { router } from "./Routes";

const Layout = () => {



  return (
 <>
    <AuthContext.Consumer>
      {(auth) => (
        <>
         <TopBar auth={auth}></TopBar>
       </>
      )}
    </AuthContext.Consumer>
            <RouterProvider router={router} ></RouterProvider></>

  );
};

export default Layout;
