<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CatMunicipiosModel;

class CatMunicipios extends ResourceController
{
	use ResponseTrait;
	protected $request;
	protected $prefijoCampos = "ctmu_";
	protected $campos = ["ctmu_id", "ctmu_nombre", "ctmu_sigla", "ctmu_estado_sigla"];
	protected $camposQueryParams = ["id", "nombre", "sigla", "estado_sigla"];

	public function index()
	{
		$model = new CatMunicipiosModel();
		return $this->getIndexCatalogo($model,$this->prefijoCampos,$this->camposQueryParams,$this->campos);
	}
	
	public function show($id = 0)
	{
		$model = new CatMunicipiosModel();
		return $this->getShowCatalogo($model,$this->prefijoCampos,$this->camposQueryParams,$this->campos,$id);
	}
}
