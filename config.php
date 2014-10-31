<?php
    date_default_timezone_set('America/New_York'); 
	$currentCookieParams = session_get_cookie_params(); 
	$rootDomain = '.lurnn.com'; 
    error_reporting(E_ALL-E_WARNING-E_NOTICE);
	session_set_cookie_params( 
	    $currentCookieParams["lifetime"], 
	    $currentCookieParams["path"], 
	    $rootDomain, 
	    $currentCookieParams["secure"], 
	    $currentCookieParams["httponly"] 
	); 

	session_name('lurnn');
	@session_start();
	$config['db_host'] = "68.178.139.103";
	$config['db_user'] = "lurnn";
	$config['db_pass'] = "Samcro15!";
	$config['db_name'] = "lurnn";
	
	$config['db_host'] = "68.178.143.42";
	$config['db_user'] = "lurnnbeta";
	$config['db_pass'] = "Samcro15!";
	$config['db_name'] = "lurnnbeta";

	mysql_connect($config['db_host'], $config['db_user'], $config['db_pass'])or die("cannot connect for insert"); 
    mysql_select_db($config['db_name'])or die("cannot select DB");
    include_once("functions.php");
