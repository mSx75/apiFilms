<?php

require 'api/components/Config.php';
$f3=require('framework/base.php');

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

$f3->config('api/configs/config.ini');
$f3->config('api/configs/routes.ini');

$f3->route('GET /',
	function($f3) {
		header('Location:./v1');
	}
);

$f3->route('GET /v1',
	function($f3) {
		require 'home.php';
	}
);

$f3->set('ONERROR',
    function($f3) {
        Api::response($f3->get('ERROR.code'));

    }
);

$f3->run();
