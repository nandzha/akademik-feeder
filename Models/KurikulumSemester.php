<?php
namespace Models;

use Libraries\AppResources;

class KurikulumSemester extends AppResources\Models
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
        $this->conn->render_table("kurikulum_list_view", "id_kurikulum_sp", $this->setFields("list"));
    }

    public function detail()
    {
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("mata_kuliah_kurikulum_smt", "id_kuliah_kurikulum", $this->setFields("detail"));
    }

    public function getMkSmt()
    {
        if ($this->session->getValue('desc') != 'ALL') {
            $this->conn->filter('id_sms', $this->session->getValue('desc'), '=');
        }

        $this->conn->filter('id_smt', $this->session->getValue('idsmt'), '=');

        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->sort("a.id_smt", "DESC");
        $this->conn->sort("a.nm_mk", "ASC");
        $this->conn->render_sql("
            SELECT * FROM (
            SELECT
            aa.id_kls,
            aa.id_sms,
            aa.id_smt,
            aa.nm_kls,
            bb.kode_mk,
            bb.nm_mk,
            aa.sks_mk,
            cc.nm_smt
            FROM kelas_kuliah aa
            JOIN mata_kuliah bb ON aa.id_mk = bb.id_mk
            JOIN semester cc ON aa.id_smt = cc.id_smt
            ) a
        ", "id_kls", $this->setFields("mksmt"));
    }

    public function setRules()
    {
        return [
            'nm_kurikulum_sp' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            "nm_kurikulum_sp" => $action->get_value("nm_kurikulum_sp"),
            "jml_sem_normal" => $action->get_value("jml_sem_normal"),
            "jml_sks_lulus" => $action->get_value("jml_sks_lulus"),
            "jml_sks_wajib" => $action->get_value("jml_sks_wajib"),
            "jml_sks_pilihan" => $action->get_value("jml_sks_pilihan"),
            "id_sms" => $action->get_value("id_sms"),
            "id_jenj_didik" => $action->get_value("id_jenj_didik"),
            "id_smt_berlaku" => $action->get_value("id_smt_berlaku"),
        ];
    }

    protected function setFields($table)
    {
        $fields = [
            "list" => [
                "nm_kurikulum_sp",
                "jml_sem_normal",
                "jml_sks_lulus",
                "jml_sks_wajib",
                "jml_sks_pilihan",
                "id_sms",
                "nm_prodi",
                "id_jenj_didik",
                "id_smt_berlaku",
            ],
            "detail" => [
                "id_kurikulum_sp",
                "nm_kurikulum_sp",
                "smt",
                "kode_mk",
                "nm_mk",
                "sks_mk",
                "sks_tm",
                "sks_prak",
                "sks_prak_lap",
                "sks_sim",
                "smt",
                "a_wajib",
            ],
            "mksmt" => [
                "id_kls",
                "id_sms",
                "id_smt",
                "nm_kls",
                "kode_mk",
                "nm_mk",
                "sks_mk",
                "nm_smt",
            ],
        ];
        return implode(",", $fields[$table]);
    }

    public function insert($action)
    {
        $this->get_values($action);
        $action->set_id($this->uuid->v4());
        $this->data['id_kurikulum_sp'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("kurikulum", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("kurikulum", $this->data, array("id_kurikulum_sp" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("kurikulum", array("id_kurikulum_sp" => $action->get_id()));
        $action->success();
    }

}
