<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioRecuperacionPasswordModel extends Model
{
	protected $table = "usuarios_recuperacion_password";
	protected $primaryKey = "usurecp_id";
	protected $allowedFields = [
		"usurecp_usu_id",
		"usurecp_usu_correo",
		"usurecp_codigo_recuperacion",
		"usurecp_created_at",
		"usurecp_psw_changed_at",
		"usurecp_expiry",
		"usurecp_email_sended_at",
		"usurecp_sta",
	];
	protected $useAutoIncrement = true;
	protected $useTimestamps = false;
	protected $createdField = "usurecp_created_at";

	protected $validationRules = [
		"usurecp_usu_id" => "required|integer|max_length[11]",
		"usurecp_usu_correo" => "required|valid_email|max_length[70]",
		"usurecp_codigo_recuperacion" => "required",
		"usurecp_created_at" => "required",
		"usurecp_sta" => "required|integer|exact_length[1]",
	];

	protected $returnType = \App\Entities\UsuarioRecuperacionPassword::class;
}
