<?php
namespace Models;

use Libraries\AppResources;

class Nilai extends AppResources\Models
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'mahasiswa';
    }

    public function lists()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->sort("nm_lemb ASC, id_smt DESC, kode_mk ASC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("list_nilai_view", "id_kls", $this->setFields("list_nilai"));
    }

    public function kuliahNilai()
    {
        $this->conn->useModel($this);
        $this->conn->sort("id_smt DESC, nipd ASC");
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("detail_nilai_view", "id_nilai", $this->setFields("kuliah_nilai"));
    }

    public function mhsNilai()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->sort("id_smt DESC");
        $this->conn->sort("semester DESC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_sql("
        SELECT
            a.id_nilai,
            a.id_kls,
            a.id_reg_pd,
            b.id_sms,
            b.id_smt,
            b.id_mk,
            b.kode_mk,
            b.nm_mk,
            b.sks_mk,
            a.nilai_angka,
            a.nilai_huruf,
            a.nilai_indeks,
            b.nm_prodi,
            b.kode_prodi,
            b.nm_kls,
            get_semester(b.id_smt, c.mulai_smt) AS semester
        FROM
            nilai a
        JOIN (
            SELECT
                aa.id_kls,
                aa.id_mk,
                aa.id_sms,
                aa.id_smt,
                bb.kode_mk,
                bb.nm_mk,
                bb.sks_mk,
                dd.nm_lemb AS nm_prodi,
                dd.kode_prodi,
                aa.nm_kls
            FROM
                kelas_kuliah aa
            JOIN mata_kuliah bb ON aa.id_mk = bb.id_mk
            JOIN semester cc ON aa.id_smt = cc.id_smt
            JOIN sms dd ON aa.id_sms = dd.id_sms
        ) b ON a.id_kls = b.id_kls
        JOIN (
            SELECT
                bb.nipd,
                aa.nm_pd,
                bb.id_reg_pd,
                SUBSTR(bb.mulai_smt,1,4) AS mulai_smt
            FROM
                mahasiswa aa
            JOIN mahasiswa_pt bb ON aa.id_pd = bb.id_pd
        ) c ON c.id_reg_pd = a.id_reg_pd
        ", "id_nilai", $this->setFields("mhs_nilai"));
    }

    public function mhsKrs()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_sql("
            SELECT
                a.id_kls,
                a.id_nilai,
                b.id_reg_pd,
                b.nipd,
                b.nm_pd,
                b.jk,
                b.nm_prodi
            FROM
                nilai a
            JOIN (
                SELECT
                    aa.id_pd,
                    aa.id_reg_pd,
                    aa.id_sms,
                    aa.nipd,
                    bb.nm_pd,
                    bb.jk,
                    cc.nm_lemb AS nm_prodi
                FROM
                    mahasiswa_pt aa
                JOIN mahasiswa bb ON aa.id_pd = bb.id_pd
                JOIN sms cc ON aa.id_sms = cc.id_sms
            ) b ON a.id_reg_pd = b.id_reg_pd
        ", "a.id_nilai", $this->setFields("mhs_krs"));
    }

    public function setRules()
    {
        return [
            'id_kls' => [
                'rules' => ['required'],
            ],
            'id_reg_pd' => [
                'rules' => ['required'],
            ],
            'nilai_angka' => [
                'rules' => ['max' => 3, "numeric", 'callback' => 'generateNilaiHuruf'],
            ],
            'nilai_huruf' => [
                'rules' => ['max' => 3],
                'filter' => ['trim', 'strtoupper', 'ucwords'],
            ],
            'nilai_indeks' => [
                'rules' => ['max' => 3, "numeric"],
            ],
        ];
    }

    protected function generateNilaiHuruf($field, $value, $label)
    {
        $v = $this->value();

        $criteria = [
            'id_sms' => $this->session->getValue('desc'),
            'bobot_nilai_min' => $value,
        ];

        $this->db->where('id_sms', '=', $this->session->getValue('desc'), 'AND');
        $this->db->where('bobot_nilai_min', '>=', $value, 'OR');
        $this->db->where('bobot_nilai_maks', '<=', $value);

        $bobot = $this->db->getOne('bobot_nilai');
        if ($bobot) {
            $v['nilai_huruf'] = $bobot->nilai_huruf;
            $v['nilai_index'] = $bobot->nilai_indeks;
            return true;
        }

        $this->setErrorMessage($field, $label . ' Tidak ada range nilai');

        return false;
    }

    protected function parsingNilai($nilai, $huruf = true)
    {

        if (empty($nilai) || $nilai === '') {
            return true;
        }

        $nilai = explode("#", $nilai);

        $nilai_huruf = trim($nilai[0]);
        $nilai_indeks = $nilai[1];

        if ($huruf) {
            return $nilai_huruf;
        }

        return $nilai_indeks;
    }

    protected function get_values($action)
    {
        $this->data = [
            'id_kls' => $action->get_value("id_kls"),
            'id_reg_pd' => $action->get_value("id_reg_pd"),
            'nilai_angka' => $action->get_value("nilai_angka"),
            'nilai_huruf' => $this->parsingNilai($action->get_value("nilai_huruf"), true),
            'nilai_indeks' => $this->parsingNilai($action->get_value("nilai_huruf"), false),
            // otomatis dari tabel bobot_nilai berdasarkan nilai dan id_sms
        ];
    }

    protected function setFields($table)
    {
        $fields = [
            "list_nilai" => [
                "id_kls",
                "nm_lemb",
                "id_smt",
                "kode_mk",
                "nm_mk",
                "nm_kls",
                "sks_mk",
                "peserta_kelas",
                "nilai_mhs",
            ],
            "kuliah_nilai" => [
                "id_smt",
                "id_kls",
                "id_reg_pd",
                "id_sms",
                "nm_kls",
                "nipd",
                "nm_pd",
                "mulai_smt",
                "nilai_angka",
                "nilai_huruf",
            ],
            "mhs_nilai" => [
                "id_smt",
                "semester",
                "kode_mk",
                "nm_mk",
                "nm_kls",
                "sks_mk",
                "nilai_angka",
                "nilai_huruf",
                "nilai_indeks"
            ],
            "mhs_krs" => [
                "nipd",
                "nm_pd",
                "jk",
                "nm_prodi",
            ],
        ];
        return implode(",", $fields[$table]);
    }
    public function insert($action)
    {
        $this->get_values($action);

        if ($data = $this->validation($action)) {
            $this->db->insert("nilai", $data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($data = $this->validation($action)) {
            $this->db->update("nilai", $data, array("id_nilai" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("nilai", array("id_nilai" => $action->get_id()));
        $action->success();
    }

}
