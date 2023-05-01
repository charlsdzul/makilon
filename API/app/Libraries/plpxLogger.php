<?php
namespace App\Libraries;
use App\Models\LogUsuarioModel;
use App\Models\LogSistemaModel;
use App\Entities\LogUsuario;
use App\Entities\LogSistema;

class PlpxLogger
{
	function log($tipo, $mensaje, $logLine)
	{
		$router = service("router");
		$controller = $router->controllerName();
		$method = $router->methodName();

		$info = [
			"controller" => $controller,
			"metodo" => $method,
			"logLine" => $logLine,
		];

		$mensajeCompleto = "Controller: {controller} | Metodo: {metodo} | LogLine: {logLine} | " . $mensaje;
		log_message($tipo, $mensajeCompleto, $info);
	}

	public function loggerUsuario(LogUsuario $lUsuario)
	{
		$logModel = new LogUsuarioModel();
		$logModel->insert($lUsuario);
	}

	public function loggerSistema(LogSistema $lSistema)
	{
		$logModel = new LogSistemaModel();
		$logModel->insert($lSistema);
	}
}
