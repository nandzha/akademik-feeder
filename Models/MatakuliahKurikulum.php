<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;

class MatakuliahKurikulum extends Resources\Validation
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
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("mata_kuliah_kurikulum_smt", "id_mk_kurikulum", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_mk' => [
                'rules' => ['required'],
            ],
            'id_kurikulum_sp' => [
                'rules' => ['required'],
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
            "id_kurikulum_sp" => $action->get_value("id_kurikulum_sp"),
            "id_mk" => $action->get_value("id_mk"),
            "smt" => $action->get_value("smt"),
            "sks_mk" => $action->get_value("sks_mk"),
            "sks_tm" => $action->get_value("sks_tm"),
            "sks_prak" => $action->get_value("sks_prak"),
            "sks_prak_lap" => $action->get_value("sks_prak_lap"),
            "sks_sim" => $action->get_value("sks_sim"),
            "a_wajib" => $action->get_value("a_wajib"),
        ];
    }

    protected function setFields()
    {
        $fields = [
            "id_mk_kurikulum",
            "id_kurikulum_sp",
            "id_mk",
            "nm_kurikulum_sp",
            "kode_mk",
            "nm_mk",
            "sks_mk",
            "sks_tm",
            "sks_prak",
            "sks_prak_lap",
            "sks_sim",
            "smt",
            "a_wajib",
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

        if ($this->validation($action)) {
            $this->db->insert("mata_kuliah_kurikulum", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("mata_kuliah_kurikulum", $this->data, array("id_mk_kurikulum" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("mata_kuliah_kurikulum", array("id_mk_kurikulum" => $action->get_id()));
        $action->success();
    }

}
