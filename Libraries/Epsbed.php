<?php
namespace Libraries;
use Models, Resources;

class Epsbed extends Resources\Validation {

    public $ruleType   = null;
    public $checkKDKMK = true;
    public $checkTHSMS = true;
    public $checkSmt   = true;

    public function __construct()
    {
        parent::__construct();
        // $this->session      = new Resources\Session;
        // $this->request      = new Resources\Request;
        // $this->rules	= new Epsbedrules;

        $this->M 		= new Models\Main;
        $this->rules    = Resources\Config::epsbed();
    }

    /**
     * get semester mahasiswa
     * @param  int $angkatan tahun angkatan mahasiswa
     * @return int value semester mahasiswa ex: 5
     */
    public function getSemester($angkatan){
    	$semester = $this->getTahunSmt(); //20132

		if ($semester %2 != 0){ //jika semester gasal
			$a = (($semester + 10)-1)/10;
			$b = $a - $angkatan;
			$c = ($b*2)-1;
		}else{ // jika semester genap
			$a = (($semester + 10)-2)/10;
			$b = $a - $angkatan;
			$c = $b * 2;
		}

		return $c;
    }


    /**
     * get tahun semester mahasiswa
     * semester ganjil september - februari ex: 20131
     * semester genap maret-agustus ex: 20132
     * @return integer tahun semester ex: 20142
     */
    public function getTahunSmt($tahun=null, $bulan=null){
        $tahun = date('Y');
        $bulan = date('m');
        if ($bulan >= 9 && $bulan <= 12)
            $thsms = $tahun.'1';

        if ($bulan >= 1 && $bulan <= 2 )
            $thsms = ($tahun-1).'1';

        if ($bulan >= 3 && $bulan <= 8 )
            $thsms = ($tahun-1).'2';

        return $thsms;
    }

    public function checkSemester($field, $value, $label) {
        $v = $this->value();

        if( ! $this->checkSmt )
            return true;

        $this->setErrorMessage($field, $label.'Tidak di ijinkan di semester ini');

        return false;
    }

    public function inputNilai($field, $value, $label) {
        $nilai = array('A','B','C','D','E');

        if(in_array($value, $nilai) )
            return true;

        if (empty($value)) {
            $this->setErrorMessage($field, ' Yakin Input nilai Kosong?');
            return false;
        }

        $this->setErrorMessage($field, ' Input nilai hanya huruf besar A sampai E');

        return false;
    }


    public function sumSKS($data = array()){
        $jmSks = 0;
        if (! empty($data) )
            foreach ($data as $r) {
                $jmSks = $jmSks + trim($r->sks_mk);
            }

        return $jmSks;
    }

    public function sumSKSTempuh($data = array()){
        $sksTempuh = 0;
        $nilai = array('A','B','C','D','E');

        if (! empty($data) )
            foreach ($data as $r) {
                if ( in_array(trim($r->nilai_huruf), $nilai) )
                    $sksTempuh = $sksTempuh + trim($r->sks_mk);
            }

        return $sksTempuh;
    }

    public function sumSKSLulus($data = array()){
        $sksLulus = 0;
        $nilai = array('A','B','C');

        if (! empty($data) )
            foreach ($data as $r) {
                if ( in_array(trim($r->nilai_huruf), $nilai) )
                    $sksLulus = $sksLulus + trim($r->sks_mk);
            }

        return $sksLulus;
    }

    public function sumIPK($data = array()){
        $sksTempuh = 0;
        $jmTot = 0;
        $total = 0;
        $nilai = array('A','B','C','D','E');

        if (! empty($data) ) {

            foreach ($data as $r) {
                if ( in_array(trim($r->nilai_huruf), $nilai) )
                    $sksTempuh = $sksTempuh + trim($r->sks_mk);

                $total = $r->nilai_indeks * trim($r->sks_mk);
                $jmTot = $jmTot + $total;
            }
            $ipk = round($jmTot / $sksTempuh, 2);
            $strIpk = number_format($ipk,2);
            $strIpk = str_replace(".",",",$strIpk);

        }


        if($sksTempuh==0)
            return number_format(0,2);

        return $strIpk;
    }
}
