<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CatEstados extends Entity
{   
        protected $table = 'cat_estados';
        protected $primaryKey = 'ctes_id';
        //protected $allowedFields = ['ctcaan_cantidad','ctcaan_sta'];
        protected $returnType    = \App\Entities\CatEstados::class;
        protected $datamap = [ 
                'id' => 'ctes_id',
                'nombre' => 'ctes_nombre',
                'sigla' => 'ctes_sigla',               
            ];
    
}