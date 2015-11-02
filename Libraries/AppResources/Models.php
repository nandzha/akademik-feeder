<?php
namespace Libraries\AppResources;

use Dhtmlx\Connector;
use Libraries;
use Resources;

class Models extends Resources\Validation
{
    protected $ruleName;

    public function __construct()
    {
        parent::__construct();
        $this->db = new Resources\Database;
        $this->conn = new Connector\JSONDataConnector($this->db, "MySQLi");
        $this->uuid = new Libraries\UUID;
        $this->session = new Resources\Session;
    }

    public function setRules()
    {
        $name = $this->db
            ->getOne('cms_validation', ['name' => $this->ruleName, 'a_active' => '1'], ['id']);

        $this->db
            ->select()
            ->from('cms_validation')
            ->where('a_active', '=', '1', 'AND')
            ->where('parent_id', '=', $name->id);

        if ($result = $this->db->getAll()) {
            foreach ($result as $r) {
                $rules[$r->name] = [
                    'rules'  => $this->toArray($r->rules),
                    'label'  => $r->label,
                    'filter' => $this->toArray($r->filter)
                ];
            }
            return $rules;
        }
        return false;
    }

    private function toArray($string){
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

}
