<?php
namespace Models;

use Libraries\AppResources;

class Sync extends AppResources\Models
{
    public function __construct()
    {
        parent::__construct();
        $this->ruleName = 'mahasiswa';
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

     public function insertTableRecursif($table, $data){
        foreach ($data as $row) {
            $data = $this->db->insert($table, $row);
        }
        return $data;
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

    public function hitungIPS($id_reg_pd) {
        $result = $this->db->row("
        SELECT
            ROUND((SUM(b.sks_mk * a.nilai_indeks) / SUM(b.sks_mk)), 2) AS ips
        FROM
            nilai a
        INNER JOIN kelas_kuliah b ON a.id_kls = b.id_kls
        WHERE
            b.id_sms = b.id_sms
        AND a.id_reg_pd = '{$id_reg_pd}'
        GROUP BY
            a.id_reg_pd,
            b.id_smt
        ");
        return ($result->ips == '')? '2.00' : $result->ips;
    }

    public function hitungSksSmt($id_reg_pd) {
        $result = $this->db->row("
        SELECT
            SUM(b.sks_mk) AS sks_smt
        FROM
            nilai a
        INNER JOIN kelas_kuliah b ON a.id_kls = b.id_kls
        WHERE
            b.id_sms = b.id_sms
        AND a.id_reg_pd = '{$id_reg_pd}'
        GROUP BY
            a.id_reg_pd,
            b.id_smt
        ");
        return ( $result->sks_smt == '')? '10' : $result->sks_smt;
    }

    public function getPropertyMhsByNim($criteria)
    {
        $data = $this->db->getOne('mahasiswa_list_view', $criteria);
        if ($data) {
            return $data;
        }

        return false;
    }


}
