<?php namespace App\Models;

use CodeIgniter\Model;

class CatSeccionesModel extends Model
{
	protected $table = "cat_secciones";
	protected $primaryKey = "ctse_id";
	protected $returnType = \App\Entities\CatSecciones::class;
}
