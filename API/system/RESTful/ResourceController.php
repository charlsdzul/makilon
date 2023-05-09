<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\RESTful;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;
use App\Entities\LogUsuario;
use App\Entities\LogSistema;
use App\Libraries\PlpxLogger;

use Error;
use stdClass;

/**
 * An extendable controller to provide a RESTful API for a resource.
 */
class ResourceController extends BaseResource
{
	use ResponseTrait;

	protected $codesPlpx = [
		"ok" => 200,
		"created" => 201,
		"deleted" => 200,
		"updated" => 200,
		"no_content" => 204,
		"invalid_request" => 400,
		"unsupported_response_type" => 400,
		"invalid_scope" => 400,
		"temporarily_unavailable" => 400,
		"invalid_grant" => 400,
		"invalid_credentials" => 400,
		"invalid_refresh" => 400,
		"no_data" => 400,
		"invalid_data" => 400,
		"access_denied" => 401,
		"unauthorized" => 401,
		"invalid_client" => 401,
		"forbidden" => 403,
		"resource_not_found" => 404,
		"not_acceptable" => 406,
		"resource_exists" => 409,
		"conflict" => 409,
		"resource_gone" => 410,
		"payload_too_large" => 413,
		"unsupported_media_type" => 415,
		"too_many_requests" => 429,
		"server_error" => 500,
		"unsupported_grant_type" => 501,
		"not_implemented" => 501,
	];

	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return Response|string|void
	 */
	public function index()
	{
		return $this->fail(lang("RESTful.notImplemented", ["index"]), 501);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @param int|string|null $id
	 *
	 * @return Response|string|void
	 */
	public function show($id = null)
	{
		return $this->fail(lang("RESTful.notImplemented", ["show"]), 501);
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return Response|string|void
	 */
	public function new()
	{
		return $this->fail(lang("RESTful.notImplemented", ["new"]), 501);
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return Response|string|void
	 */
	public function create()
	{
		return $this->fail(lang("RESTful.notImplemented", ["create"]), 501);
	}

	/**
	 * Return the editable properties of a resource object
	 *
	 * @param int|string|null $id
	 *
	 * @return Response|string|void
	 */
	public function edit($id = null)
	{
		return $this->fail(lang("RESTful.notImplemented", ["edit"]), 501);
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @param string|null|int$id
	 *
	 * @return Response|string|void
	 */
	public function update($id = null)
	{
		return $this->fail(lang("RESTful.notImplemented", ["update"]), 501);
	}

	/**
	 * Delete the designated resource object from the model
	 *
	 * @param int|string|null $id
	 *
	 * @return Response|string|void
	 */
	public function delete($id = null)
	{
		return $this->fail(lang("RESTful.notImplemented", ["delete"]), 501);
	}

	/**
	 * Set/change the expected response representation for returned objects
	 *
	 * @return void
	 */
	public function setFormat(string $format = "json")
	{
		if (in_array($format, ["json", "xml"], true)) {
			$this->format = $format;
		}
	}

	/**
	 ************************************************************************** FUNCIONES CUSTOMS PARA LA API
	 */
	public function validateId($id)
	{
		$rule = [
			"id" => "integer|max_length[10]|min_length[1]|greater_than[0]",
		];

		if (
			$this->validateData(
				[
					"id" => $id,
				],
				$rule
			)
		) {
			return true;
		} else {
			return false;
		}
	}

	public function validateQueryParams($uri, $request, $campos, &$errorMessage)
	{
		$order = $request->getGet("order");
		$ordeBy = $request->getGet("orderby");

		if ($order != "") {
			if (strcasecmp($order, "desc") && strcasecmp($order, "asc")) {
				$errorMessage = lang("CommonErrors.queryParamsInvalidos", ["'order'"]);
				return false;
			}
		}

		if ($ordeBy != "") {
			if (!in_array($ordeBy, $campos)) {
				$errorMessage = lang("CommonErrors.queryParamsInvalidos", ["'orderby'"]);
				return false;
			}
		}

		return true;
	}

	public function getUri()
	{
		return current_url(true);
	}

	public function getIndexCatalogo($model, $prefijoCampos, $camposQueryParams, $campos)
	{
		$errorMessage = "";
		$logger = new PlpxLogger();

		try {
			if (!$this->validateQueryParams($this->getUri(), $this->request, $camposQueryParams, $errorMessage)) {
				return $this->respondPlpx("invalid_request", $errorMessage, null);
			}

			$order = $this->request->getGet("order");
			$orderBy = $this->request->getGet("orderby");

			$builder = $model->where($prefijoCampos . "sta", 1);
			$builder = $model->orderBy($orderBy != "" ? $prefijoCampos . $orderBy : $prefijoCampos . "id", $order ?? "asc");
			$builder = $model->select($campos)->findAll();

			return $this->respondPlpx("ok", "", $builder);
		} catch (Error $e) {
			//log_message("error", $e);
			$logger->log("critical", $e, __LINE__);
			return $this->respondPlpx("server_error", lang("CommonErrors.solicitudNoProcesada"), null);
		}
	}

	public function getShowCatalogo($model, $prefijoCampos, $camposQueryParams, $campos, $id)
	{
		$logger = new PlpxLogger();

		try {
			if (!$this->validateQueryParams($this->getUri(), $this->request, $camposQueryParams, $errorMessage)) {
				return $this->respondPlpx("invalid_request", $errorMessage, null);
			}

			if (!$this->validateId($id)) {
				return $this->respondPlpx("invalid_request", lang("CommonErrors.idInvalido"), null);
			}

			$order = $this->request->getGet("order");
			$orderBy = $this->request->getGet("orderby");

			$builder = $model->where($prefijoCampos . "id", $id);
			$builder = $model->where($prefijoCampos . "sta", 1);
			$builder = $model->orderBy($orderBy != "" ? $prefijoCampos . $orderBy : $prefijoCampos . "id", $order ?? "asc");
			$builder = $model->select($campos)->findAll();

			return $this->respondPlpx("ok", "", $builder);
		} catch (Error $e) {
			//log_message("error", $e);
			$logger->log("critical", $e, __LINE__);
			return $this->respondPlpx("server_error", lang("CommonErrors.solicitudNoProcesada"), null);
		}
	}

	// public function respondPlpx($responseName, $message = "", $data = null)
	// {
	// 	$responseDesc = strtoupper($responseName);
	// 	$responseCode = $this->codesPlpx[$responseName];
	// 	//$message = lang($message);

	// 	if (is_string($message)) {
	// 		$message = lang($message);
	// 	}

	// 	$response[] = [
	// 		"code" => $responseCode,
	// 		"codeDescription" => $responseDesc,
	// 		"data" => $data,
	// 		//"message" => $message,
	// 	];

	// 	return $this->respond($response[0], $responseCode);
	// }

	public function respondPlpx($responseName, $data = null)
	{
		$responseDesc = strtoupper($responseName);
		$responseCode = $this->codesPlpx[$responseName];

		$isError = false;

		$errorsName = ["server_error", "invalid_request"];

		if (in_array($responseName, $errorsName)) {
			$isError = true;
		}

		$response = [
			"code" => $responseCode,
			"codeDescription" => $responseDesc,
		];

		if ($isError) {
			$response = [...$response, "errors" => [$data]];
		} else {
			$response = [...$response, "data" => [$data]];
		}

		return $this->respond($response, $responseCode);
	}

	public function apiResponse($responseName, $data = null)
	{
		$responseDesc = strtoupper($responseName);
		$responseCode = $this->codesPlpx[$responseName];

		$isError = false;

		$errorsName = ["server_error", "invalid_request"];

		if (in_array($responseName, $errorsName)) {
			$isError = true;
		}

		$response = [
			"code" => $responseCode,
			"codeDescription" => $responseDesc,
		];

		if ($isError) {
			$response = [...$response, "errors" => [$data]];
		} else {
			$response = [...$response, "data" => [$data]];
		}

		return $this->respond($response, $responseCode);
	}

	public function apiResponseError($responseName, $errors = null)
	{
		$responseDesc = strtoupper($responseName);
		$responseCode = $this->codesPlpx[$responseName];

		$response = new stdClass();
		$response->status = $responseCode;
		$response->description = $responseDesc;
		$response->errors = $errors;
		$response = (array) $response;

		return $this->respond($response, $responseCode);
	}

	// public function respondErrorPlpx($responseName, $errors = null)
	// {
	// 	$responseDesc = strtoupper($responseName);
	// 	$responseCode = $this->codesPlpx[$responseName];

	// 	$response[] = [
	// 		"code" => $responseCode,
	// 		"codeDescription" => $responseDesc,
	// 		"errors" => $errors,
	// 	];

	// 	return $this->respond($response[0], $responseCode);
	// }

	public function loggerPlpx($tipo, $mensaje, $logLine)
	{
		$logger = new PlpxLogger();
		$logger->log($tipo, $mensaje, $logLine);
	}
}
