<?php
namespace Controllers;

use Libraries\AppResources;
use Models;
use Dhtmlx\Connector;

class Tools extends AppResources\Controller
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
        return $this->view->render('tools-index.html', $this->data);
    }

    public function sync($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\MahasiswaPt;
            return $mhs->init();
        }
        return $this->view->render('tools-sync.html', $this->data);
    }

    public function trakm()
    {
        $model = new Models\MahasiswaAktifitas;

        if ($this->request->post('webix_operation') == 'insert') {
            $results = $model->autoInsertAkm();
            $this->outputJSON($results, 200);
            return;
        }

        return $this->view->render('tools-trakm.html', $this->data);
    }

    public function syncExcel()
    {
        return $this->view->render('tools-sync-excel.html', $this->data);
    }

    public function syncAccess()
    {
        return $this->view->render('tools-sync-access.html', $this->data);
    }

    public function nilaiExcel($p=false)
    {
        if ($p == 'data') {
            $nilai = new Models\ReadNilai;
            $data  = $nilai->readExcel();
            $this->outputJSON($data, 200);
        }
        return $this->view->render('tools-nilai-excel.html', $this->data);
    }

}
