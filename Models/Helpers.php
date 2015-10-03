<?php
namespace Models;

use Resources;

class Helpers
{
    public function __construct()
    {
        $this->db = new Resources\Database('pddikti');
        $this->ctrl = new Resources\Controller;
        $this->uri = new Resources\Uri;
        $this->content = new Main;
        $this->panel = new Panels;
        $this->session = new Resources\Session;
        $this->cfg = Resources\Config::main();
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
            ->from('cms_menu as a')
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

}
