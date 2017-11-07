<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */
$db = new mysql_db($db_host, $db_user, $db_pwd, $db_name, false);
add_database($db, $db_name);

class GameSession extends BusinessObject {

    function GameSession() {
        $this->table_name = "gamesession";
        $this->field_metadata = array(
            "id" => array("int", true, false, false, false, false),
            "name" => array("text", false, true, false, false, true),
            "idUser" => array("int", false, true, false, false, true),
            "dateCreated" => array("datetime", false, true, false, false, true),
            "dateUpdated" => array("datetime", false, true, false, false, true),
            "size" => array("int", false, true, false, false, true),
            "content" => array("text", false, true, false, false, true)
        );
        parent::BusinessObject();
    }

    function fill_ids() {
        global $data_objects;
        $this->data["id"] = $data_objects[$this->db_key]->sql_nextid();
    }

    /**
     * The function below validates an array containing the information of
     * a new game session or an existing one.
     * @param array $arrData
     * @param boolean $is_update
     * @return array
     */
    public static function validate($arrData, $is_update = false) {

        $response['status'] = true;
        $response['message'] = 'OK';

        // GAE SESSION  MUST EXIST
        if ($is_update === true) {
            if (!isset($arrData['id']) or self::getGameSessionById($arrData['id']) === false) {
                $response['status'] = false;
                $response['message'] = 'Missing game session ID for updating, or game session ID not valid.';
                return $response;
            }
        }

        // USER MUST EXIST
        $exists = User::getUserByToken($arrData['token']);
        if ($exists === false) {
            $response['status'] = false;
            $response['message'] = 'User token not valid for game session.';
            return $response;
        }

        return $response;
    }

    /**
     * Creates a game session from array params
     * @param array $arrParams
     * @return int or false
     */
    public static function create($arrParams) {
        $result = false;
        $data = new GameSession();
        $name = self::randomKey(12);
        $data->set_data('name', $name);
        $userArr = User::getUserByToken($arrParams['token']);
        $contentStr = str_replace("\\", "", $arrParams['content']);
        
        $data->set_data('idUser', (int) $userArr['id']);
        $data->set_data_clean('content', $contentStr);
        $data->set_data('size', (int) $arrParams['size']);
        $data->set_data('dateCreated', date('Y-m-d H:i:s'));
        $data->set_data('dateUpdated', date('Y-m-d H:i:s'));

        if ($data->save()) {
            return $data->get_data('id');
        }
        // not found
        return false;
    }

    protected static function randomKey($length) {
        $pool = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $key;
    }

    /**
     * Updates a game session by array params
     * @param array $arrParams
     * @return int or false
     */
    public static function update($arrParams) {

        $result = false;
        $data = new GameSession();
        $data->add_filter('id', '=', $arrParams['id']);
        $data->load();

        $contentStr = str_replace("\\", "", $arrParams['content']);
        $data->set_data_clean('content', $contentStr);
        $data->set_data('dateUpdated', date('Y-m-d H:i:s'));

        if ($data->save()) {
            return $data->get_data('id');
        }
        // not found
        return false;
    }

    /**
     * Returns an array with the information of a Game Session, by given Id
     * @param int $id
     * @return array or false
     */
    public static function getGameSessionById($id) {
        
        $data = new GameSession();
        $data->add_filter('id', '=', (int) $id);

        if ($data->load()) {
            
            $arrData['id'] = $data->get_data('id');
            $arrData['name'] = $data->get_data('name');
            $arrData['idUser'] = $data->get_data('idUser');
            $arrData['size'] = $data->get_data('size');
            $arrData['content'] = $data->get_data('content');
            $arrData['dateCreated'] = $data->get_data('dateCreated');
            $arrData['dateUpdated'] = $data->get_data('dateUpdated');
            
            return $arrData;
        } else {
            return false;
        }
    }

}

class GameSessionCollection extends BusinessObjectCollection {

    function GameSessionCollection() {
        parent::BusinessObjectCollection();
    }

    function create_singular($row) {
        $obj = new GameSession();
        $obj->load_from_list($row);
        return $obj;
    }

    // GET SESSIONS PER USER
    public static function getList($idUser) {
        

        
        
        $sql = ' SELECT * FROM gamesession '
                . ' WHERE 1 AND idUser IN (SELECT id FROM user WHERE token LIKE  "' . $idUser . '") '
                . ' ORDER BY dateCreated DESC ';
        $dataCollection = new GameSessionCollection();
        $dataCollection->loadSQL($sql);
        $count = $dataCollection->get_count();
        if ($count != 0) {
            for ($i = 0; $i < $count; $i++) {
                $data = &$dataCollection->items[$i];
                $simplearr = array(
                    $data->get_data("id"),
                    $data->get_data("name"),
                    $data->get_data("content"),
                    $data->get_data("size"),
                    date('F d H:i', strtotime($data->get_data("dateCreated"))),
                    $data->get_data("dateUpdated")
                );


                $result[] = $simplearr;
            }
        } else {
            // EMPTY LIST
            $result = false;
        }
        return $result;
    }

}
