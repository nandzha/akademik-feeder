<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;

class MahasiswaKrs extends Resources\Validation
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
        $this->conn->dynamic_loading(50);
        $this->conn->sort("id_smt DESC");
        $this->setFilter();
        $this->conn->render_sql("
            SELECT
                a.id_nilai,
                a.id_kls,
                a.id_reg_pd,
                b.*
            FROM
                nilai a
            JOIN (
                SELECT
                    aa.id_sms,
                    aa.id_kls,
                    bb.id_mk,
                    bb.kode_mk,
                    bb.nm_mk,
                    aa.sks_mk,
                    aa.nm_kls,
                    aa.id_smt
                FROM
                    kelas_kuliah aa
                JOIN mata_kuliah bb ON aa.id_mk = bb.id_mk
            ) b ON a.id_kls = b.id_kls
        ", "id_nilai", $this->setFields());
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
            "id_smt",
            "kode_mk",
            "nm_mk",
            "nm_kls",
            "sks_mk",
        ];
        return implode(",", $fields);
    }

    protected function get_values($action)
    {
        // die(var_dump( $action->get_data() ));
        $this->data = [
            'id_kls' => $action->get_value("id_kls"),
            'id_reg_pd' => $action->get_value("id_reg_pd"),
            'asal_data' => "9",
            'nilai_huruf' => null,
            'nilai_angka' => null,
            'nilai_indeks' => null,
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
            $this->db->insert("nilai", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("nilai", $this->data, array("id_nilai" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("nilai", array("id_nilai" => $action->get_id()));
        $action->success();
    }

}
