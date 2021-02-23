<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{

  protected $table      = 'usuarios';
  protected $primaryKey = 'id_usuario';

  protected $allowedFields =
  [
    'perfil', 'nombre', 'apellidos', 'email', 'password',
    'suscripcion', 'verificacion', 'email_encriptado',
    'patrocinador', 'deleted_at', 'id_empresa', 'envios',
  ];

}
