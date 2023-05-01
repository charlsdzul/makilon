<?php namespace App\Models;

use CodeIgniter\Model;

class CatModalidadesModel extends Model
{
	protected $table = "cat_modalidades";
	protected $primaryKey = "ctmo_id";
	protected $returnType = \App\Entities\CatModalidades::class;
}
