<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;
use Libraries;

class KelasKuliah extends Resources\Validation
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
        if ($this->session->getValue('desc') != 'ALL') {
            $this->conn->filter('id_sms', $this->session->getValue('desc'), '=');
        }

        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->sort("nm_prodi ASC, id_smt DESC, nm_mk ASC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("list_kelas_perkuliahan_view", "id_kls", $this->setFields("list"));
    }

    public function setRules()
    {
        return [
            'id_smt' => [
                'rules' => ['required']
            ],
            'id_sms' => [
                'rules' => ['required']
            ],
            'id_mk' => [
                'rules' => ['required']
            ],
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

    protected function get_values($action)
    {
        $this->data = [
            'id_sms'       => $action->get_value("id_sms"),
            'id_smt'       => $action->get_value("id_smt"),
            'id_mk'        => $action->get_value("id_mk"),
            'nm_kls'       => $action->get_value("nm_kls"),
            'sks_mk'       => $action->get_value("sks_mk"),
            'sks_tm'       => $action->get_value("sks_tm"),
            'sks_prak'     => $action->get_value("sks_prak"),
            'sks_prak_lap' => $action->get_value("sks_prak_lap"),
            'sks_sim'      => $action->get_value("sks_sim"),

        ];
    }

    protected function setFields($table)
    {
        $fields = [
            "list" => [
                "id_kls",
                "id_sms",
                "id_mk",
                "nm_prodi",
                "id_smt",
                "kode_mk",
                "nm_mk",
                "nm_kls",
                "sks_mk",
                "nm_smt"
            ]
        ];
        return implode(",", $fields[$table]);
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
        $action->set_id($this->uuid->v4());
        $this->data['id_kls'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("kelas_kuliah", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("kelas_kuliah", $this->data, array("id_kls" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("kelas_kuliah", array("id_kls" => $action->get_id()));
        $action->success();
    }

}
