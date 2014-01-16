<?php 

class Action{

	public static function findById($table, $id){
		global $bdd;
  		$query = $bdd->prepare('SELECT * FROM ' . $table . ' WHERE id=?');
  		$query->execute(array($id));

  		return $query->fetch(PDO::FETCH_ASSOC);
	}



	public static function filmsAction($stat, $type){
		$film = self::findById('films', $param = F3::get('PARAMS.id'));
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
						Api::response(400, array(.$filmTitle. ' deja ' . $type));
					}else{
						$query = $bdd->prepare('INSERT INTO films_users(id_user, id_film, status_type) VALUES(:id_user, :id_film, :status_type)');
						$query->bindParam(':id_user', 		$userId,	PDO::PARAM_INT);
						$query->bindParam(':id_film', 		$filmId,	PDO::PARAM_INT);
						$query->bindParam(':status_type', 	$status,	PDO::PARAM_INT);

						if($query->execute()){
							Api::response(400, array('Vous avez ' .$type. ' ' . $filmTitle));
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

}

 ?>