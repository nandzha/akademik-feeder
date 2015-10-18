<?php
namespace Models;

use Libraries\AppResources;

class Matakuliah extends AppResources\Models
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'mahasiswa';
    }

    public function init()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("mata_kuliah_view", "id_mk", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_sms' => [
                'rules' => ['required'],
            ],
            'jns_mk' => [
                'rules' => ['required'],
            ],
            'kel_mk' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function setFields()
    {
        $fields = [
            "id_mk",
            "kode_mk",
            "nm_mk",
            "sks_mk",
            "sks_tm",
            "sks_prak",
            "sks_prak_lap",
            "sks_sim",
            "nm_prodi",
            "nm_jns_mk",
            "nm_kel_mk",
            "a_bahan_ajar",
            "a_sap",
            "a_silabus",
        ];
        return implode(",", $fields);
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_mk" => $action->get_id(),
            "a_bahan_ajar" => $action->get_value("a_bahan_ajar"),
            "a_diktat" => $action->get_value("a_diktat"),
            "a_sap" => $action->get_value("a_sap"),
            "a_silabus" => $action->get_value("a_silabus"),
            "acara_prak" => $action->get_value("acara_prak"),
            "id_jenj_didik" => $action->get_value("id_jenj_didik"),
            "id_sms" => $action->get_value("id_sms"),
            "jns_mk" => $action->get_value("jns_mk"),
            "kel_mk" => $action->get_value("kel_mk"),
            "kode_mk" => $action->get_value("kode_mk"),
            "metode_pelaksanaan_kuliah" => $action->get_value("metode_pelaksanaan_kuliah"),
            "nm_mk" => $action->get_value("nm_mk"),
            "sks_mk" => $action->get_value("sks_mk"),
            "sks_prak" => $action->get_value("sks_prak"),
            "sks_prak_lap" => $action->get_value("sks_prak_lap"),
            "sks_sim" => $action->get_value("sks_sim"),
            "sks_tm" => $action->get_value("sks_tm"),
        ];
    }

    public function insert($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->insert("mata_kuliah", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("mata_kuliah", $this->data, array("id_mk" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("mata_kuliah", array("id_mk" => $action->get_id()));
        $action->success();
    }

}
