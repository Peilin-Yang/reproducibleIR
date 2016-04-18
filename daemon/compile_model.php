<?php
require_once "/var/www/".getenv("SITENAME")."/public_html/includes/config.php";

class Deamon {
    const SQL_FETCH_ONE_MODEL = "SELECT * FROM models WHERE compile_status=-1 ORDER BY last_modified_dt ASC LIMIT 10";
    const SQL_UPDATE_COMPILE_MODEL = "UPDATE models SET last_compile_dt=:last_compile_dt, compile_status=:compile_status, compile_msg=:compile_msg WHERE mid=:mid";

    private $_env_sitename;
    private $_code_path;
    private $_compile_workingdir;
    private $_compile_fn;
    private $_compile_docker_cmd;

    public function __construct() {
        $this->_env_sitename = getenv("SITENAME");
        $this->_code_path = getenv("CODE_PATH");
        $this->_compile_workingdir = "/var/www/".$this->_env_sitename."/public_html/code_for_compile/";
        $this->_compile_fn = "compile";
        $this->_compile_docker_cmd = "docker run --rm -v ".$this->_code_path."code_for_compile/:/functions/ yangpeilyn/rires_eval:latest -m compile";
    }

    private function join_paths() {
        $paths = array();
        foreach (func_get_args() as $arg) {
            if ($arg !== '') { $paths[] = $arg; }
        }
        return preg_replace('#/+#','/',join('/', $paths));
    }

    private function compile_using_docker($code_text, &$return_code, &$msg) {
        if (!file_exists($this->_compile_workingdir)) {
            mkdir($this->_compile_workingdir, $recursive=true);
        }
        chdir($this->_compile_workingdir);
        file_put_contents($this->_compile_fn, $code_text);
        #var_dump('output compile file');
        $command = $this->_compile_docker_cmd;

        $descriptorspec = array(
           0 => array("pipe", "r"),  // stdin
           1 => array("pipe", "w"),  // stdout
           2 => array("pipe", "w"),  // stderr
        );
        $process = proc_open($command, $descriptorspec, $pipes, dirname(__FILE__), null);
        /*$r = exec($command, $output, $return_var);
        var_dump($r);
        var_dump($output);
        var_dump($return_var);*/
        $status = proc_get_status($process);
        while ($status["running"]) {
          sleep(1);
          $status = proc_get_status($process);
        }
        $return_code = $status['exitcode'];
        if ($return_code !== 0) {
            $msg = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
        }
    }

    private function update_model_compile_db($compile_status, $compile_msg, $mid) {
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_UPDATE_COMPILE_MODEL);
            $stmt->execute(array(
                ':last_compile_dt' => gmdate('Y-m-d H:i:s'),
                ':compile_status' => $compile_status, 
                ':compile_msg' => $compile_status === 0 ? "" : $compile_msg,
                ':mid' => $mid
            ));
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }

    public function compile_model() {
        //$this->validate_admin($uid, $apikey);
        try {
            global $db;
            $stmt = $db->prepare(self::SQL_FETCH_ONE_MODEL);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $value) {
                $status_code;
                $status_msg;
                $this->compile_using_docker($value['mbody'], $status_code, $status_msg);
                //var_dump($status_code);
                //var_dump($status_msg);
                $this->update_model_compile_db(
                    $status_code, 
                    $status_msg, 
                    $value['mid']
                );
            };
        } catch( PDOException $Exception ) {
            throw new RuleException($Exception->getMessage(), 401);
        }
    }
}

$deamon= new Deamon();
$deamon->compile_model();
?>