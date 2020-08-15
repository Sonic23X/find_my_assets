<?php

namespace App\Controllers;

class Activo extends BaseController
{

	protected $session;
  protected $activoModel;

  function __construct()
  {
    $this->session = \Config\Services::session( );
    $this->activoModel = model( 'App\Models\ActivoModel' );
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function SearchActivo( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
        $activo = $this->activoModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
                                    ->first( );

        if ( $activo )
          $json = array( 'status' => 200, 'activo' => $activo );
        else
          $json = array( 'status' => 401, 'msg' => 'El activo no se ha encontrado en el sistema' );

        echo json_encode( $json );
      }
      catch (\Exception $e)
      {
        echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function NewActivo( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {

        $insert =
        [
          'ID_Activo' => $this->request->getVar( 'codigo' ),
          'Nom_Activo' => $this->request->getVar( 'nombre' ),
          'ID_Tipo' => $this->request->getVar( 'tipo' ),
          'Des_Activo' => $this->request->getVar( 'descripcion' ),
          'NSerie_Activo' => $this->request->getVar( 'no_serie' ),
          'ID_CC' => $this->request->getVar( 'centro_costo' ),
          'User_Inventario' => $this->request->getVar( 'asignacion' ),
        ];

        if ( $this->activoModel->insert( $insert ) )
        {
          echo json_encode( array( 'status' => 200, 'msg' => '¡Activo registrado!' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al registrar el activo. Intente más tarde' ) );
      }
      catch (\Exception $e)
      {
        echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );
  }

  //método que funciona exclusivamente con AJAX - JQUERY
  function UpdateInfoActivo( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {

				$update =
        [
          'Nom_Activo' => $this->request->getVar( 'nombre' ),
          'ID_Tipo' => $this->request->getVar( 'tipo' ),
          'Des_Activo' => $this->request->getVar( 'descripcion' ),
          'NSerie_Activo' => $this->request->getVar( 'no_serie' ),
          'ID_CC' => $this->request->getVar( 'centro_costo' ),
          'User_Inventario' => $this->request->getVar( 'asignacion' ),
        ];

				if ( $this->activoModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( $update )->update( ) )
        {
          echo json_encode( array( 'status' => 200, 'msg' => '¡Activo registrado!' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar el activo. Intente más tarde' ) );

      }
      catch (\Exception $e)
      {
        echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );
  }

	//método que funciona exclusivamente con AJAX - JQUERY
  function SetCoordenadas( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {

				$update =
        [
          'GPS' => $this->request->getVar( 'gps' ),
          'ID_Area' => $this->request->getVar( 'area' ),
        ];

				if ( $this->activoModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( $update )->update( ) )
					echo json_encode( array( 'status' => 200 ) );
				else
					echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar el activo. Intente más tarde' ) );

      }
      catch (\Exception $e)
      {
        echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
      }
    }
    else
      return view( 'errors/cli/error_404' );
  }

}
