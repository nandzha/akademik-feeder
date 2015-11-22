<?php
namespace Models;

use Libraries\AppResources;

class MahasiswaPt extends AppResources\Models
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
        $this->conn->dynamic_loading(30);
        $this->setFilter();
        $this->conn->render_sql("
            SELECT
                a.nm_pd,
                a.tmpt_lahir,
                a.tgl_lahir,
                a.jk,
                c.*
            FROM
                mahasiswa a
            LEFT JOIN (
                SELECT
                    aa.id_pd,
                    aa.id_reg_pd,
                    aa.nipd,
                    bb.nm_jns_daftar,
                    aa.mulai_smt,
                    aa.tgl_masuk_sp,
                    cc.nm_lemb AS nm_jurusan,
                    dd.nm_lemb AS nm_pt
                FROM
                    mahasiswa_pt aa
                LEFT JOIN jenis_pendaftaran bb ON aa.id_jns_daftar = bb.id_jns_daftar
                LEFT JOIN sms cc ON aa.id_sms = cc.id_sms
                LEFT JOIN satuan_pendidikan dd ON aa.id_sp = dd.id_sp
            ) c ON a.id_pd = c.id_pd
        ", "id_pd", $this->setFields());
    }

    protected function setFields()
    {
        $fields = [
            "id_pd",
            "id_reg_pd",
            "nipd",
            "nm_pd",
            "tmpt_lahir",
            "tgl_lahir",
            "jk",
            "nm_jns_daftar",
            "mulai_smt",
            "tgl_masuk_sp",
            "nm_jurusan",
        ];
        return implode(",", $fields);
    }

    protected function get_values($action)
    {
        $this->data['event_name'] = $action->get_value("event_name");
        $this->data['start_date'] = $action->get_value("start_date");
        $this->data['end_date'] = $action->get_value("end_date");
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
