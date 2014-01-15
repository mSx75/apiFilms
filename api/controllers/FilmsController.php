<?php

class FilmsController{


	public function __construct(){
	}

	public function listAllFilms(){
		Api::right(0);

		global $bdd;
		$query = $bdd->query('SELECT * FROM films');

		if($query->execute()){
			$allFilms = $query->fetchAll(PDO::FETCH_ASSOC);
			Api::response(400, array($allFilms));
		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
		}
	}


	public function listFilmsByID(){
		Api::right(0);
		$film = Api::findById('films', $param = F3::get('PARAMS.id'));
		Api:: response(400, array($film));
	}


	public function createFilm(){
		Api::right(2);

		( isset($_POST['title']) ) 			?	$title 		 = $_POST['title'] 			:	Api::response(400, array('error' => 'Veuillez rentrer un titre')) ;
		( isset($_POST['description']) ) 	?	$description = $_POST['description'] 	:	Api::response(400, array('error' => 'Veuillez rentrer une description')) ;

		global $bdd;
		$query = $bdd->prepare('SELECT * FROM films WHERE title=?');
		if($query->execute(array($title))){
			if($query->fetch(PDO::FETCH_ASSOC)){
				Api::response(400, array('error' => 'Film deja ajoute'));
			}else{
				$query = $bdd->prepare('INSERT INTO films(title, description) VALUES(:title, :description)');
				$query->bindParam(':title', 		$title, 		PDO::PARAM_STR);
				$query->bindParam(':description', 	$description, 	PDO::PARAM_STR);
				if($query->execute()){
					Api::response(200, array('Film ' . $title . ' ajoute'));
				}
			}
		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
		}
	}


	public function updateFilm(){
		Api::right(2);
		$film = Api::findById('films', $param = F3::get('PARAMS.id'));
		$data = Put::get();

		$title 			=  (isset($data['title'])) 			? $data['title'] 		: $film['title'];
		$description 	=  (isset($data['description'])) 	? $data['description'] 	: $film['description'];

		$filmId = $film['id'];
		global $bdd;
		$query = $bdd->prepare('SELECT * FROM films WHERE title=?');
		if($query->execute(array($title))){
			if($query->fetch(PDO::FETCH_ASSOC)){
				Api::response(400, array('error' => 'Film deja ajoute'));
			}else{
				$query = $bdd->prepare('UPDATE films SET title=:title, description=:description WHERE id=:id');
				$query->bindParam(':title', 		$title, 		PDO::PARAM_STR);
				$query->bindParam(':description', 	$description,	PDO::PARAM_STR);
				$query->bindParam(':id', 			$filmId, 		PDO::PARAM_INT);
				if($query->execute()){
					Api::response(400, array('Film ' . $title . ' mis a jour '));
				}
			}
		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
		}

	}


	public function deleteAllFilms(){
		Api::right(2);

		global $bdd;
		$query = $bdd->prepare('DELETE FROM films');
		if($query->execute()){
			Api::response(400, array('Tous les films ont ete supprimes'));
		}
	}


	public function deleteFilmsByID(){
		Api::right(2);
		$film = Api::findById('films', $param = F3::get('PARAMS.id'));

		$filmId = $film['id'];
		global $bdd;
		$query = $bdd->prepare('DELETE FROM films WHERE id=:id');
		$query->bindParam(':id', $filmId, PDO::PARAM_INT);
		if($query->execute()){
			Api::response(400, array('Film ' . $film['title'] . ' Supprime '));
		}	
	}

}