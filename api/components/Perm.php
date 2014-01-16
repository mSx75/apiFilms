<?php 

	class Perm{

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
  					Api::response(400, array('error'=>'Accés Refusé, niveau de droit insuffisant'));
  				}
  			}else{
  				if($right_default != 0){
  					Api::response(400, array('error'=>'Token Invalide'));
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

	}

 ?>