<?php

namespace App\Controllers;

class Usuario extends BaseController
{
	public $session = null;

	public function __construct()
	{
		$this->session = \Config\Services::session();
	}
	public function registro()
	{
		$viewData = [
			"titulo" => SITE_NAME,
			"url_inicio" => base_url() . "index.php/inicio",
			"url_inicio_name" => lang("URL.urlInicio"),
			"url_inicio_visible" => true,
			"url_misanuncios" => base_url() . "index.php/mis-anuncios",
			"url_misanuncios_name" => lang("URL.urlMisAnuncios"),
			"url_misanuncios_visible" => $this->session->usu_id ? true : false,
			"url_nuevoanuncio" => base_url() . "index.php/mis-anuncios/nuevo",
			"url_nuevoanuncio_name" => lang("URL.urlNuevoAnuncio"),
			"url_nuevoanuncio_visible" => $this->session->usu_id ? true : false,
			"url_ingresar" => base_url() . "index.php/ingresar",
			"url_ingresar_name" => lang("URL.urlIngresar"),
			"url_crearcuenta" => base_url() . "index.php/usuario/registro",
			"url_crearcuenta_name" => lang("URL.urlCrearCuenta"),
			"micuenta_visible" => $this->session->usu_id ? true : false,
			"micuenta_name" => lang("URL.urlMiCuenta"),
			"url_configuracion" => base_url() . "index.php/mis-anuncios/nuevo",
			"url_configuracion_visible" => true,
			"url_configuracion_name" => lang("URL.urlConfiguraciones"),
			"url_salir" => base_url() . "index.php/mi-cuenta/salir",
			"url_salir_visible" => true,
			"url_salir_name" => lang("URL.urlSalir"),
		];

		//echo "dddsdsadasdsadsad";
		return view("headers/header_general") .
			view("modules/topmenu", $viewData) .
			view("pages/usuario_registro", $viewData) .
			view("scripts/usuario_registro_scr", $viewData);
		// . view('menu')
		// . view('content', $data)
		// . view('footer');
	}
}
