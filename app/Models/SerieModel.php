<?php

namespace App\Models;

use CodeIgniter\Model;

class SerieModel extends Model
{

  protected $table      = 'numseriehistorial';
  protected $primaryKey = 'id';

  protected $allowedFields =
  [
    'id_activo', 'id_draft', 'num_serie', 
  ];

}
