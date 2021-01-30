<?php

namespace App\Controllers;

class Company extends BaseController
{
    protected $session;
	protected $userModel;
	protected $empresaModel;
	protected $sucursalModel;
	protected $ccModel;
	protected $areaModel;
	protected $db;

	function __construct()
	{
    	$this->session = \Config\Services::session( );
		$this->userModel = model( 'App\Models\UserModel' );
		$this->empresaModel = model( 'App\Models\EmpresaModel' );
		$this->db = \Config\Database::connect();
	}

    function Index()
    {
        if ( $this->session->has( 'isLoggin' ) && $this->session->has( 'tipo' ) && $this->session->tipo == 'admin')
		{
			//CSS, METAS y titulo
			$head = array( 'title' => 'Empresas | Find my assets', 'css' => 'dashboard' );
			echo view( 'backoffice/common/head', $head );

			//sidebar
			$sidebar = array( 'name' => $this->session->name );
			echo view( 'backoffice/common/sidebar', $sidebar );

			//navbar
			echo view( 'backoffice/common/navbar' );

			//content - companies

			$SQL = "SELECT empresas.* FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
			$builder = $this->db->query( $SQL );
			$empresas = $builder->getResult( );

			$usuarios = [];
			$periodo_activo = [];


			foreach ($empresas as $empresa) 
			{
				$SQL = "SELECT count(id_usuario) as users FROM user_empresa WHERE user_empresa.id_empresa = " . $empresa->id_empresa;
				$builder = $this->db->query( $SQL );
				$users = $builder->getResult();

				array_push($usuarios, $users[0]->users);

				$SQL = "SELECT * FROM empresa_periodo WHERE id_empresa = " . $empresa->id_empresa . " AND status = 1";
				$builder = $this->db->query( $SQL );
				$periodo = $builder->getResult();

				if ($periodo != null) 
				{
					array_push($periodo_activo, ['inicio' => $periodo[0]->fecha_inicio, 'fin' => $periodo[0]->fecha_fin]);
				}
				else
				{
					array_push($periodo_activo, ['inicio' => null, 'fin' => null]);
				}
			}

			$data =
			[
				'companies' => $empresas,
				'users' => $usuarios,
				'fechas' => $periodo_activo,
			];
			echo view( 'backoffice/sections/companies', $data );

			//Scripts y librerias
			$footer = array( 'js' => 'empresa' );
			echo view( 'backoffice/common/footer2', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingreso' ) );
			return view( 'functions/redirect', $data );
		}
	}
	
	function ChangeImage()
	{
		if ( $this->request->isAJAX( ) )
		{
			try
			{
				$photo = $this->request->getFile( 'file' );

				$image = file_get_contents( $photo->getTempName( ) );

				$update =
				[
					'photo' => $image,
				];

				if ( $this->empresaModel->where( 'id_empresa', $this->request->getVar( 'id' ) )->set( $update )->update( ) )
				{
					echo json_encode( array( 'status' => 200, 'msg' => '¡Logo actualizado!' ) );
				}
				else
					echo json_encode( array( 'status' => 400, 'msg' => 'Error al actualizar el logo. Intente más tarde' ) );

			}
			catch (\Exception $e)
			{
				echo json_encode( array( 'status' => 400, 'msg' => $e->getMessage( ) ) );
			}
		}
		else
			return view( 'errors/cli/error_404' );
	}

	function UpdateCompany()
	{
		if ( $this->request->isAJAX( ) )
		{
			$update =
			[
				'nombre' => $this->request->getVar( 'name' ),
			];

			$this->empresaModel->where( 'id_empresa', $this->request->getVar( 'id' ) )->set( $update )->update( );

			//Buscamos si existe un ya un periodo
			$SQL = "SELECT * FROM empresa_periodo WHERE id_empresa = " . $this->request->getVar( 'id' ) . " AND status = 1";
			$builder = $this->db->query( $SQL );
			$periodo = $builder->getResult( );

			if ($periodo != null)
			{
				$SQL = "UPDATE empresa_periodo SET fecha_inicio = '" . $this->request->getVar( 'start' ) . "', fecha_fin = '" . $this->request->getVar( 'end' ) . "' WHERE id = " . $periodo[0]->id;
				$builder = $this->db->query( $SQL );
			}
			else
			{
				$SQL = "INSERT INTO empresa_periodo(id_empresa, fecha_inicio, fecha_fin, status) VALUES (". $this->request->getVar( 'id' ).", '".$this->request->getVar( 'start' )."', '".$this->request->getVar( 'end' )."', 1)";
				$builder = $this->db->query( $SQL );
			}

			echo json_encode( array( 'status' => 200, 'msg' => '¡Cambios guardados con exito!' ) );
		}
		else
			return view( 'errors/cli/error_404' );
	}
}