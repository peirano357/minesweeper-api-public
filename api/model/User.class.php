<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */
$db = new mysql_db($db_host, $db_user, $db_pwd, $db_name, false);
add_database($db, $db_name);

class User extends BusinessObject {

    function User() {
        $this->table_name = "user";
        $this->field_metadata = array(
            "id" => array("int", true, false, false, false, false),
            "firstName" => array("text", false, true, false, false, true),
            "lastName" => array("text", false, true, false, false, true),
            "email" => array("text", false, true, false, false, true),
            "password" => array("text", false, true, false, false, true),
            "token" => array("text", false, true, false, false, true),
            "userName" => array("text", false, true, false, false, true)
        );
        parent::BusinessObject();
    }

    function fill_ids() {
        global $data_objects;
        $this->data["id"] = $data_objects[$this->db_key]->sql_nextid();
    }

    public function validateToken($hash) {
        $data = new User();
        $data->add_filter('token', '=', $hash);
        if ($data->load()) {
            return true;
        } else {
            return false;
        }
    }

    public function validarUserExists($hash) {
        $data = new User();
        $data->add_filter('password', '=', $hash);
        if ($data->load()) {
            return $data->get_data('id');
        } else {
            return false;
        }
    }

    public static function validate($arrData) {

        $response['status'] = true;
        $response['message'] = 'OK';

        // name, lastName, email, password
        if (!isset($arrData['firstName']) or $arrData['firstName'] == '') {
            $response['status'] = false;
            $response['message'] = 'You must input your First Name.';
            return $response;
        }

        if (!isset($arrData['lastName']) or $arrData['lastName'] == '') {
            $response['status'] = false;
            $response['message'] = 'You must input your Last Name.';
            return $response;
        }

        if (!isset($arrData['email']) or $arrData['email'] == '') {
            $response['status'] = false;
            $response['message'] = 'You must input your Email Address.';
            return $response;
        }

        if (!isset($arrData['userName']) or $arrData['userName'] == '') {
            $response['status'] = false;
            $response['message'] = 'You must input a Username for your account.';
            ;
            return $response;
        }

        if (!isset($arrData['password']) or $arrData['password'] == '') {
            $response['status'] = false;
            $response['message'] = 'You must input a Password for your account.';
            return $response;
        }



        $user = new User();
        $user->add_filter('email', 'LIKE', $arrData['email']);
        if ($user->load()) {
            $response['status'] = false;
            $response['message'] = 'Given email address exists for another user.';
            return $response;
        }

        $user = new User();
        $user->add_filter('userName', 'LIKE', '%' . $arrData['userName'] . '%');
        if ($user->load()) {
            $response['status'] = false;
            $response['message'] = 'Given username exists for another user.';
            return $response;
        }

        return $response;
    }

    public function getUserByCredentials($username, $password) {
        $data = new User();
        $data->add_filter('userName', '=', $username);
        $data->add_filter('AND');
        $data->add_filter('password', '=', md5($password));
        if ($data->load()) {
            $arrData['id'] = $data->get_data('id');
            $arrData['firstName'] = $data->get_data('firstName');
            $arrData['lastName'] = $data->get_data('lastName');
            $arrData['token'] = $data->get_data('token');
            $arrData['email'] = $data->get_data('email');
            
            $arrData['userName'] = $data->get_data('userName');
            return $arrData;
        } else {
            return false;
        }
    }

    /**
     * Retrieves a user data by a given token
     * @param string $token
     * @return array or false
     */
    public static function getUserByToken($token) {
        $data = new User();
        $data->add_filter('token', 'LIKE', $token);
        if ($data->load()) {
            $arrData['id'] = $data->get_data('id');
            $arrData['firstName'] = $data->get_data('firstName');
            $arrData['lastName'] = $data->get_data('lastName');
            $arrData['token'] = $data->get_data('token');
            $arrData['email'] = $data->get_data('email');
            $arrData['userName'] = $data->get_data('userName');
            return $arrData;
        } else {
            return false;
        }
    }

    /**
     * Retrieves a user Id by a given token
     * @param string $token
     * @return int or false
     */
    public static function getUserIdByToken($token) {

        $data = new User();
        $data->add_filter('token', 'LIKE', $token);
        if ($data->load()) {
            return $data->get_data('id');
        } else {
            return false;
        }
    }

    public function validateUserIdyPassword($id, $password) {
        $data = new User();
        $data->add_filter('id', '=', (int) $id);
        $data->add_filter('AND');
        $data->add_filter('password', '=', $password);
        if ($data->load()) {
            return true;
        } else {
            return false;
        }
    }

    public static function create($arrParams) {
        $data = new User();
        $data->set_data('firstName', $arrParams['firstName']);
        $data->set_data('lastName', $arrParams['lastName']);
        $data->set_data('email', $arrParams['email']);
        $data->set_data('password', md5($arrParams['password']));
        $data->set_data('userName', $arrParams['userName']);
        $data->set_data('token', md5(rand(1, 9999999999999) . date('Y-m-d h:i:s')));

        if ($data->save()) {
            return $data->get_data('id');
        }
        // not found
        return false;
    }

}

class UserCollection extends BusinessObjectCollection {

    function UserCollection() {
        parent::BusinessObjectCollection();
    }

    function create_singular($row) {
        $obj = new User();
        $obj->load_from_list($row);
        return $obj;
    }

}
