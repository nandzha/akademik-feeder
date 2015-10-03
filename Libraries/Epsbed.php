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

    public function setRules() {
        if (! empty($this->ruleType))
            return $this->rules[$this->ruleType];
        return false;
    }

    /**
     * check if KDKMK exist in THSMS and KDPST 
     * @param  [type] $field [description]
     * @param  [type] $value [description]
     * @param  [type] $label [description]
     * @return [type]        [description]
     */
    public function kdkmkIsExist($field, $value, $label) {
        $v = $this->value();
                
        if( ! $this->checkKDKMK )
            return true;

        $args = array(
            'table' => 'tbkmk',
            'criteria' => array(
                'KDKMKTBKMK' => $value, 
                'THSMSTBKMK' => $v['THSMSTBKMK'], 
                'KDPSTTBKMK' => $v['KDPSTTBKMK'] 
                )
            );

        if( ! $is = $this->M->getOne($args) )
            return true;
        
        $this->setErrorMessage($field, $label.' already exists (THSMSTBKMK & KDPSTTBKMK)');            

        return false;
    }

    /**
     * cek if tahun semester sudah ada
     * @param  [type] $field [description]
     * @param  [type] $value [description]
     * @param  [type] $label [description]
     * @return [type]        [description]
     */
    public function thsmsIsExist($field, $value, $label) {
        $v = $this->value();

        if( ! $this->checkTHSMS )
            return true;

        $args = array(
            'table' => 'trstsreg',
            'criteria' => array(
                'NIMHS' => $v['NIMHS'],
                'THSMS' => $value
                )
            );

        if( ! $is = $this->M->getOne($args) )
            return true;
        
        $this->setErrorMessage($field, ' Already exists (THSMS & NIMHS)');            

        return false;
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
                $jmSks = $jmSks + $r->SKSMKTBKMK;
            }

        return $jmSks;
    }

    public function sumSKSTempuh($data = array()){
        $sksTempuh = 0;
        $nilai = array('A','B','C','D','E');

        if (! empty($data) ) 
            foreach ($data as $r) {
                if ( in_array($r->NLAKHTRNLM, $nilai) )
                    $sksTempuh = $sksTempuh + $r->SKSMKTBKMK;
            }

        return $sksTempuh;
    }

    public function sumSKSLulus($data = array()){
        $sksLulus = 0;
        $nilai = array('A','B','C');

        if (! empty($data) ) 
            foreach ($data as $r) {
                if ( in_array($r->NLAKHTRNLM, $nilai) )
                    $sksLulus = $sksLulus + $r->SKSMKTBKMK;
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
                if ( in_array($r->NLAKHTRNLM, $nilai) )
                    $sksTempuh = $sksTempuh + $r->SKSMKTBKMK;

                $total = $r->BOBOTTRNLM * $r->SKSMKTBKMK;
                $jmTot = $jmTot + $total;
            }

            $ipk = round($jmTot / $sksTempuh,2);
            $strIpk = number_format($ipk,2);
            $strIpk = str_replace(".",",",$strIpk);

        }


        if($sksTempuh==0)
            return number_format(0,2);

        return $strIpk;
    }
}