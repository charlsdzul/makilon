<?php namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = "logs";
    protected $primaryKey = "log_id";
    protected $allowedFields = [
        "log_tipo",
        "log_accion",
        "log_linea",
        "log_controller",
        "log_method",
        "log_mensaje",
        "log_exception",
        "log_request_respond",
        "log_usuario_id",
        "log_usuario_correo",
        "log_origen",
        "log_request_ip",
        "log_usuario",
        "log_usuario_respuesta",
    ];
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $createdField = "log_created_at";
    protected $updatedField = "log_updated_at";
    protected $deletedField = "log_deleted_at";
    protected $returnType = \App\Entities\Log::class;
}
