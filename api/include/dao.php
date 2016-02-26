<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/includes/error_code.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/util.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/ruleexception.php");

class DAO {
    const SQL_SELECT_USER = "SELECT * FROM users WHERE uid = :uid and apikey = :apikey";
    const SQL_SELECT_USER_BY_USERNAME = "SELECT * FROM users WHERE username = :username";

    public function __construct() {

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

    public function verify_admin($uid, $apikey) {
        $host_user = $this->get_user($uid, $apikey);
        if (count($host_user) == 0) {
            throw new RuleException(ERR501, 501);
        } else if ($host_user[0]['isAdmin'] == "0") {
            throw new RuleException(ERR502, 502);
        }
        return $host_user[0];
    }

    protected function get_user($uid, $apikey) {
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

    protected function get_user_by_username($username) {
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
}

$dao = new DAO();
?>