<?php
namespace Models;

use Dhtmlx\Connector;
use Libraries;
use Resources;

class DosenRiwayatSert extends Resources\Validation
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
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("dosen_sertifikasi_view", "id_dos_sert", $this->setFields());
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
            "no_peserta" => $action->get_value("no_peserta"),
            "bid_studi" => $action->get_value("bid_studi"),
            "id_jns_sert" => $action->get_value("id_jns_sert"),
            "thn_sert" => $action->get_value("thn_sert"),
            "no_sk_sert" => $action->get_value("no_sk_sert"),
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

    protected function setFields()
    {
        $fields = [
            "nm_jns_sert",
            "no_peserta",
            "no_sk_sert",
            "thn_sert",
            "bid_studi",
        ];
        return implode(",", $fields);
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
        $this->data['id_dos_sert'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("dosen_sertifikasi", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("dosen_sertifikasi", $this->data, array("id_dos_sert" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("dosen_sertifikasi", array("id_dos_sert" => $action->get_id()));
        $action->success();
    }

}
