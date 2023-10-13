<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CatMunicipios extends Entity
{
	protected $table = "cat_municipios";
	protected $primaryKey = "ctmu_id";
	protected $returnType = \App\Entities\CatMunicipios::class;
	protected $datamap = [
		"id" => "ctmu_id",
		"nombre" => "ctmu_nombre",
		"sigla" => "ctmu_sigla",
		"estado_sigla" => "ctmu_estado_sigla",
	];
}
