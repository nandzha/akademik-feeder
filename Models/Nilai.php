<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;

class Nilai extends Resources\Validation
{
    protected $data = [];
    protected $checkEventName = true;

    public function __construct()
    {
        parent::__construct();
        $this->db = new Resources\Database('pddikti');
        $this->conn = new Connector\JSONDataConnector($this->db, "MySQLi");
        $this->session = new Resources\Session;
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
            b.nm_kls
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
            'nilai_huruf' => [
                'rules' => ['max'=>3],
                'filter' => ['trim', 'strtoupper', 'ucwords'],
            ],
        ];
    }

    protected function nilaiIndeks($id_sms, $nilai_huruf){
        $data =  $this->db->getOne( 'bobot_nilai',
            [
                "id_sms"      => $id_sms,
                "nilai_huruf" => trim(strtoupper($nilai_huruf))
            ], ["nilai_indeks"] );

        return $data->nilai_indeks;
    }

    protected function get_values($action)
    {
        $this->data = [
            'id_kls'       => $action->get_value("id_kls"),
            'id_reg_pd'    => $action->get_value("id_reg_pd"),
            'nilai_huruf'  => $action->get_value("nilai_huruf"),
            'nilai_indeks' => $this->nilaiIndeks($action->get_value("id_sms"), $action->get_value("nilai_huruf")),
            // otomatis dari tabel bobot_nilai berdasarkan nilai dan id_sms
        ];
    }

    protected function setFilter()
    {
        $request = new Resources\Request;
        $filters = $request->get('filter');

        if ($filters) {
            $filter = "";

            foreach ($filters as $key => $value) {
                $filter .= $key . " like '" . $value . "%' AND ";
            }

            $filter = rtrim($filter, "AND ");
            $this->conn->filter($filter);
        }
        return false;
    }

    protected function validation($action)
    {

        if (!$this->validate($this->data)) {
            $action->invalid();
            $action->set_response_attribute("details", $this->messages());
            return false;
        }
        return $this->value();
    }

    protected function messages()
    {
        $msg = $this->errorMessages();
        $text = "";

        if ($msg) {
            foreach ($msg as $key => $value) {
                $text .= $key . " : " . $value . ", ";
            }
        }

        $text = rtrim($text, ", ");
        return $text;
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
                "kode_mk",
                "nm_mk",
                "nm_kls",
                "sks_mk",
                "nilai_huruf",
            ],
            "mhs_krs" => [
                "nipd",
                "nm_pd",
                "jk",
                "nm_prodi",
            ]
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
