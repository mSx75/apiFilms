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
  			$right = $match['right'];
  			if($right < $right_default){
  				Api::response(400, array('error'=>'Accés Refusé'));
  				die();
  			}
  		}else{
  			Api::response(400, array('error'=>'Token Invalide'));
  			die();
  		}
	}
}