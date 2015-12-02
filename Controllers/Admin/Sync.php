<?php
namespace Controllers\Admin;

use Models;
use Resources;
use Libraries;

class Sync extends Resources\Controller
{

    protected $url = 'http://unsiq.ac.id:8082/ws/live.php?wsdl';
    protected $username = '061030';
    protected $password = '29012015';
    protected $dbAccess;

    public function __construct()
    {
        parent::__construct();
        Resources\Import::composer();

        $this->client  = new \nusoap_client($this->url, true);
        $this->request = new Resources\Request;
        $this->sync    = new Models\Sync;
        $this->proxy   = $this->client->getProxy();
    }

    public function index()
    {
        $this->token();
    }

    public function token()
    {
        return $this->proxy->GetToken($this->username, $this->password);
    }

    public function listTable()
    {
        $results = $this->proxy->ListTable($this->token());
        if (!empty($results['error_code'])) {
            Resources\Tools::setStatusHeader(404);
            throw new Resources\HttpException('[error:' . $results['error_code'] . '] ' . $results['error_desc']);
        }

        foreach ($results['result'] as $k => $v) {
            $count = $this->proxy->GetCountRecordset($this->token(), $results['result'][$k]['table'], "");

            $dataTable[] = [
                'column_name' => $results['result'][$k]['table'],
                'type' => $results['result'][$k]['jenis'],
                'description' => $results['result'][$k]['keterangan'],
                'count' => $count['result'],
            ];
        }
        $column = [
            "column_name VARCHAR(100)",
            "type VARCHAR(50)",
            "description VARCHAR(200)",
            "count VARCHAR(100)"
        ];
        $this->sync->createTempTable("tmp_list_table", $column);

        foreach ($dataTable as $row) {
            $this->sync->insertTempTable("tmp_list_table", $row);
        }
        $results = $this->sync->getTempTable("tmp_list_table");

        $this->outputJSON($results, 200);
    }

    public function getall($table, $filter=false, $order=false){
        // $filter = "p.id_sms = '833f76f5-282f-4d75-b4fa-549c5869ee7b'";
        // $filter = "nm_pd ilike '%deni fahrizal%'";
        // $filter = "nipd like '0720402120001%' ";
        // $filter = "nm_wil ilike '%mojotengah%' ";
        // $filter    = "mulai_smt = '20141'";
        // $order     = "nipd asc";
        $limit     = 10;
        $offset    = 0;

        $results = $this->proxy->GetRecordset($this->token(), $table, $filter, $order, $limit, $offset);
        // $this->outputJSON($results, 200);
        return $results;
    }

    /**
     * proses sinkronisasi
     * 1. cek jumlah record server dan lokal
     * 2. cek last_update pada server dan lokal
     * @return [type] [description]
     */
    public function syncing ($table){
        $data['countS'] = $this->proxy->GetCountRecordset($this->token(), $table, "");
        $data['countL'] = $this->sync->getCountRecord(rtrim($table, ".raw") );

        $filter   = "mulai_smt = '20141'";
        $order    = "nipd ASC";
        $limit    = 2;
        $offset   = 0;

        $this->sync->copyTempTable("temp_".rtrim($table, ".raw"), rtrim($table, ".raw") );

        $resS = $this->proxy->GetRecordset($this->token(), $table, $filter, $order, $limit, $offset);

        foreach ($resS['result'] as $row) {
            $this->sync->insertTempTable("temp_".rtrim($table, ".raw"), $row);
        }

        // $data['resT'] = $this->sync->getTempTable("temp_".rtrim($table, ".raw"));
        // $data['resL'] = $this->sync->getAllRecord(rtrim($table, ".raw"), $filter, $order, $limit, $offset);

        //tampilkan data yang beda
        $data['unmatched'] = $this->sync->compareData("temp_".rtrim($table, ".raw"), rtrim($table, ".raw"), ['id_reg_pd', 'nipd'], $filter, $order, $offset, $limit );

        //sinkronkan ke tabel lokal

        $this->outputJSON($data, 200);
    }

    public function test(){
        $column = [
            "column_name VARCHAR(100)",
            "type VARCHAR(50)",
            "description VARCHAR(200)",
            "count VARCHAR(100)"
        ];
        $this->sync->createTempTable("tmp_list_table", $column);
        $this->sync->insertTempTable("tmp_list_table", ['column_name'=>"test", "type"=>"test"]);
        $results = $this->sync->getTempTable("tmp_list_table");
        $this->outputJSON($results, 200);
    }

    public function mataKuliah(){
        $last_update = $this->request->post('last_update', FILTER_SANITIZE_STRING);
        $filter = "last_update >= '". $last_update ."'";
        $mk = $this->sync->getAllRecord("mata_kuliah", $filter);
        if ($mk){
            foreach ($mk as $r) {
                $record[]=[
                    'id_mk' => $r->id_mk,
                    'id_sms' => $r->id_sms,
                    'id_jenj_didik' => $r->id_jenj_didik,
                    'kode_mk' => $r->kode_mk,
                    'nm_mk' => $r->nm_mk,
                    'jns_mk' => $r->jns_mk,
                    'kel_mk' => $r->kel_mk,
                    'sks_mk' => $r->sks_mk,
                    'sks_tm' => $r->sks_tm,
                    'sks_prak' => $r->sks_prak,
                    'sks_prak_lap' => $r->sks_prak_lap,
                    'sks_sim' => $r->sks_sim,
                    'metode_pelaksanaan_kuliah' => $r->metode_pelaksanaan_kuliah,
                    'a_sap' => $r->a_sap,
                    'a_silabus' => $r->a_silabus,
                    'a_bahan_ajar' => $r->a_bahan_ajar,
                    'acara_prak' => $r->acara_prak,
                    'a_diktat' => $r->a_diktat,
                    'tgl_mulai_efektif' => $r->tgl_mulai_efektif,
                    'tgl_akhir_efektif' => $r->tgl_akhir_efektif,
                    // 'last_update' => $r->last_update,
                    // 'soft_delete' => $r->soft_delete,
                    // 'last_sync' => $r->last_sync,
                    // 'id_updater' => $r->id_updater
                ];

                $res['result'][] = [ 'kode'=>$r->kode_mk, 'nama' => $r->nm_mk ];
            }

            $results = $this->proxy->InsertRecordset($this->token(), "mata_kuliah", json_encode($record) );
            $results = array_replace_recursive($res, $results);
        }else{
            $results['result'] = [
                'error_code' => "400",
                'error_desc' => "Data berdasarkan tanggal {$last_update} tidak di temukan"
            ];
        }
        $this->outputJSON($results, 200);
    }

    public function mkFromExcel(){
        $excel = new Libraries\PhpExcel;
        $inputFileName = APP."excel/mata_kuliah.xlsx";

        $args= [
            'sheetName' => ['mata_kuliah'],
            'format'    => ['tgl_mulai_efektif','tgl_akhir_efektif'],
            'substr'    => [
                'id_jenj_didik', 'a_sap', 'a_silabus',
                'a_bahan_ajar', 'acara_prak', 'a_diktat', 'jns_mk', 'kel_mk'
            ],
            'separator' => ['id_sms']
        ];

        $mk = $excel->ReadXlsSeveral($inputFileName,$args);
        if ($mk){
            foreach ($mk as $r) {
                $record[]=[
                    'id_sms' => $r['id_sms'],
                    'id_jenj_didik' => $r['id_jenj_didik'],
                    'kode_mk' => $r['kode_mk'],
                    'nm_mk' => $r['nm_mk'],
                    'jns_mk' => $r['jns_mk'],
                    'kel_mk' => $r['kel_mk'],
                    'sks_mk' => $r['sks_mk'],
                    'sks_tm' => $r['sks_tm'],
                    'sks_prak' => $r['sks_prak'],
                    'sks_prak_lap' => $r['sks_prak_lap'],
                    'sks_sim' => $r['sks_sim'],
                    'metode_pelaksanaan_kuliah' => $r['metode_pelaksanaan_kuliah'],
                    'a_sap' => $r['a_sap'],
                    'a_silabus' => $r['a_silabus'],
                    'a_bahan_ajar' => $r['a_bahan_ajar'],
                    'acara_prak' => $r['acara_prak'],
                    'a_diktat' => $r['a_diktat'],
                    'tgl_mulai_efektif' => $r['tgl_mulai_efektif'],
                    'tgl_akhir_efektif' => $r['tgl_akhir_efektif'],
                    // 'last_update' => $r->last_update,
                    // 'soft_delete' => $r->soft_delete,
                    // 'last_sync' => $r->last_sync,
                    // 'id_updater' => $r->id_updater
                ];

                $res['result'][] = [ 'kode'=>$r['kode_mk'], 'nama' => $r['nm_mk'] ];
            }

            $results = $this->proxy->InsertRecordset($this->token(), "mata_kuliah", json_encode($record) );
            $results = array_replace_recursive($res, $results);
        }else{
            $results['result'] = [
                'error_code' => "400",
                'error_desc' => "Nama sheet harus: mata_kuliah"
            ];
        }

        $this->outputJSON($results, 200);
    }

    private function connectAccesDB(){
        $dbName = APP . "access/pddikti.accdb";
        if (!file_exists($dbName)) {
            die("Could not find database file.");
        }
        try {
            $this->dbAccess = new \PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};;Dbq=$dbName");
        } catch(PDOException $e) {
            echo "Error: ".$e->getMessage()."<br />";
        }
    }

    public function fromAccess(){
        $this->connectAccesDB();

        $sql = "SELECT * FROM mata_kuliah";
        $mk = $this->dbAccess->query($sql)->fetchAll();

        die(var_dump($mk));

        if ($mk){
            foreach ($mk as $r) {
                $record[]=[
                    'id_sms' => $r['id_sms'],
                    'id_jenj_didik' => $r['id_jenj_didik'],
                    'kode_mk' => $r['kode_mk'],
                    'nm_mk' => $r['nm_mk'],
                    'jns_mk' => $r['jns_mk'],
                    'kel_mk' => $r['kel_mk'],
                    'sks_mk' => $r['sks_mk'],
                    'sks_tm' => $r['sks_tm'],
                    'sks_prak' => $r['sks_prak'],
                    'sks_prak_lap' => $r['sks_prak_lap'],
                    'sks_sim' => $r['sks_sim'],
                    'metode_pelaksanaan_kuliah' => $r['metode_pelaksanaan_kuliah'],
                    'a_sap' => $r['a_sap'],
                    'a_silabus' => $r['a_silabus'],
                    'a_bahan_ajar' => $r['a_bahan_ajar'],
                    'acara_prak' => $r['acara_prak'],
                    'a_diktat' => $r['a_diktat'],
                    'tgl_mulai_efektif' => $r['tgl_mulai_efektif'],
                    'tgl_akhir_efektif' => $r['tgl_akhir_efektif'],
                    // 'last_update' => $r->last_update,
                    // 'soft_delete' => $r->soft_delete,
                    // 'last_sync' => $r->last_sync,
                    // 'id_updater' => $r->id_updater
                ];

                $res['result'][] = [ 'kode'=>$r['kode_mk'], 'nama' => $r['nm_mk'] ];
            }

            $results = $this->proxy->InsertRecordset($this->token(), "mata_kuliah", json_encode($record) );
            $results = array_replace_recursive($res, $results);
        }else{
            $results['result'] = [
                'error_code' => "400",
                'error_desc' => "Data tidak di temukan"
            ];
        }

        $this->outputJSON($results, 200);
    }

    private function sp(){
        return [
        '0adb1259-3c70-4132-adf6-c9fbac350824' => 'Ilmu Al-Qur`an dan Tafsir',
        '1b16790e-2519-4f79-9c6d-43c70a29dd94' => 'Manajemen',
        '1d9a2cab-31ce-4c3f-a6e3-b641ac7dfc1b' => 'Pendidikan Agama Islam',
        '1f42c85c-4015-476c-8ccb-2e7b9c96b3e2' => 'Hukum Ekonomi Syari`ah (Mu`amalah)',
        '31a05130-7c08-46a7-9bcf-83098176ed7c' => 'Pendidikan Fisika',
        '3a75d665-e189-44d3-ab56-0cfc7d63cc9e' => 'Hukum Keluarga (Ahwal Syakhshiyah)',
        '57113fbe-807d-481f-a870-7136b01066df' => 'Sastra Inggris',
        '5eb9e566-a0f1-4691-a9f7-a2d5859e556b' => 'Pendidikan Guru Raudhatul Athfal',
        '6e343592-475a-416d-8407-f77e6a3f8689' => 'Manajemen Informatika',
        '70e86e15-85aa-4902-a223-fe9703d66ab1' => 'Kebidanan',
        '7f33e6ac-aac5-4c09-a82a-a2aa039153a3' => 'Pendidikan Bahasa Arab',
        '833f76f5-282f-4d75-b4fa-549c5869ee7b' => 'Teknik Elektronika',
        '83a0b8ad-8da3-4b38-b33e-7e849e801021' => 'Teknik Informatika',
        '8b635745-0d18-4941-a00f-d3f793a1d125' => 'Perbankan Syariah',
        '8e99373c-62a3-4b9f-b88b-9defa95781fd' => 'Komunikasi dan Penyiaran Islam',
        '9ae04002-37f2-46a5-8404-99e26721f056' => 'Teknik Sipil',
        'b86909a1-49fd-4d84-b4bd-d8e2350cb96c' => 'PGMI',
        'b992e234-2fbc-46af-a8b8-f0dc47ca5223' => 'Ilmu Politik',
        'bab5b528-2ce7-4aab-b537-8fc72724d1f9' => 'Mesin Produksi',
        'd0f55536-f20c-463e-8b5a-06eba731581a' => 'Arsitektur',
        'd22dd7d1-539f-4eb0-9fae-b2b992ed2cfd' => 'Ilmu Hukum',
        'dd54cb24-01bc-4f01-a391-4a5efbc0f683' => 'Pendidikan Islam',
        'e8372392-ac46-491d-b200-735e053f18e8' => 'Akuntansi',
        'f74242ca-e66c-42cd-88fb-4ec46fd79e88' => 'Keperawatan',
        ];
    }

    public function deleteBobotNilai(){
        $results = $this->proxy->GetRecordset($this->token(), 'bobot_nilai', '', '', '', 0);
        $record  = [];
        foreach ($results['result'] as $r) {
            $record = [
                'kode_bobot_nilai' => $r['kode_bobot_nilai']
            ];
            $results[] = $this->proxy->DeleteRecord($this->token(), 'bobot_nilai', json_encode($record));
        }

        // $results = $this->proxy->DeleteRecordset($this->token(), 'bobot_nilai', json_encode($record));
        $this->outputJSON($results, 200);
    }

    public function bobotNilaiFromExcel(){
        $excel = new Libraries\PhpExcel;
        $inputFileName = APP."excel/bobot_nilai.xlsx";

        $args= [
            'sheetName' => array('bobot_nilai'),
            'format'    => ['tgl_mulai_efektif','tgl_akhir_efektif']
        ];

        $mk = $excel->ReadXlsSeveral($inputFileName,$args);
        if ($mk){
            $sp = $this->sp();
            foreach ($sp as $key => $value) {
                foreach ($mk as $r) {
                    $record[]=[
                        'id_sms'           => $key,
                        'nilai_huruf'      => $r['nilai_huruf'],
                        'bobot_nilai_min'  => $r['bobot_nilai_min'],
                        'bobot_nilai_maks' => $r['bobot_nilai_maks'],
                        'nilai_indeks'     => $r['nilai_indeks'],
                        'tgl_mulai_efektif'=> $r['tgl_mulai_efektif'],
                        'tgl_akhir_efektif'=> $r['tgl_akhir_efektif']

                    ];

                    $res['result'][] = [ 'kode'=>$r['nilai_huruf'], 'nama' => $r['nilai_indeks'] ];
                }
            }

            $results = $this->proxy->InsertRecordset($this->token(), "bobot_nilai", json_encode($record) );
            $results = array_replace_recursive($res, $results);
        }else{
            $results['result'] = [
                'error_code' => "400",
                'error_desc' => "Data tidak di temukan"
            ];
        }

        $this->outputJSON($results, 200);
    }

    private function table(){
        return [
            ['name' => 'mahasiswa', 'key' => '' ],
            ['name' => 'mahasiswa_pt', 'key' => '' ],
            ['name' => 'dosen', 'key' => '' ],
            ['name' => 'dosen_pt', 'key' => '' ],
            ['name' => 'mata_kuliah', 'key' => '' ],
            ['name' => 'kurikulum', 'key' => '' ],
            ['name' => 'mata_kuliah_kurikulum', 'key' => '' ],
            ['name' => 'kelas_kuliah', 'key' => '' ],
            ['name' => 'ajar_dosen', 'key' => '' ],
            ['name' => 'bobot_nilai', 'key' => '' ],
            ['name' => 'nilai', 'key' => '' ],
            ['name' => 'nilai_transfer', 'key' => '' ],
            ['name' => 'kuliah_mahasiswa', 'key' => '' ],
            ['name' => 'dosen_jabfung', 'key' => '' ],
            ['name' => 'dosen_kepangkatan', 'key' => '' ],
            ['name' => 'dosen_pendidikan', 'key' => '' ],
            ['name' => 'dosen_sertifikasi', 'key' => '' ],
            ['name' => 'dosen_struktural', 'key' => '' ],
            ['name' => 'substansi_kuliah', 'key' => '' ],
        ];
    }


    /**
     * syncronize mode upload belum di coba
     * @return [type] [description]
     */
    public function syncPush(){
        $last_update = $this->request->post('last_update', FILTER_SANITIZE_STRING);
        // $filter = "last_update >= '". $last_update ."'";
        $tables = $this->table();

        foreach ($tables as $table) {
            $filter  = "last_update >= '". $last_update ."' AND mode = 'insert'";
            $results = $this->sync->getAllRecord($table['name'], $filter);
            if ($results){
                $res['result'][$table] = $results;
                /*foreach ($results as $r) {
                    $res['result'][] = [ 'kode'=>$r->kode_mk, 'nama' => $r->nm_mk ];
                }*/

                // deteksi mode apakah update atau insert
                // tambah satu kolom lagi untuk deteksi mode
                $response = $this->proxy->InsertRecordset($this->token(), $table['name'], json_encode($results) );
                $response = array_replace_recursive($res, $response);
            }

            $filter  = "last_update >= '". $last_update ."' AND mode = 'update'";
            $results = $this->sync->getAllRecord($table['name'], $filter);
            if ($results){
                $res['result'][$table] = $results;
                foreach ($results as $r) {
                    $key        = [$table['key'] => $r[ $table['key'] ] ];
                    $data[]     = $r;
                    $records[] = ['key' => $key, 'data'=> $data];

                    $res['result'][] = [ 'kode'=>$r->kode_mk, 'nama' => $r->nm_mk ];
                }

                // deteksi mode apakah update atau insert
                // tambah satu kolom lagi untuk deteksi mode
                $response = $this->proxy->UpdateRecordset($this->token(), $table['name'], json_encode($records) );
                $response = array_replace_recursive($res, $response);
            }

            if(!$results){
                $response['result'] = [
                    'error_code' => "400",
                    'error_desc' => "Data berdasarkan tanggal {$last_update} tidak di temukan"
                ];
            }
        }

        $this->outputJSON($response, 200);
    }

    /**
     * syncronize mode download belum dicoba
     * @return [type] [description]
     */
    public function syncPull(){
        $tables = $this->table();
        $last_update = $this->request->post('last_update', FILTER_SANITIZE_STRING);
        $filter = "last_update >= '". $last_update ."'";

        foreach ($tables as $table) {
            $results = $this->getall($table, $filter);
            foreach ($results as $row) {
                $insert = $this->sync->insertTempTable($table, $row);
                $res['result'][] = [
                    'kode'       => $r->kode,
                    'nama'       => $r->nama,
                    'error_desc' => $insert
                ];
            }
            $res[$table] = $res;
        }
        $this->outputJSON($res, 200);
    }

    public function mahasiswa(){
        $record[0] = [
            // 'id_pd' => '',
            'nm_pd' => 'test',
            'jk' => 'L',
            // 'nisn' => '',
            // 'nik' => '',
            // 'tmpt_lahir' => '',
            'tgl_lahir' => '2001-09-01',
            'id_agama' => '98',
            'id_kk' => '0',
            'id_sp' => '6d0ac338-04d4-40e7-82ed-efbf5b66e956',
            // 'jln' => '',
            // 'rt' => '',
            // 'rw' => '',
            // 'nm_dsn' => '',
            'ds_kel' => 'WONOKROMO',
            'id_wil' => '999999',
            // 'kode_pos' => '',
            // 'id_jns_tinggal' => '',
            // 'id_alat_transport' => '',
            // 'telepon_rumah' => '',
            // 'telepon_seluler' => '',
            // 'email' => '',
            'a_terima_kps' => '0',
            // 'no_kps' => '',
            'stat_pd' => 'A',
            // 'nm_ayah' => '',
            // 'tgl_lahir_ayah' => '',
            // 'id_jenjang_pendidikan_ayah' => '',
            // 'id_pekerjaan_ayah' => '',
            // 'id_penghasilan_ayah' => '',
            'id_kebutuhan_khusus_ayah' => '0',
            'nm_ibu_kandung' => '',
            // 'tgl_lahir_ibu' => '',
            // 'id_jenjang_pendidikan_ibu' => '',
            // 'id_penghasilan_ibu' => '',
            // 'id_pekerjaan_ibu' => '',
            'id_kebutuhan_khusus_ibu' => '0',
            // 'nm_wali' => '',
            // 'tgl_lahir_wali' => '',
            // 'id_jenjang_pendidikan_wali' => '',
            // 'id_pekerjaan_wali' => '',
            // 'id_penghasilan_wali' => '',
            'kewarganegaraan' => 'ID',
            // 'regpd_id_reg_pd' => '',
            // 'regpd_id_sms' => '',
            // 'regpd_id_pd' => '',
            // 'regpd_id_sp' => '',
            // 'regpd_id_jns_daftar' => '',
            // 'regpd_nipd' => '',
            // 'regpd_tgl_masuk_sp' => '',
            // 'regpd_id_jns_keluar' => '',
            // 'regpd_tgl_keluar' => '',
            // 'regpd_ket' => '',
            // 'regpd_skhun' => '',
            // 'regpd_a_pernah_paud' => '',
            // 'regpd_a_pernah_tk' => '',
            // 'regpd_mulai_smt' => '',
            // 'regpd_sks_diakui' => '',
            // 'regpd_jalur_skripsi' => '',
            // 'regpd_judul_skripsi' => '',
            // 'regpd_bln_awal_bimbingan' => '',
            // 'regpd_bln_akhir_bimbingan' => '',
            // 'regpd_sk_yudisium' => '',
            // 'regpd_tgl_sk_yudisium' => '',
            // 'regpd_ipk' => '',
            // 'regpd_no_seri_ijazah' => '',
            // 'regpd_sert_prof' => '',
            // 'regpd_a_pindah_mhs_asing' => '',
            // 'regpd_nm_pt_asal' => '',
            // 'regpd_nm_prodi_asal' => '',
            // 'last_update' => '',
            // 'soft_delete' => '',
            // 'last_sync' => '',
            // 'id_updater' => '',
            // 'last_update_local' => ''
        ];

        $results = $this->proxy->InsertRecordset($this->token(), "mahasiswa", json_encode($record) );
        $this->outputJSON($results, 200);
    }

    public function mkKurikulum(){
        $filter = "id_kurikulum_sp = '2114d099-c2b3-4bc1-82c6-0ee801fead28'";
        $mk = $this->sync->getAllRecord("mata_kuliah_kurikulum", $filter);
        if ($mk){
            foreach ($mk as $r) {
                $record[]=[
                    'id_kurikulum_sp' => $r->id_kurikulum_sp,
                    'id_mk' => $r->id_mk,
                    'smt' => $r->smt,
                    'sks_mk' => $r->sks_mk,
                    'sks_tm' => $r->sks_tm,
                    'sks_prak' => $r->sks_prak,
                    'sks_prak_lap' => $r->sks_prak_lap,
                    'sks_sim' => $r->sks_sim,
                    'a_wajib' => $r->a_wajib
                ];

                $res['result'][] = [ 'kode'=>$r->id_mk ];
            }

            $results = $this->proxy->InsertRecordset($this->token(), "mata_kuliah_kurikulum", json_encode($record) );
            $results = array_replace_recursive($res, $results);
        }else{
            $results['result'] = [
                'error_code' => "400",
                'error_desc' => "Data berdasarkan tanggal {$last_update} tidak di temukan"
            ];
        }
        $this->outputJSON($results, 200);
    }

    public function kelaskuliah(){
        $filter = "id_sms = '83a0b8ad-8da3-4b38-b33e-7e849e801021' AND id_smt='20151'";
        $rec = $this->sync->getAllRecord("kelas_kuliah", $filter);
        if ($rec){
            foreach ($rec as $r) {
                $record[]=[
                    'id_sms'           => $r->id_sms,
                    'id_smt'           => $r->id_smt,
                    'id_mk'            => $r->id_mk,
                    'nm_kls'           => $r->nm_kls,
                    'sks_mk'           => $r->sks_mk,
                    'sks_tm'           => $r->sks_tm,
                    'sks_prak'         => $r->sks_prak,
                    'sks_prak_lap'     => $r->sks_prak_lap,
                    'sks_sim'          => $r->sks_sim,
                    'tgl_mulai_koas'   => '2015-09-01',
                    'tgl_selesai_koas' => '2020-09-01',
                ];

                $res['result'][] = [ 'kode'=>$r->id_mk ];
            }

            $results = $this->proxy->InsertRecordset($this->token(), "kelas_kuliah", json_encode($record) );
            $results = array_replace_recursive($res, $results);
        }else{
            $results['result'] = [
                'error_code' => "400",
                'error_desc' => "Data tidak di temukan"
            ];
        }
        $this->outputJSON($results, 200);
    }

    public function krsmahasiswa(){
        // $filter = "id_sms = '83a0b8ad-8da3-4b38-b33e-7e849e801021' AND id_smt='20151'";
        $rec = $this->sync->getAllRecord("nilai");
        if ($rec){
            foreach ($rec as $r) {
                $record[]=[
                    'id_kls'    => $r->id_kls,
                    'id_reg_pd' => $r->id_reg_pd,
                    'asal_data' => $r->asal_data,

                ];

                $res['result'][] = [ 'id_reg_pd'=>$r->id_reg_pd, 'id_kls' => $r->id_kls];
            }

            $results = $this->proxy->InsertRecordset($this->token(), "nilai", json_encode($record) );
            $results = array_replace_recursive($res, $results);
        }else{
            $results['result'] = [
                'error_code' => "400",
                'error_desc' => "Data tidak di temukan"
            ];
        }
        $this->outputJSON($results, 200);
    }

    public function mahasiswaLulus(){
        $excel = new Libraries\PhpExcel;
        $inputFileName = APP."excel/mahasiswa_lulus.xlsx";

        $args= [
            'sheetName' => [
                // 'AK2007',
                'AK2008',
                // 'AK2009',
                // 'AK2010',
                // '2011',
                // '2012',
                // '2013',
                // '2014_1',
                // '2014_2',
                // '2015'
                ],
            'format'    => ['TGL_LAHIR','TGL_LULUS','TGL_WISUDA']
        ];

        $mhs = $excel->ReadXlsSeveral($inputFileName,$args);
        if ($mhs){
            foreach ($mhs as $r) {
                $criteriaMhs = [
                    'nipd'        => $r['NIM'],
                    // 'lower_nm_pd' => strtolower($r['NAMA']),
                    'id_sms'      => '83a0b8ad-8da3-4b38-b33e-7e849e801021', //ti
                ];

                $propMhs = (object) ['id_pd'=>'', 'id_reg_pd'=>''];

                if ( $prop = $this->sync->getPropertyMhsByNim($criteriaMhs) )
                    $propMhs = $prop;

                // die(var_dump($propMhs));
                //mahasiswa_pt
                $key2       = ['id_reg_pd' => $propMhs->id_reg_pd]; // sebagai filter (where)
                $data2      = [
                    'id_jns_keluar'   => '1',
                    'no_seri_ijazah'  => $r['NO_SERI_IJAZAH'],
                    'ipk'             => $r['IPK'],
                    'jalur_skripsi'   => '1',
                    'judul_skripsi'   => $r['JUDULTA'],
                    'tgl_keluar'      => $r['TGL_LULUS'],
                    'tgl_sk_yudisium' => $r['TGL_WISUDA']
                    ]; // sebagai values 1 lulus
                $records2[] = ['key' => $key2, 'data' => $data2];

                //mahasiswa
                $key3       = ['id_pd' => $propMhs->id_pd]; // sebagai filter (where)
                $data3      = ['stat_pd' => 'L']; // sebagai values D
                $records3[] = ['key' => $key3, 'data' => $data3];

                $res['mahasiswa_pt']['result'][] = [ 'nim'=>$r['NIM'], 'nama'=>$r['NAMA']];
                $res['mahasiswa']['result'][]    = [ 'nim'=>$r['NIM'], 'nama'=>$r['NAMA']];

            }

            $results['mahasiswa_pt'] = $this->proxy->UpdateRecordset($this->token(), 'mahasiswa_pt', json_encode($records2));
            $results['mahasiswa']    = $this->proxy->UpdateRecordset($this->token(), 'mahasiswa', json_encode($records3));

            $results = array_replace_recursive($res, $results);

            // $this->sync->insertTableRecursif('log_mahasiswa_pt', $results['mahasiswa_pt']['result']);
            // $this->sync->insertTableRecursif('log_mahasiswa', $results['mahasiswa']['result']);

        }else{
            $results['result'] = [
                'error_code' => "400",
                'error_desc' => "Nama sheet tidak ada"
            ];
        }

        $this->outputJSON($results, 200);
    }

}
