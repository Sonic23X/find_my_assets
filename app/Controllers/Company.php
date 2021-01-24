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
		$this->sucursalModel = model( 'App\Models\SucursalModel' );
		$this->ccModel = model( 'App\Models\CCModel' );
		$this->areaModel = model( 'App\Models\AreaModel' );
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

			//content - scanner
			echo view( 'backoffice/sections/companies' );

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
}