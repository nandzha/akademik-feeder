<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;

class Semester extends Resources\Validation
{
    protected $data = [];
    protected $checkEventName = true;

    public function __construct()
    {
        parent::__construct();
        $this->db = new Resources\Database('pddikti');
        $this->conn = new Connector\JSONDataConnector($this->db, "MySQLi");
        $this->session = new Resources\Session;
    }

    public function init()
    {
        $this->conn->useModel($this);
        $this->conn->filter("a_periode_aktif='1'");
        $this->getFilter();
        $this->conn->sort("id_smt DESC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("semester","id_smt",$this->setFields());
    }

    public function setRules()
    {
        return [
            'id_smt' => [
                'rules' => ['required']
            ]
        ];
    }

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

    protected function setFields()
    {
        $fields = [
            "id_smt",
            "id_thn_ajaran",
            "nm_smt"
        ];
        return implode(",", $fields);
    }

    protected function get_values($action)
    {
        $this->data = [
            'id_smt' => $action->get_value("id_smt"),
            'nm_smt' => $action->get_value("nm_smt")
        ];
    }

    protected function validation($action)
    {

        if (!$this->validate($this->data)) {
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

        if ($this->validation($action)) {
            $this->db->insert("semester", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("semester", $this->data, array("id_smt" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("semester", array("id_smt" => $action->get_id()));
        $action->success();
    }

}
