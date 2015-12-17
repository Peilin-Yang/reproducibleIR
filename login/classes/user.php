<?php
include('password.php');
class User extends Password{

    private $_db;

    function __construct($db){
    	parent::__construct();
    
    	$this->_db = $db;
    }

	private function get_user_hash($username){	

		try {
			$stmt = $this->_db->prepare('SELECT password FROM users WHERE username = :username AND active="Yes" ');
			$stmt->execute(array('username' => $username));
			
			$row = $stmt->fetch();
			return $row['password'];

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function login($username,$password){
		$hashed = $this->get_user_hash($username);
		
		if($this->password_verify($password,$hashed) == 1){
		    $_SESSION['loggedin'] = true;

		    $stmt = $this->_db->prepare('SELECT uid,apikey,firstLoginAt FROM users WHERE username = :username ');
			$stmt->execute(array('username' => $username));
			$row = $stmt->fetch();
			if ($row['firstLoginAt'] === NULL) {
				$stmt1 = $this->_db->prepare("UPDATE users SET firstLoginAt=:firstLoginAt WHERE username = :username");
				$stmt1->execute(array(
					':firstLoginAt' => gmdate('Y-m-d H:i:s'),
					':username' => $username
				));
			}
		    $_SESSION['uid'] = $row['uid'];
		    $_SESSION['apikey'] = $row['apikey'];
		    $_SESSION['username'] = $username;

		    return true;
		} 	
	}
		
	public function logout(){
		session_destroy();
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}		
	}
	
}


?>