<?php

namespace App\Controllers;

class Activo extends BaseController
{

	protected $session;
	protected $userModel;
	protected $tipoModel;
	protected $empresaModel;
	protected $draftModel;

  function __construct()
  {
    $this->session = \Config\Services::session( );
		$this->tipoModel = model( 'App\Models\TipoModel' );
		$this->userModel = model( 'App\Models\UserModel' );
		$this->empresaModel = model( 'App\Models\EmpresaModel' );
		$this->draftModel = model( 'App\Models\DraftModel' );
  }

	//método que funciona exclusivamente con AJAX - JQUERY
  function GetDataForm( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
				$tipos = $this->tipoModel->findAll( );
				$usuarios = $this->userModel->findAll( );
				$empresas = $this->empresaModel->findAll( );

        if ( $tipos )
          $json = array( 'status' => 200, 'types' => $tipos, 'users' => $usuarios, 'empresas' => $empresas );
        else
          $json = array( 'status' => 401, 'msg' => 'No se pudo obtener la informacion del servidor' );

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
  function SearchActivo( )
  {
    if ( $this->request->isAJAX( ) )
    {
      try
      {
				$campos =
				[
					'ID_Activo', 'Nom_Activo', 'BC_Activo', 'ID_Company', 'ID_Sucursal',
			    'ID_Area', 'ID_CC', 'ID_Asignado', 'ID_Proceso', 'ID_Status', 'Fec_Compra',
			    'Pre_Compra', 'Fec_Expira', 'NSerie_Activo', 'ID_Tipo',
					'Des_Activo', 'Fec_InicioDepre', 'ID_MetDepre',
			    'Vida_Activo', 'GPS', 'Fec_Inventario', 'User_Inventario', 'Comentarios',
					'User_Create', 'User_Update', '	User_Delete',
			    'TS_Create', 'TS_Update', 'TS_Delete'
				];

        $activo = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
																	 ->select( $campos )
                                   ->first( );

				if ( $activo == null )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Activo no encontrado' ) );
					return;
				}

				$user = $this->userModel->where( 'id_usuario', $activo[ 'User_Inventario' ] )->first( );


				$tipo = $this->tipoModel->where( 'id', $activo[ 'ID_Tipo' ] )->first( );

        echo json_encode( array( 'status' => 200, 'activo' => $activo, 'user' => $user, 'tipo' => $tipo ) );

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
	function ValidateActivo( )
	{
		if ( $this->request->isAJAX( ) )
		{
			$already = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
																	->first( );

			if ( $already )
				echo json_encode( array( 'status' => 400, 'msg' => 'Ya existe un activo con este ID' ) );
			else
				echo json_encode( array( 'status' => 200 ) );
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
				//validar que no exista un activo con ese id
				// TODO: anexar ID empresa
				$already = $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )
																		->first( );

				if ( $already )
				{
					echo json_encode( array( 'status' => 400, 'msg' => 'Ya existe un activo con este ID' ) );
					return;
				}

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

        if ( $this->draftModel->insert( $insert ) )
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

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( $update )->update( ) )
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
					'Vida_Activo' => $this->request->getVar( 'vida' ),
					'ID_Company' => $this->request->getVar( 'empresa' ),
					'ID_Sucursal' => $this->request->getVar( 'sucursal' ),
          'ID_Area' => $this->request->getVar( 'area' ),
        ];

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'codigo' ) )->set( $update )->update( ) )
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

	//método que funciona exclusivamente con AJAX - JQUERY
	function GetImageFront( $codigo )
	{
		if ( $this->request->isAJAX( ) )
    {
      try
      {
				$activo = $this->draftModel->where( 'ID_Activo', $codigo )
																	 ->select( [ 'Ima_ActivoFront' ] )
                                   ->first( );

				$imgFront = null;

				if ( $activo[ 'Ima_ActivoFront' ] != null )
				{
					$dataImage = 'data:image/jpeg;base64,'. base64_encode( $activo[ 'Ima_ActivoFront' ] );

					$imgFront = '<img id="front-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageFront( )">';
				}

				echo $imgFront;
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
	function GetImageRight( $codigo )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$activo = $this->draftModel->where( 'ID_Activo', $codigo )
																	 ->select( [ 'Ima_ActivoRight' ] )
																	 ->first( );

				$imgRight = null;

				if ( $activo[ 'Ima_ActivoRight' ] != null )
				{
					$dataImage = 'data:image/jpeg;base64,'. base64_encode( $activo[ 'Ima_ActivoRight' ] );

					$imgRight = '<img id="right-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageRight( )">';
				}

				echo $imgRight;
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
	function GetImageLeft( $codigo )
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$activo = $this->draftModel->where( 'ID_Activo', $codigo )
																	 ->select( [ 'Ima_ActivoLeft' ] )
																	 ->first( );

				$imgLeft = null;

				if ( $activo[ 'Ima_ActivoLeft' ] != null )
				{
					$dataImage = 'data:image/jpeg;base64,'. base64_encode( $activo[ 'Ima_ActivoLeft' ] );

					$imgLeft = '<img id="left-image" class="img-fluid" style="height: 100px; width: 100px;" src="'. $dataImage .'" onclick="viewImageLeft( )">';
				}

				echo $imgLeft;
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
	function SetImage( )
	{
		if ( $this->request->isAJAX( ) )
    {
      try
      {
				// TODO: Conseguir imagen anterior, y si existe, borrarla
				// TODO: Conseguir el id de la empresa
				$update = [ ];

				$photo = $this->request->getFile( 'file' );

				$image = file_get_contents( $photo->getTempName( )  );

				switch ( $this->request->getVar( 'type' ) )
				{
					case 'front':
						$update =
						[
							'Ima_ActivoFront' => $image,
						];
						break;
					case 'right':
						$update =
						[
							'Ima_ActivoRight' => $image,
						];
						break;
					case 'left':
						$update =
						[
							'Ima_ActivoLeft' => $image,
						];
						break;
				}

				if ( $this->draftModel->where( 'ID_Activo', $this->request->getVar( 'activo' ) )->set( $update )->update( ) )
        {
          echo json_encode( array( 'status' => 200, 'msg' => '¡Imagen actualizada!' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar la imagen del activo. Intente más tarde' ) );

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
	function DeleteImage( )
	{
		if ( $this->request->isAJAX( ) )
    {
      try
      {
				$activo = $this->request->getVar( 'codigo' );
				$tipo;

				switch ( $this->request->getVar( 'type' ) )
				{
					case 'front':
						$tipo = 'Ima_ActivoFront';
						break;
					case 'right':
						$tipo = 'Ima_ActivoRight';
						break;
					case 'left':
						$tipo = 'Ima_ActivoLeft';
						break;
				}

				$update =
				[
					$tipo => null
				];

				if ( $this->draftModel->where( 'ID_Activo', $activo )->set( $update )->update( ) )
        {
          echo json_encode( array( 'status' => 200, 'msg' => '¡Imagen eliminada!' ) );
        }
        else
          echo json_encode( array( 'status' => 400, 'msg' => 'Error al eliminar la imagen del activo. Intente más tarde' ) );

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
