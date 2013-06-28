<?php

class AnalyzeController extends BaseController {

	
	protected $layout = 'layouts.master';

	public function showFileAnalyze($fileID = null, $reportID = null) {

		//TODO: make sure that $fileID and $reportID are **not** null!

		// connect to mongo and get the full namespace and file name out of the database
		$reports = LMongo::collection('reports')->where('_id', new MongoID($reportID))->first();


		// make sure we have a calculation for that ID!
		if($reports == NULL){
			
			die("Whoops!  no calculation(s) can be preformed for that ID!");
			//TODO: logging / much better user error!

		} 


		/*
			assuming that the report is valid and all that stuff, we go off to make a view

			the view will use JS to go get the data from the API and build a table
		*/

		$pageData = array(

			'fileID' 	=> $fileID,
			'reportID' 	=> $reportID

			);
		$this->layout->content = View::make('analyze/tableAjax')->with('pageData', $pageData);

	}

	public function showFileAnalyzeRoot($fileID = null) {

		// default case
		if($fileID === null) {

			//TODO: error catching?
			return Redirect::to('/data')->with('error', 'no file ID. :( something broke');

		} else {

			// attempt to get the file info from the ID
			$fileInfo = LMongo::collection('uploads')->where('_id', new MongoID($fileID))->get();

			// we only expect 1 file
			if($fileInfo->count() !== 1){
				echo("VERY BAD, MORE THAN 1 FILE!");
				die("Oops!");

				//TODO: better errors!
			}

			// OK! we have the file, now we need to pull out the file type to get a list of comptable calcs.
			$fileInfo = $fileInfo->getNext();

			$reports = LMongo::collection('reports')->where('worksOn', $fileInfo['type'])->get();

			// make sure something came back
			// it is probably faster to check hasNext rather than do a count!
			if($reports->getCursor()->hasNext() != TRUE){
				die("DID NOT GET BACK ANY CALCS!");
			}

			//var_dump($reports->toArray());
			$tableData = array();
			$rows = array();

			// go through each calculation builging the table data
			foreach ($reports as $row) {
				$rows[] = array(
					'id'	=> $row['_id'], 
					'name' 	=> $row['name'],
					'desc' 	=> $row['description'],
					'cost'	=> $row['cost']
				);
				
			}


			$tableData = array(
				'caption'	=> 'someComment', 
				'threads'	=> array('Report','Name', 'Description', 'Cost'),
				'rows'		=> $rows
				);

			$pageData = array(
				'tableData' => $tableData,
				'fileID' => $fileID
				);

			$this->layout->content = View::make('analyze/list')->with('pageData', $pageData);

			/*
				so we get the FILE ID.
					what type of file is it?
					what tests are available for that file

					show the user a list of tests and the descriptions and all that jazz

					they can then request a url like

					/analyze/2390dflkj0sdfkjwr3/23048eoiwer092384

					^ run 'report' 23048eoiwer092384 on file 2390dflkj0sdfkjwr3
			*/

		}
		
	}


	public function showAnalyzeRoot() {


		// for now, we just re-direct to /data as there's nothing significant to see here, now
		return Redirect::to('/data');

	}

}
