<?php namespace Reporter\Report;

use \LMongo\Facades\LMongo;
use MongoId;


class basicCalcs {

	/**
	 *	Database connection instance
	 */
	protected $mongo;



	/**

	PUBLIC FUNCTIONS

	*/

	public function __construct() {

		
		/*
			we need to make sure that we have a mongo connection

			LMongo does not yet support advanced agregation and the like, so we must use the native PHP driver

			TODO: validation / testing ?
		*/
		$this->mongo = LMongo::connection()->getMongoClient();

	}

	public function register() {

		//TODO: throw this into a mongo instance :)

		echo("i am registering!");

		$info = array(

			"worksOn" => 2,
			"description" => "A summary of device and carrier information",
			"cost" => "0.1",
			"name" => "Basic Summary Information",

			"loader" => array(
				"ns" => "Reporter\\Report\\basicCalcs", 

				"fn" => array(
					"devicesPerCarrier" 
				)
			)
		);

	}

	/**
		calculation functions
	*/

	public function devicesPerCarrier() {

		// collection we need for this calc is records
		$c = $this->mongo->reporter->records;

		// define the aggregate pipeline
		$ops = array(

			// group together the device, carrier pairs.
			// count each pair in a proerty called 'subtotal'
			array(
				'$group' => array(
					"_id" 	=> array("d" => '$device', "c"=>'$carrier'),
					"subtotal" => array('$sum' => 1)
				) 

			),

			// sort by subtotal
			array('$sort' => array (
					'subtotal' => -1
				)
			),

			// group by carrier
			// pushing the device / subtotal pairs onto a 'list' per carrier
			array(
				'$group' => array(
					"_id" 	=> '$_id.c',
					"devices" => array(
						'$push' => array(
							"device" 	=> '$_id.d',
							"subtotal"	=> '$subtotal'
						)
					),

					"total" => array(
						'$sum' => '$subtotal'
						)

				) 

			),

			// sort by carrier
			array('$sort' => array (
					'total' => -1
				)
			)

		);
		
		$results = $c->aggregate($ops);

		//make sure things went OK
		//TODO: much better error handling!
		if ($results['ok'] != 1) {
			print_r($results);
			die("not OK!");
		} else {
			$results = $results['result'];
		}


		// little bit of massaging needed for JSON 
		$output = array(
			'columns' => array('#','carrier', 'total', 'device', 'count'),
			'caption' => "list of carrier and devices per carrier in descending order of popularity"	
		);

		foreach ($results as $doc) {

			$record = array(

				"carrier" 	=> $doc["_id"],
				"total"		=> $doc["total"],
				"devices"	=> $doc["devices"]

			);

			$output['rows'][] = $record;
		}
		
		return $output;
		
	}

	public function countPerDevice() {

		// collection we need for this calc is records
		$c = $this->mongo->reporter->records;

		// define the aggregate pipeline
		$ops = array(

			// group together the device, carrier pairs.
			// count each pair in a proerty called 'subtotal'
			array(
				'$project' => array(
					"device" 	=> 1 
				) 

			),

			array(

				'$group' => array(
					"_id" 		=> '$device',
					"count"	=> array(
							'$sum' => 1
						)
					)
			),

			array(
				'$sort' => array(
					"count" => -1
				)
			)
		);
		
		$results = $c->aggregate($ops);

		//make sure things went OK
		//TODO: much better error handling!
		if ($results['ok'] != 1) {
			print_r($results);
			die("<br><br>not OK!");
		} else {
			$results = $results['result'];
		}

		// little bit of massaging needed for JSON 
		$output = array(
			'columns' => array('#','device', 'count'),
			'caption' => "list of total number of installs per device"	
		);

		foreach ($results as $doc) {

			$record = array(

				"device" 	=> $doc["_id"],
				"count"		=> $doc["count"]
			);

			$output['rows'][] = $record;
		}
		
		return $output;
		
	}




};

?>