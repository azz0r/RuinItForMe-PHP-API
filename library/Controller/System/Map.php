<?php


class Controller_System_Map extends System_Controller {


	public function get () {

        $object = array('public_field' => true, 'private_field' => 'true');

        return array(
            'get' => $this->map->get('Test', $object),
            'put' => $this->map->put('Test', $object),
            'post' => $this->map->post('Test', $object)
        );
	}
}