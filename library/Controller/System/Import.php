<?php


class Controller_System_Import extends System_Controller {


	public function get () {

//		$users = array();
//		/* managers */
//		$users[] = array('f' => 'Aaron', 'l' => 'Lote', 't' => 'Director of Technology', 'r' => 'admin');
//		$users[] = array('f' => 'Adam', 'l' => 'Biddle', 't' => 'Director of Technology');
//		$users[] = array('f' => 'Member', 'l' => 'Member', 't' => 'Director of Content');
//		$users[] = array('f' => 'Katie', 'l' => 'White', 't' => 'Director of Brands');

//
//		$teams = array(
//			'Content Team' => array('editorial calendar', 'phone calls', 'meeting', 'planning'),
//			'Brands' => array('managing', 'management meeting', 'training', 'HR', 'documentation'),
//			'Managers' => array('managing', 'management meeting', 'training', 'HR', 'documentation'),
//			'Creative' => array('design', 'planning', 'creative', 'photoshop')
//		);
//		$clients = array(
//			"Robbie Williams" => "Music",
//			"Girls Aloud" => "Music",
//			"Hugh Jackman" => "Actor",
//			"Dove UK" => "Brand",
//			"Cornetto" => "Food Brand",
//			"Amex" => "Bank Brand"
//		);
//
//		/* remove collections */
//		$collection = $this->db->Client;
//		$collection->drop();
//
//		$collection = $this->db->Team;
//		$collection->drop();
//
//		$collection = $this->db->User;
//		$collection->drop();
//
//        $collection = $this->db->Hive;
//        $collection->drop();
//
//		$x = 1;
//		while ($x < 2) {
//
//            $name =  "Hive Tracking ".$x;
//            $this->db->Hive->insert(array('name' => $name, 'deleted' => false, 'updated' => new MongoDate(), 'created' => new MongoDate()));
//            $organisation = $this->db->Hive->findOne(array('name' => $name));
//            $hiveId = new MongoId($organisation['_id']);
//
//            /* create clients */
//			foreach ($clients as $key => $value) {
//				$this->db->Client->insert(array('name' => $key, 'sector' => $value, 'deleted' => false, 'hive_id' => $hiveId, 'created' => new MongoDate(), 'updated' => new MongoDate()));
//			}
//			/* create teams */
//			foreach ($teams as $key => $value) {
//				$this->db->Team->insert(array('name' => $key, 'tasks' => $value, 'holiday_days_per_year' => 20, 'hours_per_day' => 8, 'deleted' => false, 'hive_id' => $hiveId, 'updated' => new MongoDate(), 'created' => new MongoDate()));
//			}
//			foreach ($users as $user) {
//				$userObject = array(
//					"birth_date"=>rand(1950, 1990).'-0'.rand(1,9).'-'.rand(11,28),
//					"job_probation_ends"=>"2013-11-06",
//					"job_start_date"=>"2013-02-01",
//					"sign_in_count"=>0,
//
//					"email" => strtolower($user['f'].'.'.$user['l']."@hivetracking".($x == 1 ? '' : $x).".com"),
//					"personal_email" => strtolower($user['f'].'.'.$user['l']."@gmail".($x == 1 ? '' : $x).".com"),
//
//					"first_name"=> $user['f'],
//					"last_name"=> $user['l'],
//					"job_title"=> $user['t'],
//
//					'role' => isset($user['r']) ? $user['r'] : 'member',
//
//					"personal_address"=> rand(0, 500)." street",
//					"personal_phone_number"=>rand(500000, 99999999),
//					"work_address"=>"Hive Tracking, Urlwin Walk, Brixton, SW9 6QH",
//					"work_phone_number"=>rand(500000, 99999999),
//
//					"encrypted_password"=>System_Password::encrypt('admin123'),
//
//					'deleted' => false,
//					"hive_id" => $hiveId,
//					'created' => new MongoDate(),
//					'updated' => new MongoDate()
//				);
//				$this->db->User->insert($userObject);
//			}
//
//			//create leader objects for our teams
//
//
//			//content team
//			$leader = $this->db->User->findOne(array('email' => "adam.biddle@hivetracking".($x == 1 ? '' : $x).".com"));
//			$userz = array("lara.dowd", "tom.smyth", "nick.whittingham", "nick.radclyff", "sam.slinger", "kate.willoby");
//			$userz = $this->getUsers($userz, $x);
//			$this->db->Team->update(array('name' => 'Content Team', 'hive_id' => $hiveId), array('$set' => array('leader' => $this->modelUser($leader), 'users' => (array) $userz)));
//
//
//			//brands team
//			$leader = $this->db->User->findOne(array('email' => "katie.white@hivetracking".($x == 1 ? '' : $x).".com"));
//			$userz = array("kate.gunton", "dave.stamp","emma.burns","sam.williams","louise.bury","kate.murphy");
//			$userz = $this->getUsers($userz, $x);
//			$this->db->Team->update(array('name' => 'Brands', 'hive_id' => $hiveId), array('$set' => array('leader' => $this->modelUser($leader), 'users' => (array) $userz)));
//
//
//			//managers
//			$leader = $this->db->User->findOne(array('email' => "aaron.lote@hivetracking".($x == 1 ? '' : $x).".com"));
//			$userz = array("adam.biddle", "john.montoya", "alex.williamson", "katie.white");
//			$userz = $this->getUsers($userz, $x);
//			$this->db->Team->update(array('name' => 'Managers', 'hive_id' => $hiveId), array('$set' => array('leader' => $this->modelUser($leader), 'users' => (array) $userz)));
//
//
//			//creative
//			$leader = $this->db->User->findOne(array('email' => "alex.williamson@hivetracking".($x == 1 ? '' : $x).".com"));
//			$userz = array("ross.dexter", "amber.ford","anthony.pheolong");
//			$userz = $this->getUsers($userz, $x);
//			$this->db->Team->update(array('name' => 'Creative', 'hive_id' => $hiveId), array('$set' => array('leader' => $this->modelUser($leader), 'users' => (array) $userz)));
//
//			$x++;
//		}
		return;
	}


}