<?php
namespace App\Libraries;

class PlpxValidaciones
{
	function validaCorreo($correo)
	{

		/**
		 * Valida un email.
		 */

		return filter_var($correo, FILTER_VALIDATE_EMAIL);
	}
}