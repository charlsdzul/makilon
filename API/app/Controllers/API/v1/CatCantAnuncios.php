<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CatCantAnunciosModel;

class CatCantAnuncios extends ResourceController
{
	use ResponseTrait;
	protected $request;
	protected $prefijoCampos = "ctcaan_";
	protected $campos = ["ctcaan_id", "ctcaan_cantidad"];
	protected $camposQueryParams = ["id", "cantidad"];

	public function index()
	{
		$model = new CatCantAnunciosModel();
		return $this->getIndexCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos);
	}

	public function show($id = 0)
	{
		$model = new CatCantAnunciosModel();
		return $this->getShowCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos, $id);
	}
}
