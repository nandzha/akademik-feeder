<?php
namespace Libraries\AppResources;

use Resources, Models, Libraries;
use Libraries\AppResources\View;
use Libraries\AppResources\TwigDriver;

/**
 * Custom controller untuk TDD (triptodieng.com)
 */
class Controller extends Resources\Controller {

	/**
	 * @var TDD\AppResources\View
	 */
	protected $view, $viewPath;
	private
		$viewFile,
		$configMain;

	/**
	 * Contructor
	 */
	public function __construct() {
		parent::__construct();

		$child = get_class($this);

        $this->childClass = array(
                            'namespaceArray' => explode( '\\', $child),
                            'namespaceString' => $child
                        );
        $this->configMain = Resources\Config::main();

        $this->setViewPath();
        $this->view    = new View(new TwigDriver, $this->viewPath);
        $this->request = new Resources\Request;
        $this->session = new Resources\Session;
	}

	private function setViewPath(){
		$this->viewPath = APP.'views';

		if( $this->childClass['namespaceArray'][0] == 'Modules' ){
            $this->viewPath = $this->configMain['module']['path'].$this->childClass['namespaceArray'][0].'/'.$this->childClass['namespaceArray'][1].'/views/';
        }
	}

	public function login(){
		if( $this->session->getValue('penggunaId') ){
			return true;
		}

		return false;

	}

    public function propertyAkademik($idsmt=false){
        $helper = new Models\Helpers;

        $SessionidSmt = $idsmt;
        if($idsmt = $this->session->getValue('idsmt')){
            $SessionidSmt = $idsmt;
        }

        $semester = $helper->getSemester($SessionidSmt);
        $data = [
            'idsmt'    => $this->session->getValue('idsmt'),
            'semester' => $semester->nm_smt
        ];
        return $data;
    }

	private function isAccessGranted($module){
		$this->request = new Resources\Request;
		$this->user    = new Models\Users;

        if( ! $next = $this->request->get('next',FILTER_SANITIZE_URL,FILTER_VALIDATE_URL) )
                $next = $module;

        $args  = array(
            'on'    => array(
                'a.group_id'=>$this->session->getValue('grupId')
            ),
            'where' => array(
                'b.type'=>'back',
                'a.is_active'=>'1',
                'a.group_id'=>$this->session->getValue('grupId'),
                'b.url' => $next
            ),
            'fields' => array('b.url')
        );

        if ($this->user->getOneRole($args))
            return true;

        return false;
    }

    /**
     * Adding Middle Layer to authenticate every request
     * Checking if the request has valid api key in the 'Authorization' header
     */
    private function authenticate() {
        // Getting request headers
        $headers = apache_request_headers();
        $response = array();

        // Verifying Authorization Header
        if (isset($headers['Authorization'])) {

            // get the api key
            $api_key = $headers['Authorization'];
            // validating api key
            if (!$this->user->isValidApiKey($api_key)) {
                // api key is not present in users table
                $response["error"] = true;
                $response["message"] = "Access Denied. Invalid Api key";
                $this->outputJSON($response, 200);
                exit;
            } else {
                global $user_id;
                // get user primary key id
                $user_id = $this->user->getUserId($api_key);
            }
        } else {
            // api key is missing in header
            $response["error"] = true;
            $response["message"] = "Api key is misssing";
            $this->outputJSON($response, 200);
            exit;
        }
    }

    /**
     * Verifying required params posted or not
     */
    private function verifyRequiredParams($required_fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;

        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }

        if ($error) {
            // Required field(s) are missing or empty
            // echo error json and stop the app
            $response = array();
            $response["error"] = true;
            $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';

            $this->outputJSON($response, 200);
            exit;
        }
    }

}
