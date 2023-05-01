<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CatModalidadesModel;

class CatModalidades extends ResourceController
{
	use ResponseTrait;
	protected $request;
	protected $prefijoCampos = "ctmo_";
	protected $campos = ["ctmo_id", "ctmo_nombre", "ctmo_sigla", "ctmo_frase"];
	protected $camposQueryParams = ["id", "nombre", "sigla", "frase"];


	public function index()
	{
		$model = new CatModalidadesModel();
		return $this->getIndexCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos);
	}

	public function show($id = 0)
	{
		$model = new CatModalidadesModel();
		return $this->getShowCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos, $id);
	}
}