<?php namespace App\Models;

use CodeIgniter\Model;

class VacanteModel extends Model
{
    protected $table = "vac_vacante";
    protected $primaryKey = "vac_id";
    protected $returnType = \App\Entities\Vacante::class;
}
