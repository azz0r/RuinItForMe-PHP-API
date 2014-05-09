<?php


class Controller_System_Session extends System_Controller {


	public function get () {

		return session_id ();
	}
}