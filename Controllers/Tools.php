<?php
namespace Controllers;

use Libraries\AppResources;
use Models;

class Tools extends AppResources\Controller
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
        return $this->view->render('tools-index.html');
    }

    public function sync($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\MahasiswaPt;
            return $mhs->init();
        }
        return $this->view->render('tools-sync.html');
    }

    public function syncExcel()
    {
        return $this->view->render('tools-sync-excel.html');
    }

    public function syncAccess()
    {
        return $this->view->render('tools-sync-access.html');
    }

}
