<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpresaModel extends Model
{

  protected $table      = 'empresas';
  protected $primaryKey = 'id_empresa';

  protected $allowedFields =
  [
    'id_usuario', 'nombre', 'rfc', 'razonsocial', 'ciec_pass',
    'key_file', 'cer_file', 'fea_pass', 'default'
  ];

}
