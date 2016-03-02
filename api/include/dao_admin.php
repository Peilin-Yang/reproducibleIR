<?php
require_once "dao.php";

class DAOAdmin extends DAO {
    const SQL_UPDATE_INFO = "INSERT INTO info (name, content) VALUES(:name, :content) ON DUPLICATE KEY UPDATE name=:name, content=:content";
    const SQL_GET_INFO = "SELECT content FROM info WHERE name=:name";

    public function __construct() {

    }

    private function validate_admin($uid, $apikey) {
        $host_user = $this->verify_admin($uid, $apikey);
        if (count($host_user) == 0) {
            throw new RuleException(ERR501, 501);
        }
    }

    public function update_instruction($uid, $apikey, $content) {
        $this->validate_admin($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_UPDATE_INFO);
            $stmt->bindValue(':name', "code_instruction", PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->execute();
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function get_info($uid, $apikey, $name) {
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

$dao_admin = new DAOAdmin();
?>