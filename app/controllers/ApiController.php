<?php


class ApiController extends BaseController {

	
	public function FileAnalyze($apiKey = null, $fileID = null, $reportID = null) {

		//die("api: " . $apiKey . " fileID: " . $fileID . " report: " . $reportID);
		//TODO: make sure that $apiKey, $fileID and $reportID are **not** null!

		// connect to mongo and get the full namespace and file name out of the database
		$reports = LMongo::collection('reports')->where('_id', new MongoID($reportID))->first();


		// make sure we have a calculation for that ID!
		if($reports == NULL){
			
			die("Whoops!  no calculation(s) can be preformed for that ID!");
			//TODO: logging / much better user error!

		} 

		// get the information needed to load up class and function(s)
		$loader = $reports['loader'];


		// build up a class name from the database info
		$className = $loader['ns'];

		/*
			the end goal is to have a set of functions that can be called for every name space.

			for now, though, only calling 1 will be sufficient

		*/

		// spin up an instance
		$instance = new $className;

		// get space for output.
		$out = array();
		$data = array();

		foreach ($loader['fn'] as $functionName) {

			//call the function
			$result = $instance->$functionName();

			//store the data in a section of the data array with a key of the function name
			$data[$functionName] = $result;

		}

		//TODO: as long as nothing broke, we're OK!

		//send OK mesaage
		$out['status'] = array("code" => 0, "msg" => "ok");
		$out['data'] = $data;
		
		return Response::json($out);

	}

}