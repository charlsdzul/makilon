<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Vacante extends Entity
{
    protected $table = 'vac_vacante';
    protected $primaryKey = 'vac_id';
    protected $returnType = \App\Entities\Vacante::class;
    protected $datamap = [
        'sigla' => 'catpu_sigla',
        'descripcion' => 'catpu_descripcion',
    ];

}
