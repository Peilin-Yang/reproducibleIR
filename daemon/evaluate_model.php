<?php
require_once "/var/www/".getenv("SITENAME")."/public_html/includes/config.php";
require_once "/var/www/".getenv("SITENAME")."/public_html/api/include/ruleexception.php";

class Deamon {
    const SQL_FETCH_EVALUATION = "SELECT * FROM evaluation WHERE evaluate_status=-1 ORDER BY submitted_dt ASC LIMIT 1";
    const SQL_SET_TEMP_INFO = "UPDATE evaluation SET evaluate_status=-2 WHERE id=:id";
    const SQL_UPDATE_EVALUATION_ENTRY = "UPDATE evaluation SET evaluated_dt=:evaluated_dt, evaluate_status=:evaluate_status, evaluate_msg=:evaluate_msg, performances=:performances WHERE id=:id";
    const SQL_GET_INFO = "SELECT m.mbody, q.query_path, q.evaluation_path, i.path FROM models as m, evaluation as e, index_paths as i, query_paths as q WHERE e.id = :evaluation_id and e.mid = m.mid and e.query_tag=q.query_tag and q.index_id=i.id";

    private $_env_sitename;
    private $_code_path;
    private $_compile_workingdir;
    private $_compile_fn;
    private $_evaluate_docker_cmd;

    public function __construct() {
        $this->_env_sitename = getenv("SITENAME");
        $this->_code_path = getenv("CODE_PATH");
        $this->_query_path = getenv("QUERY_PATH");
        $this->_index_path = getenv("INDEX_PATH");
        $this->_judgement_path = getenv("JUDGEMENT_PATH");
        $this->_compile_workingdir = "/var/www/".$this->_env_sitename."/public_html/code_for_compile/";
        $this->_compile_fn = "evaluate";
        $this->_evaluate_docker_cmd = "docker run --rm -v ".$this->_code_path."code_for_compile/:/functions/ -v ".$this->_index_path.":/indexes/ -v ".$this->_query_path.":/queries/ -v ".$this->_judgement_path.":/judgments/ yangpeilyn/rires_eval:latest -a";
    }

    private function join_paths() {
        $paths = array();
        foreach (func_get_args() as $arg) {
            if ($arg !== '') { $paths[] = $arg; }
        }
        return preg_replace('#/+#','/',join('/', $paths));
    }

    private function evaluate_using_docker($code_text, $index_path, $query_path, 
                    $judgement_path, &$return_code, &$err_msg, &$output) {
        if (!file_exists($this->_compile_workingdir)) {
            mkdir($this->_compile_workingdir, $recursive=true);
        }
        chdir($this->_compile_workingdir);
        file_put_contents($this->_compile_fn, $code_text);
        $command = $this->_evaluate_docker_cmd." ".$this->_compile_fn." ".$index_path." ".$query_path." ".$judgement_path;
        $descriptorspec = array(
           0 => array("pipe", "r"),  // stdin
           1 => array("pipe", "w"),  // stdout
           2 => array("pipe", "w"),  // stderr
        );
        $process = proc_open($command, $descriptorspec, $pipes, dirname(__FILE__), null);
        $status = proc_get_status($process);
        while ($status["running"]) {
          sleep(1);
          $status = proc_get_status($process);
        }
        $return_code = $status['exitcode'];
        if ($return_code !== 0) {
            $err_msg = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
        } else {
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
        }
    }

    private function update_evaluation_status($evaluate_status, $evaluate_msg, 
            $performances_str, $id) {
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_UPDATE_EVALUATION_ENTRY);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->bindValue(':evaluate_status', $evaluate_status, PDO::PARAM_STR);
            $stmt->bindValue(':evaluate_msg', $evaluate_msg, PDO::PARAM_STR);
            $stmt->bindValue(':performances', $performances_str, PDO::PARAM_STR);
            $stmt->bindValue(':evaluated_dt', gmdate('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function evaluate_model() {
        //$this->validate_admin($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_FETCH_EVALUATION);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $value) {
                $st0 = $db->prepare(self::SQL_SET_TEMP_INFO);
                $st0->bindValue(':id', $value['id'], PDO::PARAM_STR);
                $st0->execute();
                $st = $db->prepare(self::SQL_GET_INFO);
                $st->bindValue(':evaluation_id', $value['id'], PDO::PARAM_STR);
                $st->execute();
                $row = $st->fetch(PDO::FETCH_ASSOC);
                $model_body = $row['mbody'];
                $index_path = $row['path'];
                $query_path = $row['query_path'];
                $judgement_path = $row['evaluation_path'];
                $status_code;
                $status_msg;
                $performances_str;
                $this->evaluate_using_docker($model_body, $index_path, $query_path, 
                    $judgement_path, $status_code, $status_msg, $performances_str);
                //var_dump($status_code);
                //var_dump($status_msg);
                $this->update_evaluation_status(
                    $status_code, 
                    $status_msg, 
                    $performances_str,
                    $value['id']
                );
            };
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }
}

$deamon= new Deamon();
$deamon->evaluate_model();
?>