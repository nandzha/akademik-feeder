<?php
namespace Controllers\Admin;

use Dhtmlx\Connector;
use Libraries\AppResources;
use Models;
use Resources;

class Dashboard extends AppResources\Controller
{
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->is_login();
        $this->data = $this->propertyAkademik();
    }

    private function is_login()
    {
        if (!$this->login()) {
            $this->redirect('login');
        }

    }

    public function index()
    {
        return $this->view->render('admin-dashboard.html', $this->data);
    }
}
