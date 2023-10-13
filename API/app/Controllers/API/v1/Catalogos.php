<?php

namespace App\Controllers\API\v1;

use App\Models\CatPuestosEspecificosModel;
use App\Models\CatPuestosModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Catalogos extends ResourceController
{
    use ResponseTrait;
    protected $request;

    public function puestos()
    {
        $model = new CatPuestosModel();
        return $this->getIndexCatalogo($model, "catpu_", ["descripcion", "sigla"], ["catpu_sigla", "catpu_descripcion"]);
    }

    public function puestosEspecificos()
    {
        $model = new CatPuestosEspecificosModel();
        return $this->getIndexCatalogo($model, "catpue_", ["descripcion", "sigla"], ["catpue_sigla", "catpue_descripcion"]);
    }

    // public function puesto($id = 0)
    // {
    //     $model = new CatPuestosModel();
    //     return $this->getShowCatalogo($model, $this->prefijoCampos, $this->camposQueryParams, $this->campos, $id);
    // }
}
