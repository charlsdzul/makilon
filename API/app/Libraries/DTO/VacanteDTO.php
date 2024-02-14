<?php
namespace App\Libraries\DTO;

class VacanteDTO
{
    public $titulo;
    public $puesto;
    public $puestoOtro;
    public $puestoEspecifico;
    public $puestoEspecificoOtro;

    public function __construct()
    {
        $this->titulo = "";
        $this->puesto = "";
        $this->puestoOtro = "";
        $this->puestoEspecifico = "";
        $this->puestoEspecificoOtro = "";
    }
}
