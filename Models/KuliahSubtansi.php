<?php
namespace Models;

use Dhtmlx\Connector;
use Libraries;
use Resources;

class KuliahSubtansi extends Resources\Validation
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
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("kuliah_subtansi", "id_subst", $this->setFields());
    }

    public function setRules()
    {
        return [
            'nm_subst' => [
                'rules' => ['required'],
            ],
            'id_sms' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            "id_sms" => $action->get_value("id_sms"),
            "nm_subst" => $action->get_value("nm_subst"),
            "id_jns_subst" => $action->get_value("id_jns_subst"),
            "sks_mk" => $action->get_value("sks_mk"),
            "sks_tm" => $action->get_value("sks_tm"),
            "sks_prak" => $action->get_value("sks_prak"),
            "sks_prak_lap" => $action->get_value("sks_prak_lap"),
            "sks_sim" => $action->get_value("sks_sim"),
        ];
    }

    protected function setFields()
    {
        $fields = [
            "id_subst",
            "nm_subst",
            "id_jns_subst",
            "nm_jns_subst",
            "id_sms",
            "nm_prodi",
            "sks_mk",
            "sks_tm",
            "sks_prak",
            "sks_prak_lap",
            "sks_sim",
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
        $this->data['id_subst'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("substansi_kuliah", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("substansi_kuliah", $this->data, array("id_subst" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("substansi_kuliah", array("id_subst" => $action->get_id()));
        $action->success();
    }

}
