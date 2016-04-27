<?php
require_once "dao.php";

class DAOAdmin extends DAO {
    const SQL_UPDATE_INFO = "INSERT INTO info (name, content) VALUES(:name, :content) ON DUPLICATE KEY UPDATE name=:name, content=:content";
    const SQL_GET_INFO = "SELECT content FROM info WHERE name=:name";
    const SQL_ADD_INDEX = "INSERT INTO index_paths (uid, iname, path, notes, stats, add_dt) VALUES(:uid, :name, :path, :notes, :stats, :add_dt)";
    const SQL_UPDATE_INDEX = "UPDATE index_paths SET uid=:uid, iname=:name, path=:path, notes=:notes, stats=:stats, add_dt=:add_dt WHERE id=:id";
    const SQL_ADD_QUERY = "INSERT INTO query_paths (uid, index_id, name, query_path, evaluation_path, notes, add_dt) VALUES(:uid, :index_id, :name, :query_path, :evaluation_path, :notes, :add_dt)";
    const SQL_UPDATE_QUERY = "UPDATE query_paths SET uid=:uid, index_id=:index_id, name=:name, query_path=:query_path, evaluation_path=:evaluation_path, notes=:notes, add_dt=:add_dt WHERE query_tag=:query_tag";
    
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

    public function add_update_index($uid, $apikey, $iid, 
            $name, $path, $notes, $stats) {
        $this->validate_admin($uid, $apikey);
        try {
            global $db;
            if (empty($iid)) {
                $stmt = $db->prepare(self::SQL_ADD_INDEX);
            } else {
                $stmt = $db->prepare(self::SQL_UPDATE_INDEX);
                $stmt->bindValue(':id', $iid, PDO::PARAM_STR);
            }
            $stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':path', $path, PDO::PARAM_STR);
            $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);
            $stmt->bindValue(':stats', $stats, PDO::PARAM_STR);
            $stmt->bindValue(':add_dt', gmdate('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function add_update_query($uid, $apikey, $query_tag, 
            $index_id, $name, $query_path, $evaluation_path, $notes) {
        $this->validate_admin($uid, $apikey);
        try {
            global $db;
            if (empty($query_tag)) {
                $stmt = $db->prepare(self::SQL_ADD_QUERY);
            } else {
                $stmt = $db->prepare(self::SQL_UPDATE_QUERY);
                $stmt->bindValue(':query_tag', $query_tag, PDO::PARAM_STR);
            }
            $stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
            $stmt->bindValue(':index_id', $index_id, PDO::PARAM_STR);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':query_path', $query_path, PDO::PARAM_STR);
            $stmt->bindValue(':evaluation_path', $evaluation_path, PDO::PARAM_STR);
            $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);
            $stmt->bindValue(':add_dt', gmdate('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }
}

$dao_admin = new DAOAdmin();
?>