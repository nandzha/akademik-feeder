<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;

//model is a class, which will be used for all data operations
//we expect that it has next methods get, update, insert, delete
//if method was not defined - we will use default logic
class Event extends Resources\Validation
{
    protected $data = [];
    protected $checkEventName = true;

    public function __construct()
    {
        parent::__construct();
        $this->db = new Resources\Database;
        $this->conn = new Connector\JSONDataConnector($this->db, "MySQLi");
        // $this->val     = new Validation($this->setRules());
    }

    public function init()
    {
        $this->conn->configure("test_events", "event_id", "start_date, end_date, event_name");
        $this->conn->useModel($this);
        // $this->conn->filter("event_name","%tennis%","like");
        // $this->conn->filter("event_name like '%tennis%' and start_date like '%2014-07-27%'");
        $this->getFilter();
        $this->conn->dynamic_loading(30);
        //bisa menggunakan render_sql
        $this->conn->render();
    }

    public function setRules()
    {
        return [
            'event_name' => [
                'rules' => ['required', 'min' => 3, 'callback' => 'eventNameIsExist'],
                'label' => 'Nama Event',
                'filter' => ['trim', 'strtolower', 'ucwords'],
            ],
        ];
    }

    /*public function get($request){
    $data = $this->db->getAll("test_events");
    return $this->convertToArray($data);
    }

    protected function convertToArray($data){
    foreach ($data as $row) {
    $result[] = (array) $row;
    }
    return $result;
    }*/

    protected function getFilter()
    {
        $request = new Resources\Request;
        $filters = $request->get('filter');

        if ($filters) {
            $filter = "";
            foreach ($filters as $key => $value) {
                $filter .= $key . " like '%" . $value . "%' AND ";
            }

            $filter = rtrim($filter, "AND ");

            $this->conn->filter($filter);
        }
        return false;
    }

    protected function eventNameIsExist($field, $value, $label)
    {
        $v = $this->value();

        if (!$this->checkEventName) {
            return true;
        }

        $criteria = [
            'event_name' => $value,
        ];

        $eventName = $this->db->getOne('test_events', $criteria);
        if (!$eventName) {
            return true;
        }

        $this->setErrorMessage($field, $label . ' already exists');

        return false;
    }

    protected function get_values($action)
    {
        // $action->set_id($this->conn->uuid());
        $this->data['event_name'] = $action->get_value("event_name");
        $this->data['start_date'] = $action->get_value("start_date");
        $this->data['end_date'] = $action->get_value("end_date");
    }

    protected function validation($action)
    {

        if (!$this->validate($this->data)) {
            // die (var_dump( $this->val->value() ) );
            $action->invalid();
            $action->set_response_attribute("details", $this->messages());
            return false;
        }
        return true;
    }

    protected function messages()
    {
        $msg = $this->errorMessages();
        $text = "";

        if ($msg) {
            foreach ($msg as $key => $value) {
                $text .= $key . " : " . $value . ", ";
            }
        }

        $text = rtrim($text, ", ");
        return $text;
    }

    public function insert($action)
    {
        $this->get_values($action);

        // die(var_dump($action));
        /*if( !$this->validate($this->data) ){
        die (var_dump( $this->errorMessages() ) );
        }*/

        if ($this->validation($action)) {
            // die (var_dump( $this->value() ) );
            $this->db->insert("test_events", $this->data);
            $action->success($this->db->insertId());
            $action->set_response_attribute("foo", "ini fuulan insert");
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("test_events", $this->data, array("event_id" => $action->get_id()));
            $action->success();
            $action->set_response_attribute("foo", "ini fuulan update");
        }
    }

    public function delete($action)
    {
        $this->db->delete("test_events", array("event_id" => $action->get_id()));
        $action->success();
        $action->set_response_attribute("foo", "ini fuulan delete");
    }

}
