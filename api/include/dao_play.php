<?php
require_once "dao.php";

class DAOPlay extends DAO {
    const SQL_ADD_MODEL = "INSERT INTO models (uid, mname, mpara, mnotes, mbody, submitted_dt, last_modified_dt) VALUES(:uid, :mname, :mpara, :mnotes, :mbody, :submitted_dt, :last_modified_dt)";
    const SQL_GET_MODEL = "SELECT * FROM models WHERE name=:name";

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

    public function get_model($uid, $apikey, $name) {
        $this->validate_admin($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_GET_INFO);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
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