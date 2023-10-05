<?php namespace App\Models;

use CodeIgniter\Model;

class CatPuestosModel extends Model
{
    protected $table = "cat_puestos";
    protected $primaryKey = "catpu_sigla";
    protected $returnType = \App\Entities\CatPuestos::class;
}
