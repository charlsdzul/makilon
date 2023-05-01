<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CatApartados extends Entity
{   
        protected $table = 'cat_apartados';
        protected $primaryKey = 'ctap_id';
        //protected $allowedFields = ['ctap_nombre','ctap_sigla','ctap_sta','ctap_seccion_sigla'];
        protected $returnType    = \App\Entities\CatApartados::class;
        protected $datamap = [ 
                'id' => 'ctap_id',
                'nombre' => 'ctap_nombre',
                'sigla' => 'ctap_sigla',
                'seccion_sigla' => 'ctap_seccion_sigla',
            ];
    
}