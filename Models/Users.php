<?php
namespace Models;

use Libraries;
use Resources;

class Users
{

    public function __construct()
    {

        $this->db = new Resources\Database;
        $this->hash = new Libraries\Passhash;
    }

    public function getOne($criteria = array())
    {

        return $this->db->getOne('cms_users', $criteria);
    }

    public function getOneMhs($criteria = array())
    {

        return $this->db->getOne('mahasiswa_pt', $criteria);
    }

    public function getAll($args = array())
    {

        $default = array(
            'limit' => 10,
            'page' => 1,
            'orderBy' => 'ID',
            'sort' => 'DESC',
            'criteria' => array(),
            'fields' => array(),
        );

        $args = array_merge($default, $args);

        $offset = ($args['limit'] * $args['page']) - $args['limit'];

        return $this->db
            ->orderBy($args['orderBy'], $args['sort'])
            ->limit($args['limit'], $offset)
            ->getAll('cms_users', $args['criteria'], $args['fields']);
    }

    public function getTotal($criteria = array())
    {

        $this->db->select('COUNT(*)');

        if (!empty($criteria)) {
            foreach ($criteria as $key => $val) {
                $this->where($key, '=', $val, 'AND');
            }
        }

        return $this->db->from('cms_users')->getVar();
    }

    public function insert($data = array())
    {

        if ($this->db->insert('cms_users', $data)) {
            return $this->db->insertId();
        }

        return false;
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

    /* ------------- `users` table method ------------------ */

    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function createUser($name, $email, $password)
    {
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExists($email)) {
            // Generating password hash
            $password_hash = $this->hash->hash($password);

            // Generating API key
            $api_key = $this->generateApiKey();

            $data = [
                'user_login' => $name,
                'user_email' => $email,
                'user_pass' => $password_hash,
                'api_key' => $api_key,
            ];

            $result = $this->db->insert('cms_users', $data);

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                $response["error"] = false;
                $response["message"] = "You are successfully registered";
            } else {
                // Failed to create user
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing";
            }
        } else {
            // User with same email already existed in the db
            $response["error"] = true;
            $response["message"] = "Sorry, this email already existed";
        }

        return $response;
    }

    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkLogin($email, $password)
    {

        $result = $this->db->getOne('cms_users', ['user_email' => $email], ['user_pass']);

        if ($result) {
            // Found user with the email
            // Now verify the password

            if ($this->hash->check_password($result->user_pass, $password)) {
                // User password is correct
                return true;
            } else {
                // user password is incorrect
                return false;
            }
        } else {
            // user not existed with the email
            return false;
        }
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($email)
    {

        $result = $this->db->getOne('cms_users', ['user_email' => $email]);
        return $result;
    }

    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByEmail($email)
    {

        $result = $this->db->getOne('cms_users', ['user_email' => $email]);
        if ($result) {
            return $result;
        }

        return false;
    }

    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */
    public function getApiKeyById($user_id)
    {
        $result = $this->db->getOne('table_name', ['id_user' => $user_id], ['api_key']);
        if ($result) {
            return $result->api_key;
        } else {
            return null;
        }
    }

    /**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getUserId($api_key)
    {
        $result = $this->db->getOne('cms_users', ['api_key' => $api_key], ['id_user']);
        if ($result) {
            return $result->id_user;
        } else {
            return false;
        }
    }

    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidApiKey($api_key)
    {
        $result = $this->db->getOne('cms_users', ['api_key' => $api_key]);
        return $result;
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey()
    {
        return md5(uniqid(rand(), true));
    }

    /* ------------- `tasks` table method ------------------ */

    /**
     * Creating new task
     * @param String $user_id user id to whom task belongs to
     * @param String $task task text
     */
    public function createTask($user_id, $task)
    {
        // $stmt = $this->conn->prepare("INSERT INTO tasks(task) VALUES(?)");
        // $stmt->bind_param("s", $task);
        // $result = $stmt->execute();
        // $stmt->close();

        $result = $this->db->insert('test_tasks', ['task' => $task]);

        if ($result) {
            // task row created
            // now assign the task to user
            $new_task_id = $this->db->insertId();
            $res = $this->createUserTask($user_id, $new_task_id);

            if ($res) {
                // task created successfully
                return $new_task_id;
            } else {
                // task failed to create
                return null;
            }
        } else {
            // task failed to create
            return null;
        }
    }

    /**
     * Fetching single task
     * @param String $task_id id of the task
     */
    public function getTask($task_id, $user_id)
    {
        // $stmt = $this->conn->prepare("SELECT t.id, t.task, t.status, t.created_at from tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        // $stmt->bind_param("ii", $task_id, $user_id);

        $result = $this->db->results("SELECT t.id, t.task, t.status, t.created_at
            from test_tasks t, test_user_tasks ut
            WHERE t.id = " . $task_id . " AND ut.task_id = t.id AND ut.user_id = " . $user_id);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * Fetching all user tasks
     * @param String $user_id id of the user
     */
    public function getAllUserTasks($user_id)
    {
        /*
        $tasks = $this->db->select('t.*')
        ->from('test_taskss t, test_user_tasks ut')
        ->where('t.id', '=', '`ut.task_id`','AND')
        ->where('ut.user_id', '=', $user_id)
        ->getAll();
         */
        $tasks = $this->db->results("SELECT t.* FROM test_tasks t, test_user_tasks ut WHERE t.id = ut.task_id AND ut.user_id = " . $user_id);

        return $tasks;
    }

    /**
     * Updating task
     * @param String $task_id id of the task
     * @param String $task task text
     * @param String $status task status
     */
    public function updateTask($user_id, $task_id, $task, $status)
    {
        $stmt = $this->conn->prepare("UPDATE tasks t, user_tasks ut set t.task = ?, t.status = ? WHERE t.id = ? AND t.id = ut.task_id AND ut.user_id = ?");
        $stmt->bind_param("siii", $task, $status, $task_id, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /**
     * Deleting a task
     * @param String $task_id id of the task to delete
     */
    public function deleteTask($user_id, $task_id)
    {
        $stmt = $this->conn->prepare("DELETE t FROM tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();

        return $num_affected_rows > 0;
    }

    /* ------------- `user_tasks` table method ------------------ */

    /**
     * Function to assign a task to user
     * @param String $user_id id of the user
     * @param String $task_id id of the task
     */
    public function createUserTask($user_id, $task_id)
    {
        // $stmt = $this->conn->prepare("INSERT INTO user_tasks(user_id, task_id) values(?, ?)");
        // $stmt->bind_param("ii", $user_id, $task_id);
        // $result = $stmt->execute();

        $result = $this->db->insert('test_user_tasks', ['user_id' => $user_id, 'task_id' => $task_id]);

        if ($result) {
            return $result;
        }
    }
}
