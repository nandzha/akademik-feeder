<?php
namespace Models;

use Libraries\AppResources;
use Resources;

class Sugest extends AppResources\Models
{

    public function __construct()
    {
        parent::__construct();
        $this->request = new Resources\Request;
        $this->ruleName = 'mahasiswa';
    }

    protected function getFilter($key)
    {
        $filters = $this->request->get('filter');

        if ($filters) {
            $filter = $filters[$key] . " like '%" . $filters['value'] . "%'";
            $this->conn->filter($filter);
        }
        return false;
    }

    public function thnAiaran()
    {
        $this->getFilter('nm_thn_aiaran');
        $this->conn->render_table('tahun_aiaran', 'id_thn_aiaran', 'nm_thn_aiaran(value)');
    }

    public function prodi()
    {
        $this->getFilter('nm_lemb');
        $this->conn->render_table('sms', 'id_sms', 'nm_lemb(value)');
    }

    public function pt()
    {
        $this->getFilter('nm_lemb');
        $this->conn->render_table('satuan_pendidikan', 'id_sp', 'nm_lemb(value)');
    }

    public function jenjang()
    {
        $this->getFilter('nm_jenj_didik');
        $this->conn->render_table('jenjang_pendidikan', 'id_jenj_didik', 'nm_jenj_didik(value)');
    }

    public function smt()
    {
        $this->getFilter('nm_smt');
        $this->conn->filter("a_periode_aktif = 1");
        $this->conn->sort("id_smt DESC");
        $this->conn->render_table('semester', 'id_smt', 'nm_smt(value)');
    }

    public function mk()
    {
        $this->getFilter('nm_mk');
        $this->conn->sort("nm_mk ASC");
        $this->conn->render_table('mata_kuliah', 'id_mk', 'nm_mk(value), kode_mk');
    }

    public function mhs()
    {
        $this->getFilter('nm_pd');
        $this->conn->sort("nm_pd ASC");
        $this->conn->render_table('mahasiswa_suggest', 'id_reg_pd', 'nm_pd(value), nipd');
    }

    public function dosen()
    {
        $this->conn->filter("a.id_kls = '".$this->request->get('id_kls')."'");
        $this->conn->sort("nm_ptk ASC");
        $this->conn->render_sql("
        SELECT
            a.id_ajar,
            a.id_kls,
            b.*,
            c.nm_kls,
            c.nm_mk,
            c.sks_mk,
            b.nm_ptk AS value
        FROM
            ajar_dosen a
        JOIN (
            SELECT
                aa.id_reg_ptk,
                bb.id_ptk,
                bb.nm_ptk,
                bb.nidn
            FROM
                dosen_pt aa
            JOIN dosen bb ON aa.id_ptk = bb.id_ptk
        ) b ON a.id_reg_ptk = b.id_reg_ptk
        JOIN (
            SELECT
                aa.id_kls,
                bb.nm_mk,
                bb.sks_mk,
                aa.nm_kls
            FROM
                kelas_kuliah aa
            JOIN mata_kuliah bb ON aa.id_mk = bb.id_mk
        ) c ON a.id_kls = c.id_kls
            ", 'id_reg_ptk', 'value, nidn');
    }

    public function bobotnilai()
    {
        if ($this->session->getValue('desc') != 'ALL') {
            $this->conn->filter('id_sms', $this->session->getValue('desc'), '=');
        }

        $this->conn->sort("nilai_huruf ASC");
        $this->conn->render_table('suggest_bobot_nilai', 'id', 'value');
    }

    public function wilayah()
    {
        $this->getFilter('nm_kec');
        $this->conn->sort("nm_wil ASC");
        $this->conn->render_table('wilayah_view', 'id_wil', 'nm_wil(value)');

    }

}
