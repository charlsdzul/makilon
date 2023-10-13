<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CatSeccionesModel;

class CatSecciones extends ResourceController
{
	use ResponseTrait;
	protected $request;
	protected $prefijoCampos = "ctse_";
	protected $campos = ["ctse_id", "ctse_nombre", "ctse_sigla"];
	protected $camposQueryParams = ["id", "nombre", "sigla"];

	public function index()
	{
		$model = new CatSeccionesModel();
		return $this->getIndexCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos);
	}

	public function show($id = 0)
	{
		$model = new CatSeccionesModel();
		return $this->getShowCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos, $id);
	}
}