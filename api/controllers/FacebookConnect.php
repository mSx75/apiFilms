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
        $facebook->getloginUrl(array("scope" => "email",  "display" => "popup"));
  		// $login = $facebook->getloginUrl(array("scope" => "email",  "display" => "popup"));
  		// echo '<a href=" '.$login.' ">Connection</a>';

  		$session = $facebook->getUser();

  		if($session == 0){
  			die();
  		}else{
  			$info = $facebook->api('/me');

  			$token = Perm::tokenGenerator();
  			$fname = $info['first_name'];
  			$lname = $info['last_name'];
  			$email = $info['email'];

  			global $bdd;
  			$query = $bdd->prepare('SELECT * FROM users WHERE email=?');
  			$query->execute(array($email));

  			if($match = $query->fetch(PDO::FETCH_ASSOC)){
                Api::response(200, array('Vous etes connecte, Token de votre compte : ' . $match['token']));
  			}else{
  				$query = $bdd->prepare('INSERT INTO users (name, firstname, email, usergroup, token) VALUES (:name, :firstname, :email, :usergroup, :token) ');
  				$query->bindParam(':name', $lname, PDO::PARAM_STR);
  				$query->bindParam(':firstname', $fname, PDO::PARAM_STR);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
  				$query->bindParam(':usergroup', 1, PDO::PARAM_INT);
  				$query->bindParam(':token', $token, PDO::PARAM_STR);
  				if($query->execute()){
                    Api::response(400, array('Votre compte a ete cree, Token de votre compte : ' . $token));
                }
  			}
  		}
	}


}

 ?>