<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Vacante extends Entity
{
    protected $table = 'vac_vacante';
    protected $primaryKey = 'vac_id';
    protected $returnType = \App\Entities\Vacante::class;
    protected $datamap = [
        'id' => 'vac_id',
        'titulo' => 'vac_titulo',
    ];

}
