<?php
namespace Controllers\Admin;

use Libraries\AppResources;
use Models;
use Dhtmlx\Connector;

class Cetak extends AppResources\Controller
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
        return $this->view->render('cetak-index.html', $this->data);
    }

    public function krs()
    {
        return $this->view->render('cetak-krs.html', $this->data);
    }

    public function krsher()
    {
        return $this->view->render('cetak-krsher.html', $this->data);
    }

    public function transkrip()
    {
        return $this->view->render('cetak-transkrip.html', $this->data);
    }

    public function absensi($p = false)
    {
        if ($p == 'data') {
            $model = new Models\KelasKuliah;
            return $model->detail_kelas();
        }
        return $this->view->render('cetak-absensi.html', $this->data);
    }

}
