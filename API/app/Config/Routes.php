<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group("api/v1", ["namespace" => 'App\Controllers\API\v1'], static function ($routes) {
    $routes->get("catalogos/puestos", "catalogos::puestos");
    $routes->get("catalogos/puestosEspecificos", "catalogos::puestosEspecificos");
    $routes->post("auth/login", "auth::login");
    $routes->post("auth/authenticated", "auth::authenticated");
    $routes->post("vacante", "vacante::crear");
});
