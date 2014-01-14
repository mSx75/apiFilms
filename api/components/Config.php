<?php 

/*--------------	CONNECTION BDD	--------------*/
	try{
		$host 		= 'localhost';
		$dbname 	= 'fatfreemobile';
		$login	 	= 'root';
		$pass	 	= '';

		$bdd = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $login, $pass);

	}catch(Exception $e){

		die('Erreur :' .$e->getMessage());

	}

	/*------------------------------------------------*/
 ?>