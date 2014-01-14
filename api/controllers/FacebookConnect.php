<?php 

class FacebookConnect{

	public static function login(){

		require 'libs/facebook/facebook.php';

		$config = array(
      		'appId' => '507911519323868',
      		'secret' => 'c084523bf92e9c6b894f50c78e33a6d8',
      		'fileUpload' => false, 
      		'allowSignedRequest' => false 
  		);

  		$facebook = new Facebook($config);

  		$login = $facebook->getloginUrl(array("scope" => "email",  "display" => "popup"));
  		echo '<a href=" '.$login.' ">Connection</a>';

  		$session = $facebook->getUser();

  		if($session == 0){
  			die();
  		}else{
  			/*$fql = 'SELECT email, first_name, last_name FROM user WHERE uid="'.$session.'"';
  			$param_fql = array('method' => 'fql.query', 'query' => $fql);*/

  			$info = $facebook->api('/me');

  			$token = md5(uniqid());
  			$fname = $info['first_name'];
  			$lname = $info['last_name'];
  			$email = $info['email'];

  			global $bdd;
  			$query = $bdd->prepare('SELECT * FROM users WHERE email=?');
  			$query->execute(array($email));
  			// $match = $query->fetchAll(PDO::FETCH_ASSOC);

  			if($match = $query->fetch(PDO::FETCH_ASSOC)){
  				echo '<br>Token du compte FB';
  	
  			}else{
  				$query = $bdd->prepare('INSERT INTO users (name, firstname, email, token) VALUES (:name, :firstname, :email, :token) ');
  				$query->bindParam(':name', $lname);
  				$query->bindParam(':firstname', $fname);
  				$query->bindParam(':email', $email);
  				$query->bindParam(':token', $token);
  				$query->execute();
  			}

  		}

	}

}

 ?>