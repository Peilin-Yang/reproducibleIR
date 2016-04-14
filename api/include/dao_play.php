<?php
require_once "dao.php";

class DAOPlay extends DAO {
    const SQL_ADD_MODEL = "INSERT INTO models (uid, mname, mpara, mnotes, mbody, submitted_dt, last_modified_dt) VALUES(:uid, :mname, :mpara, :mnotes, :mbody, :submitted_dt, :last_modified_dt)";
    const SQL_GET_MODEL = "SELECT * FROM models";

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

    public function add_model($uid, $apikey, $mname, $mpara, $mnotes, $mbody) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_ADD_MODEL);
            $stmt->execute(array(
                ':uid' => $uid,
                ':mname' => $mname,
                ':mpara' => $mpara,
                ':mnotes' => $mnotes,
                ':mbody' => $mbody,
                ':submitted_dt' => gmdate('Y-m-d H:i:s'),
                ':last_modified_dt' => gmdate('Y-m-d H:i:s'),
            ));
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

    public function get_model($uid, $apikey, $request_uid, 
            $sort_field = "0", $order = "desc", $start = "0", 
            $end = "-1") {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $sql_qry = self::SQL_GET_MODEL;
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
}

$dao_play = new DAOPlay();
?>