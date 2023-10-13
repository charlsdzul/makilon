<?php namespace App\Models;

use CodeIgniter\Model;

class CatEstadosModel extends Model
{
	protected $table = "cat_estados";
	protected $primaryKey = "ctes_id";
	protected $returnType = \App\Entities\CatEstados::class;
}
