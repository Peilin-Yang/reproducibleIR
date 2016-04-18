<?php
require_once "dao.php";

class DAOPlay extends DAO {
    const SQL_ADD_MODEL = "INSERT INTO models (uid, mname, mpara, mnotes, mbody, submitted_dt, last_modified_dt) VALUES(:uid, :mname, :mpara, :mnotes, :mbody, :submitted_dt, :last_modified_dt)";
    const SQL_UPDATE_MODEL = "UPDATE models SET mname=:mname, mpara=:mpara, mnotes=:mnotes, mbody=:mbody, last_modified_dt=:last_modified_dt WHERE mid=:mid";
    const SQL_GET_MODEL_LIST = "SELECT mid,mname,mpara,submitted_dt,last_modified_dt,last_compile_dt,compile_status FROM models";
    const SQL_GET_MODEL_DETAIL = "SELECT * FROM models WHERE mid=:mid LIMIT 1";
    const SQL_GET_INDEX_LIST = "SELECT * FROM index_paths";
    const SQL_GET_INDEX_DETAIL = "SELECT * FROM index_paths WHERE id=:id LIMIT 1";
    const SQL_GET_QUERY_LIST = "SELECT * FROM query_paths";
    const SQL_GET_QUERY_DETAIL = "SELECT * FROM query_paths WHERE query_tag=:query_tag LIMIT 1";

    private static $column_lookup = [
        "0" => "uid",
        "1" => "mname",
        "2" => "submitted_dt",
        "3" => "last_modified_dt",
        "4" => "last_compile_dt",
        "5" => "compile_status"
    ];

    public function __construct() {

    }

    private function validate_user($uid, $apikey) {
        $host_user = $this->verify_user($uid, $apikey);
        if (count($host_user) == 0) {
            throw new RuleException(ERR501, 501);
        }
    }

    public function add_update_model($uid, $apikey, $mid, $mname, $mpara, $mnotes, $mbody) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            if (empty($mid)) {
                $stmt = $db->prepare(self::SQL_ADD_MODEL);
                $stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
                $stmt->bindValue(':submitted_dt', gmdate('Y-m-d H:i:s'), PDO::PARAM_STR);
            } else {
                $stmt = $db->prepare(self::SQL_UPDATE_MODEL);
                $stmt->bindValue(':mid', $mid, PDO::PARAM_STR);
            }
            $stmt->bindValue(':mname', $mname, PDO::PARAM_STR);
            $stmt->bindValue(':mpara', $mpara, PDO::PARAM_STR);
            $stmt->bindValue(':mnotes', $mnotes, PDO::PARAM_STR);
            $stmt->bindValue(':mbody', $mbody, PDO::PARAM_STR);
            $stmt->bindValue(':last_modified_dt', gmdate('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    private function column_name($field) {
        if (array_key_exists($field, static::$column_lookup)) {
            return static::$column_lookup[$field];
        }
        return static::$column_lookup["0"]; 
    }

    public function get_model_list($uid, $apikey, $request_uid, 
            $sort_field = "0", $order = "desc", $start = "0", 
            $end = "-1") {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $sql_qry = self::SQL_GET_MODEL_LIST;
            if (!empty($request_uid)) {
                $sql_qry .= " WHERE uid=:uid";
            }
            $sql_qry .= " order by ".$this->column_name($field);
            if (strcasecmp($order, "asc") == 0) {
                $sql_qry .= " asc ";
            }
            if (ctype_digit($start) && ctype_digit($end)) {
                // limit clause is zero-based
                $offset = max(intval($start)-1, 0);
                if (intval($end) >= 0) {
                    $count = max(intval($end)-intval($start)+1, 0);
                } else {
                    $count = 999999;
                }
                $sql_qry .= " limit $offset, $count";
            }
            $stmt = $db->prepare($sql_qry);
            if (!empty($request_uid)) {
                $stmt->bindValue(':uid', $request_uid, PDO::PARAM_STR);
            }
            //var_dump($sql_qry);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function get_model_details($uid, $apikey, $mid) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_MODEL_DETAIL);
            $stmt->bindValue(':mid', $mid, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }


    public function get_index_list($uid, $apikey) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_INDEX_LIST);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function get_index_details($uid, $apikey, $iid) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_INDEX_DETAIL);
            $stmt->bindValue(':id', $iid, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function get_query_list($uid, $apikey) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_QUERY_LIST);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function get_query_details($uid, $apikey, $query_tag) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_QUERY_DETAIL);
            $stmt->bindValue(':query_tag', $query_tag, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }
}

$dao_play = new DAOPlay();
?>