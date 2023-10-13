<?php namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CatCantAnuncios extends Entity
{   
        protected $table = 'cat_cant_anuncios';
        protected $primaryKey = 'ctcaan_id';
        //protected $allowedFields = ['ctcaan_cantidad','ctcaan_sta'];
        protected $returnType    = \App\Entities\CatCantAnuncios::class;
        protected $datamap = [ 
                'id' => 'ctcaan_id',
                'cantidad' => 'ctcaan_cantidad',
            ];
    
}