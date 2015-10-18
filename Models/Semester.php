<?php
namespace Models;

use Libraries\AppResources;

class Semester extends AppResources\Models
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'mahasiswa';
    }

    public function init()
    {
        $this->conn->useModel($this);
        $this->conn->filter("a_periode_aktif='1'");
        $this->setFilter();
        $this->conn->sort("id_smt DESC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("semester", "id_smt", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_smt' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function setFields()
    {
        $fields = [
            "id_smt",
            "id_thn_ajaran",
            "nm_smt",
        ];
        return implode(",", $fields);
    }

    protected function get_values($action)
    {
        $this->data = [
            'id_smt' => $action->get_value("id_smt"),
            'nm_smt' => $action->get_value("nm_smt"),
        ];
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
