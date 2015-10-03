<?php
namespace Models;

use Dhtmlx\Connector;
use Libraries;
use Resources;

class DosenRiwayatStruk extends Resources\Validation
{
    protected $data = [];
    protected $checkEventName = true;

    public function __construct()
    {
        parent::__construct();
        $this->db = new Resources\Database('pddikti');
        $this->conn = new Connector\JSONDataConnector($this->db, "MySQLi");
        $this->uuid = new Libraries\UUID;
        $this->session = new Resources\Session;

    }

    public function init()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->sort("id_dos_struktural DESC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("dosen_struktural_view", "id_dos_struktural", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_sms' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_sp" => "6d0ac338-04d4-40e7-82ed-efbf5b66e956",
            "id_ptk" => $action->get_value("id_ptk"),
            "id_sms" => $action->get_value("id_sms"),
            "sk_jabatan" => $action->get_value("sk_jabatan"),
            "id_jabstruk" => $action->get_value("id_jabstruk"),
            "tgl_sk_jabatan" => $action->get_value("tgl_sk_jabatan"),
            "tmt_jabatan" => $action->get_value("tmt_jabatan"),
        ];
    }

    protected function setFilter()
    {
        $request = new Resources\Request;
        $filters = $request->get('filter');

        if ($filters) {
            $filter = "";

            foreach ($filters as $key => $value) {
                $filter .= $key . " like '" . $value . "%' AND ";
            }

            $filter = rtrim($filter, "AND ");
            $this->conn->filter($filter);
        }
        return false;
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

    protected function setFields()
    {
        $fields = [
            "nm_jabstruk",
            "tgl_sk_jabatan",
            "sk_jabatan",
            "tmt_jabatan",
        ];
        return implode(",", $fields);
    }

    public function insert($action)
    {
        $this->get_values($action);
        $action->set_id($this->uuid->v4());
        $this->data['id_dos_struktural'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("dosen_struktural", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("dosen_struktural", $this->data, array("id_dos_struktural" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("dosen_struktural", array("id_dos_struktural" => $action->get_id()));
        $action->success();
    }

}
