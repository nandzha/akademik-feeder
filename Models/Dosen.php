<?php
namespace Models;

use Dhtmlx\Connector;
use Libraries;
use Resources;

class Dosen extends Resources\Validation
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
        $this->setFilter();
        $this->conn->sort("nm_ptk", "ASC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_table("dosen_list_view", "id_ptk", $this->setFields("list"));
    }

    public function detail()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->render_table("dosen_detail_view", "id_ptk", $this->setFields("detail"));
    }

    public function setRules()
    {
        return [
            'nm_ptk' => [
                'rules' => ['required', 'min' => 3],
                'label' => 'Nama Dosen',
                'filter' => ['trim', 'strtoupper', 'ucwords'],
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

    protected function setFields($table)
    {
        $fields = [
            "list" => [
                "id_sms",
                "id_ptk",
                "id_reg_ptk",
                "nidn",
                "nm_ptk",
                "jk",
                "tmpt_lahir",
                "nm_agama",
                "nm_stat_pegawai",
                "nm_lemb",
            ],
            "detail" => [
                "nm_ptk", "tmpt_lahir", "jk", "id_stat_aktif", "tgl_lahir", "id_agama",
                "nidn", "nm_ibu_kandung", "nip", "nik", "npwp", "id_ikatan_kerja", "sk_cpns",
                "sk_angkat", "id_lemb_angkat", "id_stat_pegawai", "tgl_sk_cpns", "tmt_sk_angkat",
                "id_pangkat_gol", "jln", "nm_dsn", "rt", "rw", "ds_kel", "no_tel_rmh", "kode_pos",
                "no_hp", "email", "stat_kawin", "nm_suami_istri", "nip_suami_istri", "tmt_pns",
                "id_pekerjaan_suami_istri",
            ],
        ];
        return implode(",", $fields[$table]);
    }

    protected function get_values($action)
    {
        $this->data = [
            'nm_ptk' => $action->get_value("nm_ptk"),
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
        $this->data['id_ptk'] = $action->get_id();

        if ($this->validation($action)) {
            $this->db->insert("dosen", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($this->validation($action)) {
            $this->db->update("dosen", $this->data, array("id_ptk" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("dosen", array("id_ptk" => $action->get_id()));
        $action->success();
    }

}
