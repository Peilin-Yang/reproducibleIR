<?php
require_once "dao.php";

class DAOAdmin extends DAO {
    const SQL_UPDATE_INFO = "INSERT INTO info (name, content) VALUES(:name, :content) ON DUPLICATE KEY UPDATE name=:name, content=:content";

    public function __construct() {

    }

    public function update_instruction($uid, $content) {
        $host_user = $this->verify_admin($uid);
        if (count($host_user) == 0) {
            throw new RuleException(ERR501, 501);
        }
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
}

$dao_admin = new DAOAdmin();
?>