<?php 

class UsersController{


	public function __construct(){

	}


	public function listAllUsers(){
		Api::right(0);

		global $bdd;
  		$query = $bdd->query('SELECT * FROM users');
  		$query->execute();

  		$allUser = $query->fetchAll(PDO::FETCH_ASSOC);
		Api::response(200, array($allUser));
	}


	public function listUserbyID(){
		Api::right(0);

		$param = F3::get('PARAMS.id');
		global $bdd;
  		$query = $bdd->prepare('SELECT * FROM users WHERE id=?');
  		$query->execute(array($param));

  		$user = $query->fetch(PDO::FETCH_ASSOC);
  		
		Api::response(200, array($user));
	}


	public function createUser(){
		Api::right(2);

		$tokenVerif = md5(uniqid());
  		( isset($_POST['first_name']) ) ? $first_name 	= $_POST['first_name'] 	: Api::response(400, array('error'=>'Veuillez rentrer un prenom')); 
  		( isset($_POST['last_name']) ) 	? $last_name 	= $_POST['last_name'] 	: Api::response(400, array('error'=>'Veuillez rentrer un nom')); 
  		( isset($_POST['email']) ) 		? $email 		= $_POST['email'] 		: Api::response(400, array('error'=>'Veuillez rentrer un email')); 
  		( isset($_POST['right']) ) 		? $right 		= $_POST['right'] 		: Api::response(400, array('error'=>'Veuillez rentrer un type de compte')); 
  		( isset($_POST['token']) ) 		? $token 		= $tokenVerif			: Api::response(400, array('error'=>'Probleme de token'));

		Api::response(200, array());
	}


	public function badRequest(){
		Api::response(400, array('error' => 'Bad Request'));
		die();
	}


}

 ?>