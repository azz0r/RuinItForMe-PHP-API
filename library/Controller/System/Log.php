<?php


class Controller_System_Log extends System_Controller {


	public function get () {

		$array = range(0,3);
		$this->_log('I am a logged message from a controller');
		$this->_log($array);
		$this->_log((object) range(0,5));
		return true;
	}
}