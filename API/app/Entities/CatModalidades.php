<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CatModalidades extends Entity
{   
        protected $table = 'cat_modalidades';
        protected $primaryKey = 'ctmo_id';
        //protected $allowedFields = ['ctmo_nombre','ctmo_sigla','ctmo_frase','ctmo_sta '];
        protected $returnType    = \App\Entities\CatModalidades::class;
        protected $datamap = [ 
                'id' => 'ctmo_id',
                'nombre' => 'ctmo_nombre',
                'sigla' => 'ctmo_sigla',     
                'frase' => 'ctmo_frase',            
            ];
    
}