<?php namespace App\Models;

use CodeIgniter\Model;

class LogSistemaModel extends Model
{
    protected $table = "logs_sistema";
    protected $primaryKey = "logsis_id";
    protected $allowedFields = [
        "logsis_tipo",
        "logsis_accion",
        "logsis_mensaje",
        "logsis_exception",
        "logsis_request_ip",
        "logsis_request_respond",
        "logsis_linea",
        "logsis_usuario_id",
        "logsis_controller",
        "logsis_method",
        "logsis_inserted_at",
    ];
    protected $useAutoIncrement = true;
    protected $useTimestamps = false;
    protected $insertedField = "logsis_inserted_at";
    protected $returnType = \App\Entities\LogSistema::class;
}
