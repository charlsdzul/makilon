<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CatSecciones extends Entity
{
	protected $table = "cat_secciones";
	protected $primaryKey = "ctse_id";
	protected $returnType = \App\Entities\CatSecciones::class;
	protected $datamap = [
		"id" => "ctse_id",
		"nombre" => "ctse_nombre",
		"sigla" => "ctse_sigla",
	];
}
