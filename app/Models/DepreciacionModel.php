<?php

namespace App\Models;

use CodeIgniter\Model;

class DepreciacionModel extends Model
{

  protected $table      = 'depreciacion';
  protected $primaryKey = 'id';

  protected $allowedFields =
  [
    'ID_Depre', 'Metodo', 'Observaciones',
  ];

}
