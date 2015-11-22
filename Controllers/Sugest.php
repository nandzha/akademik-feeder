<?php
namespace Controllers;

use Libraries\AppResources;
use Models;
use Resources;

class Sugest extends AppResources\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->session = new Resources\Session;
        $this->sugest = new Models\Sugest;
    }

    public function index()
    {
        return false;
    }

    public function thnAjaran()
    {
        $this->sugest->thnAjaran();
    }

    public function prodi()
    {
        $this->sugest->prodi();
    }

    public function pt()
    {
        $this->sugest->pt();
    }

    public function jenjang()
    {
        $this->sugest->jenjang();
    }

    public function smt()
    {
        $this->sugest->smt();
    }

    public function mk()
    {
        $this->sugest->mk();
    }

    public function mhs()
    {
        $this->sugest->mhs();
    }

    public function dosen()
    {
        $this->sugest->dosen();
    }

    public function bobotnilai()
    {
        $this->sugest->bobotnilai();
    }

    public function wilayah(){
        $wilayah = $this->sugest->wilayah();
        $this->outputJSON($wilayah, 200);
    }
}
