<?php
namespace Models;

use Libraries\AppResources;

class DosenRiwayatSert extends AppResources\Models
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
        $this->conn->render_table("dosen_sertifikasi_view", "id_dos_sert", $this->setFields());
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
            "no_peserta" => $action->get_value("no_peserta"),
            "bid_studi" => $action->get_value("bid_studi"),
            "id_jns_sert" => $action->get_value("id_jns_sert"),
            "thn_sert" => $action->get_value("thn_sert"),
            "no_sk_sert" => $action->get_value("no_sk_sert"),
        ];
    }

    protected function setFields()
    {
        $fields = [
            "nm_jns_sert",
            "no_peserta",
            "no_sk_sert",
            "thn_sert",
            "bid_studi",
        ];
        return implode(",", $fields);
    }

    public function insert($action)
    {
        $this->get_values($action);
        $action->set_id($this->uuid->v4());
        $this->data['id_dos_sert'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("dosen_sertifikasi", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("dosen_sertifikasi", $this->data, array("id_dos_sert" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("dosen_sertifikasi", array("id_dos_sert" => $action->get_id()));
        $action->success();
    }

}
