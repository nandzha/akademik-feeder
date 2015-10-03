<?php
namespace Controllers;

use Libraries\AppResources;
use Models;

class Mahasiswa extends AppResources\Controller
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
        return $this->view->render('mahasiswa-list.html');
    }

    public function lst()
    {
        $mhs = new Models\Mahasiswa;
        return $mhs->init();
    }

    public function detail()
    {
        $mhs = new Models\Mahasiswa;

        if ($this->request->get('action') == 'get' or $this->request->get('editing') == true) {
            return $mhs->detail();
        }

        return $this->view->render('mahasiswa-detail.html');
    }

    public function riwpend($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\MahasiswaPt;
            return $mhs->init();
        }
        return $this->view->render('mahasiswa-list-history-pend.html');
    }

    public function krslst($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\MahasiswaKrs;
            return $mhs->init();
        }
        return $this->view->render('mahasiswa-list-krs.html');
    }

    public function nilailst($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\Nilai;
            return $mhs->mhsNilai();
        }
        return $this->view->render('mahasiswa-list-nilai.html');
    }

    public function kuliahaktifitas($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\MahasiswaAktifitas;
            return $mhs->init();
        }
        return $this->view->render('mahasiswa-list-aktifitas.html');
    }

    public function mksmt($p = false)
    {
        if ($p == 'data') {
            $mhs = new Models\KurikulumSemester;
            return $mhs->getMkSmt();
        }
        return $this->view->render('mahasiswa-list-aktifitas.html');
    }

}
