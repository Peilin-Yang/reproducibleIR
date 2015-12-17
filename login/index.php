<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/includes/config.php"); 
require_once ($_SERVER["DOCUMENT_ROOT"]."/reproducibleIR/login/includes/head.php"); 

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: '.SITE); } 

//if form has been submitted process it
if(isset($_POST['submit'])){

	//very basic validation
	if(strlen($_POST['username']) < 3){
		$error[] = 'Username is too short.';
	} else {
		$stmt = $db->prepare('SELECT username FROM users WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['username']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])) {
			$error[] = 'Username provided is already in use.';
		}

	}

	if(strlen($_POST['password']) < 6){
		$error[] = 'Password is too short.';
	}

	if(strlen($_POST['passwordConfirm']) < 6){
		$error[] = 'Confirm password is too short.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}
			
	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {
			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO users (username,password,email,active,regAt) VALUES (:username,:password,:email,:active,:regAt)');
            $stmt->execute(array(
                ':username' => $_POST['username'],
				':password' => $hashedpassword,
				':email' => $_POST['email'],
				':active' => $activasion,
                ':regAt' => gmdate('Y-m-d H:i:s')
            ));

			$id = $db->lastInsertId('uid');

			//send email
            try {
                $message = array(
                    'text' => "Dear ".$_POST['username'].",\n\nThank you for registering at ".SITENAME."\n\n To activate your account, please click on this link:\n\n ".DIR."activate.php?x=$id&y=$activasion\n\n Regards\n\n Site Admin \n\n",
                    'subject' => "[".SITENAME."]Registration Confirmation",
                    'from_email' => FromEmail,
                    'from_name' => SITENAME.'Admin',
                    'to' => array(
                        array(
                            'email' => $_POST['email'],
                            'name' => $_POST['username'],
                            'type' => 'to'
                        )
                    ),
                    'headers' => array('Reply-To' => ReplyEmail),
                );
                $async = false;
                $result = $mandrill->messages->send($message, $async);
                //print_r($result);  
            } catch (Mandrill_Error $e) {
                // Mandrill errors are thrown as exceptions
                echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
                // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
                throw $e;
            }

			//redirect to index page
			header('Location: index.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}

//define page title
$title = SITENAME;

//include header template
require('layout/header.php'); 
?>


<div class="container">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h2>Please Sign Up</h2>
				<p>Already a member? <a href='login.php'>Login</a></p>
				<hr>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				//if action is joined show sucess
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2 class='bg-success'>Registration successful, please check your email to activate your account.</h2>";
				}
				?>

				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1">
				</div>
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="2">
				</div>
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
						</div>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="4">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
				</div>
			</form>
		</div>
	</div>

</div>

<?php 
//include header template
require('layout/footer.php'); 
?>
