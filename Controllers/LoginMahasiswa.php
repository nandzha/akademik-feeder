<?php
namespace Controllers;

use Libraries;
use Libraries\AppResources;
use Models;
use Resources;

class LoginMahasiswa extends AppResources\Controller
{
    protected $ruleType = 'mhsSignin';
    protected $pagesUrl = false;

    public function __construct()
    {
        parent::__construct();
        $this->session         = new Resources\Session;
        $this->helper          = new Models\Helpers;
        $this->user            = new Models\Users;
        $this->loginValidation = new Models\LoginValidation;
        $this->request         = new Resources\Request;
    }

    public function index()
    {

        if ($this->userId = $this->session->getValue('userId')) {
            $this->redirect('profile');
            return;
        }

        $requestSignature = new Libraries\RequestSignature;

        // Validate the POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$requestSignature->validate($this->request->post('signature'))) {
            Resources\Tools::setStatusHeader(404);
            throw new Resources\HttpException('Page not found! 4');
            // $data['msgUser'] = 'Signature is Wrong' ;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data['signature'] = $requestSignature->generate();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$next = $this->loginValidation->validateValues($this->ruleType)) {
                $data['msgUser'] = $this->loginValidation->errorMessages('username') ;
                $data['signature'] = $requestSignature->generate();
            } else {
                $this->redirect($this->pagesUrl . $next);
            }
        }

        return $this->view->render('mhs/mhs-login.html', $data);
    }

    public function setConfig($ruleType, $pagesUrl){
        $this->ruleType = $ruleType;
        $this->pagesUrl = $pagesUrl;
    }

    private function isAccessGranted($module)
    {
        if (!$next = $this->request->get('next', FILTER_SANITIZE_URL, FILTER_VALIDATE_URL)) {
            $next = $module;
        }

        $args = array(
            'on' => array(
                'a.group_id' => $this->session->getValue('grupId'),
            ),
            'where' => array(
                'b.type' => 'back',
                'a.is_active' => '1',
                'a.group_id' => $this->session->getValue('grupId'),
                'b.url' => $next,
            ),
            'fields' => array('b.url'),
        );

        if ($this->user->getOneRole($args)) {
            return true;
        }

        return false;
    }

    public function signout()
    {
        $this->session->destroy();
        $this->redirect('login');
    }
}
