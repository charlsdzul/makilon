<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . "Config/Routes.php")) {
	require SYSTEMPATH . "Config/Routes.php";
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace("App\Controllers");
$routes->setDefaultController("Home");
$routes->setDefaultMethod("index");
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get("/", "Home::index");
$routes->get("/registro", "Usuario::registro");

// URL de la API
$routes->group("api/v1", ["namespace" => 'App\Controllers\API\v1'], static function ($routes) {
	$routes->resource("cantanuncios", ["controller" => "catcantanuncios"]);
	$routes->resource("estados", ["controller" => "catestados"]);
	$routes->resource("apartados", ["controller" => "catapartados"]);
	$routes->resource("modalidades", ["controller" => "catmodalidades"]);
	$routes->resource("municipios", ["controller" => "catmunicipios"]);
	$routes->resource("secciones", ["controller" => "catsecciones"]);

	// $routes->get("municipios/", "catmunicipios");
	// $routes->get("municipios/(:segment)", 'catmunicipios::show/$1');

	// $routes->get("secciones/", "catsecciones");
	// $routes->get("secciones/(:segment)", 'catsecciones::show/$1');
	$routes->post("usuario/registro", "usuarios::registro");
	$routes->get("usuario/confirmacion/(:segment)/(:hash)", "usuarios::confirmacion/$1/$2");
	$routes->post("usuario/login", "usuarios::login");
	$routes->post("usuario/authenticated", "usuarios::authenticated");

	$routes->post("usuario/logout", "usuarios::logout");
	$routes->put("usuario/", "usuarios::actualizaDatos");
	$routes->put("usuario/correo", "usuarios::actualizaCorreo");
	$routes->put("usuario/psw", "usuarios::actualizaPassword");
	$routes->post("usuario/psw/recuperar", "usuarios::recuperarPassword");
	//$routes->put("usuario/pws/recuperar/confirmacion", "usuarios::recuperarPassword");

	//$routes->resource("registro", ["controller" => "usuarios"]);
});

// $routes->group("api/v1/usuario", ["namespace" => 'App\Controllers\API\v1'], static function ($routes) {
// 	$routes->get("registro", ["controller" => "usuarios"]);

// });

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . "Config/" . ENVIRONMENT . "/Routes.php")) {
	require APPPATH . "Config/" . ENVIRONMENT . "/Routes.php";
}
