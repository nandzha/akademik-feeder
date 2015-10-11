<?php
namespace Controllers;

use Libraries;
use Libraries\AppResources;
use Models;
use Resources;

class Api extends AppResources\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->S = new Resources\Session;
        $this->M = new Models\Main;
        $this->rest = new Resources\Rest;
        $this->req = new Resources\Request;
        $this->epsbed = new Libraries\Epsbed;
        $this->session = new Resources\Session;
        $this->helper = new Models\Helpers;

    }

    public function index()
    {
        if ($userId = $this->session->getValue('userId')) {
            $data['nim'] = $userId;
        }

        $data['smtMhs'] = $this->epsbed->getSemester(2014);
        $data['thnSmt'] = $this->epsbed->getTahunSmt();
        $data['baseUri'] = $this->uri->baseUri;
        $this->outputJSON($data, 200);
    }

    public function menu()
    {
        // $menu = $helper->get_menu_structure('back');
        // $this->outputJSON($menu, 200);
        $this->helper->menuInit();
    }

}
