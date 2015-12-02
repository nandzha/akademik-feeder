<?php
namespace Controllers\Admin;

use Dhtmlx\Connector;
use Libraries;
use Libraries\AppResources;
use Models;
use Resources;

class Kuliah extends AppResources\Controller
{
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->request = new Resources\Request;
        $this->uuid = new Libraries\UUID;
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
        return $this->view->render('kuliah-matakuliah-list.html', $this->data);
    }

    public function mklst()
    {
        $model = new Models\Matakuliah;

        if ($this->request->post('webix_operation') == 'insert') {
            $data = $this->request->post(false, FILTER_SANITIZE_STRING);
            return $model->insert(new Connector\Data\DataAction('insert', $this->uuid->v4(), $data));
        }

        if ($this->request->post('webix_operation') == 'update') {
            $data = $this->request->post(false, FILTER_SANITIZE_STRING);
            return $model->update(new Connector\Data\DataAction('update', $this->request->post('id'), $data));
        }

        return $model->init();
    }

    public function subtansi($p = false)
    {
        $model = new Models\KuliahSubtansi;

        if ($p == 'data') {
            return $model->init();
        }

        if ($this->request->post('webix_operation') == 'insert') {
            $data = $this->request->post(false, FILTER_SANITIZE_STRING);
            return $model->insert(new Connector\Data\DataAction('insert', $this->uuid->v4(), $data));
        }

        if ($this->request->post('webix_operation') == 'update') {
            $data = $this->request->post(false, FILTER_SANITIZE_STRING);
            return $model->update(new Connector\Data\DataAction('update', $this->request->post('id'), $data));
        }

        return $this->view->render('kuliah-subtansi-list.html', $this->data);
    }

    public function kurikulumlst()
    {
        $model = new Models\KurikulumSemester;
        return $model->init();
    }

    public function kurikulum($p = false)
    {
        if ($p == 'data') {
            $model = new Models\MatakuliahKurikulum;
            return $model->init();
        }
        return $this->view->render('kuliah-kurikulum-semester-list.html', $this->data);
    }

    public function kelaslst()
    {
        $model = new Models\KelasKuliah;
        return $model->init();
    }

    public function kelas($p = false)
    {
        if ($p == 'mhs') {
        	$model = new Models\Nilai;
            return $model->mhsKrs();
        }

        if ($p == 'dosen') {
            $model = new Models\DosenAjar;
            return $model->init();
        }

        return $this->view->render('kuliah-kelas-list.html', $this->data);
    }

    public function smtlst(){
        $model = new Models\Semester;
        return $model->init();
    }

    public function listkelas($p=false){
        if ($p == 'data') {
            $model = new Models\KelasKuliah;
            return $model->init();
        }

        return $this->view->render('kuliah-add-kelas.html', $this->data);
    }

    public function nilailst()
    {
        $model = new Models\Nilai;
        return $model->lists();
    }

    public function nilai($p = false)
    {
        if ($p == 'data') {
            $model = new Models\Nilai;
            return $model->kuliahNilai();
        }
        return $this->view->render('kuliah-nilai-list.html', $this->data);
    }

    public function aktifitasmhs($p = false)
    {
        if ($p == 'data') {
            $model = new Models\MahasiswaAktifitas;
            return $model->detail();
        }

        return $this->view->render('kuliah-aktifitas-list.html', $this->data);
    }

    public function statusmhs($p = false)
    {
        $model = new Models\MahasiswaStatus;
        if ($p == 'data') {
            return $model->init();
        }
        if ($p == 'detail') {
            return $model->detail();
        }
        return $this->view->render('kuliah-mhs-status-list.html', $this->data);
    }

}
