<?php
namespace Controllers;

use Libraries\AppResources;
use Models;

class Dosen extends AppResources\Controller
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
        return $this->view->render('dosen-list.html');
    }

    public function lst()
    {
        $dosen = new Models\Dosen;
        return $dosen->init();
    }

    public function detail()
    {
        $dosen = new Models\Dosen;
        if ($this->request->get('action') == 'get' or $this->request->get('editing') == true) {
            return $dosen->detail();
        }
        return $this->view->render('dosen-detail.html');
    }

    public function penugasan($p = false)
    {
        if ($p == 'data') {
            $dosen = new Models\DosenPenugasan;
            return $dosen->init();
        }
        return $this->view->render('dosen-list-penugasan.html');
    }

    public function aktifitasngajar($p = false)
    {
        if ($p == 'data') {
            $dosen = new Models\DosenAktifitas;
            return $dosen->init();
        }

        return $this->view->render('dosen-list-aktifitas-ngajar.html');
    }

    public function mksmt()
    {
        $mksmt = new Models\KurikulumSemester;
        return $mksmt->getMkSmt();
    }

    public function riwfung($p = false)
    {
        if ($p == 'data') {
            $dosen = new Models\DosenRiwayatFung;
            return $dosen->init();
        }
        return $this->view->render('dosen-list-riwayat-fung.html');
    }

    public function riwpang($p = false)
    {
        if ($p == 'data') {
            $dosen = new Models\DosenRiwayatPangkat;
            return $dosen->init();
        }
        return $this->view->render('dosen-list-riwayat-pangkat.html');
    }

    public function riwpend($p = false)
    {
        if ($p == 'data') {
            $dosen = new Models\DosenRiwayatPend;
            return $dosen->init();
        }
        return $this->view->render('dosen-list-riwayat-pend.html');
    }

    public function riwsert($p = false)
    {
        if ($p == 'data') {
            $dosen = new Models\DosenRiwayatSert;
            return $dosen->init();
        }
        return $this->view->render('dosen-list-riwayat-sert.html');
    }

    public function riwstruk($p = false)
    {
        if ($p == 'data') {
            $dosen = new Models\DosenRiwayatStruk;
            return $dosen->init();
        }
        return $this->view->render('dosen-list-riwayat-struk.html');
    }
}
