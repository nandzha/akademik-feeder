<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;

class MahasiswaAktifitas extends Resources\Validation
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
        $this->conn->sort("id_smt DESC");
        $this->setFilter();
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("mahasiswa_aktifitas_kuliah_view", "id_kuliah_pd", $this->setFields('list'));
    }

    public function detail()
    {
        /*
        // http://docs.dhtmlx.com/connector__php__mixedconnector.html
        $mhs_pt = new Connector\JSONDataConnector($this->db, "MySQLi");
        $mhs_pt->configure("mahasiswa_pt", "id_reg_pd", "nipd");
        $mhs = new Connector\JSONDataConnector($this->db, "MySQLi");
        $mhs->configure("mahasiswa","id_pd","nm_pd");
        $conn = new Connector\MixedConnector($this->db, "MySQLi");
        $conn->add("data", $mhs_pt);
        $conn->add("data", $mhs);
        $conn->render();
        */
        $this->setFilter();
        $this->conn->dynamic_loading(10);
        $this->conn->sort("id_smt DESC");
        $this->conn->render_sql("
            SELECT
                a.id_kuliah_pd,
                b.nipd,
                b.nm_pd,
                b.nm_lemb AS nm_prodi,
                b.mulai_smt,
                a.id_smt,
                c.nm_stat_mhs,
                a.ips,
                a.ipk,
                a.sks_smt,
                a.sks_total,
                a.id_reg_pd,
                a.id_stat_mhs
            FROM
                kuliah_mahasiswa a
            INNER JOIN (
                SELECT
                    aa.id_reg_pd,
                    aa.nipd,
                    bb.nm_pd,
                    cc.nm_lemb,
                    aa.mulai_smt
                FROM
                    mahasiswa_pt aa
                INNER JOIN mahasiswa bb ON aa.id_pd = bb.id_pd
                INNER JOIN sms cc ON aa.id_sms = cc.id_sms
            ) b ON a.id_reg_pd = b.id_reg_pd
            LEFT JOIN status_mahasiswa c ON a.id_stat_mhs = c.id_stat_mhs
        ", "id_kuliah_pd", $this->setFields('detail'));
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

    protected function get_values($action)
    {
        $this->data['event_name'] = $action->get_value("event_name");
        $this->data['start_date'] = $action->get_value("start_date");
        $this->data['end_date'] = $action->get_value("end_date");
    }
    protected function setFields($table)
    {
        $fields = [
            "list" => [
                "id_smt",
                "id_reg_pd",
                "nm_stat_mhs",
                "ips",
                "ipk",
                "sks_smt",
                "sks_total",
                ],
            "detail"=>[
                "id_smt",
                "nipd",
                "nm_pd",
                "nm_prodi",
                "mulai_smt",
                "nm_stat_mhs",
                "ips",
                "ipk",
                "sks_smt",
                "sks_total"
                ]
        ];
        return implode(",", $fields[$table]);
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
