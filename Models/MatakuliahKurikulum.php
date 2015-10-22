<?php
namespace Models;

use Libraries\AppResources;

class MatakuliahKurikulum extends AppResources\Models
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
        if ($this->session->getValue('desc') != 'ALL') {
            $this->conn->filter('id_sms', $this->session->getValue('desc'), '=');
        }
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("mata_kuliah_kurikulum_smt", "id_mk_kurikulum", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_mk' => [
                'rules' => ['required'],
            ],
            'id_kurikulum_sp' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_kurikulum_sp" => $action->get_value("id_kurikulum_sp"),
            "id_mk" => $action->get_value("id_mk"),
            "smt" => $action->get_value("smt"),
            "sks_mk" => $action->get_value("sks_mk"),
            "sks_tm" => $action->get_value("sks_tm"),
            "sks_prak" => $action->get_value("sks_prak"),
            "sks_prak_lap" => $action->get_value("sks_prak_lap"),
            "sks_sim" => $action->get_value("sks_sim"),
            "a_wajib" => $action->get_value("a_wajib"),
        ];
    }

    protected function setFields()
    {
        $fields = [
            "id_mk_kurikulum",
            "id_kurikulum_sp",
            "id_mk",
            "nm_kurikulum_sp",
            "kode_mk",
            "nm_mk",
            "sks_mk",
            "sks_tm",
            "sks_prak",
            "sks_prak_lap",
            "sks_sim",
            "smt",
            "a_wajib",
            "id_sms"
        ];
        return implode(",", $fields);
    }

    public function insert($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->insert("mata_kuliah_kurikulum", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("mata_kuliah_kurikulum", $this->data, array("id_mk_kurikulum" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("mata_kuliah_kurikulum", array("id_mk_kurikulum" => $action->get_id()));
        $action->success();
    }

}
