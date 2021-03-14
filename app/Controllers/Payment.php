<?php

namespace App\Controllers;

class Payment extends BaseController
{
    protected $session;
    protected $userModel;
    protected $db;

    function __construct()
    {
        $this->session = \Config\Services::session( );
        $this->userModel = model( 'App\Models\UserModel' );
        $this->db = \Config\Database::connect( );
    }

    function Index( )
    {
        if ( $this->session->has( 'isLoggin' ) )
		{
            //CSS, METAS y titulo
            $head = array( 'title' => 'Perfil | Find my assets', 'css' => 'dashboard' );
            echo view( 'backoffice/common/head', $head );

            //sidebar
			$SQL = "SELECT empresas.id, empresas.nombre FROM empresas, user_empresa WHERE user_empresa.id_empresa = empresas.id_empresa AND user_empresa.id_usuario = " . $this->session->id;
			$builder = $this->db->query( $SQL );
			$empresas = $builder->getResult( );
			$sidebar = array( 'name' => $this->session->name, 'empresas' => $empresas );
			echo view( 'backoffice/common/sidebar', $sidebar );

            //navbar
            echo view( 'backoffice/common/navbar' );

            //content - inicio
            echo view( 'backoffice/options/payment' );

            //Scripts y librerias
            $footer = array( 'js' => 'user' );
            echo view( 'backoffice/common/footer2', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingrephp sso' ) );
            return view( 'functions/redirect', $data );
    	}
	
    }

}