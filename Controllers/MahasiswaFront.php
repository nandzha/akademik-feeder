<?php
namespace Controllers;

use Libraries\AppResources;
use Models;

class MahasiswaFront extends AppResources\Controller
{
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->is_login();
        // $this->login();
        $this->data = $this->propertyAkademik();
    }

    private function is_login()
    {
        if (!$this->login()) {
            $this->redirect();
        }

    }

    public function index()
    {
        return $this->view->render('mhs/mhs-profile.html', $this->data);
    }

    public function login()
    {
        $login = new LoginAdministrator;
        $login->setConfig('mhsSignin',false);
        $login->index();
    }

    public function profile(){
        return $this->view->render('mhs/mhs-profile.html', $this->data);
    }

    public function krs(){
        return $this->view->render('mhs/mhs-krs.html', $this->data);
    }

    public function nilai(){
        return $this->view->render('mhs/mhs-nilai.html', $this->data);
    }

    public function skripsi(){
        return $this->view->render('mhs/mhs-skripsi.html', $this->data);
    }

    public function sp(){
        return $this->view->render('mhs/mhs-sp.html', $this->data);
    }

}
