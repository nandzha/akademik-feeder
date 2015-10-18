<?php
namespace Models;

use Libraries\AppResources;

class MahasiswaStatus extends AppResources\Models
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'mahasiswa';
    }

    public function init()
    {
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("list_mahasiswa_status_view", "id_reg_pd", $this->setFields('list'));
    }

    public function detail()
    {
        $this->conn->useModel($this);
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("list_mahasiswa_status_view", "id_reg_pd", $this->setFields('detail'));
    }

    public function setRules()
    {
        return [
            'id_reg_pd' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            'id_reg_pd' => $action->get_value("id_reg_pd"),
            'id_jns_keluar' => $action->get_value("id_jns_keluar"),
            'tgl_keluar' => $action->get_value("tgl_keluar"),
            'ket' => $action->get_value("ket"),
            'jalur_skripsi' => $action->get_value("jalur_skripsi"),
            'judul_skripsi' => $action->get_value("judul_skripsi"),
            'bln_awal_bimbingan' => $action->get_value("bln_awal_bimbingan"),
            'bln_akhir_bimbingan' => $action->get_value("bln_akhir_bimbingan"),
            'sk_yudisium' => $action->get_value("sk_yudisium"),
            'tgl_sk_yudisium' => $action->get_value("tgl_sk_yudisium"),
            'ipk' => $action->get_value("ipk"),
            'no_seri_ijazah' => $action->get_value("no_seri_ijazah"),
        ];
    }

    protected function setFields($table)
    {
        $fields = [
            'list' => [
                "id_reg_pd",
                "nipd",
                "nm_pd",
                "nm_lemb",
                "mulai_smt",
                "ket_keluar",
                "tgl_keluar",
                "ket",
            ],
            'detail' => [
                "id_reg_pd",
                "tgl_keluar",
                "ket",
                "id_jns_keluar",
                "jalur_skripsi",
                "judul_skripsi",
                "bln_awal_bimbingan",
                "bln_akhir_bimbingan",
                "sk_yudisium",
                "tgl_sk_yudisium",
                "ipk",
                "no_seri_ijazah",
            ],
        ];
        return implode(",", $fields[$table]);
    }

    public function insert($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->insert("mahasiswa_pt", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("mahasiswa_pt", $this->data, array("id_reg_pd" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("mahasiswa_pt", array("id_reg_pd" => $action->get_id()));
        $action->success();
    }

}
