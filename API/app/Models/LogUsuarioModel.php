<?php namespace App\Models;

use CodeIgniter\Model;

class LogUsuarioModel extends Model
{
	protected $table = "logs_usuarios";
	protected $primaryKey = "log_id";
	protected $allowedFields = [
		"log_usuario_id",
		"log_usuario",
		"log_usuario_correo",
		"log_accion",
		"log_mensaje",
		"log_mensaje_json",
		"log_request_ip",
		"log_request_respond",
		"log_linea",
		"log_controller",
		"log_method",
		"log_inserted_at",
	];
	protected $useAutoIncrement = true;
	protected $useTimestamps = false;
	protected $insertedField = "log_inserted_at";
	protected $returnType = \App\Entities\LogUsuario::class;
}
