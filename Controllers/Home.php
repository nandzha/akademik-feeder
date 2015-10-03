<?php
namespace Controllers;

use Dhtmlx\Connector;
use Libraries\AppResources;
use Models;
use Resources;

class Home extends AppResources\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_login();
    }

    private function is_login()
    {
        if (!$this->login()) {
            $this->redirect('login');
        }

    }

    public function index()
    {
        return $this->view->render('admin-dashboard.html');
    }

    public function dhtmlx()
    {
        $this->dhtmlx = new Models\Dhtmlx;
        return $this->dhtmlx->test();
    }

    public function model()
    {
        $this->db = new Resources\Database('pddikti');
        $this->conn = new Connector\JSONDataConnector($this->db, "MySQLi");
        $this->conn->configure("events", "event_id", "start_date, end_date, event_name");
        $this->conn->useModel(new Models\Event);
        $this->conn->dynamic_loading(30);
        $this->conn->render();
        // return $this->dhtmlx->model();
    }

}
