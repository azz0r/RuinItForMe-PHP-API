<?php


error_log('POST: '.print_r($_POST,true), 3, "/var/tmp/my-errors.log");
error_log('GET: '.print_r($_GET,true), 3, "/var/tmp/my-errors.log");


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


define('_ROOT', realpath ('../'));
define('DS',    DIRECTORY_SEPARATOR);


define('_LIBRARY',  _ROOT.DS.'library/');
define('_CONFIG',   _ROOT.DS.'config/');
define('_EMAIL',    _ROOT.DS.'config/emails/');
define('_PUBLIC',   _ROOT.DS.'public/');
define('_PRIVATE',  _ROOT.DS.'private/');
define('_LOG',      _PRIVATE.'logs/');


defined ('_ENV') || define('_ENV', (getenv ('_ENV') ? getenv ('_ENV') : 'dev'));


try {
	require_once (_CONFIG.'functions.php');

	$module = isset($_GET['__module']) ? $_GET['__module'] : 'index';
	$action = isset($_GET['__action']) ? $_GET['__action'] : 'index';

	$system = new System_Controller();
	$system
		->_env ();
	$system
		->_config ();
	$system
		->_errors ();
	$system
		->_db ();
	$system
		->_vars ();
    $system
        ->_map ();
	$system
		->_method ();
	$system
		->_path ($module, $action);

	$system
		->_log (
            array(
                'ip' => $_SERVER["REMOTE_ADDR"],
                'env' => $system->env,
                'controller' => $system->_getControllerFile(),
                'queryParams' => http_build_query($system->vars),
                'post' => $_POST,
                'get' => $_GET
            )
        );

	$system
		->run (true);

} catch (Exception $e) {
	$system->setError (($e->getCode () > 0 ? $e->getCode () : 501), $e->getMessage ());
	return $system->run ();
} catch (MongoException $e) {
    $system->setError (($e->getCode () > 0 ? $e->getCode () : 501), $e->getMessage ());
    return $system->run ();
}