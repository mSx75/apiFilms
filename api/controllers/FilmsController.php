<?php

class FilmsController{


	public function __construct(){

	}

	public function listAllFilms(){
		Perm::right(0);

		global $bdd;
		$query = $bdd->query('SELECT * FROM films');

		if($query->execute()){
			$allFilms = $query->fetchAll(PDO::FETCH_ASSOC);
			if(isset($_REQUEST['search'])){
  				$search = $_REQUEST['search'];
  				$like = '\'%' .$search. '%\'';
  				$query = $bdd->prepare('SELECT * FROM films WHERE title LIKE '.$like.' OR description LIKE '.$like);
  				if($query->execute()){
  					$allFilms = $query->fetchAll(PDO::FETCH_ASSOC);
  				}
  			}
			Api::response(200, array($allFilms));
		}else{
			Api::response(400, array('error' => 'Erreur lors du chargement des donnees'));
		}
	}


	public function listFilmsByID(){
		Perm::right(0);
		$film = Action::findById('films', F3::get('PARAMS.id'));

		if(isset($_REQUEST['sort'])){
			$film = Action::sortById('films');
		}

		Api:: response(200, array($film));
	}


	public function createFilm(){
		Perm::right(2);

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
		Perm::right(2);
		$film = Action::findById('films', F3::get('PARAMS.id'));
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
		Perm::right(2);

		global $bdd;
		$query = $bdd->prepare('DELETE FROM films');
		if($query->execute()){
			Api::response(400, array('Tous les films ont ete supprimes'));
		}
	}


	public function deleteFilmsByID(){
		Perm::right(2);
		$film = Action::findById('films', F3::get('PARAMS.id'));

		$filmId = $film['id'];
		global $bdd;
		$query = $bdd->prepare('DELETE FROM films WHERE id=:id');
		$query->bindParam(':id', $filmId, PDO::PARAM_INT);
		if($query->execute()){
			Api::response(400, array('Film ' . $film['title'] . ' Supprime '));
		}	
	}






	public function filmLike(){
		Perm::right(1);
		Action::filmsAction(1, 'Liked');
	}


	public function filmWatched(){
		Perm::right(1);
		Action::filmsAction(2, 'Watched');
	}


	public function filmWantToWatch(){
		Perm::right(1);
		Action::filmsAction(3, 'Wanted Watch');
	}


	public function deleteFilmLike(){
		Perm::right(1);
		Action::deleteFilmsAction(1, 'Liked');
	}


	public function deleteFilmWatched(){
		Perm::right(1);
		Action::deleteFilmsAction(2, 'Watched');
	}


	public function deleteFilmWantToWatch(){
		Perm::right(1);
		Action::deleteFilmsAction(3, 'Wanted Watch');
	}



}