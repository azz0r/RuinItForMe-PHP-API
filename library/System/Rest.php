<?php


class System_Rest {


	public static function getStatusCodeMessage ($status) {

		$codes = Array (
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);

		return (isset($codes[$status])) ? $codes[$status] : '';
	}


	public static function getErrors ($errors = array ()) {

		$errors['100'] = array (
			'code'    => 500,
			'message' => 'This method is deprecated'
		);
		$errors['404'] = array (
			'code'    => 404,
			'message' => "The object you requested doesn't exist"
		);
		$errors['501'] = array (
			'code'    => 501,
			'message' => 'General error, see attached message'
		);
		// 200 validation errors
		$errors['205'] = array (
			'code'    => 422,
			'message' => 'Validation errors attached'
		);
		// 210 could not insert error
		$errors['210'] = array (
			'code'    => 501,
			'message' => 'Unknown issue that caused insertion to fail'
		);
		// 220 not an admin permission error
		$errors['220'] = array (
			'code'    => 403,
			'message' => "Permission error, you don't have permission to-do this"
		);
		// 230 general exception
		$errors['230'] = array (
			'code'    => 501,
			'message' => 'Application exception, message attached'
		);
		// 240 mongo cursor
		$errors['240'] = array (
			'code'    => 501,
			'message' => 'Database exception, message attached'
		);
		// 250 mongo cursor
		$errors['250'] = array (
			'code'    => 501,
			'message' => 'id required, not present in request'
		);
		// 400 - sickness
		$errors['400'] = array (
			'code'    => 501,
			'message' => 'you may not log sickness for yourself'
		);
        // 760 - hive
        $errors['760'] = array (
            'code'    => 501,
            'message' => 'A hive exists with that company name OR a user exists with that email address'
        );

		return $errors;
	}


	public static function getMemoryUsage ($size) {

		$unit = array ('b', 'kb', 'mb', 'gb', 'tb', 'pb');

		return @round ($size / pow (1024, ($i = floor (log ($size, 1024)))), 2).' '.$unit[$i];
	}
}