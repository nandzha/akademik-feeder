<?php
namespace Models;

use Resources;

class Main
{
    public function __construct()
    {
        $this->db = new Resources\Database;
        // $this->cache = new Resources\Cache('memcached');
    }

    public function getVarOptions($criteria = null)
    {
        return $this->db
            ->select('value')
            ->from('cms_options')
            ->where('config_name', '=', $criteria)
            ->getVar();
    }

    public function saveMenu($id, $data)
    {
        if ($id != null) {
            $this->db->update('cms_menu', $data, array('id' => $id));
            return $this->getLastInsert('cms_menu', $id);
        } else if ($this->db->insert('cms_menu', $data)) {
            return $this->getLastInsert('cms_menu', $this->db->insertId());
        }

        return false;
    }

    public function saveUser($id, $data)
    {
        if ($id != null) {
            $this->db->update('cms_users', $data, array('ID' => $id));
            return $this->getLastInsert('cms_users', $id);
        } else if ($this->db->insert('cms_users', $data)) {
            return $this->getLastInsert('cms_users', $this->db->insertId());
        }

        return false;
    }

    public function deleteUser($data)
    {
        return $this->db
            ->where('ID', 'in', $data, 'AND')
            ->where('user_level', '<>', 'admin')
            ->delete('cms_users');
    }

    public function saveGroup($data, $id = null)
    {
        if ($id == '1') {
            return false;
        }

        if ($id != null) {
            $this->db->update('cms_user_group', $data, array('id' => $id));
            return $this->getLastInsert('cms_user_group', $id);
        } else if ($this->db->insert('cms_user_group', $data)) {
            return $this->getLastInsert('cms_user_group', $this->db->insertId());
        }

    }

    public function removeGroup($data)
    {
        $this->db->where('id', 'in', $data, 'AND')
            ->where('id', '<>', '1')
            ->delete('cms_user_group');
        //delete juga dari table cms_role_menu_group
        return $this->db->where('group_id', 'in', $data, 'AND')
            ->where('group_id', '<>', '1')
            ->delete('cms_role_menu_group');
    }

    public function getRoleMenu($args = array())
    {
        $default = array(
            'orderBy' => 'b.id',
            'sort' => 'ASC',
            'column' => 'a.group_id',
            'operator' => '=',
            'value' => null,
            'fields' => array('a.role_menu_id as DT_RowId',
                'b.id as mnid', 'b.title', 'a.is_active', 'b.parent_id as parent'),
        );

        $args = array_merge($default, $args);

        if (!empty($args['criteria'])) {
            $this->db->where($args['criteria']);
        }

        return $this->db
            ->select($args['fields'])
            ->from('cms_role_menu_group as a')
            ->join('cms_menu as b', 'RIGHT')
            ->on('a.menu_id ', '=', 'b.id', 'AND')
            ->on($args['column'], $args['operator'], $args['value'])
            ->where('b.type', '=', 'back')
            ->orderBy($args['orderBy'], $args['sort'])
            ->getAll();
    }

    public function getOneRole($args = array())
    {
        $default = array(
            'on' => array(),
            'where' => array('b.type' => 'back', 'a.is_active' => '1'),
            'fields' => array(),
        );

        $args = array_merge($default, $args);

        if (!empty($args['where'])) {
            foreach ($args['where'] as $key => $val) {
                $this->db->where($key, '=', $val, 'AND');
            }
        }

        if (!empty($args['on'])) {
            foreach ($args['on'] as $key => $val) {
                $this->db->on($key, '=', $val, 'AND');
            }
        }

        return $this->db
            ->select($args['fields'])
            ->from('cms_role_menu_group as a')
            ->join('cms_menu as b', 'RIGHT')
            ->on('a.menu_id ', '=', 'b.id')
            ->getOne();
    }

    public function saveRoleMenu($v)
    {
        $r = $this->db->row("SELECT * FROM cms_role_menu_group
							WHERE role_menu_id ='" . $v['role_menu_id'] . "'
							AND menu_id='" . $v['menu_id'] . "'");
        $value = array_slice($v, 1);
        if ($r) {
            $do['update'] = $this->db->update('cms_role_menu_group', $value, array('role_menu_id' => $v['role_menu_id']));
        } else {
            $do['insert'] = $this->db->insert('cms_role_menu_group', $value);
            $do['change'] = $this->db->insertId();
        }
        return $do;
    }

    private function dataFormat($data, $type)
    {
        $rest = new Resources\Rest;
        return $rest->wrapResponseOutput($data, $type);
    }

    public function replace($data = array())
    {
        return $this->db->replace('test', array('id' => 'id+1', 'nama' => 'holic'));
    }

    public function save($args = array(), $data = array())
    {
        $default = array(
            'id' => null,
            'PK' => null,
            'table' => null,
            'callback' => null,
        );

        $callBack = array(
            'id' => null,
            'tid' => null,
            'sid' => null,
            'operation' => null,
            'status' => 'Failed',
            'data' => null,
        );

        $args = array_merge($default, $args);

        if ($args['id'] != null) {
            $this->db->update($args['table'], $data, array($args['PK'] => $args['id']));
            $callBack['tid'] = $args['id'];
            $callBack['id'] = $args['id'];
            $callBack['operation'] = 'update';
            $callBack['status'] = 'Success';
            $callBack['data'] = $data[$args['callback']];
        } else if ($this->db->insert($args['table'], $data)) {
            $callBack['sid'] = $this->db->insertId();
            $callBack['id'] = $this->db->insertId();
            $callBack['operation'] = 'insert';
            $callBack['status'] = 'Success';
            $callBack['data'] = ($data[$args['callback']]) ? $data[$args['callback']] : "New record";
        }

        return $callBack;
    }

    public function updateOrder($args = array())
    {
        $data = array();
        $default = array(
            'id' => null,
            'old' => null,
            'new' => null,
            'column' => null,
            'table' => null,
        );
        $args = array_merge($default, $args);

        /*if (! is_null($args['old'])){
        $this->db->where($args['column'], '>=>', $args['old']);
        $data[$args['column']] = $args['column']."-1";
        $this->db->update($args['table'], $data);
        }

        if (! is_null($args['new'])){
        $this->db->where($args['column'], '>=', $args['new']);
        $data[$args['column']] = $args['column']+1;
        $this->db->update($args['table'], $data);
        }

        if (! is_null($args['id'])){
        $this->db->where('id', '=', $args['id']);
        $data[$args['column']] = $args['new'];
        $this->db->update($args['table'], $data);
        }*/

        $this->db->query("UPDATE films SET rank = rank-1 WHERE rank >= " . $args['old']);
        $this->db->query("UPDATE films SET rank = rank+1 WHERE rank >= " . $args['new']);
        $this->db->query("UPDATE films SET rank = " . $args['new'] . "  WHERE  id = " . $args['id']);
    }

    public function deleteOne($args)
    {
        $default = array(
            'table' => null,
            'PK' => null,
            'criteria' => array(),
        );
        $args = array_merge($default, $args);

        $callBack = array(
            'id' => null,
            'operation' => 'delete',
            'status' => 'Failed',
            'data' => null,
        );

        if ($this->db->where($args['PK'], 'IN', $args['criteria'])->delete($args['table'])) {
            $callBack['id'] = $args['criteria'];
            $callBack['status'] = 'Success';
            $callBack['data'] = $args['criteria'];
        }

        return $callBack;
    }

    public function delete($table, $where)
    {
        return $this->db->where('id', 'in', $where)->delete($table);
    }

    public function getLastInsert($table, $where)
    {
        return $this->db
            ->where('id', '=', $where)
            ->getOne($table);
    }

    public function getOne($args = array(), $type = 'object')
    {
        $default = array(
            'table' => null,
            'criteria' => array(),
            'fields' => array(),
        );

        $args = array_merge($default, $args);

        $results = $this->db->getOne($args['table'], $args['criteria'], $args['fields']);

        if ($type == 'object') {
            return $results;
        }

        return $this->dataFormat($results, $type);
    }

    public function getAll($args = array(), $type = 'object')
    {
        $default = array(
            'table' => null,
            'PK' => null,
            'limit' => null,
            'page' => 1,
            'orderBy' => 'null',
            'sort' => 'ASC',
            'criteria' => array(),
            'fields' => array('*'),
            'cache' => true,
        );

        $args = array_merge($default, $args);

        $offset = ($args['limit'] * $args['page']) - $args['limit'];

        if (!is_null($args['limit'])) {
            $this->db->limit($args['limit'], $offset);
        }

        if (!is_null($args['PK'])) {
            array_push($args['fields'], $args['PK'] . " AS id");
        }

        $results = $this->db
            ->orderBy($args['orderBy'], $args['sort'])
            ->getAll($args['table'], $args['criteria'], $args['fields']);
        /*
        $keyCache = $args['table'];

        if (! empty($args['criteria']) )
        $keyCache = $args['table'].'_'.implode('_',array_keys($args['criteria']));

        if ( ! $results = $this->cache->getValue($keyCache, $args['table']) ){

        $results = $this->db
        ->orderBy($args['orderBy'], $args['sort'])
        ->getAll($args['table'], $args['criteria'], $args['fields']);

        if ($args['cache'])
        $this->cache->setValue($keyCache, $results, 1000, $args['table']);

        }
         */

        if ($type == 'object') {
            return $results;
        }

        return $this->dataFormat($results, $type);
    }

    public function getAllIn($args = array(), $type = 'object')
    {
        $default = array(
            'table' => null,
            'PK' => null,
            'limit' => null,
            'page' => 1,
            'orderBy' => 'null',
            'sort' => 'ASC',
            'criteria' => array(),
            'criteriaIN' => array(),
            'fields' => array('*'),
            'operator' => 'IN',
        );

        $args = array_merge($default, $args);

        $offset = ($args['limit'] * $args['page']) - $args['limit'];

        if (!is_null($args['limit'])) {
            $this->db->limit($args['limit'], $offset);
        }

        if (!is_null($args['PK'])) {
            array_push($args['fields'], $args['PK'] . " AS id");
        }

        if (!empty($args['criteriaIN'])) {
            $this->db->where(array_keys($args['criteriaIN'])[0], $args['operator'], explode(',', array_values($args['criteriaIN'])[0]), 'AND');
        }

        if (!empty($args['criteria'])) {
            foreach ($args['criteria'] as $key => $value) {
                $this->db->where($key, '=', $value, 'AND');
            }
        }

        $results = $this->db
            ->select($args['fields'])
            ->from($args['table'])
            ->orderBy($args['orderBy'], $args['sort'])
            ->getAll();

        if ($type == 'object') {
            return $results;
        }

        return $this->dataFormat($results, $type);
    }

    public function getAllLike($args = array(), $type = 'object')
    {
        $default = array(
            'table' => null,
            'PK' => null,
            'limit' => null,
            'page' => 1,
            'orderBy' => 'null',
            'sort' => 'ASC',
            'criteria' => array(),
            'fields' => array('*'),
        );

        $args = array_merge($default, $args);

        $offset = ($args['limit'] * $args['page']) - $args['limit'];

        if (!is_null($args['limit'])) {
            $this->db->limit($args['limit'], $offset);
        }

        if (!is_null($args['PK'])) {
            array_push($args['fields'], $args['PK'] . " AS id");
        }

        if (!empty($args['criteria'])) {
            foreach ($args['criteria'] as $key => $value) {
                $this->db->where($key, 'like', "%" . $value . "%", 'OR');
            }
        }

        $results = $this->db
            ->select($args['fields'])
            ->from($args['table'])
            ->orderBy($args['orderBy'], $args['sort'])
            ->getAll();

        if ($type == 'object') {
            return $results;
        }

        return $this->dataFormat($results, $type);
    }

    public function chart($type = null)
    {
        $results = $this->db
            ->select('count(a.nim) as count, a.thaka, a.kdpro, b.sing_prodi')
            ->from('mhs a')
            ->join('master_prodi b')->on('b.kdpro', '=', 'a.kdpro')
            ->limit(3)
            ->groupBy('a.thaka, a.kdpro')
            ->getAll();

        $i = 1;
        foreach ($results as $r) {
            $prodi[] = array('prodi' => $r->sing_prodi, 'count' => $r->count);
            $chart[] = array(
                'id' => $i,
                'month' => $r->thaka, // thak
            );
            $chart[] = array_column($prodi, 'count', 'prodi');
            $i++;
        }
        $a = array_column($prodi, 'count');

        if (is_null($type)) {
            return $chart;
        }

        return $this->dataFormat($chart, $type);
    }

    public function buildTree($id = 0, $args = array())
    {
        $default = array(
            'table' => 'films_tree',
            'criteria' => array('parent' => $id),
        );

        $args = array_merge($default, $args);

        $results = $this->getAll($args);

        $data = array();
        if ($results) {
            foreach ($results as $r) {
                $r->data = $this->buildTree($r->id);
                $data[] = $r;
            }
        }
        return $data;
    }

}
