<?php

namespace App\Controllers\API\v1;

use App\Libraries\DTO\VacanteDTO;
use App\Libraries\Validators\VacanteValidator;
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

    public function logSistema($args)
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

            $requestBody = $this->request->getJsonVar();
            $vacante = new VacanteDTO();
            populateObject($vacante, $requestBody);

            //var_dump($vacante);

            $validationResult = VacanteValidator::validateNuevaVacante($vacante);

            //$errorsValidator = vacanteValidator($vacante);

            if (count($validationResult) > 0) {
                //Se guarda LOG porque, en teoria, no deberia tener errores en el validator,
                //porque en el front ya estan la validaciones.
                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "warning", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "No pasó las validaciones del VALIDATOR.",
                    "log_request_respond" => "invalid_request",
                    "log_exception" => json_encode($validationResult),
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", $validationResult);
            }

            //PENDIENTE SANITIZAR Y VALIDAR CAMPOS DE ENTRADA

            $dataToken = getDataTokenFromRequest($this->request);

            $data = [
                "vac_titulo" => $requestBody["titulo"],
                "vac_puesto" => $requestBody["puesto"],
                "vac_puesto_otro" => $requestBody["puestoOtro"],
                "vac_puesto_especifico" => $requestBody["puestoEspecifico"],
                "vac_puesto_especifico_otro" => $requestBody["puestoEspecificoOtro"],
                "vac_creado_sta" => 1,
                "vac_sta" => 0,
                "vac_usuario_id" => $dataToken->id,
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
            $vacanteModel = new VacanteModel();
            $builder = $vacanteModel->builder();

            // SORTER
            $sorter = $this->request->getVar('sorter');
            $orderField = $sorter["field"] ?? "";
            $orderOption = $sorter["order"] ?? "";

            if ($orderOption == "ascend") {
                $orderOption = "ASC";
            } else if ($orderOption == "descend") {
                $orderOption = "DESC";
            } else {
                $orderOption = "";
            }

            $db = db_connect();
            if ($db->fieldExists($orderField, 'vac_vacantes')) {
                $builder->orderBy($orderField, $orderOption);
            }

            // PAGINATION
            $pagination = $this->request->getVar('pagination');
            $page = $pagination["page"] ?? 1;
            $perPage = $pagination["perPage"] ?? 20;
            $offset = ($page - 1) * $perPage;
            $query = $builder->get($perPage, $offset);

            //FILTER

            //GET RESULTS
            $vacantes = $query->getResult();

            //COUNT RESULTS
            $totalCount = $builder->countAllResults(false); // $query->getNumRows();

            $response = [
                "results" => $vacantes,
                "totalCount" => $totalCount,
            ];

            return $this->apiResponse("ok", $response);
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
    public function actions($vacanteId = null)
    {
        $logAccion = "Vacantes Actions";

        try {
            $requestBody = $this->request->getJsonVar(["action"]);
            $action = $requestBody["action"];
            $dataToken = getDataTokenFromRequest($this->request);

            if (is_null($dataToken)) {

                $response = getErrorResponseByCode(["code" => 2200]);

                // $dataLog = [
                //     "log_origen" => "usuario", "log_tipo" => "notice", "log_accion" => $logAccion, "log_linea" => __LINE__,
                //     "log_mensaje" => "No se pudo obtener data del Token.",
                //     "log_request_respond" => "invalid_request",
                //     "log_usuario_respuesta" => $response,
                // ];

                // $this->logSistema($dataLog);
                // return $this->apiResponseError("invalid_request", [$response]);
            }

            if (is_null($action)) {

                $response = getErrorResponseByCode(["code" => 2200]);

                // $dataLog = [
                //     "log_origen" => "usuario", "log_tipo" => "notice", "log_accion" => $logAccion, "log_linea" => __LINE__,
                //     "log_mensaje" => "No se recibio ID de la vacante.: " . $vacanteId,
                //     "log_request_respond" => "invalid_request",
                //     "log_usuario_respuesta" => $response,
                // ];

                // $this->logSistema($dataLog);
                // return $this->apiResponseError("invalid_request", [$response]);
            }

            if (is_null($vacanteId)) {

                $response = getErrorResponseByCode(["code" => 2200]);

                // $dataLog = [
                //     "log_origen" => "usuario", "log_tipo" => "notice", "log_accion" => $logAccion, "log_linea" => __LINE__,
                //     "log_mensaje" => "No se recibio ID de la vacante.: " . $vacanteId,
                //     "log_request_respond" => "invalid_request",
                //     "log_usuario_respuesta" => $response,
                // ];

                // $this->logSistema($dataLog);
                // return $this->apiResponseError("invalid_request", [$response]);
            }

            switch ($action) {
                case "VACANTE_PERTENECE_USUARIO":
                    {
                        $usuarioId = $dataToken->id;
                        $vacanteModel = new VacanteModel();
                        $vacante = $vacanteModel
                            ->select("vac_id")->where("vac_id", $vacanteId)->where("vac_usuario_id", $usuarioId)->first();

                        if (is_null($vacante)) {
                            $response = getErrorResponseByCode(["code" => 2201]);
                            return $this->apiResponseError("invalid_request", [...$response]);
                        }

                        $response = getErrorResponseByCode(["code" => 2202]);
                        return $this->apiResponse("ok", [...$response]);
                    }
                default:
                    $response = getErrorResponseByCode(["code" => 201]);
                    return $this->apiResponseError("invalid_request", [...$response]);
            }

        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            $response = getErrorResponseByCode(["code" => 201]);

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

    public function vacante($vacanteId = null)
    {
        $logAccion = "Obtener Vacante";

        try {

            $dataToken = getDataTokenFromRequest($this->request);

            if (is_null($dataToken)) {

                $response = getErrorResponseByCode(["code" => 2200]);

                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "notice", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "No se pudo obtener data del Token.",
                    "log_request_respond" => "invalid_request",
                    "log_usuario_respuesta" => $response,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            if (is_null($vacanteId)) {

                $response = getErrorResponseByCode(["code" => 2200]);

                $dataLog = [
                    "log_origen" => "usuario", "log_tipo" => "notice", "log_accion" => $logAccion, "log_linea" => __LINE__,
                    "log_mensaje" => "No se recibio ID de la vacante.: " . $vacanteId,
                    "log_request_respond" => "invalid_request",
                    "log_usuario_respuesta" => $response,
                ];

                $this->logSistema($dataLog);
                return $this->apiResponseError("invalid_request", [$response]);
            }

            $usuarioId = $dataToken->id;
            $vacanteModel = new VacanteModel();
            $vacante = $vacanteModel
                ->select("vac_id,vac_titulo,vac_puesto,vac_puesto_otro,vac_puesto_especifico,vac_puesto_especifico_otro")->where("vac_id", $vacanteId)->where("vac_usuario_id", $usuarioId)->first();

            if (is_null($vacante)) {
                $response = getErrorResponseByCode(["code" => 2201]);
                return $this->apiResponseError("invalid_request", [...$response]);
            }

            $response = getErrorResponseByCode(["code" => 2202]);
            return $this->apiResponse("ok", $vacante);
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
            $response = getErrorResponseByCode(["code" => 2200]);

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
