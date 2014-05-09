<?php


class System_Controller {


    # private variables
    public $config;
    private $controller;
    private $debug = array ();

    # public variables
    public $db;
    public $logging;
    public $env;
    public $roles;
    public $vars;
    public $outputJSON = true;

    # default rest variables
    public $statusCode = 200;
    public $method = 'get';

    # error data
    public $error = false;
    public $errorData = array ();
    public $errorsList = array ();


    # pass an instance of itself and the new instance has access to the public vars
    public function __construct ($options = array ()) {
        $this->privateLog(__FUNCTION__);

        $this->results = array();

        if (isset($options) && !empty($options)) {
            foreach ($options as $key => $value) {
                $this->$key = $value;
            }
        }
    }


    # sets this->config
    # parse the ini file and set the variables for the config used by controllers
    public function _config () {
        $this->privateLog(__FUNCTION__);

        $iniData = parse_ini_file (_CONFIG.'application.ini');

        if ($iniData === false) {
            return false;
        } else {
            foreach ($iniData as $key => $value) {
                $explodeKey = explode('.', $key);
                if (count($explodeKey) > 1) {
                    if (!isset($iniData[$explodeKey[0]])) {
                        $iniData[$explodeKey[0]] = array();
                    }
                    $iniData[$explodeKey[0]][$explodeKey[1]] = $value;
                    unset($iniData[$key]);
                }
            }
        }
        $this->config = (array) $iniData;
    }

    # sets this->db

    public function _db () {
        $this->privateLog(__FUNCTION__);

        $dbParams = array (
            "host"     => $this->config['db']['host'],
            "username" => $this->config['db']['user'],
            "password" => $this->config['db']['pass'],
            "database"   => $this->config['db']['database']
        );
        $mongoDB  = new Mongo("mongodb://{$dbParams['host']}");
        $this->db = $mongoDB->{$dbParams['database']};
    }



    # sets this->map for mapping objects
    public function _map () {
        $this->privateLog(__FUNCTION__);

        $this->map = new System_Map();
    }


    # sets this->logging object for logging to a text file for tailing purposes
    public function _log ($message, $filename=null) {
        $this->privateLog(__FUNCTION__);

        # if logging is enabled, then proceed
        if ($this->config['log']['write']) {
            # if we log to the database, then write
            if ($this->config['log']['database']) {
                if (is_string($message)) {
                    $message = array('message' => (array) $message);
                }
                $this->db->Log_Request->insert($message);
                # we want to log to a file
            } else {
                # if filename is null, use the config version
                if (is_null($filename)) {
                    $filename = $this->config['log']['defaultFile'];
                }
                # open file
                $fd = fopen (_LOG.$filename.'.log', "a");

                # if its an object or array, print_r it
                if (is_object($message) || is_array(($message))) {
                    $message = print_r($message, true);
                }

                // append date/time to message
                $str = date ("r").' - '.$message;

                // write string
                fwrite ($fd, $str."\n");

                // close file
                fclose ($fd);
            }
        }
    }


    # get the errors available for the api
    public function _errors () {
        $this->privateLog(__FUNCTION__);

        $this->errorsList = System_Rest::getErrors ();
    }


    # set the this->env
    # if we are dev then force errors on

    public function _env () {
        $this->privateLog(__FUNCTION__);

        $this->env = _ENV;

        if ($this->env == 'dev') {
            error_reporting (E_ALL);
            ini_set ('display_errors', '1');
        }
    }


    # figure out which controller to load
    # sets this->controller with access to the instance of the class and the path values
    public function _path ($module, $action) {
        $this->privateLog(__FUNCTION__);

        $name     = 'Controller_'.ucfirst ($module).'_'.ucfirst ($action);
        $file     = str_replace ('_', '/', $name).'.php';
        $filePath = _LIBRARY.$file;

        if (!file_exists ($filePath)) {
            throw new Exception('Route is missing', 404);
        } else {
            $this->controller = (object) array (
                'name'     => $name,
                'file'     => $file,
                'filePath' => $filePath,
                'instance' => new $name($this)
            );
        }
    }


    # convenience method to log what the user was trying to access
    public function _getControllerFile() {
        $this->privateLog(__FUNCTION__);

        return $this->controller->file;
    }


    # get the method the user wants to use
    # note that we allow _method on a post request to overview the method
    # most mobile browsers don't support real delete and put requests (to my knowledge)
    public function _method () {
        $this->privateLog(__FUNCTION__);

        # is this a post request
        $post = isset($_POST['__data']) ? (array) json_decode ($_POST['__data']) : $_POST;

        if (isset($post['_method']) && !empty($post['_method'])) {
            switch (strtolower($post['_method'])) {
                case 'put':
                    $this->method = 'put';
                    break;
                case 'delete':
                    $this->method = 'delete';
                    break;
                default:
                    $this->method = 'post';
                    break;
            }
        } else if (!empty($_POST)) {
            $this->method = 'post';
        }
    }


    # sets this->vars
    # based on a post or get request
    public function _vars () {
        $this->privateLog(__FUNCTION__);

        # merge post and get data into a simple output
        $this->vars = array_merge ($_GET, $_POST);

        # remove anything protected, aka anything that begins with __
        unset($this->vars['__module'], $this->vars['__action'], $this->vars['session_id'],  $this->vars['_method']);
    }


    public function privateLog($message) {
        error_log($message."\n", 3, "/var/tmp/my-errors.log");
    }


    public function setError ($code, $data = array ()) {
        $this->privateLog(__FUNCTION__);

        $this->error      = true;
        $this->statusCode = isset($this->errorsList[$code]['code']) ? $this->errorsList[$code]['code'] : 501;

        $this->errorData  = (object) array (
            'code'    => $code,
            'data'    => $data,
            'message' => isset($this->errorsList[$code]['message']) ? $this->errorsList[$code]['message'] : 500
        );
        return $this;
    }


    # used to figure out the id, either by checking the data sent (post/put/delete/get request) or by checking the URL
    public function _getId() {
        $this->privateLog(__FUNCTION__);

        if (isset($this->vars['id'])) {
            return $this->vars['id'];
        } else if (isset($this->vars['_id'])) {
            return $this->vars['_id'];
        } else {
            return false;
        }
    }


    # get a parameter quickly and use a fallback if its not set
    public function _getParam($param, $fallback = null) {
        $this->privateLog(__FUNCTION__);

        return isset($this->vars[$param]) ? $this->vars[$param] : $fallback;
    }


    # get the default filters we need for paging, and sorting
    public function _getQueryFilters() {
        $this->privateLog(__FUNCTION__);

        # set the filters for the controller
        $this->filters = new stdClass();

        # make default filters just in case
        $defaultFilters = array(
            'from'          => 0,
            'to'            => 100,
            'order_by'      => 'id',
            'order_direction' => 'DESC'
        );

        # to cut down code we have keys => value
        $keys = array('from' => 'from', 'to' => 'to', 'order_direction' => 'orderDirection', 'order_by' => 'orderBy');

        # loop through the keys and values and set the filters
        foreach ($keys as $key => $value) {
            if (isset($this->vars[$key])) {
                $this->filters->$value = $this->vars[$key];
            } else {
                $this->filters->$value = $defaultFilters[$key];
            }
        }
    }


    # check we're requesting a valid user id filter
    public function isValidUserIdFilter($requested) {
        $this->privateLog(__FUNCTION__);

        $validUserIdFilters = array('allUserIds', 'userIdsILead', 'userIdsILeadExcludingMe');
        return in_array($requested, $validUserIdFilters) ? true : false;
    }


    # outputs the return
    # $output optionally returns the output for internal calls
    public function run () {
        $this->privateLog(__FUNCTION__);

        # run the method and set $return
        if (isset($this->controller->instance)) {

            if (method_exists($this->controller->instance, 'init')) {
                $this->controller->instance->init();
                $output = $this->controller->instance->{$this->method} ();
            } else {
                $output = $this->controller->instance->{$this->method} ();
            }

            # we ran the method required and error is now true
            if ($this->controller->instance->error) {
                $output             = $this->controller->instance->errorData;
                $this->statusCode   = $this->controller->instance->statusCode;
            }
            # probably an exception outside the controller, so use the pure System_Controller
        } else {
            $output = $this->errorData;
        }

        if ($this->outputJSON===true) {
            # set vars for header output
            $lastModified = filemtime (dirname (__FILE__));
            $maxAge       = 17280000; //200 days

            # set standard headers
            header ('Last-Modified: '.date ('r', $lastModified));
            header ('Expires: '.date ('r', $lastModified + $maxAge));
            header ('ETag: '.dechex ($lastModified));
            header ("Cache-Control: must-revalidate, proxy-revalidate, max-age=$maxAge, s-maxage=$maxAge");


            # set the header for errors after running the return
            header ('HTTP/1.1 '.$this->statusCode.' '.System_Rest::getStatusCodeMessage ($this->statusCode));
            header ('Content-type: text/html; charset=utf-8');

            # set some debug data
            $this->debug['memory_usage'] = System_Rest::getMemoryUsage (memory_get_usage ());

            //output
            echo json_encode (array ('data' => $output, 'debug' => $this->debug));
            exit;
        } else {
            return $output;
        }
    }


    public function internalCall($options = array()) {

        // clone the instance of the controller manager
        $instance               = clone $this;

        // wipe the results array
        $instance->results      = array();

        // check a method was set, if it wasnt, default to get
        $instance->method       = isset($options['method']) ? $options['method'] : 'get';

        // check vars were sent, if not, default to an array
        $instance->vars         = isset($options['vars']) ? $options['vars'] : array();

        // load the path we request
        $instance->_path($options['module'], $options['action']);

        // set the output to be raw, not json
        $instance->outputJSON = false;

        // run the action we requested
        return $instance->run();
    }


}