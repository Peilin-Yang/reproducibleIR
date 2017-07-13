<?php
require_once "dao.php";

class DAOPlay extends DAO {
    const SQL_ADD_MODEL = "INSERT INTO models (uid, mname, mpara, mnotes, mbody, submitted_dt, last_modified_dt, compile_status) VALUES(:uid, :mname, :mpara, :mnotes, :mbody, :submitted_dt, :last_modified_dt, :compile_status)";
    const SQL_UPDATE_MODEL = "UPDATE models SET mname=:mname, mpara=:mpara, mnotes=:mnotes, mbody=:mbody, last_modified_dt=:last_modified_dt, compile_status=:compile_status WHERE mid=:mid";
    const SQL_FIND_EVALUATE = "SELECT * FROM evaluation WHERE mid=:mid and query_tag=:query_tag and pertube_type=:pertube_type and pertube_paras_str=:pertube_paras_str";
    const SQL_ADD_EVALUATE = "INSERT INTO evaluation (mid, query_tag, pertube_type, pertube_paras_str, submitted_dt) VALUES(:mid, :query_tag, :pertube_type, :pertube_paras_str, :submitted_dt)";
    const SQL_UPDATE_EVALUATE = "UPDATE evaluation SET submitted_dt=:submitted_dt, evaluate_status=-1 WHERE mid=:mid and query_tag=:query_tag and pertube_type=:pertube_type and pertube_paras_str=:pertube_paras_str";
    const SQL_GET_ALL_MODELS_LIST = 'SELECT m.mid,CONCAT(u.firstname,CONCAT(" ", u.lastname)) AS user,m.mname,m.mpara,m.last_modified_dt FROM models m, users u WHERE m.compile_status=0 AND u.uid=m.uid';
    const SQL_GET_MODEL_LIST = "SELECT mid,mname,mpara,submitted_dt,last_modified_dt,last_compile_dt,compile_status FROM models";
    const SQL_GET_MODEL_DETAIL = "SELECT * FROM models WHERE mid=:mid LIMIT 1";
    const SQL_GET_MODEL_EVALUATION_DETAIL = "SELECT e.id, e.pertube_type, e.evaluated_dt, e.evaluate_status, e.evaluate_msg, e.performances, q.name, m.mname FROM evaluation as e, models as m, query_paths as q WHERE e.mid=:mid and m.mid=:mid and q.query_tag=e.query_tag and e.pertube_type=:pertube_type";
    const SQL_GET_PERTUBE_EVALUATION = "SELECT e.evaluate_status, e.pertube_type, e.pertube_paras_str, e.performances, q.name, m.mname FROM evaluation as e, models as m, query_paths as q WHERE e.mid=:mid and m.mid=:mid and q.query_tag=e.query_tag and e.pertube_type!=0 and e.evaluate_status=0";
    const SQL_GET_INDEX_LIST = "SELECT * FROM index_paths";
    const SQL_GET_INDEX_DETAIL = "SELECT * FROM index_paths WHERE id=:id LIMIT 1";
    const SQL_GET_QUERY_LIST = "SELECT * FROM query_paths";
    const SQL_GET_QUERY_LIST_FULL = "SELECT q.query_tag, q.name, q.notes as qnotes, i.iname, i.notes as inotes, i.stats FROM query_paths as q, index_paths as i WHERE q.index_id=i.id";
    const SQL_GET_QUERY_LIST_FULL2 = "SELECT evaluate_status FROM evaluation WHERE mid=:mid and query_tag=:query_tag";
    const SQL_GET_QUERY_DETAIL = "SELECT * FROM query_paths WHERE query_tag=:query_tag LIMIT 1";
    const SQL_GET_EVALUATION_BY_QUERY = "SELECT m.mname, m.mpara, m.mnotes, e.performances FROM models as m, evaluation as e WHERE e.query_tag=:query_tag and e.evaluate_status=0 and e.pertube_type=0 and e.mid=m.mid";

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
            $stmt->bindValue(':compile_status', -1, PDO::PARAM_STR);
            $stmt->bindValue(':last_modified_dt', gmdate('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    private function find_or_update_evaluation_entry($find_stmt, $mid, $query_tag, $pertube_type, $pertube_paras_str) {
        global $db;
        $find_stmt->execute();
        if ($find_stmt->rowCount() == 0) {
            $update_stmt = $db->prepare(self::SQL_ADD_EVALUATE);
        } else {
            $update_stmt = $db->prepare(self::SQL_UPDATE_EVALUATE);
        }
        $update_stmt->bindValue(':mid', $mid, PDO::PARAM_STR);
        $update_stmt->bindValue(':query_tag', $query_tag, PDO::PARAM_STR);
        $update_stmt->bindValue(':pertube_type', $pertube_type, PDO::PARAM_STR);
        $update_stmt->bindValue(':pertube_paras_str', $pertube_paras_str, PDO::PARAM_STR);
        $update_stmt->bindValue(':submitted_dt', gmdate('Y-m-d H:i:s'), PDO::PARAM_STR);
        $update_stmt->execute();
    }

    public function evaluate_model($uid, $apikey, $mid, $query_list_str, 
            $pertube_type_list_str) {
        global $db;
        $this->validate_user($uid, $apikey);
        $query_list = explode(",", $query_list_str);
        $pertube_type_list = explode(",", $pertube_type_list_str);
        foreach ($query_list as $query_tag) {
            try {
                $find_stmt = $db->prepare(self::SQL_FIND_EVALUATE);
                $find_stmt->bindValue(':mid', $mid, PDO::PARAM_STR);
                $find_stmt->bindValue(':query_tag', $query_tag, PDO::PARAM_STR);
                foreach ($pertube_type_list as $pertube_type) {
                    $find_stmt->bindValue(':pertube_type', $pertube_type, PDO::PARAM_STR);
                    switch ($pertube_type) {
                        case '0':
                            $find_stmt->bindValue(':pertube_paras_str', "", PDO::PARAM_STR);
                            $this->find_or_update_evaluation_entry($find_stmt, $mid, $query_tag, $pertube_type, "");
                            break;
                        case '1': //LV1
                            foreach (range(0.1, 1, 0.1) as $pace) {
                                $para_str = "b:".$pace;
                                $find_stmt->bindValue(':pertube_paras_str', $para_str, PDO::PARAM_STR);
                                $this->find_or_update_evaluation_entry($find_stmt, $mid, $query_tag, $pertube_type, $para_str);
                            }
                            break;
                        case '2': //LV3
                            foreach (range(0, 3, 1) as $pace) {
                                $para_str = "k:".pow(10, $pace);
                                $find_stmt->bindValue(':pertube_paras_str', $para_str, PDO::PARAM_STR);
                                $this->find_or_update_evaluation_entry($find_stmt, $mid, $query_tag, $pertube_type, $para_str);
                            }
                            break;
                        case '3': //TN1(constant)
                            foreach (range(0, 6, 1) as $pace) {
                                $para_str = "k:".pow(10, $pace);
                                $find_stmt->bindValue(':pertube_paras_str', $para_str, PDO::PARAM_STR);
                                $this->find_or_update_evaluation_entry($find_stmt, $mid, $query_tag, $pertube_type, $para_str);
                            }
                            break;
                        case '4': //TN2(linear)
                            foreach (range(-1, 4, 1) as $pace) {
                                $para_str = "b:".pow(10, $pace);
                                $find_stmt->bindValue(':pertube_paras_str', $para_str, PDO::PARAM_STR);
                                $this->find_or_update_evaluation_entry($find_stmt, $mid, $query_tag, $pertube_type, $para_str);
                            }
                            break;
                        case '5': //TG1(constant)
                            foreach (range(0, 6, 1) as $pace) {
                                $para_str = "k:".pow(10, $pace);
                                $find_stmt->bindValue(':pertube_paras_str', $para_str, PDO::PARAM_STR);
                                $this->find_or_update_evaluation_entry($find_stmt, $mid, $query_tag, $pertube_type, $para_str);
                            }
                            break;
                        case '6': //TG3(constant)
                            foreach (range(0, 6, 1) as $pace) {
                                $para_str = "k:".pow(10, $pace);
                                $find_stmt->bindValue(':pertube_paras_str', $para_str, PDO::PARAM_STR);
                                $this->find_or_update_evaluation_entry($find_stmt, $mid, $query_tag, $pertube_type, $para_str);
                            }
                            break;
                        default:
                            # code...
                            break;
                    }
                }
            } catch( PDOException $Exception ) {
                throw new RuleException($Exception->getMessage(), 401);
            }
        } 
    }

    private function column_name($field) {
        if (array_key_exists($field, static::$column_lookup)) {
            return static::$column_lookup[$field];
        }
        return static::$column_lookup["0"]; 
    }


    public function get_all_models_list($uid, $apikey, 
            $start = "0", $end = "-1") {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $sql_qry = self::SQL_GET_ALL_MODELS_LIST;
            $sql_qry .= " order by m.mname asc";
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

    public function get_model_evaluation_details($uid, $apikey, $mid, $pertube_type) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_MODEL_EVALUATION_DETAIL);
            $stmt->bindValue(':mid', $mid, PDO::PARAM_STR);
            $stmt->bindValue(':pertube_type', $pertube_type, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function get_pertube_evaluation($uid, $apikey, $mid) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_PERTUBE_EVALUATION);
            $stmt->bindValue(':mid', $mid, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $r = array();
            foreach ($rows as $row) {
                //var_dump($row);
                $k = $row["pertube_type"]."-".$row["name"]);
                if (!array_key_exists($k) {
                    $r[$k] = array();
                } 
                $r[$k][$row["pertube_paras_str"]] = $row["performances"];
                //var_dump($r);
            }
            return $r;
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

    public function get_query_list_nouser() {
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_QUERY_LIST_FULL);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function get_query_list_full($uid, $apikey, $mid) {
        $this->validate_user($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_QUERY_LIST_FULL);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as &$value) {
                $st = $db->prepare(self::SQL_GET_QUERY_LIST_FULL2);
                $st->bindValue(':mid', $mid, PDO::PARAM_STR);
                $st->bindValue(':query_tag', $value['query_tag'], PDO::PARAM_STR);
                $st->execute();
                $row = $st->fetch(PDO::FETCH_ASSOC);
                $value['evaluate_status'] = $row['evaluate_status'];
            }
            return $rows;
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

    /*
    * do not need the user's log in
    */
    public function get_evaluations_of_querytag($query_tag) {
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_EVALUATION_BY_QUERY);
            $stmt->bindValue(':query_tag', $query_tag, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }
}

$dao_play = new DAOPlay();
?>