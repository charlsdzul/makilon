<?php namespace App\Models;

use CodeIgniter\Model;

class VacanteModel extends Model
{
    protected $table = "vac_vacante";
    protected $primaryKey = "vac_id";
    protected $allowedFields = [
        "vac_titulo",
        "vac_puesto",
        "vac_puesto_otro",
        "vac_puesto_especifico",
        "vac_puesto_especifico_otro",
        "vac_sta",
        "vac_creado_sta",
    ];
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $createdField = "vac_created_at";
    protected $updatedField = "vac_updated_at";
    protected $deletedField = "vac_deleted_at";
    protected $validationRules = [
        "vac_titulo" => "string|required|max_length[60]",
        "vac_puesto" => "string|max_length[20]",
        "vac_puesto_otro" => "string|max_length[20]",
        "vac_puesto_especifico" => "string|max_length[20]",
        "vac_puesto_especifico_otro" => "string|max_length[20]",
        "vac_sta" => "integer|required|exact_length[1]",
        "vac_creado_sta" => "integer|required|exact_length[1]",
    ];
    protected $returnType = \App\Entities\Vacante::class;
}
