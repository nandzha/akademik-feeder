<?php
namespace Models;

use Resources;
use Libraries;

class Sync
{
    public function __construct()
    {
        $this->db = new Resources\Database('pddikti');
        $this->uuid = new Libraries\UUID;
        $this->session = new Resources\Session;
    }

    public function createTempTable($table, $column=[]){
        $sql = "CREATE TEMPORARY TABLE IF NOT EXISTS ".$table." (". implode(", ",$column) .") ENGINE=MyISAM DEFAULT CHARSET=utf8";
        $this->db->query($sql);
    }

    public function copyTempTable($tTable, $rTable){
        $sql = "CREATE TEMPORARY TABLE IF NOT EXISTS ".$tTable."( SELECT * from ".$rTable." LIMIT 0 )";
        $this->db->query($sql);
    }

    public function insertTempTable($table, $data){
        return $this->db->insert($table, $data);
    }

    public function getTempTable($table){
        return $this->db->getAll( $table );
    }

    public function compareData($tTable, $rTable, $group=[], $filter, $order, $offset, $limit){
        return $this->db->results("
            SELECT *
            FROM (
                SELECT * FROM {$tTable}
                UNION ALL
                SELECT * FROM {$rTable}
            ) tbl
            WHERE {$filter}
            GROUP BY ".implode(", ", $group)."
            HAVING count(*) = 1
            ORDER BY {$order} LIMIT {$offset}, {$limit}");
    }

    public function getAllRecord($table, $filter=false, $order=false, $limit=false, $offset=0){
        $sql = "SELECT * FROM {$table}";

        if ($filter)
            $sql .= " where {$filter}";

        if ($order)
            $sql .= " ORDER BY {$order}";

        if ($limit)
            $sql .= " LIMIT {$offset}, {$limit}";

        return $this->db->results($sql);
    }

    public function getCountRecord($table){
        return $this->db->select('count(*)')->from($table)->getVar();
    }
}
