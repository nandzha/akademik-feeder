<?php
namespace Controllers;

use Libraries\AppResources;
use Models;

class Prodi extends AppResources\Controller
{
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->data = $this->propertyAkademik();
    }

    private function is_login()
    {
        if (!$this->login()) {
            $this->redirect('login');
        }

    }

    public function index()
    {
        return $this->view->render('prodi-index.html', $this->data);
    }

    public function matakuliah($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\MahasiswaPt;
            return $mhs->init();
        }
        return $this->view->render('prodi-matakuliah.html', $this->data);
    }

}
