<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/error_code.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/api/include/util.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/api/include/ruleexception.php");

class DAO {
    const SQL_SELECT_USER = "select username from users where uid = :uid and apikey = :apikey";
    const SQL_SELECT_USER_BY_USERNAME = "select * from users where username = :username";


    const SQL_SELECT_ALL_YEARS = "SELECT DISTINCT(year) FROM documents ORDER BY year ASC";
    const SQL_SELECT_ALL_RUNS_BY_YEAR = "SELECT DISTINCT(run) FROM documents WHERE year=:year ORDER BY run ASC";
    const SQL_SELECT_ALL_DOCS_OF_RUN = "SELECT * FROM documents WHERE run=:run";
    const SQL_SELECT_ALL_DOCS_OF_YEAR = "SELECT * FROM documents WHERE year=:year";
    const SQL_SELECT_JUDGEMENT_OF_YEAR = "SELECT * FROM judgements WHERE uid=:uid and year=:year";
    const SQL_UPDATE_JUDGEMENT = "INSERT INTO judgements (docid,year,uid,sec,rating) VALUES (:docid,:year,:uid,:sec,:rating) ON DUPLICATE KEY UPDATE rating=:rating";
	
    const SQL_SELECT_ALL_DOCS_FOR_COMPARISON_JUDGE = "SELECT docid, year, title, description, yelp_snippet FROM documents";
    const SQL_UPDATE_COMPARISON_JUDGEMENT = "INSERT INTO comp_judgements (docid,year,uid,judgement) VALUES (:docid,:year,:uid,:judgement) ON DUPLICATE KEY UPDATE judgement=:judgement";
    const SQL_SELECT_COMPARISON_JUDGEMENT = "SELECT * FROM comp_judgements WHERE uid=:uid";
    const SQL_COUNT_COMPARISON_JUDGEMENT = "SELECT COUNT(*) as count FROM comp_judgements WHERE uid=:uid";
    

    public function __construct() {

    }

    public function get_year($uid, $apikey) {
        global $db;
        $this_user = $this->verify_user($uid, $apikey);

        $query = self::SQL_SELECT_ALL_YEARS;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function get_docs_of_a_year($uid, $apikey, $year=2014, $page=1) {
        $apc_key = "docs_".$year."_".$uid."_".$page;
        global $db;
        $this_user = $this->verify_user($uid, $apikey);

        $query = self::SQL_SELECT_ALL_DOCS_OF_YEAR;
        $start = 0;
        $total = 0;
        if ($year == 2014) {
            if ($uid === "1") {
                $start = 0;
                $total = 509;
            }
            if ($uid === "2") {
                $start = 509;
                $total = 600;
            }
            if ($uid === "3") {
                $start = 909;
                $total = 600;
            }
        } else if ($year == 2013) {
            $total = 600;
        }
        $limit = min(50, $total-50*($page-1));
        $query .= " LIMIT ".($start+50*($page-1)).",".$limit;
/*        if ($year == 2014 && $uid === "1") {
            $query .= " UNION select * from toys where color = 'White' (LIMIT 3)";
        }*/
        //var_dump($query);
        $stmt = $db->prepare($query);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        return array("docs"=>$rows, "total"=>$total);
    }

    public function set_judgement($uid, $apikey, $docid, $year, $sec, $rating) {
        global $db;
        $this_user = $this->verify_user($uid, $apikey);

        $db->beginTransaction();
        try {
            $query = self::SQL_UPDATE_JUDGEMENT;
            $stmt = $db->prepare($query);
            $stmt->bindValue(':docid', $docid);
            $stmt->bindValue(':year', $year);
            $stmt->bindValue(':uid', $uid);
            $stmt->bindValue(':sec', $sec);
            $stmt->bindValue(':rating', $rating);
            $stmt->execute();
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public function get_judgement($uid, $apikey, $year) {
        global $db;
        $this_user = $this->verify_user($uid, $apikey);

        try {
            $query = self::SQL_SELECT_JUDGEMENT_OF_YEAR;
            $stmt = $db->prepare($query);
            $stmt->bindValue(':uid', $uid);
            $stmt->bindValue(':year', $year);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) == 0) {
                return array();
            }
            apc_add($apc_key, $result);
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /*
    * Compare Judge Related
    *
    *
    */
    public function get_compare_docs($uid, $apikey, $page=1) {
        global $db;
        $this_user = $this->verify_user($uid, $apikey);

        $query = self::SQL_SELECT_ALL_DOCS_FOR_COMPARISON_JUDGE;
        //$start = 0;
        //$total = 2109;
        if ($uid === "1") {
            $start = 0;
            $total = 1300;
        }
        if ($uid === "2") {
            $start = 900;
            $total = 1209;
        }
        /*if ($uid === "3") {
            $start = 909;
            $total = 600;
        }*/
        $limit = min(50, $total-50*($page-1));
        $query .= " LIMIT ".($start+50*($page-1)).",".$limit;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        return array("docs"=>$rows, "total"=>$total);
    }

    public function set_compare_judgement($uid, $apikey, $docid, $year, $judgement) {
        global $db;
        $this_user = $this->verify_user($uid, $apikey);

        $db->beginTransaction();
        try {
            $query = self::SQL_UPDATE_COMPARISON_JUDGEMENT;
            $stmt = $db->prepare($query);
            $stmt->bindValue(':docid', $docid);
            $stmt->bindValue(':year', $year);
            $stmt->bindValue(':uid', $uid);
            $stmt->bindValue(':judgement', $judgement);
            $stmt->execute();
            $db->commit();

            $query = self::SQL_COUNT_COMPARISON_JUDGEMENT;
            $stmt = $db->prepare($query);
            $stmt->bindValue(':uid', $uid);
            $stmt->execute();
            $rows = $stmt->fetch(PDO::FETCH_ASSOC); 

            return array("count"=>$rows["count"]);
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public function get_compare_judgement($uid, $apikey) {
        global $db;
        $this_user = $this->verify_user($uid, $apikey);

        try {
            $query = self::SQL_SELECT_COMPARISON_JUDGEMENT;
            $stmt = $db->prepare($query);
            $stmt->bindValue(':uid', $uid);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) == 0) {
                return array();
            }
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /*
     * returns user record is user is valid;
     * throws ruleexception is user is not valid
     */
    public function verify_user($uid, $apikey) {
        $host_user = $this->get_user($uid, $apikey);
        if (count($host_user) == 0) {
            throw new RuleException(ERR501, 501);
        }

        return $host_user[0];
    }

    private function get_user($uid, $apikey) {
        global $db;

        $stmt = $db->prepare(self::SQL_SELECT_USER);
        $stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
        $stmt->bindValue(':apikey', $apikey, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) == 0) {
            return array();
        }

        return $result;
    }

    private function get_user_by_username($username) {
        global $db;

        $stmt = $db->prepare(static::SQL_SELECT_USER_BY_USERNAME);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) == 0) {
            return array();
        }

        return $result;
    }

    private function getUniqueId(){
        return uniqid("", false);
    }
}

$dao = new DAO();
?>