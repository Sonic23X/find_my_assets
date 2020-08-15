<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivoModel extends Model
{

  protected $table      = 'activos';
  protected $primaryKey = 'id';

  protected $allowedFields =
  [
    'ID_Activo', 'Nom_Activo', 'BC_Activo', 'ID_Company', '	ID_Sucursal',
    'ID_Area', 'ID_CC', 'ID_Asignado', 'ID_Proceso', 'ID_Status', 'Fec_Compra',
    'Img_FacCompra', 'Pre_Compra', 'Fec_Expira', 'Img_Garantia',
    'NSerie_Activo', 'ID_Tipo', 'Des_Activo', 'Fec_InicioDepre', 'ID_MetDepre',
    'Vida_Activo', 'GPS', 'Fec_Inventario', 'User_Inventario', 'Comentarios',
    'Ima_Activo0', 'Ima_ActivoLeft', 'Ima_ActivoRight', '	Ima_ActivoFront',
    'Ima_ActivoBack', 'User_Create', 'User_Update', '	User_Delete',
    'TS_Create', 'TS_Update', 'TS_Delete'
  ];

}
