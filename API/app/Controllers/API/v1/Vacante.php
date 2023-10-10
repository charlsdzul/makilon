<?php

namespace App\Controllers\API\v1;

use App\Models\VacanteModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class Vacante extends ResourceController
{
    use ResponseTrait;
    public $session = null;
    public $router = null;
    public $lUsario = null;
    public $controllerName = "API/v1/Vacante";

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->router = \Config\Services::router();
        helper("/Validators/usuarioValidator");
        helper("/utils");
    }

    private function logSistema($args)
    {
        $args2 = [
            "log_controller" => $this->controllerName,
            "log_method" => $this->router->methodName(),
            "log_request_ip" => $this->request->getIPAddress(),
        ];

        logSistema([...$args, ...$args2]);
    }

    /**
     * Permite al usuario hacer login en el sistema.
     *
     * @return Response
     */
    public function crear()
    {
        $logAccion = "Crear Vacante";

        try {
            $titulo = $this->request->getJsonVar("titulo");
            $puesto = $this->request->getJsonVar("puesto");
            $puestoOtro = $this->request->getJsonVar("puestoOtro");
            $puestoEspecifico = $this->request->getJsonVar("puestoEspecifico");
            $puestoEspecificoOtro = $this->request->getJsonVar("puestoEspecificoOtro");

            //PENDIENTE SANITIZAR Y VALIDAR CAMPOS DE ENTRADA

            $data = [
                "vac_titulo" => $titulo,
                "vac_puesto" => $puesto,
                "vac_puesto_otro" => $puestoOtro,
                "vac_puesto_especifico" => $puestoEspecifico,
                "vac_puesto_especifico_otro" => $puestoEspecificoOtro,
                "vac_creado_sta" => 1,
                "vac_sta" => 0,
            ];

            $vacanteModel = new VacanteModel();
            $idInserted = $vacanteModel->insert($data);

            //VERIFICA SI HAY ERRORES SEGUN EL MODELO
            if (count($vacanteModel->errors()) > 0) {
                $errors = "";

                foreach ($vacanteModel->errors() as $error) {
                    $errors = $errors . " | " . $error;
                }

                $dataLog = [
                    "log_origen" => "usuario",
                    "log_tipo" => "critical",
                    "log_accion" => $logAccion,
                    "log_mensaje" => "No pasó las validaciones del MODELO.",
                    "log_request_respond" => "invalid_request",
                    "log_exception" => $errors,
                    "log_linea" => __LINE__,
                ];

                $this->logSistema($dataLog);
                $response = getErrorResponseByCode(["code" => 2100]);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            $response = getErrorResponseByCode(["code" => 2101]);
            return $this->apiResponse("ok", [...$response, "idVacante" => $idInserted]);
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            $response = getErrorResponseByCode(["code" => 2100]);

            $dataLog = [
                "log_origen" => "usuario",
                "log_tipo" => "critical",
                "log_accion" => $logAccion,
                "log_mensaje" => "ERROR CATCH." . $mensaje,
                "log_request_respond" => "server_error",
                "log_exception" => $e,
                "log_usuario_respuesta" => $response,
                "log_linea" => __LINE__,
            ];

            $this->logSistema($dataLog);
            return $this->apiResponseError("server_error", $response);
        }
    }
}
