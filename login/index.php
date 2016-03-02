<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/login/includes.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/api/include/send_mail.php");

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: '.SITE); } 

//if form has been submitted process it
if(isset($_POST['submit'])) {

	//very basic validation
	if(strlen($_POST['username']) < 3){
		$error[] = 'Username is too short.';
	} else if (strlen($_POST['username']) > 64) {
    $error[] = 'Username is too long.';
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

  // Google reCAPTCHA verification
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = array(
    'secret' => '6LdTpBgTAAAAAI0Oy10NbMP7NPSdODVVs5yJjWHy', 
    'response' => $_POST['g-recaptcha-response']
  );

  // use key 'http' even if you send the request to https://...
  $options = array(
      'http' => array(
          'method'  => 'POST',
          'content' => http_build_query($data),
      ),
  );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  if ($result === FALSE) { 
    $error[] = 'reCAPTCHA verification failed.';
  } else {
    $result = json_decode($result, true);
    if ($result["success"] === FALSE) {
      $error[] = 'reCAPTCHA verification failed.';
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
            $stmt = $db->prepare('SELECT COUNT(*) FROM users');
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $isAdmin = $row["COUNT(*)"] == "0" ? 1 : 0;

			$stmt = $db->prepare('INSERT INTO users (username,password,email,firstname,middlename,lastname,institute,isAdmin,active,regAt) VALUES (:username,:password,:email,:firstname,:middlename,:lastname,:institute,:isAdmin,:active,:regAt)');
            $stmt->execute(array(
                ':username' => $_POST['username'],
        		':password' => $hashedpassword,
        		':email' => $_POST['email'],
                ':firstname' => $_POST['firstname'],
                ':middlename' => $_POST['middlename'],
                ':lastname' => $_POST['lastname'],
                ':institute' => $_POST['institute'],
                ':isAdmin' => $isAdmin,
        		':active' => $activasion,
                ':regAt' => gmdate('Y-m-d H:i:s')
            ));

			$id = $db->lastInsertId('uid');

            $mail_to = $_POST['email'];
            $mail_subject = "[".SITENAME."]Registration Confirmation";
            $mail_body = "Dear ".$_POST['username'].",\n\nThank you for registering at ".SITENAME."\n\n To activate your account, please click on this link:\n\n ".DIR."activate.php?x=$id&y=$activasion\n\n Regards\n\n Site Admin \n\n";
            send_mail($mail_to, $mail_subject, $mail_body);

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
			<form role="form" method="post" action="" data-toggle="validator" autocomplete="off">
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
					<input type="text" name="username" data-error="The username can only consist of alphabetical, number, dot and underscore. Minimum length is 3 and maximum length is 64." data-minlength="3" maxlength="64" pattern="^[a-zA-Z0-9_\.]+$" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" tabindex="1" required>
          <div class="help-block with-errors"></div>
				</div>
				<div class="form-group">
					<input type="email" data-error="email address is invalid" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="2" required>
          <div class="help-block with-errors"></div>
				</div>
				<div class="form-group">
					<input type="password" name="password" id="password" data-minlength="3" class="form-control input-lg" placeholder="Password" tabindex="3" required>
          <div class="help-block with-errors"></div>
				</div>
				<div class="form-group">
					<input type="password" name="passwordConfirm" id="passwordConfirm" data-match="#password" data-match-error="Password don't match" class="form-control input-lg" placeholder="Confirm Password" tabindex="4" required>
          <div class="help-block with-errors"></div>
				</div>
				<div class="form-group">
          <input type="text" name="firstname" id="firstname" class="form-control input-lg" placeholder="First Name" value="<?php if(isset($error)){ echo $_POST['firstname']; } ?>" tabindex="5" required>
          <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <input type="text" name="middlename" id="middlename" class="form-control input-lg" placeholder="Middle Name" value="<?php if(isset($error)){ echo $_POST['middlename']; } ?>" tabindex="6">
          <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <input type="text" name="lastname" id="lastname" class="form-control input-lg" placeholder="Last Name" value="<?php if(isset($error)){ echo $_POST['lastname']; } ?>" tabindex="7" required>
          <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <input type="text" name="institute" id="institute" class="form-control input-lg" placeholder="Affiliation" value="<?php if(isset($error)){ echo $_POST['institute']; } ?>" tabindex="8" required>
          <div class="help-block with-errors"></div>
        </div>

        <div class="g-recaptcha" data-sitekey="6LdTpBgTAAAAACXhQ5q7jRLM9apH5IiOXsLwWtZh"></div>

				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="9"></div>
				</div>
			</form>
		</div>
	</div>

</div>

<?php 
//include header template
require('layout/footer.php'); 
?>
