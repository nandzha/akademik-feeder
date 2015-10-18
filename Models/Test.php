<?php
namespace Models;

use Libraries\AppResources;

class Test extends AppResources\Models
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'mahasiswa';
    }

    protected function setFields($table)
    {
        $fields = [
            'list' => [
                'id_pd', 'id_reg_pd', 'nm_pd', 'nipd', 'jk', 'nm_agama',
                'tgl_lahir', 'nm_lemb', 'nm_stat_mhs', 'id_sms',
            ],
            'detail' => [
                'id_agama', 'FK__agama', 'id_jenjang_pendidikan_ayah', 'FK__jenjang_pendidikan_ayah',
                'id_jenjang_pendidikan_ibu', 'FK__jenjang_pendidikan_ibu', 'id_jenjang_pendidikan_wali',
                'FK__jenjang_pendidikan_wali', 'id_kebutuhan_khusus_ayah', 'FK__kebutuhan_khusus_ayah',
                'id_kebutuhan_khusus_ibu', 'FK__kebutuhan_khusus_ibu', 'id_kk', 'FK__kk', 'id_pekerjaan_ayah',
                'FK__pekerjaan_ayah', 'id_pekerjaan_ibu', 'FK__pekerjaan_ibu', 'id_pekerjaan_wali',
                'FK__pekerjaan_wali', 'id_penghasilan_ayah', 'FK__penghasilan_ayah', 'id_penghasilan_ibu',
                'FK__penghasilan_ibu', 'id_penghasilan_wali', 'FK__penghasilan_wali', 'id_sp', 'FK__sp',
                'id_wil', 'FK__wil', 'id_pd', 'nm_pd', 'jk', 'nisn', 'nik', 'tmpt_lahir', 'tgl_lahir',
                'jln', 'rt', 'rw', 'nm_dsn', 'ds_kel', 'kode_pos', 'id_jns_tinggal', 'id_alat_transport',
                'telepon_rumah', 'telepon_seluler', 'email', 'a_terima_kps', 'no_kps', 'stat_pd', 'nm_ayah',
                'tgl_lahir_ayah', 'nm_ibu_kandung', 'tgl_lahir_ibu', 'nm_wali', 'tgl_lahir_wali', 'kewarganegaraan',
                'regpd_id_reg_pd', 'regpd_id_sms', 'regpd_id_pd', 'regpd_id_sp', 'regpd_id_jns_daftar',
                'regpd_nipd', 'regpd_tgl_masuk_sp', 'regpd_id_jns_keluar', 'regpd_tgl_keluar', 'regpd_ket',
                'regpd_skhun', 'regpd_a_pernah_paud', 'regpd_a_pernah_tk', 'regpd_mulai_smt', 'regpd_sks_diakui',
                'regpd_jalur_skripsi', 'regpd_judul_skripsi', 'regpd_bln_awal_bimbingan', 'regpd_bln_akhir_bimbingan',
                'regpd_sk_yudisium', 'regpd_tgl_sk_yudisium', 'regpd_ipk', 'regpd_no_seri_ijazah', 'regpd_sert_prof',
                'regpd_a_pindah_mhs_asing', 'regpd_nm_pt_asal', 'regpd_nm_prodi_asal',
            ],
        ];
        return implode(",", $fields[$table]);
    }

    protected function get_values($action)
    {
        if (empty($this->data)) {
            $this->data = [
                'nipd'  => $action->get_value("nipd"),
                'nm_pd' => $action->get_value("nm_pd"),
            ];
        }
    }

    public function init()
    {
        if ($this->session->getValue('desc') != 'ALL') {
            $this->conn->filter('id_sms', $this->session->getValue('desc'), '=');
        }

        $this->conn->sort("nipd DESC, nm_pd ASC");
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("mahasiswa_list_view", "id_reg_pd", $this->setFields('list'));
    }

    public function detail()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->render_table("mahasiswa_detail_view", "id_pd", $this->setFields('detail'));
    }

    public function insert($action, $return = true)
    {
        $this->get_values($action);

        $action->set_id($this->uuid->v4());
        $this->data['id_pd'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("mahasiswa", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("mahasiswa", $this->data, array("id_pd" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("mahasiswa", array("id_pd" => $action->get_id()));
        $action->success();
    }

}
