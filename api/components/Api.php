<?php

class Api{
	
	public static function response($code, $data, $error = false){

		header('Content-type: application/json; charset=utf-8');
		header('HTTP/1.1 '. self::status($code));
	
		$response = array('meta'=>array('code'=>$code));

		if($error){
			$response['meta']['error'] = $error;
		}
		else{
			if($data){
				$response['data'] = $data;
			}
		}

		print json_encode($response);
		die();
	}

	private static function status($code){
		switch ($code) {
			case 200:
				return $status = $code . 'OK';
				break;

			case 400:
				return $status = $code . 'Bad Request';
				break;

			case 404:
				return $status = $code . 'Not Found';
				break;

			case 405:
				return $status = $code . 'Method Not Allowed';
				break;
			
			default:
				return $status = '400 Bad Request';
				break;
		}
	}

	public static function right($right_default = 0){
		/*$httpHOST 	= $_SERVER['HTTP_HOST'];
		$httpURL 	= $_SERVER['REQUEST_URI'];
		$baseUrl 	= $httpHOST.$httpURL;*/
		$tokenAccess = $_REQUEST['token_access'];

		global $bdd;
  		$query = $bdd->prepare('SELECT * FROM users WHERE token=?');
  		$query->execute(array($tokenAccess));
  		if($match = $query->fetch(PDO::FETCH_ASSOC)){
  			$usergroup = $match['usergroup'];
  			if($usergroup < $right_default){
  				self::response(400, array('error'=>'Accés Refusé, niveau de droit insuffisant'));
  			}
  		}else{
  			if($right_default != 0){
  				self::response(400, array('error'=>'Token Invalide'));
  			}
  		}
	}


	public static function tokenGenerator(){
		$myToken = md5(uniqid());
		global $bdd;
		$query = $bdd->prepare('SELECT * FROM users WHERE token=?');
		$query->execute(array($myToken));
		
		if($query->fetch()){
			$myToken = self::tokenGenerator();
		}

		return $myToken;
	}


	public static function findById($table, $id){
		global $bdd;
  		$query = $bdd->prepare('SELECT * FROM ' . $table . ' WHERE id=?');
  		$query->execute(array($id));

  		return $query->fetch(PDO::FETCH_ASSOC);
	}


	public static function badRequest(){
		self::response(405, array('error' => 'Method Not Allowed'));
	}


}