<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;
use Libraries;

class DosenAktifitas extends Resources\Validation
{
    protected $data = [];
    protected $checkEventName = true;

    public function __construct()
    {
        parent::__construct();
        $this->db   = new Resources\Database('pddikti');
        $this->conn = new Connector\JSONDataConnector($this->db, "MySQLi");
        $this->uuid = new Libraries\UUID;
        $this->session = new Resources\Session;
    }

    public function init()
    {
        $this->conn->useModel($this);
        $this->setFilter();
        $this->conn->sort("id_smt DESC");
        $this->conn->dynamic_loading(30);
        $this->conn->render_sql("
            SELECT
                b.*,
                c.*,
                a.jml_tm_renc,
                a.jml_tm_real
            FROM ajar_dosen a
            LEFT JOIN (
                SELECT
                aa.id_ptk,
                aa.id_reg_ptk,
                bb.nm_ptk,
                bb.tmpt_lahir,
                bb.tgl_lahir,
                bb.jk,
                cc.nm_agama,
                dd.nm_stat_aktif
                FROM dosen_pt aa
                LEFT JOIN dosen bb ON aa.id_ptk = bb.id_ptk
                LEFT JOIN agama cc ON bb.id_agama = cc.id_agama
                LEFT JOIN status_keaktifan_pegawai dd ON bb.id_stat_aktif = dd.id_stat_aktif
            ) b ON a.id_reg_ptk = b.id_reg_ptk
            LEFT JOIN (
                SELECT
                aa.id_smt,
                aa.id_kls,
                bb.kode_mk,
                bb.nm_mk,
                aa.nm_kls
                FROM kelas_kuliah aa
                LEFT JOIN mata_kuliah bb ON aa.id_mk = bb.id_mk
            ) c ON a.id_kls = c.id_kls

        ", "id_ajar", $this->setFields());
    }

    public function setRules()
    {
        return [
            'id_kls' => [
                'rules' => ['required'],
            ],

            'id_reg_ptk' => [
                'rules' => ['required'],
            ],
        ];
    }

    protected function get_values($action)
    {
        $this->data = [
            'id_kls'      => $action->get_value('id_kls'),
            'id_reg_ptk'  => $action->get_value('id_reg_ptk'),
            'id_jns_eval' => '1'
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
            "jml_tm_renc",
            "jml_tm_real",
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
        return $this->value();
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
        $this->data['id_ajar'] = $action->get_id();

        if ($data = $this->validation($action)) {

            $this->db->insert("ajar_dosen", $this->data);
            $action->success($this->db->insertId());
        }
    }

    public function update($action)
    {
        $this->get_values($action);

        if ($data = $this->validation($action)) {
            $this->db->update("ajar_dosen", $this->data, array("id_ajar" => $action->get_id()));
            $action->success();
        }
    }

    public function delete($action)
    {
        $this->db->delete("ajar_dosen", array("id_ajar" => $action->get_id()));
        $action->success();
    }

}
