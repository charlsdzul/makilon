<?php

namespace App\Controllers\API\v1;

use App\Models\VacanteModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class Vacante extends ResourceController
{
    use ResponseTrait;
    public $router = null;
    public function __construct()
    {
        $this->router = \Config\Services::router();
        helper("/Validators/vacanteValidator");
        helper("/utils");
    }

    private function logSistema($args)
    {
        $args2 = [
            "log_controller" => "API/v1/Vacante",
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

            $requestBody = $this->request->getJsonVar(["titulo", "puesto", "puestoOtro", "puestoEspecifico", "puestoEspecificoOtro"]);

            if (!vacanteValidator($requestBody, $errorsValidator)) {

                //Se guarda LOG porque, en teoria, no deberia tener errores en el validator,
                //porque en el front ya estan la validaciones.
                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "warning", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "No pasó las validaciones del VALIDATOR.",
                    "log_request_respond" => "invalid_request",
                    "log_exception" => json_encode($errorsValidator),
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", $errorsValidator);
            }

            //PENDIENTE SANITIZAR Y VALIDAR CAMPOS DE ENTRADA

            $data = [
                "vac_titulo" => $requestBody["titulo"],
                "vac_puesto" => $requestBody["puesto"],
                "vac_puesto_otro" => $requestBody["puestoOtro"],
                "vac_puesto_especifico" => $requestBody["puestoEspecifico"],
                "vac_puesto_especifico_otro" => $requestBody["puestoEspecificoOtro"],
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

                $response = getErrorResponseByCode(["code" => 2100]);

                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "critical", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "No pasó las validaciones del MODELO.",
                    "log_request_respond" => "invalid_request",
                    "log_exception" => $errors,
                    "log_usuario_respuesta" => $response,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            $response = getErrorResponseByCode(["code" => 2101]);
            return $this->apiResponse("ok", [...$response, "idVacante" => $idInserted]);
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            $response = getErrorResponseByCode(["code" => 2100]);

            $dataLog = [
                "log_origen" => "usuario", "log_tipo" => "critical", "log_accion" => $logAccion, "log_linea" => __LINE__,
                "log_mensaje" => "ERROR CATCH. " . $mensaje,
                "log_request_respond" => "server_error",
                "log_exception" => $e,
                "log_usuario_respuesta" => $response,
            ];

            $this->logSistema($dataLog);
            return $this->apiResponseError("server_error", $response);
        }
    }

    public function misVacantes()
    {
        $logAccion = "Mis Vacantes";

        try {
            // $something = $this->request->getVar('page');
            //echo $something;

            $requestBody = $this->request->getJsonVar(["page", "perPage", "total"]);

            $page = $requestBody["page"] ?? 1;
            $perPage = $requestBody["perPage"] ?? 20;
            //$total = $requestBody["total"] ?? 200;
            $offset = ($page - 1) * $perPage;

            $vacanteModel = new VacanteModel();
            $builder = $vacanteModel->builder();
            $query = $builder->get($perPage, $offset);
            $vacantes = $query->getResult();

            $totalCount = $query->getNumRows();

            $response = [
                "results" => $vacantes,
                "totalCount" => $totalCount,
            ];

            return $this->apiResponse("ok", $response);

            // $requestBody = $this->request->getJsonVar(["vac_titulo"]);

            // $vacanteModel = new VacanteModel();
            // $builder = $vacanteModel->paginate();

            // $query = $builder->get(10, 20);
            // $vacantes = $query->getResult();
            // $totalCount = $query->getNumRows();

            // $response = [
            //     "results" => $vacantes,
            //     "totalCount" => $totalCount,
            // ];

            // return $this->apiResponse("ok", $response);

        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            $response = getErrorResponseByCode(["code" => 2100]);

            $dataLog = [
                "log_origen" => "usuario", "log_tipo" => "critical", "log_accion" => $logAccion, "log_linea" => __LINE__,
                "log_mensaje" => "ERROR CATCH. " . $mensaje,
                "log_request_respond" => "server_error",
                "log_exception" => $e,
                "log_usuario_respuesta" => $response,
            ];

            $this->logSistema($dataLog);
            return $this->apiResponseError("server_error", $response);
        }
    }
}
