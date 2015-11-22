<?php
namespace Models;

use Libraries\AppResources;

class DosenAjar extends AppResources\Models
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
        $this->conn->sort("c.nm_prodi ASC, c.id_smt DESC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_sql("
            SELECT
                a.id_ajar,
                a.id_kls,
                a.id_reg_ptk,
                b.nidn,
                b.nm_ptk,
                c.nm_kls,
                c.sks_mk,
                c.id_smt,
                c.kode_prodi,
                c.nm_prodi,
                d.nm_subst,
                a.id_subst,
                a.sks_subst_tot,
                a.sks_tm_subst,
                a.sks_prak_subst,
                a.sks_prak_lap_subst,
                a.sks_sim_subst,
                a.jml_tm_renc,
                a.jml_tm_real,
                a.id_jns_eval
            FROM
                ajar_dosen a
            JOIN (
                SELECT
                    ta.*
                FROM
                    (
                        SELECT
                            aa.id_ptk,
                            aa.id_reg_ptk,
                            bb.nm_ptk,
                            bb.nidn
                        FROM
                            dosen_pt aa
                        JOIN dosen bb ON aa.id_ptk = bb.id_ptk
                    ) ta
            ) b ON a.id_reg_ptk = b.id_reg_ptk
            JOIN (
                SELECT
                cc.id_kls,
                cc.id_sms,
                cc.id_smt,
                cc.nm_kls,
                dd.kode_prodi,
                dd.nm_lemb AS nm_prodi,
                cc.sks_mk
                FROM
                    kelas_kuliah cc
                JOIN sms dd ON cc.id_sms = dd.id_sms
            ) c ON a.id_kls = c.id_kls
            LEFT JOIN substansi_kuliah d ON a.id_subst = d.id_subst
        ", "a.id_ajar", $this->setFields("kls_kuliah_dosen"));
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_reg_ptk" => $action->get_value("id_reg_ptk"),
            "id_kls" => $action->get_value("id_kls"),
            "id_subst" => $action->get_value("id_subst"),
        ];
    }

    protected function setFields($table)
    {
        $fields = [
            "kls_kuliah_dosen" => [
                "nidn",
                "nm_ptk",
                "nm_subst",
                "jml_tm_renc",
                "jml_tm_real",
                "id_jns_eval",
            ],
        ];
        return implode(",", $fields[$table]);
    }

    public function insert($action)
    {
        $this->get_values($action);
        $action->set_id($this->uuid->v4());
        $this->data['id_ajar'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("ajar_dosen", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("ajar_dosen", $this->data, array("id_ajar" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("ajar_dosen", array("id_ajar" => $action->get_id()));
        $action->success();
    }

}
