<?php 

class Action{

	public static function findById($table, $id){
		global $bdd;
  		$query = $bdd->prepare('SELECT * FROM ' . $table . ' WHERE id=?');
  		if($query->execute(array($id))){
  			return $query->fetch(PDO::FETCH_ASSOC);
  		}
	}



	public static function sortById($table){
		$sort = $_REQUEST['sort'];
		$itemId = F3::get('PARAMS.id');
		global $bdd;
		$query = $bdd->prepare('SELECT ' .$sort. ' FROM ' .$table. ' WHERE id=?');
		if($query->execute(array($itemId))){
			return $query->fetch(PDO::FETCH_ASSOC);
		}
	}



	public static function filmsAction($stat, $type){
		$film = self::findById('films', F3::get('PARAMS.id'));
		$status = $stat;
		$tokenUser = $_REQUEST['token_access'];
		$filmId = $film['id'];
		$filmTitle = $film['title'];

		global $bdd;
		$query = $bdd->prepare('SELECT * FROM users WHERE token=?');
		if($query->execute(array($tokenUser))){
			if($match = $query->fetch(PDO::FETCH_ASSOC)){
				$userId = $match['id'];
				$query = $bdd->prepare('SELECT * FROM films_users WHERE id_user=? && id_film=? && status_type=' .$stat. ' ');
				if($query->execute(array($userId, $filmId))){
					if($match = $query->fetch(PDO::FETCH_ASSOC)){
						Api::response(400, array($filmTitle . ' deja ' . $type));
					}else{
						$query = $bdd->prepare('INSERT INTO films_users(id_user, id_film, status_type) VALUES(:id_user, :id_film, :status_type)');
						$query->bindParam(':id_user', 		$userId,	PDO::PARAM_INT);
						$query->bindParam(':id_film', 		$filmId,	PDO::PARAM_INT);
						$query->bindParam(':status_type', 	$status,	PDO::PARAM_INT);

						if($query->execute()){
							Api::response(200, array('Vous avez ' .$type. ' ' . $filmTitle));
						}
					}
				}else{
					Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
				}
			}
		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
		}
	}



	public static function deleteFilmsAction($stat, $type){
		$film = self::findById('films', F3::get('PARAMS.id'));
		$status = $stat;
		$tokenUser = $_REQUEST['token_access'];
		$filmId = $film['id'];
		$filmTitle = $film['title'];

		global $bdd;
		$query = $bdd->prepare('SELECT * FROM users WHERE token=?');
		if($query->execute(array($tokenUser))){
			if($match = $query->fetch(PDO::FETCH_ASSOC)){
				$userId = $match['id'];
				$query = $bdd->prepare('SELECT * FROM films_users WHERE id_user=? && id_film=? && status_type=' .$stat. ' ');
				if($query->execute(array($userId, $filmId))){
					if($query->fetch(PDO::FETCH_ASSOC)){
						$query = $bdd->prepare('DELETE FROM films_users WHERE id_user=? && id_film=? && status_type=' .$stat. ' ');
						if($query->execute(array($userId, $filmId))){
							Api::response(200, array($filmTitle . ' a ete dis' . $type));
						}
					}else{
						Api::response(400, array('Vous n avez pas ' .$type. ' ' .$filmTitle));
					}
				}	
			}	
		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
		}
	}



	public static function listFilmsByUser($stat){
		Perm::rightSwitch($user = self::findById('users', F3::get('PARAMS.id')));
		$status = $stat;
		$userId = $user['id'];

		global $bdd;
		$query = $bdd->prepare('SELECT films_users.id, title, description FROM films_users LEFT JOIN films ON id_film=films.id WHERE id_user=? && status_type=' . $stat . ' ');
		if($query->execute(array($userId))){
			$allLike = $query->fetchAll(PDO::FETCH_ASSOC);
			Api::response(200, array($allLike));
		}
	}



}

 ?>