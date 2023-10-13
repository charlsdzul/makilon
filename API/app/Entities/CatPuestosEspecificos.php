<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CatPuestosEspecificos extends Entity
{
    protected $table = 'cat_puestos_especificos';
    protected $primaryKey = 'catpue_sigla';
    protected $returnType = \App\Entities\CatPuestosEspecificos::class;
    protected $datamap = [
        'sigla' => 'catpue_sigla',
        'descripcion' => 'catpue_descripcion',
    ];

}
