<?php namespace App\Models;

use CodeIgniter\Model;

class CatMunicipiosModel extends Model
{
	protected $table = "cat_municipios";
	protected $primaryKey = "ctmu_id";
	protected $returnType = \App\Entities\CatMunicipios::class;
}
