<?php namespace App\Models;

use CodeIgniter\Model;

class CatPuestosEspecificosModel extends Model
{
    protected $table = "cat_puestos_especificos";
    protected $primaryKey = "catpue_sigla";
    protected $returnType = \App\Entities\CatPuestosEspecificos::class;
}
