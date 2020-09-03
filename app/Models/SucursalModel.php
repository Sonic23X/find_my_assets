<?php

namespace App\Models;

use CodeIgniter\Model;

class SucursalModel extends Model
{

  protected $table      = 'sucursales';
  protected $primaryKey = 'id';

  protected $allowedFields =
  [
    'ID_Empresa', 'Desc', 'Calle', 'Numero', 'Comuna',
    'Ciudad', 'EstReg', 'Pais', 'Postal',
  ];

}
