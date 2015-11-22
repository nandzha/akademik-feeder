<?php
namespace Models;

use Libraries\AppResources;

class KuliahAktifitas extends AppResources\Models
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
        $this->conn->dynamic_loading(30);
        $this->conn->render_sql("");
    }

    protected function get_values($action)
    {
        $this->data['event_name'] = $action->get_value("event_name");
        $this->data['start_date'] = $action->get_value("start_date");
        $this->data['end_date'] = $action->get_value("end_date");
    }

    public function insert($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->insert("kuliah_mahasiswa", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("kuliah_mahasiswa", $this->data, array("id_kuliah_pd" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("kuliah_mahasiswa", array("id_kuliah_pd" => $action->get_id()));
        $action->success();
    }

}
