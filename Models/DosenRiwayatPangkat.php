<?php
namespace Models;

use Libraries\AppResources;

class DosenRiwayatPangkat extends AppResources\Models
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
        $this->conn->render_table("dosen_kepangkatan_view", "id_dos_pangkat", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_sms' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_sp" => "6d0ac338-04d4-40e7-82ed-efbf5b66e956",
            "id_ptk" => $action->get_value("id_ptk"),
            "id_sms" => $action->get_value("id_sms"),
            "id_pangkat_gol" => $action->get_value("id_pangkat_gol"),
            "sk_pangkat" => $action->get_value("sk_pangkat"),
            "tgl_sk_pangkat" => $action->get_value("tgl_sk_pangkat"),
            "tmt_sk_pangkat" => $action->get_value("tmt_sk_pangkat"),
            "masa_kerja_thn" => $action->get_value("masa_kerja_thn"),
            "masa_kerja_bln" => $action->get_value("masa_kerja_bln"),
        ];
    }

    protected function setFields()
    {
        $fields = [
            "id_ptk",
            "id_sms",
            "id_pangkat_gol",
            "nm_pangkat",
            "kode_gol",
            "masa_kerja_bln",
            "masa_kerja_thn",
            "sk_pangkat",
            "tgl_sk_pangkat",
            "tmt_sk_pangkat",
        ];
        return implode(",", $fields);
    }

    public function insert($action)
    {
        $this->get_values($action);
        $action->set_id($this->uuid->v4());
        $this->data['id_dos_pangkat'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("dosen_kepangkatan", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("dosen_kepangkatan", $this->data, array("id_dos_pangkat" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("dosen_kepangkatan", array("id_dos_pangkat" => $action->get_id()));
        $action->success();
    }

}
