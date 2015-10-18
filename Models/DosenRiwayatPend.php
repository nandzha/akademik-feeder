<?php
namespace Models;

use Libraries\AppResources;

class DosenRiwayatPend extends AppResources\Models
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
        $this->conn->render_table("dosen_pendidikan_view", "id_dos_pendidikan", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_jenj_didik' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_sp" => "6d0ac338-04d4-40e7-82ed-efbf5b66e956",
            "id_ptk" => $action->get_value("id_ptk"),
            "bidang_studi" => $action->get_value("bidang_studi"),
            "id_jenj_didik" => $action->get_value("id_jenj_didik"),
            "gelar" => $action->get_value("gelar"),
            "id_sp_asal" => $action->get_value("id_sp_asal"),
            "fakultas" => $action->get_value("fakultas"),
            "thn_lulus" => $action->get_value("thn_lulus"),
            "sks_lulus" => $action->get_value("sks_lulus"),
            "ipk_lulus" => $action->get_value("ipk_lulus"),
        ];
    }

    protected function setFields()
    {
        $fields = [
            "nm_jenj_didik",
            "nm_pt",
            "bidang_studi",
            "fakultas",
            "gelar",
            "ipk_lulus",
            "sks_lulus",
            "thn_lulus",
        ];
        return implode(",", $fields);
    }

    public function insert($action)
    {
        $this->get_values($action);
        $action->set_id($this->uuid->v4());
        $this->data['id_dos_pendidikan'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("dosen_pendidikan", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("dosen_pendidikan", $this->data, array("id_dos_pendidikan" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("dosen_pendidikan", array("id_dos_pendidikan" => $action->get_id()));
        $action->success();
    }

}
