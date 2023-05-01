<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioPreregistroModel extends Model
{
	protected $table = "usuarios_preregistro";
	protected $primaryKey = "usupr_id";
	protected $allowedFields = [
		"usupr_correo",
		"usupr_password",
		"usupr_codigo_activacion",
		"usupr_created_at",
		"usupr_updated_at",
		"usupr_email_sended_at",
		"usupr_confirmed_at",
		"usupr_sta",
	];
	protected $useAutoIncrement = true;
	protected $useTimestamps = false;
	protected $createdField = "usupr_created_at";
	protected $updatedField = "usupr_updated_at";
	protected $deletedField = "usupr_deleted_at";

	protected $validationRules = [
		"usupr_correo" => "string|required|valid_email|max_length[70]",
		"usupr_password" => "required|max_length[255]",
		"usupr_codigo_activacion" => "required",
		"usupr_created_at" => "required",
		"usupr_sta" => "required|integer|exact_length[1]",
	];

	protected $returnType = \App\Entities\UsuarioPreregistro::class;
}
