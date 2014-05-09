<?php


class Controller_System_Error extends System_Controller {


	public function get () {

		return $this->setError (404, array ('data', 'array', 'attached'));
	}
}