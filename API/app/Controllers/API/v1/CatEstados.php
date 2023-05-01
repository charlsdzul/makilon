<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CatEstadosModel;

class CatEstados extends ResourceController
{
	use ResponseTrait;
	protected $request;
	protected $prefijoCampos = "ctes_";
	protected $campos = ["ctes_id", "ctes_nombre", "ctes_sigla"];
	protected $camposQueryParams = ["id", "nombre", "sigla"];

	public function index()
	{
		$model = new CatEstadosModel();
		return $this->getIndexCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos);
	}

	public function show($id = 0)
	{
		$model = new CatEstadosModel();
		return $this->getShowCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos, $id);
	}
}