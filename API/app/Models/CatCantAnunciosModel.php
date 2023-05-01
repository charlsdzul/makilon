<?php namespace App\Models;

use CodeIgniter\Model;

class CatCantAnunciosModel extends Model
{
	protected $table = "cat_cant_anuncios";
	protected $primaryKey = "ctcaan_id";
	protected $returnType = \App\Entities\CatCantAnuncios::class;
}
