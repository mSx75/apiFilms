<?php

class Api{
	
	public static function response($code, $data = 0, $error = false){

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

			case 500:
				return $status = $code . 'Internal Server Error';
				break;
			
			default:
				return $status = '400 Bad Request';
				break;
		}
	}


	public static function badRequest(){
		self::response(405, array('error' => 'Method Not Allowed'));
	}




}