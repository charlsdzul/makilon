<?php namespace App\Models;

use CodeIgniter\Model;

class CatApartadosModel extends Model
{
	protected $table = "cat_apartados";
	protected $primaryKey = "ctap_id";
	protected $returnType = \App\Entities\CatApartados::class;
}
