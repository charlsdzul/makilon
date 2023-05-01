<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CatApartadosModel;
use App\Libraries\QueryParams;

class CatApartados extends ResourceController
{
	use ResponseTrait;
	protected $request;
	protected $prefijoCampos = "ctap_";
	protected $campos = ["ctap_id", "ctap_nombre", "ctap_sigla", "ctap_seccion_sigla"];
	protected $camposQueryParams = ["id", "nombre", "sigla", "seccion_sigla"];

	public function index()
	{
		$model = new CatApartadosModel();
		return $this->getIndexCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos);
	}

	public function show($id = 0)
	{
		$model = new CatApartadosModel();
		return $this->getShowCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos, $id);
	}
}