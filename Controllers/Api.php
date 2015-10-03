<?php
namespace Controllers;
use Resources, Models, Libraries;

class Api extends Resources\Controller
{    
	public function __construct(){
		parent::__construct();
		$this->S       = new Resources\Session;
		$this->M       = new Models\Main;
		$this->rest    = new Resources\Rest;
		$this->req     = new Resources\Request;
		$this->epsbed  = new Libraries\Epsbed;
		$this->session = new Resources\Session;
	}
	
	public function index(){
		if ($userId = $this->session->getValue('userId') )
			$data['nim']    = $userId;

		$data['smtMhs']  = $this->epsbed->getSemester(2014);
		$data['thnSmt']  = $this->epsbed->getTahunSmt();
		$data['baseUri'] = $this->uri->baseUri;
		$this->outputJSON($data, 200);
	}	

	public function menu(){
		$this->H = new Models\Helpers;
		$menu = $this->H->get_menu_structure('back');
		$this->outputJSON($menu, 200);
	}

	private function exception($dumpData=array(), $exception=array() ) {
		$exception = array_flip( $exception );
		$dumpData  = array_diff_key( $dumpData, $exception );	
		return $dumpData;
	}

	private function save($args = array(), $dumpData=array() ) {
		$default = array(
			'save' => array(
				'id'       => null,
				'PK'       => null,
				'table'    => null,
				'callback' => null
				),
			'remove' => array(
				'table'    => null,
				'PK'       => null,
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => null
			);
		$args = array_merge($default, $args);

		$operation = $this->req->post('webix_operation');
		$this->epsbed->ruleType = $args['rules'];

				
		if ($id = $this->req->post('id', FILTER_SANITIZE_NUMBER_INT) && $operation == 'update')
			$this->epsbed->checkKDKMK = false;

		if ($operation == "insert" OR $operation == "update"){

			if ( $data['status'] = $this->epsbed->validate() ) {
				$data['value']= $this->epsbed->value();

				$exception = array('webix_operation','value','status','messages','id');
				$dumpData = $this->exception($dumpData, $exception);
				
				if ( $messages = $this->M->save($args['save'], $dumpData) ) 
					$data['messages'] = $messages;
			}	

			if ( $messages = $this->epsbed->errorMessages() )
				$data['messages'] = $messages;

		} else if ($operation == "delete"){
			if ($messages = $this->M->deleteOne($args['remove']))
				$data['messages'] = $messages;
		}
		$this->outputJSON($data, 200);
	}

	public function msmhs($request='get'){
		$args = array(
			'get' => array(
				'table' => 'V_MSMHS_SEARCH',
				'PK' =>'NIMHSMSMHS'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'nim',
				'table' => 'mahasiswa',
				'callback'=> 'nim'
			),
			'remove' => array(
				'table' => 'mahasiswa',
				'PK' => 'nim',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => null
			);

		if ($request == 'detail'){
			$args['get']['table'] = 'mahasiswa';
			$args['get']['PK'] = 'nim';

			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			if ($this->req->get('action') == 'get'){
				$id = $this->req->get('id');
				$args['get']['criteria'] = array('nim'=>$id);
			}

			echo $this->M->getAll($args['get'],'json');
			return;
		}

		if($request=='get'){
			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			if ($this->req->get('action') == 'get'){
				$id = $this->req->get('id');
				$args['get']['criteria'] = array('NIMHSMSMHS'=>$id);
			}

			echo $this->M->getAll($args['get'],'json');
			return;
		}

		$dumpData = $this->req->post(false, FILTER_SANITIZE_STRING);
		$exception = array('KDJENMSMHS','KDPSTMSMHS','KDPTIMSMHS','NIMHSMSMHS','NMMHSMSMHS','SMAWLMSMHS','STMHSMSMHS' );
		$dumpData = $this->exception($dumpData, $exception);	
		$this->save($args, $dumpData);
	}

	public function trstsreg($request='get'){
		$args = array(
			'get' => array(
				'table' => 'trstsreg',
				'PK' =>'IDTRSTSREG'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'IDTRSTSREG',
				'table' => 'trstsreg',
				'callback'=> 'NIMHS'
				),
			'remove' => array(
				'table' => 'trstsreg',
				'PK' => 'IDTRSTSREG',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => 'addreg',
			'prop' => array(
				'table'    => 'V_MHS_PROP',
				'fields'   => array('TAHUNMSMHS')
			)
			);

		if($request=='get'){
			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			echo $this->M->getAll($args['get'],'json');
			return;
		}

		$dumpData = $this->req->post(false, FILTER_SANITIZE_STRING);
		$args['prop']['criteria'] = array('NIMHSMSMHS' => $dumpData['NIMHS']);

		$mhsProp            = $this->M->getOne($args['prop']);
		$dumpData['SMTMHS'] = $this->epsbed->getSemester($mhsProp->TAHUNMSMHS);

		if ($this->req->post('id', FILTER_SANITIZE_NUMBER_INT) && $dumpData['webix_operation'] == 'update')
			$args['rules'] = null;

		$this->save($args, $dumpData);
	}

	public function trakd($request='get'){
		$args = array(
			'get' => array(
				'table' => 'V_TRAKD',
				'PK' =>'IDTRAKD'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'IDTRAKD',
				'table' => 'trakd',
				'callback'=> 'NODOSTRAKD'
				),
			'remove' => array(
				'table' => 'trakd',
				'PK' => 'IDTRAKD',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => null
			);

		if($request=='get'){
			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			echo $this->M->getAll($args['get'],'json');
			return;
		}
		
		$dumpData = $this->req->post(false, FILTER_SANITIZE_STRING);
		$this->save($args, $dumpData);
	}

	public function tbkmk($request='get'){
		$args = array(
			'get' => array(
				'table' => 'tbkmk',
				'PK' =>'IDTBKMK',
				'orderBy' => 'id',
				'sort' => 'desc'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'IDTBKMK',
				'table' => 'tbkmk',
				'callback'=> 'THSMSTBKMK'
				),
			'remove' => array(
				'table' => 'tbkmk',
				'PK' => 'IDTBKMK',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => 'tbkmk'
			);

		if($request=='list'){
			$args['get'] = array(
				'table' => 'tbkmk',
				'PK' =>'KDKMKTBKMK'
				);
			echo $this->M->getAll($args['get'],'json');
			return;
		}

		if($request=='get'){
			if( $filter = $this->req->get('filter')){
				$args['get']['criteria'] = $filter;
			}

			echo $this->M->getAllLike($args['get'],'json');
			return;
		}

		if($request=='sugest'){
			$args['sugest'] = array(
				'table'   => 'tbkmk',
				'PK'      => 'KDKMKTBKMK',
				'fields'  => array('NAKMKTBKMK AS value'),
				'orderBy' => 'id',
				'sort'    => 'desc'
				);

			if( $filter = $this->req->get('filter')){
				$args['sugest']['criteria']['NAKMKTBKMK'] = $filter['value'];
			}

			echo $this->M->getAllLike($args['sugest'],'json');
			return;
		}

		$dumpData = $this->req->post(false, FILTER_SANITIZE_STRING);
		$exception = array('_THSMSTBKMK_', 'IDTBKMK' );
		$dumpData = $this->exception($dumpData, $exception);
		$this->save($args, $dumpData);
	}

	public function krsmhs($request='get'){
		$args = array(
			'get' => array(
				'table' => 'V_TRNLM',
				'PK' =>'IDTRNLM'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'IDTRNLM',
				'table' => 'trnlm',
				'callback'=> 'KDKMKTRNLM'
				),
			'remove' => array(
				'table' => 'trnlm',
				'PK' => 'IDTRNLM',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => null
			);

		if($request=='get'){
			if( $filter = $this->req->get('filter')){
				$args['get']['criteria'] = $filter;
			}

			echo $this->M->getAll($args['get'],'json');
			return;
		}

		$dumpData = array(
			'THSMSTRNLM' => $this->req->post('THSMSTBKMK',FILTER_SANITIZE_STRING),
			'KDPTITRNLM' => $this->req->post('KDPTITBKMK',FILTER_SANITIZE_STRING),
			'KDJENTRNLM' => $this->req->post('KDJENTBKMK',FILTER_SANITIZE_STRING),
			'KDPSTTRNLM' => $this->req->post('KDPSTTBKMK',FILTER_SANITIZE_STRING),
			'NIMHSTRNLM' => '2014100003',//$this->req->post('',FILTER_SANITIZE_STRING),
			'KDKMKTRNLM' => $this->req->post('KDKMKTBKMK',FILTER_SANITIZE_STRING),
			'KELASTRNLM' => '01'//$this->req->post('',FILTER_SANITIZE_STRING)
		);

		$this->save($args, $dumpData);
	}

	public function krsher($request='get'){
		$args = array(
			'get' => array(
				'table' => 'V_TRNLM_HER',
				'PK' =>'IDTRNLMHER'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'IDTRNLMHER',
				'table' => 'trnlmher',
				'callback'=> 'KDKMKTRNLMHER'
				),
			'remove' => array(
				'table' => 'trnlmher',
				'PK' => 'IDTRNLMHER',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => null
			);

		if($request=='get'){
			if( $filter = $this->req->get('filter')){
				$args['get']['criteria'] = $filter;
			}

			echo $this->M->getAll($args['get'],'json');
			return;
		}

		$dumpData = array(
			'IDTRNLM' => $this->req->post('IDTRNLM',FILTER_SANITIZE_STRING),
			'THSMSTRNLMHER' => $this->req->post('THSMSTRNLMHER',FILTER_SANITIZE_STRING),
			'KDPTITRNLMHER' => $this->req->post('KDPTITRNLM',FILTER_SANITIZE_STRING),
			'KDJENTRNLMHER' => $this->req->post('KDJENTRNLM',FILTER_SANITIZE_STRING),
			'KDPSTTRNLMHER' => $this->req->post('KDPSTTRNLM',FILTER_SANITIZE_STRING),
			'NIMHSTRNLMHER' => '2014100003',//$this->req->post('',FILTER_SANITIZE_STRING),
			'KDKMKTRNLMHER' => $this->req->post('KDKMKTRNLM',FILTER_SANITIZE_STRING),
			'KELASTRNLMHER' => '01',//$this->req->post('',FILTER_SANITIZE_STRING)
		);

		$this->save($args, $dumpData);

	}

	public function trnlm($request='get'){
		$args = array(
			'get' => array(
				'table' => 'V_TRNLM',
				'PK' =>'IDTRNLM'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'IDTRNLM',
				'table' => 'trnlm',
				'callback'=> 'NIMHSTRNLM'
				),
			'remove' => array(
				'table' => 'trnlm',
				'PK' => 'IDTRNLM',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => 'nilai'
			);

		if($request=='get'){
			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			echo $this->M->getAllLike($args['get'],'json');
			return;
		}
		
		$dumpData = array(
			'NIMHSTRNLM'  => $this->req->post("NIMHSTRNLM", FILTER_SANITIZE_STRING),
			'NLAKHTRNLM'  => $this->req->post("NLAKHTRNLM", FILTER_SANITIZE_STRING)
			);

		$this->save($args, $dumpData);
	}

	public function transkrip($request='get'){
		$args = array(
			'get' => array(
				'table'    => 'V_TRNLM',
				'PK'       =>'IDTRNLM',
				)
			);

		if($request=='get'){
			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			echo $this->M->getAll($args['get'],'json');
			return;
		}

		if($request=='her'){
			$args['get']['operator'] = 'NOT IN';
			$args['get']['criteriaIN'] = array("NLAKHTRNLM" => "A,B");

			if( $filter = $this->req->get('filter') )
				$args['get']['criteria'] = $filter;	

			echo $this->M->getAllIn($args['get'],'json');
			return;
		}
	}

	public function trskr($request='get'){
		$args = array(
			'get' => array(
				'table' => 'V_TRSKR',
				'PK' =>'IDTRSKR'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'IDTRSKR',
				'table' => 'trskr',
				'callback'=> 'NIMHSTRSKR'
				),
			'rules' => null
			);

		if($request=='get'){
			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			echo $this->M->getAll($args['get'],'json');
			return;
		}

		$dumpData = $this->req->post(false, FILTER_SANITIZE_STRING);
		$this->save($args, $dumpData);
	}

	public function listtbdos(){
		$args = array(
			'table' => 'tbdos',
			'PK' =>'NIDNNTBDOS',
			'limit'=>1000,
			'fields' => array('NMDOSTBDOS AS value')
		);

		$filter = $this->req->get('filter');
		if( !empty($filter) )
			$args['criteria'] = array('NMDOSTBDOS' => $filter['value'], 'NIDNNTBDOS' =>$filter['value']);

		echo $this->M->getAllLike($args,'json');
	}

	public function trlsm($request='get'){
		$args = array(
			'get' => array(
				'table' => 'V_TRLSM',
				'PK' =>'IDTRLSM'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'IDTRLSM',
				'table' => 'trlsm',
				'callback'=> 'NIMHSTRLSM'
				),
			'remove' => array(
				'table' => 'trlsm',
				'PK' => 'NIMHSTRLSM',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => null
			);

		if($request=='get'){
			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;

			if ($this->req->get('action') == 'get'){
				$id = $this->req->get('id');
				$args['get']['criteria'] = array('NIMHSTRLSM'=>$id);
			}

			$default = array(
				"BLAKHTRLSM" => "",
				"BLAWLTRLSM" => "",
				"IDTRLSM"    => "",
				"JNLLSTRLSM" => "",
				"KDJENTRLSM" => "",
				"KDPSTTRLSM" => "",
				"KDPTITRLSM" => "",
				"NIMHSTRLSM" => $id,
				"NLIPKTRLSM" => "",
				"NMMHSMSMHS" => "",
				"NODS1TRLSM" => "",
				"NODS2TRLSM" => "",
				"NODS3TRLSM" => "",
				"NODS4TRLSM" => "",
				"NODS5TRLSM" => "",
				"NOIJATRLSM" => "",
				"NOSKRTRLSM" => "",
				"SKSTTTRLSM" => "",
				"STLLSTRLSM" => "",
				"STMHSTRLSM" => "",
				"TGLLSTRLSM" => "",
				"TGLRETRLSM" => "",
				"THSMSTRLSM" => "",
				"id"         => ""
			);

			if ($data = $this->M->getAll($args['get'])){
				$this->outputJSON($data, 200);
				return;
			}
			$this->outputJSON($default, 200);
			return;
		}
		
		$dumpData = array(
			"THSMSTRLSM" => $this->epsbed->getTahunSmt(),
			"KDPTITRLSM" => $this->req->post("KDPTIMSMHS"),
			"KDJENTRLSM" => $this->req->post("KDJENMSMHS"),
			"KDPSTTRLSM" => $this->req->post("KDPSTMSMHS"),//
			"NIMHSTRLSM" => $this->req->post("NIMHSTRLSM"),
			"STMHSTRLSM" => $this->req->post("STMHSTRLSM"),
			"TGLLSTRLSM" => $this->req->post("TGLLSTRLSM"),
			"SKSTTTRLSM" => $this->req->post("SKSTTTRLSM"),
			"NLIPKTRLSM" => $this->req->post("NLIPKTRLSM"),
			"NOSKRTRLSM" => $this->req->post("NOSKRTRLSM"),
			"TGLRETRLSM" => $this->req->post("TGLRETRLSM"),
			"NOIJATRLSM" => $this->req->post("NOIJATRLSM"),
			"STLLSTRLSM" => $this->req->post("STLLSTRLSM"),
			"JNLLSTRLSM" => $this->req->post("JNLLSTRLSM"),
			"BLAWLTRLSM" => $this->req->post("BLAWLTRLSM"),
			"BLAKHTRLSM" => $this->req->post("BLAKHTRLSM"),
			"NODS1TRLSM" => $this->req->post("NODS1TRLSM"),
			"NODS2TRLSM" => $this->req->post("NODS2TRLSM")
			);

		$this->save($args, $dumpData);

		if ($this->req->post('JUDULTRSKR')){
			$args['save'] = array(
				'PK' => 'IDTRSKR',
				'table' => 'trskr',
				'callback'=> 'NIMHSTRSKR'
				);

			$dumpData = array(
				"THSMSTRSKR" => $this->epsbed->getTahunSmt(),
				"KDPTITRSKR" => $this->req->post("KDPTIMSMHS"),
				"KDJENTRSKR" => $this->req->post("KDJENMSMHS"),
				"KDPSTTRSKR" => $this->req->post("KDPSTMSMHS"),
				"NIMHSTRSKR" => $this->req->post("NIMHSTRLSM"),
				"JUDULTRSKR" => $this->req->post("JUDULTRSKR")
				);

			if ( $data['status'] = $this->epsbed->validate() ) {
				$data['value']= $this->epsbed->value();

				$exception = array_flip( array('webix_operation','value','status','messages','id') );

				$dumpData = array_diff_key( $dumpData, $exception );	
				
				if ( $messages = $this->M->save($args['save'], $dumpData) ) 
					$data['messages'] = $messages;
			}	

			if ( $messages = $this->epsbed->errorMessages() )
				$data['messages'] = $messages;
		}

		if($this->req->post('webix_operation') == 'delete'){
			$argsTRSKR = array(
				'table' => 'trskr',
				'PK' => 'NIMHSTRSKR',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				);

			$this->M->deleteOne($argsTRSKR);
		}
	}

	public function tbdos($request='get'){
		$args = array(
			'get' => array(
				'table' => 'V_TBDOS_SEARCH',
				'PK' =>'NIDNNTBDOS'
				),
			'save' => array(
				'id' => $this->req->post('id', FILTER_SANITIZE_NUMBER_INT),
				'PK' => 'nidn',
				'table' => 'dosen',
				'callback'=> 'nm_ptk'
				),
			'remove' => array(
				'table' => 'tbdos',
				'PK' => 'NIDNNTBDOS',
				'criteria' => array($this->req->post('id', FILTER_SANITIZE_NUMBER_INT)),
				),
			'rules' => null
			);
		
		if ($request == 'detail'){
			$args['get']['table'] = 'dosen';
			$args['get']['PK'] = 'nidn';

			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			if ($this->req->get('action') == 'get'){
				$id = $this->req->get('id');
				$args['get']['criteria'] = array('nidn'=>$id);
			}

			echo $this->M->getAll($args['get'],'json');
			return;
		}

		if($request=='get'){
			if( $filter = $this->req->get('filter'))
				$args['get']['criteria'] = $filter;
			
			echo $this->M->getAll($args['get'],'json');
			return;
		}

		if($request=='sugest'){
			$args['sugest'] = array(
				'table'   => 'tbdos',
				'PK'      => 'NIDNNTBDOS',
				'fields'  => array('NMDOSTBDOS AS value'),
				'orderBy' => 'id',
				'sort'    => 'desc'
				);

			if( $filter = $this->req->get('filter')){
				$args['sugest']['criteria']['NMDOSTBDOS'] = $filter['value'];
			}

			echo $this->M->getAllLike($args['sugest'],'json');
			return;
		}
		
		$dumpData = $this->req->post(false, FILTER_SANITIZE_STRING);
		$exception = array('PTINDTBDOS','KDJENTBDOS','KDPSTTBDOS','NIDNNTBDOS');
		$dumpData = $this->exception($dumpData, $exception);	

		$this->save($args, $dumpData);
	}

} 
