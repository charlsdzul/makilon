<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Vacante extends Entity
{
    protected $table = 'vac_vacantes';
    protected $primaryKey = 'vac_id';
    protected $returnType = \App\Entities\Vacante::class;
    protected $datamap = [
        'id' => 'vac_id',
        'titulo' => 'vac_titulo',
        'puesto' => 'vac_puesto',
        'puesto_otro' => 'vac_puesto_otro',
        'puesto_especifico' => 'vac_puesto_especifico',
        'puesto_especifico_otro' => 'vac_puesto_especifico_otro',

    ];

}
