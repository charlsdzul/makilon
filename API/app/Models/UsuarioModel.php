<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
	protected $table = "usuarios";
	protected $primaryKey = "usu_id";
	protected $allowedFields = [
		"usu_nombre",
		"usu_correo",
		"usu_password",
		"usu_tipo",
		"usu_sta",
		"usu_created_at",
		"usu_updated_at",
		"usu_id_preregistro",
	];
	protected $useAutoIncrement = true;
	protected $useTimestamps = false;
	protected $createdField = "usu_created_at";
	protected $updatedField = "usu_updated_at";
	protected $deletedField = "usu_deleted_at";
	protected $validationRules = [
		"usu_usuario" => "string|alpha_numeric|max_length[20]",
		"usu_nombre" => "string|max_length[50]|regex_match[" . REGEX_ALPHA_NUM_SPA . "]",
		"usu_apellido" => "string|max_length[50]|regex_match[" . REGEX_ALPHA_NUM_SPA . "]",
		"usu_correo" => "string|required|valid_email|max_length[70]",
		"usu_password" => "required|max_length[255]",
		"usu_tipo" => "required|integer|exact_length[1]",
		"usu_sta" => "required|integer|exact_length[1]",
		"usu_created_at" => "required",
		"usu_id_preregistro" => "required|integer|max_length[11]",
	];
	protected $returnType = \App\Entities\Usuario::class;
}
