<?php
namespace Models;

use Dhtmlx\Connector;
use Libraries;
use Resources;

class DosenPenugasan extends Resources\Validation
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
        $this->conn->sort("id_thn_ajaran", "DESC");
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("dosen_penugasan_view", "id_reg_ptk", $this->setFields());
    }

    public function setRules()
    {
        return [
            'no_srt_tgs' => [
                'rules' => ['required'],
                'label' => 'No Srt Tugas',
            ],
            'id_ptk' => [
                'rules' => ['required'],
            ],
            'id_thn_ajaran' => [
                'rules' => ['required'],
                'label' => 'Thn Ajaran',
            ],
            'id_sms' => [
                'rules' => ['required'],
                'label' => 'Prodi',
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

    protected function setFields()
    {
        $fields = [
            "id_thn_ajaran",
            "nm_thn_ajaran",
            "id_sms",
            "nm_prodi",
            "no_srt_tgs",
            "tgl_srt_tgs",
            "tmt_srt_tgs",
        ];
        return implode(",", $fields);
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_sp" => "6d0ac338-04d4-40e7-82ed-efbf5b66e956",
            "id_ptk" => $action->get_value("id_ptk"),
            "id_sms" => $action->get_value("id_sms"),
            "id_thn_ajaran" => $action->get_value("id_thn_ajaran"),
            "no_srt_tgs" => $action->get_value("no_srt_tgs"),
            "tgl_srt_tgs" => $action->get_value("tgl_srt_tgs"),
            "tmt_srt_tgs" => $action->get_value("tmt_srt_tgs"),
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
        $action->set_id($this->uuid->v4());
        $this->data['id_reg_ptk'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("dosen_pt", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("dosen_pt", $this->data, array("id_reg_ptk" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("dosen_pt", array("id_reg_ptk" => $action->get_id()));
        $action->success();
    }

}
