<?php
namespace Models;

use Libraries\AppResources;

class MahasiswaAktifitas extends AppResources\Models
{
    protected $data = [];
    protected $checkAkm = true;

    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'mahasiswa';
    }

    public function init()
    {
        $this->conn->useModel($this);
        $this->conn->sort("id_smt DESC");
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("mahasiswa_aktifitas_kuliah_view", "id_kuliah_pd", $this->setFields('list'));
    }

    public function detail()
    {
        /*
        // http://docs.dhtmlx.com/connector__php__mixedconnector.html
        $mhs_pt = new Connector\JSONDataConnector($this->db, "MySQLi");
        $mhs_pt->configure("mahasiswa_pt", "id_reg_pd", "nipd");
        $mhs = new Connector\JSONDataConnector($this->db, "MySQLi");
        $mhs->configure("mahasiswa","id_pd","nm_pd");
        $conn = new Connector\MixedConnector($this->db, "MySQLi");
        $conn->add("data", $mhs_pt);
        $conn->add("data", $mhs);
        $conn->render();
         */
        $this->setFilter();
        $this->conn->dynamic_loading(10);
        $this->conn->sort("id_smt DESC");
        $this->conn->render_sql("
            SELECT
                a.id_kuliah_pd,
                b.nipd,
                b.nm_pd,
                b.nm_lemb AS nm_prodi,
                b.mulai_smt,
                a.id_smt,
                c.nm_stat_mhs,
                a.ips,
                a.ipk,
                a.sks_smt,
                a.sks_total,
                a.id_reg_pd,
                a.id_stat_mhs
            FROM
                kuliah_mahasiswa a
            INNER JOIN (
                SELECT
                    aa.id_reg_pd,
                    aa.nipd,
                    bb.nm_pd,
                    cc.nm_lemb,
                    aa.mulai_smt
                FROM
                    mahasiswa_pt aa
                INNER JOIN mahasiswa bb ON aa.id_pd = bb.id_pd
                INNER JOIN sms cc ON aa.id_sms = cc.id_sms
            ) b ON a.id_reg_pd = b.id_reg_pd
            LEFT JOIN status_mahasiswa c ON a.id_stat_mhs = c.id_stat_mhs
        ", "id_kuliah_pd", $this->setFields('detail'));
    }

    public function setRules()
    {
        return [
            'id_reg_pd' => [
                'rules' => ['required', 'callback' => 'akmIsExist'],
            ],
        ];
    }

    protected function akmIsExist($field, $value, $label)
    {
        $v = $this->value();
        $id_smt = $this->session->getValue('idsmt');

        if (!$this->checkAkm) {
            return true;
        }

        $criteria = [
            'id_reg_pd' => $value,
            'id_smt' => $id_smt,
        ];

        $akm = $this->db->getOne('kuliah_mahasiswa', $criteria);
        if (!$akm) {
            return true;
        }

        $this->setErrorMessage($field, 'Nim ini sudah dimasukkan di smt ' . $id_smt);

        return false;
    }

    protected function hitungAktifitasMhs($id_reg_pd)
    {
        $criteria = [
            'id_reg_pd' => $id_reg_pd,
            'id_smt' => $this->session->getValue('idsmt'),
        ];

        $akm = (object) [
            'h_ips' => 0,
            'h_sks_smt' => 0,
        ];

        if ($data = $this->db->getOne('hitung_aktifitas_mahasiswa_view', $criteria)) {
            $akm = $data;
        }

        return $akm;
    }

    protected function get_values($action)
    {
        $akm = $this->hitungAktifitasMhs($action->get_value("id_reg_pd"));

        $this->data = [
            'id_smt' => $this->session->getValue('idsmt'),
            'id_reg_pd' => $action->get_value("id_reg_pd"),
            'ips' => $akm->h_ips,
            'sks_smt' => $akm->h_sks_smt,
            'ipk' => $action->get_value("ipk"),
            'sks_total' => $action->get_value("sks_total"),
            'id_stat_mhs' => $action->get_value("id_stat_mhs"),
        ];
    }

    protected function setFields($table)
    {
        $fields = [
            "list" => [
                "id_smt",
                "id_reg_pd",
                "id_stat_mhs",
                "nm_stat_mhs",
                "ips",
                "ipk",
                "sks_smt",
                "sks_total",
            ],
            "detail" => [
                "id_smt",
                "nipd",
                "nm_pd",
                "nm_prodi",
                "mulai_smt",
                "nm_stat_mhs",
                "ips",
                "ipk",
                "sks_smt",
                "sks_total",
            ],
        ];
        return implode(",", $fields[$table]);
    }

    public function insert($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->insert("kuliah_mahasiswa", $this->data);
            $action->success($this->db->insertId());
            $action->set_response_attribute("id_smt", $this->data['id_smt']);
            $action->set_response_attribute("ips", $this->data['ips']);
            $action->set_response_attribute("sks_smt", $this->data['sks_smt']);
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        $this->checkAkm = false;

        if ($this->validation($action)) {
            $this->db->update("kuliah_mahasiswa", $this->data, array("id_kuliah_pd" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("kuliah_mahasiswa", array("id_kuliah_pd" => $action->get_id()));
        $action->success();
    }

    public function autoInsertAkm()
    {
        $criteria = [
            'id_smt' => $this->session->getValue('idsmt'),
            // 'id_reg_pd' => '72506108-2e0b-43cb-82a8-8e2adbaded55',
        ];
        $akm = $this->db->getAll('hitung_aktifitas_mahasiswa_view', $criteria);

        if (!$akm) {
            $results['result']['error_desc'] = 'Tidak ada record ' . $this->session->getValue('idsmt');
            return $results;
        }

        foreach ($akm as $r) {
            $values = [
                'id_smt' => $r->id_smt,
                'id_reg_pd' => $r->id_reg_pd,
                'ips' => $r->h_ips,
                'sks_smt' => $r->h_sks_smt,
                // 'ipk'       => $r->h_ipk,
                // 'sks_total' => $r->h_sks_total,
                'id_stat_mhs' => 'A',
            ];

            if (!$this->validate($values)) {
                $insert = $this->messages();
            } else {
                if ($this->db->insert("kuliah_mahasiswa", $values)) {
                    $insert = '-';
                }

            }

            $results['result'][] = [
                'nim' => $r->nipd,
                'error_desc' => $insert,
            ];
        }
        return $results;
    }

}
