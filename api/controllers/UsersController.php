<?php 

class UsersController{


	public function __construct(){

	}


	public function listAllUsers(){
		Api::right(2);

		global $bdd;
  		$query = $bdd->query('SELECT * FROM users');

  		if($query->execute()){
  			$allUser = $query->fetchAll(PDO::FETCH_ASSOC);
			Api::response(200, array($allUser));
  		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
		}
	}


	public function listUserbyID(){
  		$user = Api::findById('users', $param = F3::get('PARAMS.id'));
  			/*$param = F3::get('PARAMS.id');
			global $bdd;
  			$query = $bdd->prepare('SELECT * FROM users WHERE id=?');
  			$query->execute(array($param));
  			$user = $query->fetch(PDO::FETCH_ASSOC);*/
		
  		if($user['token'] == $_REQUEST['token_access'] && $user['usergroup'] != 2){
  			Api::right(1);
  		}else{
  			Api::right(2);
  		}

  		Api::response(200, array($user));
  		
	}


	public function createUser(){
		Api::right(2);

		$tokenVerif = Api::tokenGenerator();
  		( isset($_POST['firstname']) ) 	? $fname 		= $_POST['firstname'] 	: Api::response(400, array('error' => 'Veuillez rentrer un prenom')); 
  		( isset($_POST['name']) ) 		? $lname 		= $_POST['name'] 		: Api::response(400, array('error' => 'Veuillez rentrer un nom')); 
  		( isset($_POST['email']) ) 		? $email 		= $_POST['email'] 		: Api::response(400, array('error' => 'Veuillez rentrer un email')); 
  		( isset($_POST['usergroup']) ) 	? $usergroup 	= $_POST['usergroup'] 	: Api::response(400, array('error' => 'Veuillez rentrer un type de compte')); 

  		global $bdd;
  		$query = $bdd->prepare('SELECT * FROM users WHERE email=?');

  		if($query->execute(array($email))){
  			if($query->fetch(PDO::FETCH_ASSOC)){
                Api::response(400, array('error' => 'email deja utilise'));
  			}else{
  				$query = $bdd->prepare('INSERT INTO users(firstname, name, email, token, usergroup) VALUES(:firstname, :name, :email, :token, :usergroup)');
  				$query->bindParam(':firstname', $fname, 		PDO::PARAM_STR);
  				$query->bindParam(':name', 		$lname, 		PDO::PARAM_STR);
  				$query->bindParam(':email', 	$email, 		PDO::PARAM_STR);
  				$query->bindParam(':token', 	$tokenVerif, 	PDO::PARAM_STR);
  				$query->bindParam(':usergroup', $usergroup, 	PDO::PARAM_INT);
	
  				/*$query->execute(array(
  					'firstname' => $fname,
					'name' => $lname,
					'email' => $email,
					'token' => $tokenVerif,
					'usergroup' => $usergroup
  				));*/
	
				if($query->execute()){
					Api::response(200, array('Utilisateur enregistrer'));
				}
			}  			
  		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
  		}

	}


	public function updateUser(){
		$user = Api::findById('users', $param = F3::get('PARAMS.id'));

		if($user['token'] == $_REQUEST['token_access'] && $user['usergroup'] != 2){
  			Api::right(1);
  		}else{
  			Api::right(2);
  		}

		$data = Put::get();

		$fname 		= ( isset($data['firstname']) ) 	?	$data['firstname'] 	:	$user['firstname'];
		$lname 		= ( isset($data['name']) ) 			?	$data['name'] 		:	$user['name']; 
		$email 		= ( isset($data['email']) ) 		?	$data['email'] 		:	$user['email']; 
		$usergroup 	= ( isset($data['usergroup']) ) 	?	$data['usergroup'] 	:	$user['usergroup'];

		global $bdd;
		$query = $bdd->prepare('SELECT * FROM users WHERE email=? && id!=?');

  		if($query->execute(array($email, $param))){
  			if($query->fetch(PDO::FETCH_ASSOC)){
                Api::response(400, array('error' => 'email deja utilise'));
  			}else{
  				$userId = $user['id'];
				$query = $bdd->prepare('UPDATE users SET firstname=:firstname, name=:name, email=:email, usergroup=:usergroup WHERE id=:id ');
				$query->bindParam(':firstname', $fname, 	PDO::PARAM_STR);
  				$query->bindParam(':name', 		$lname, 	PDO::PARAM_STR);
  				$query->bindParam(':email', 	$email, 	PDO::PARAM_STR);
  				$query->bindParam(':usergroup', $usergroup, PDO::PARAM_INT);
  				$query->bindParam(':id', 		$userId, 	PDO::PARAM_INT);
  				//$arrayUpdate = array('firstname' => $fname, 'name' => $lname, 'email' => $email, 'usergroup' => $usergroup);
  			
  				if($query->execute()){
					Api::response(200, array('Utilisateur ' .$fname. ' mis a jour'));
				}
			}  			
  		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
  		}

	
	}


	public function deleteAllUsers(){
		Api::right(2);
		global $bdd;
		$query = $bdd->prepare('DELETE FROM users');
		if($query->execute()){
			Api::response(200, array('Tous les utilisateurs ont ete supprimes'));
		}
	}


	public function deleteUserbyID(){
		$user = Api::findById('users', $param = F3::get('PARAMS.id'));

		if($user['token'] == $_REQUEST['token_access'] && $user['usergroup'] != 2){
  			Api::right(1);
  		}else{
  			Api::right(2);
  		}

		global $bdd;
		$userId = $user['id'];
		$query = $bdd->prepare('DELETE FROM users WHERE id=:id');
  		$query->bindParam(':id', $userId, PDO::PARAM_INT);

		if($query->execute()){
			Api::response(200, array('Utilisateur ' . $user['firstname'] . ' supprime'));
		}
	}



}

 ?>