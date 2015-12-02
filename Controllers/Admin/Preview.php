<?php
namespace Controllers\Admin;

use Libraries;
use Libraries\AppResources;
use Models;
use Resources;

class Preview extends AppResources\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->data = $this->propertyAkademik();
        $this->req = new Resources\Request;
        $this->M = new Models\Main;
        $this->epsbed = new Libraries\Epsbed;
        $this->session = new Resources\Session;
        $this->db = new Resources\Database;
    }

    public function index()
    {
        return false;
    }

    private function is_login()
    {
        if (!$this->login()) {
            $this->redirect('login');
        }

    }

    private function set_filter($method="get")
    {
        $str = "";
        $whereArr = array();
        $filter = $this->req->get("filter",FILTER_SANITIZE_STRING);

        if($method == "post"){
            $filter = $this->req->post("filter",FILTER_SANITIZE_STRING);
        }

        if ($filter) {
            foreach ($filter as $name => $value) {
                array_push($whereArr, $this->db->escape($name) . " LIKE '%" . $this->db->escape($value) . "%'");
            }
            if (count($whereArr)) {
                $str = " WHERE " . implode(" AND ", $whereArr);
            }

        }
        return $str;
    }

    private function set_sort()
    {
        $str = "";
        $sortArr = array();
        if (isset($_GET["sort"])) {
            foreach ($_GET["sort"] as $name => $dir) {
                array_push($sortArr, $this->db->escape($name) . " " . $dir);
            }
            if (count($sortArr)) {
                $str .= " ORDER BY " . implode(",", $sortArr);
            }

        }
        return $str;
    }

    public function coverAbsen()
    {
        $id_kls = $this->req->post("id");
        $id_reg_ptk = $this->req->post("id_reg_ptk");

        $data['absen'] = $this->db->results("
        SELECT
            a.id_ajar,
            a.id_kls,
            b.*, c.nm_kls,
            c.nm_mk,
            c.sks_mk,
            c.nm_smt,
            c.nm_prodi,
            c.smt
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
                bb.smt,
                aa.nm_kls,
                cc.nm_smt,
                dd.nm_lemb AS nm_prodi
            FROM
                kelas_kuliah aa
            JOIN (
                SELECT
                aaa.id_mk,
                aaa.nm_mk,
                aaa.sks_mk,
                bbb.smt
                FROM mata_kuliah aaa
                JOIN mata_kuliah_kurikulum bbb ON aaa.id_mk = bbb.id_mk
            )   bb ON aa.id_mk = bb.id_mk
            JOIN semester cc ON aa.id_smt = cc.id_smt
            JOIN sms dd ON aa.id_sms = dd.id_sms
        ) c ON a.id_kls = c.id_kls WHERE a.id_kls = '".$id_kls."' AND a.id_reg_ptk='".$id_reg_ptk."' ");

        return $this->view->render('print/coverabsen.html', $data);
    }

    public function absen()
    {
        $id_kls = $this->req->post("id");

        $data['absen'] = $this->db->results("
        SELECT
            b.nipd,
            b.nm_pd,
            c.nm_kls,
            c.nm_mk,
            c.sks_mk,
            c.smt,
            d.nidn,
            d.nm_ptk,
            c.nm_smt,
            c.nm_prodi
        FROM
            nilai a
        JOIN (
            SELECT
                bb.id_reg_pd,
                aa.nm_pd,
                bb.nipd
            FROM
                mahasiswa aa
            JOIN mahasiswa_pt bb ON aa.id_pd = bb.id_pd
        ) b ON a.id_reg_pd = b.id_reg_pd
        JOIN (
            SELECT
                aa.id_kls,
                aa.nm_kls,
                bb.nm_mk,
                bb.sks_mk,
                bb.smt,
                cc.nm_smt,
                dd.nm_lemb AS nm_prodi
            FROM
                kelas_kuliah aa
            JOIN (
                SELECT
                    bbb.id_mk,
                    bbb.nm_mk,
                    bbb.sks_mk,
                    aaa.smt
                FROM
                    mata_kuliah_kurikulum aaa
                JOIN mata_kuliah bbb ON aaa.id_mk = bbb.id_mk
            ) bb ON aa.id_mk = bb.id_mk
            JOIN semester cc ON aa.id_smt = cc.id_smt
            JOIN sms dd ON aa.id_sms = dd.id_sms
        ) c ON a.id_kls = c.id_kls
        LEFT JOIN (
            SELECT
                aa.id_reg_ptk,
                aa.id_kls,
                cc.nm_ptk,
                cc.nidn
            FROM
                ajar_dosen aa
            JOIN dosen_pt bb ON aa.id_reg_ptk = bb.id_reg_ptk
            JOIN dosen cc ON bb.id_ptk = cc.id_ptk
        ) d ON d.id_kls = a.id_kls WHERE a.id_kls = '".$id_kls."'");
        return $this->view->render('print/absen.html', $data);
    }

    public function khs()
    {
        $semester  = $this->req->get('semester', FILTER_SANITIZE_STRING);
        $id_reg_pd = $this->req->get('id_reg_pd', FILTER_SANITIZE_STRING);

        $data['nilai'] = $this->db->results("
            SELECT
                a.id_nilai,
                a.id_kls,
                a.id_reg_pd,
                a.nilai_angka,
                a.nilai_huruf,
                a.nilai_indeks,
                b.*,
                c.nipd,
                c.nm_pd,
                c.mulai_smt,
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
                    ee.nm_jenj_didik,
                    aa.nm_kls
                FROM
                    kelas_kuliah aa
                JOIN mata_kuliah bb ON aa.id_mk = bb.id_mk
                JOIN semester cc ON aa.id_smt = cc.id_smt
                JOIN sms dd ON aa.id_sms = dd.id_sms
                JOIN jenjang_pendidikan ee ON dd.id_jenj_didik = ee.id_jenj_didik
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
            ) c ON c.id_reg_pd = a.id_reg_pd WHERE a.id_reg_pd = '".$id_reg_pd."' HAVING semester = '".$semester."' ");

        $data['sumSKS'] = $this->epsbed->sumSKS($data['nilai']);
        $data['sumSKSTempuh'] = $this->epsbed->sumSKSTempuh($data['nilai']);
        $data['sumSKSLulus'] = $this->epsbed->sumSKSLulus($data['nilai']);
        $data['sumIPK'] = $this->epsbed->sumIPK($data['nilai']);

        return $this->view->render('print/khs.html', $data);
    }

    public function transkrip()
    {
        $data['nilai'] = $this->db->results("
            SELECT
                a.id_nilai,
                a.id_kls,
                a.id_reg_pd,
                a.nilai_angka,
                a.nilai_huruf,
                a.nilai_indeks,
                b.*,
                c.nipd,
                c.nm_pd,
                c.mulai_smt,
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
                    ee.nm_jenj_didik,
                    aa.nm_kls
                FROM
                    kelas_kuliah aa
                JOIN mata_kuliah bb ON aa.id_mk = bb.id_mk
                JOIN semester cc ON aa.id_smt = cc.id_smt
                JOIN sms dd ON aa.id_sms = dd.id_sms
                JOIN jenjang_pendidikan ee ON dd.id_jenj_didik = ee.id_jenj_didik
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
            ) c ON c.id_reg_pd = a.id_reg_pd " . $this->set_filter() . " ORDER BY semester");

        // JUMLAH SKS    149 $jmSks=$jmSks+$sks;
        // SKS TEMPUH    149 $sksTempuh=$sksTempuh+$sks; if nilai A-E
        // SKS LULUS    149 $sksLulus=$sksLulus+$sks; if nilai A, B, C
        // INDEKS PRESTASI KUMULATIF (IPK)    3,61
        // $ipk=round($jmTot/$sksTempuh,2);
        // $strIpk=number_format($ipk,2);
        // $strIpk=str_replace(".",",",$strIpk);
        // if($sksTempuh==0){
        //     echo  number_format(0,2);
        // }else{
        //     echo  $strIpk;
        // }
        //

        $data['sumSKS'] = $this->epsbed->sumSKS($data['nilai']);
        $data['sumSKSTempuh'] = $this->epsbed->sumSKSTempuh($data['nilai']);
        $data['sumSKSLulus'] = $this->epsbed->sumSKSLulus($data['nilai']);
        $data['sumIPK'] = $this->epsbed->sumIPK($data['nilai']);

        return $this->view->render('print/transkrip.html', $data);
    }

    public function krs()
    {
        $id_smt    = $this->data['idsmt'];
        $id_reg_pd = $this->req->post('id_reg_pd', FILTER_SANITIZE_STRING);
        $data['krs'] = $this->db->results("
            SELECT
                a.id_nilai,
                a.id_kls,
                a.id_reg_pd,
                a.nilai_angka,
                a.nilai_huruf,
                a.nilai_indeks,
                b.*,
                c.nipd,
                c.nm_pd,
                c.mulai_smt,
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
                    ee.nm_jenj_didik,
                    aa.nm_kls
                FROM
                    kelas_kuliah aa
                JOIN mata_kuliah bb ON aa.id_mk = bb.id_mk
                JOIN semester cc ON aa.id_smt = cc.id_smt
                JOIN sms dd ON aa.id_sms = dd.id_sms
                JOIN jenjang_pendidikan ee ON dd.id_jenj_didik = ee.id_jenj_didik
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
            ) c ON c.id_reg_pd = a.id_reg_pd WHERE a.id_reg_pd = '".$id_reg_pd."' ORDER BY b.id_smt");

        $data['sumSKS'] = $this->epsbed->sumSKS($data['krs']);
        $data['smt'] = $this->epsbed->getSemester($data['krs'][0]->mulai_smt);
        return $this->view->render('print/krs.html', $data);
    }

    public function krsher()
    {
        $args = array(
            'trnlm' => array(
                'table' => 'V_TRNLM_HER',
                'criteria' => array(
                    'NIMHSTRNLMHER' => $this->session->getValue('userId'), //'2014100003',
                    'THSMSTRNLMHER' => $this->session->getValue('thsmst'), //'20142',
                    'KDPSTTRNLMHER' => $this->session->getValue('prodi'), //'62201'
                ),
                'orderBy' => 'IDTRNLM',
            ),

            'mhs' => array(
                'table' => 'V_MHS_PROP',
                'criteria' => array(
                    'NIMHSMSMHS' => $this->session->getValue('userId'), //'2014100003',
                ),
            ),
        );

        $data['krs'] = $this->M->getAll($args['trnlm']);
        $data['mhs'] = $this->M->getOne($args['mhs']);
        $data['smt'] = $this->epsbed->getSemester($data['mhs']->TAHUNMSMHS);
        $data['sumSKS'] = $this->epsbed->sumSKS($data['krs']);

        return $this->view->render('print/krsher.html', $data);
    }

}
