<?php
namespace Modules\Accounting\Controllers;
use Resources, Libraries\AppResources;

class Home extends AppResources\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    	$data['path'] = $this->view->getViewPath();
        return $this->view->render('admin-dashboard.html', $data);
    }

    public function content(){
    	return $this->view->render('admin-content.html', $data);
    }
}
