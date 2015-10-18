<?php
namespace Controllers;

use Libraries\AppResources;
use Models;
use Resources;
use Libraries;

class Test extends AppResources\Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->dhtmlx = new Models\Event;
        $this->request = new Resources\Request;
        $this->user = new Models\Users;
    }

    public function index()
    {
        return $this->view->render('test-event.html');
    }

    public function dhtmlx()
    {
        return $this->dhtmlx->test();
    }

    public function model()
    {
        return $this->dhtmlx->init();
    }

    /*
    http://localhost/task_manager/v1/register   POST    name, email, password   User registration
    http://localhost/task_manager/v1/login  POST    email, password User login
    http://localhost/task_manager/v1/tasks  POST    task    To create new task
    http://localhost/task_manager/v1/tasks  GET     Fetching all tasks
    http://localhost/task_manager/v1/tasks/:id  GET     Fetching single task
    http://localhost/task_manager/v1/tasks/:id  PUT     Updating single task
    http://localhost/task_manager/v1/tasks/:id  DELETE  task, status    Deleting single task
     */

    /**
     * Adding Middle Layer to authenticate every request
     * Checking if the request has valid api key in the 'Authorization' header
     */
    private function authenticate()
    {
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
    private function verifyRequiredParams($required_fields)
    {
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

    /**
     * Validating email address
     */
    private function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response["error"] = true;
            $response["message"] = 'Email address is not valid';
            $this->outputJSON($response, 200);
            exit;
        }
    }

    public function apiRegister()
    {
        $name = $this->request->post('name');
        $email = $this->request->post('email');
        $password = $this->request->post('password');

        // validating email address
        $this->validateEmail($email);

        $data = $this->user->createUser($name, $email, $password);
        $this->outputJSON($data, 200);
    }

    public function apiLogin()
    {
        $this->verifyRequiredParams(array('email', 'password'));

        $email = $this->request->post('email');
        $password = $this->request->post('password');

        if ($this->user->checkLogin($email, $password)) {
            $user = $this->user->getUserByEmail($email);
            if ($this->user) {
                $response["error"] = false;
                $response['name'] = $user->user_login;
                $response['email'] = $user->user_email;
                $response['apiKey'] = $user->api_key;
                $response['createdAt'] = $user->user_registered;
            } else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "An error occurred. Please try again";
            }
        } else {
            // user credentials are wrong
            $response['error'] = true;
            $response['message'] = 'Login failed. Incorrect credentials';
        }

        $this->outputJSON($response, 200);
    }

    public function apiTask($id = false)
    {
        $this->authenticate();

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && !$id) {
            global $user_id;
            $response = array();

            // fetching all user tasks
            $results = $this->user->getAllUserTasks($user_id);

            $response["error"] = false;
            $response["tasks"] = $results;

            $this->outputJSON($response, 200);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$id) {
            $this->verifyRequiredParams(array('task'));

            $response = array();
            $task = $this->request->post('task');

            global $user_id;

            // creating new task
            $task_id = $this->user->createTask($user_id, $task);

            if ($task_id != null) {
                $response["error"] = false;
                $response["message"] = "Task created successfully";
                $response["task_id"] = $task_id;
                $this->outputJSON($response, 201);
            } else {
                $response["error"] = true;
                $response["message"] = "Failed to create task. Please try again";
                $this->outputJSON($response, 200);
            }
            exit;
        }

        if ((int) $id) {
            // $this->rest->requestMethod;
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                global $user_id;
                $response = array();
                // fetch task
                $result = $this->user->getTask($id, $user_id);

                if ($result != null) {
                    $response["error"] = false;
                    $response["details"] = $result;
                    $this->outputJSON($response, 200);
                    exit;
                } else {
                    $response["error"] = true;
                    $response["message"] = "The requested resource doesn't exists";
                    $this->outputJSON($response, 200);
                    exit;
                }
            }

            if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
                // check for required params
                $this->verifyRequiredParams(array('task', 'status'));

                global $user_id;
                $task = $this->request->put('task');
                $status = $this->request->put('status');
                $response = array();

                // updating task
                $result = $this->user->updateTask($user_id, $task_id, $task, $status);
                if ($result) {
                    // task updated successfully
                    $response["error"] = false;
                    $response["message"] = "Task updated successfully";
                } else {
                    // task failed to update
                    $response["error"] = true;
                    $response["message"] = "Task failed to update. Please try again!";
                }
                $this->outputJSON($response, 200);
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                global $user_id;
                $response = array();

                $result = $this->user->deleteTask($user_id, $task_id);
                if ($result) {
                    // task deleted successfully
                    $response["error"] = false;
                    $response["message"] = "Task deleted succesfully";
                } else {
                    // task failed to delete
                    $response["error"] = true;
                    $response["message"] = "Task failed to delete. Please try again!";
                }
                $this->outputJSON($response, 200);
                exit;
            }
        }
    }

    /**
     * baca form validation rule dati database
     * @return [type] [description]
     */
    public function rules(){
        $helpers = new Models\Helpers;
        $rules = $helpers->get_rules('mahasiswa');
        var_dump($rules);
    }

    /**
     * explode campuran assosiative
     * @return [type] [description]
     */
    public function walk(){
        $string  = "required,min=>3";
        $partial = explode(',', $string);
        $final   = array();
        array_walk($partial, function($val, $key) use(&$final){
            $subArray = explode('=>', $val);
            if ( count($subArray) == 1 )
                $final[$key] = $val;
            else{
                list($key, $value) = $subArray;
                $final[$key] = $value;
            }
        });
        var_dump($final);
    }

    /**
     * model versi baru ambil dari library appresource
     * @return [type] [description]
     */
    public function models(){
        $test = new Models\Test;
        // $test->init();
        var_dump($test->setRules());
    }

    public function nilaiExcel(){
        $nilai = new Models\ReadNilai;
        $data = $nilai->readExcel();

        // die(var_dump($nilai->readExcel()));
        $this->outputJSON($data, 200);
    }

    public function nilaiDb(){
        $nilai = new Models\ReadNilai;
        $data = $nilai->readDb();
        $this->outputJSON($data, 200);
    }
}
