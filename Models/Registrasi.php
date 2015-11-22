<?php
namespace Models;

use Libraries\AppResources;

class Registrasi extends AppResources\Models
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'registrasi';
    }

    public function init()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->sort("id_smt DESC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("registrasi_mahasiswa", "id_smt", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_smt' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function setFields()
    {
        $fields = [
            "id_smt",
            "smt_mhs",
            "id_reg_pd",
            "regsmt",
            "regta",
            "regkp",
            "regkkl",
            "regher",
            "maxkrs",
            "maxkrsher"
        ];
        return implode(",", $fields);
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_smt"    => $action->get_value("id_smt"),
            "smt_mhs"   => $action->get_value("smt_mhs"),
            "id_reg_pd" => $action->get_value("id_reg_pd"),
            "regsmt"    => $action->get_value("regsmt"),
            "regta"     => $action->get_value("regta"),
            "regkp"     => $action->get_value("regkp"),
            "regkkl"    => $action->get_value("regkkl"),
            "regher"    => $action->get_value("regher"),
            "maxkrs"    => $action->get_value("maxkrs"),
            "maxkrsher" => $action->get_value("maxkrsher"),
        ];
    }

    public function insert($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->insert("registrasi_mahasiswa", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("registrasi_mahasiswa", $this->data, array("id_smt" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("registrasi_mahasiswa", array("id_smt" => $action->get_id()));
        $action->success();
    }

}
