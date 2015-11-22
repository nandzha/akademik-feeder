<?php
namespace Models;

use Dhtmlx\Connector;
use Resources;

class Helpers
{
    public function __construct()
    {
        $this->db = new Resources\Database;
        $this->conn = new Connector\JSONTreeDataConnector($this->db, "MySQLi");
        $this->session = new Resources\Session;
    }

    /**
     * converter multiple array to single array
     * @param  [type] $array [description]
     * @return [type]        [description]
     */
    private function array_flatten($array)
    {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    // ini yang jalan sumber -> http://ellislab.com/forums/viewthread/181394/#858951
    public function get_menu_structure($type = 'front')
    {
        $groupId = $this->session->getValue('grupId');

        $this->db
            ->select()
            ->from('cms_menu ass a')
            ->where('a.published', '=', '1', 'AND')
            ->where('a.type', '=', $type, 'AND')
            ->orderBy('a.menu_order', 'ASC');

        if ($type == 'back') {
            $this->db->join('cms_role_menu_group as b')->on('a.id', '=', 'b.menu_id');
            $this->db->where('b.group_id', '=', $groupId, 'AND'); // $groupId
            $this->db->where('b.is_active', '=', '1');
        }

        if ($result = $this->db->getAll()) {
            foreach ($result as $r) {
                $data[$r->parent_id][] = $r;
            }
            return $this->build_menu($data, 0);
        }
        return false;
    }

    private function build_menu($data, $parent)
    {
        static $i = 0;
        if (array_key_exists($parent, $data)) {
            $menu = array();

            foreach ($data[$parent] as $r) {
                $menu = $data[$parent];
                $child = $this->build_menu($data, $r->id);
                if ($child) {
                    $menu[$r->parent_id]->data = $child;
                }
            }
            return $menu;
        } else {
            return false;
        }
    }

    public function getVarOptions($criteria = null)
    {
        return $this->db
            ->select('value')
            ->from('cms_options')
            ->where('config_name', '=', $criteria)
            ->getVar();
    }

    public function menuInit()
    {
        if ($grupId = $this->session->getValue('grupId')) {
            $this->conn->filter('group_id', $this->session->getValue('grupId'), '=');
        }

        $this->setFilter();
        $this->conn->render_table("build_menu_view", "id", $this->setFields(), false, "parent_id");
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
            'id',
            'parent_id',
            'value',
            'url',
            'menu_order',
            'details',
            'icon',
            'published',
            // 'open',
            'type',
            'group_id',
        ];
        return implode(",", $fields);
    }

    protected function checkChild($data)
    {
        if ($this->conn->get_value("has_kids") == 1) {
            $this->conn->set_kids(true);
        } else {
            $this->conn->set_kids(false);
        }

    }

    public function getSemester($id_smt)
    {
        return $this->db->getOne('semester', ['id_smt' => $id_smt]);
    }

    public function get_rules($name)
    {
        $name = $this->db
            ->getOne('cms_validation', ['name' => $name, 'a_active' => '1'], ['id']);

        $this->db
            ->select()
            ->from('cms_validation')
            ->where('a_active', '=', '1', 'AND')
            ->where('parent_id', '=', $name->id);

        $dummy = array(
            'name' => array(
                'rules' => array(
                    'required',
                    'min' => 3
                ),
                'label' => 'Full Name',
                'filter' => array('trim', 'strtolower', 'ucwords')
            ),
            'username' => array(
                'rules' => array(
                    'required',
                    'min' => 3,
                    'max' => 10,
                    'regex' => '/^([-a-z0-9_-])+$/i',
                    'callback' => 'usernameExists'
                ),
                'label' => 'Username',
                'filter' => array('trim', 'strtolower')
            )
        );

        if ($result = $this->db->getAll()) {
            foreach ($result as $r) {
                $rules[$r->name] = [
                    'rules'  => $this->toArray($r->rules),
                    // 'label'  => $r->label,
                    // 'filter' => $this->toArray($r->filter)
                ];
            }
            return $rules;
        }
        return false;
    }

    public function toArray($string){
        $partial = explode(',', $string);
        $final   = array();
        array_walk($partial, function($val, $key) use(&$final){
            $subArray = explode('=>', $val);
            if ( count($subArray) == 1 )
                $final[$key] = $val;
            else{
                list($key, $value) = $subArray;
                $final[$key] = $value;
            }
        });
        return $final;
    }
}
