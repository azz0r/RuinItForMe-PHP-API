<?php


class Controller_System_Internal extends System_Controller {


	public function get () {

        $this->results['get'] = $this->internalCall(array('module' => 'User', 'action' => 'Index', 'vars' => array('from' => 0, 'to' => 1)));
        $this->results['post'] = $this->internalCall(array('module' => 'User', 'action' => 'Index', 'method' => 'post', 'vars' => array('from' => 0, 'to' => 1)));
	    return $this->results;
    }
}