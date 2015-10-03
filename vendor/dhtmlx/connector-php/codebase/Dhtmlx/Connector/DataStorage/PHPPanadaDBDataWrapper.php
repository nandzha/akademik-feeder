<?php
namespace Dhtmlx\Connector\DataStorage;
use Dhtmlx\Connector\Connector;
use Dhtmlx\Connector\Tools\LogMaster;
use \Exception;

class PHPPanadaDBDataWrapper extends DBDataWrapper {

    public function query($sql){
        LogMaster::log($sql);
        $res=$this->connection->query($sql);

        if($res === false)
            return $this->connection->printError();

        return $res;
    }

    public function get_next($res){
        return $res->fetch_assoc();
    }

    public function get_new_id(){
        return $this->connection->insertId();
    }

    public function escape($str){
        return $this->connection->escape($str);
    }
}

?>