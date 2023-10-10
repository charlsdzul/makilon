<?php
namespace App\Libraries;

use App\Entities\Log;
use App\Models\LogModel;

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

    public function loggerSistema(Log $lSistema)
    {
        $logModel = new LogModel();
        $logModel->insert($lSistema);
    }
}
