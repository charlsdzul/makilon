<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CatPuestos extends Entity
{
    protected $table = 'cat_puestos';
    protected $primaryKey = 'catpu_sigla';
    protected $returnType = \App\Entities\CatPuestos::class;
    protected $datamap = [
        'sigla' => 'catpu_sigla',
        'descripcion' => 'catpu_descripcion',
    ];

}
