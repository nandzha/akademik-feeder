<?php
namespace Models;

use Libraries\AppResources;

class KelasKuliah extends AppResources\Models
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'mahasiswa';
    }

    public function init()
    {
        if ($this->session->getValue('desc') != 'ALL') {
            $this->conn->filter('id_sms', $this->session->getValue('desc'), '=');
        }
        $this->conn->filter('id_smt', $this->session->getValue('idsmt'), '=');

        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("list_kelas_perkuliahan_view", "id_kls", $this->setFields("list"));
    }

    public function detail_kelas()
    {
        if ($this->session->getValue('desc') != 'ALL') {
            $this->conn->filter('id_sms', $this->session->getValue('desc'), '=');
        }

        if($this->request->get('id')){
            $this->conn->filter('id_kls', $this->request->get('id', FILTER_SANITIZE_STRING), '=');
        }

        $this->conn->render_table("detail_kelas_kuliah_view", "id_kls", $this->setFields("detail"));
    }

    public function setRules()
    {
        return [
            'id_smt' => [
                'rules' => ['required'],
            ],
            'id_sms' => [
                'rules' => ['required'],
            ],
            'id_mk' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            'id_sms' => $action->get_value("id_sms"),
            'id_smt' => $action->get_value("id_smt"),
            'id_mk' => $action->get_value("id_mk"),
            'nm_kls' => $action->get_value("nm_kls"),
            'sks_mk' => $action->get_value("sks_mk"),
            'sks_tm' => $action->get_value("sks_tm"),
            'sks_prak' => $action->get_value("sks_prak"),
            'sks_prak_lap' => $action->get_value("sks_prak_lap"),
            'sks_sim' => $action->get_value("sks_sim"),

        ];
    }

    protected function setFields($table)
    {
        $fields = [
            "list" => [
                "id_kls",
                "id_sms",
                "id_mk",
                "nm_prodi",
                "id_smt",
                "kode_mk",
                "nm_mk",
                "nm_kls",
                "sks_mk",
                "nm_smt",
                "smt"
            ],
            "detail" =>[
                "peserta_kelas",
                "dosen_mengajar",
                "kode_mk",
                "nm_mk",
                "nm_kls"
            ]
        ];
        return implode(",", $fields[$table]);
    }

    public function insert($action)
    {
        $this->get_values($action);
        $action->set_id($this->uuid->v4());
        $this->data['id_kls'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("kelas_kuliah", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("kelas_kuliah", $this->data, array("id_kls" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("kelas_kuliah", array("id_kls" => $action->get_id()));
        $action->success();
    }

}
