<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoModel extends Model
{

  protected $table      = 'tipos';
  protected $primaryKey = 'id';

  protected $allowedFields =
  [
    '	Desc', 'ID_Pais', 

  ];

}
