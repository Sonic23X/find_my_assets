<?php

namespace App\Controllers;

class User extends BaseController
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
            $sidebar = array( 'name' => $this->session->name );
            echo view( 'backoffice/common/sidebar', $sidebar );

            //navbar
            echo view( 'backoffice/common/navbar' );

            //content - inicio
            echo view( 'backoffice/options/profile' );

            //Scripts y librerias
            $footer = array( 'js' => 'dashboard' );
            echo view( 'backoffice/common/footer2', $footer );
		}
		else
		{
			$data = array( 'url' => base_url( '/ingrephp sso' ) );
            return view( 'functions/redirect', $data );
    	}
	
    }

}