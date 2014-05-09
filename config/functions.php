<?php


mb_internal_encoding ("UTF-8");
set_time_limit (30);
set_include_path (implode (PATH_SEPARATOR, array (_LIBRARY, get_include_path ())));

//
//if (get_magic_quotes_gpc ()) {
//	$_GET    = array_map ('stripslashes', $_GET);
//	$_POST   = @array_map ('stripslashes', $_POST);
//	$_COOKIE = array_map ('stripslashes', $_COOKIE);
//}


 function is_mongoid($id) {
	return preg_match('/^[0-9a-z]{24}$/', $id) ? true : false;
}

function is_valid_email($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function __autoload ($className) {

	$className = str_replace ('_', '/', $className);

	if (file_exists (_LIBRARY.$className.'.php')) {
		require (_LIBRARY.$className.'.php');
	} else {
		throw new Exception($className.' could not be loaded', 404);
	}
}


/*remove slashes from strings!*/
if (get_magic_quotes_gpc ()) {
	$strip_slashes_deep = function ($value) use (&$strip_slashes_deep) {

		return is_array ($value) ? array_map ($strip_slashes_deep, $value) : stripslashes ($value);
	};
	$_GET               = array_map ($strip_slashes_deep, $_GET);
	$_POST              = array_map ($strip_slashes_deep, $_POST);
	$_COOKIE            = array_map ($strip_slashes_deep, $_COOKIE);
}

