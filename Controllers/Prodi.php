<?php
namespace Controllers;

use Libraries\AppResources;
use Models;

class Prodi extends AppResources\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_login();
    }

    private function is_login()
    {
        if (!$this->login()) {
            $this->redirect('login');
        }

    }

    public function index()
    {
        return $this->view->render('prodi-index.html');
    }

    public function matakuliah($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\MahasiswaPt;
            return $mhs->init();
        }
        return $this->view->render('prodi-matakuliah.html');
    }

}
