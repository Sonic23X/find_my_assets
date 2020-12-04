<?php

namespace App\Models;

use CodeIgniter\Model;

class CCModel extends Model
{

  protected $table      = 'cc';
  protected $primaryKey = 'id';

  protected $allowedFields =
  [
    'ID_CC', 'Desc', 'Subcuenta', 'id_empresa',
  ];

}
