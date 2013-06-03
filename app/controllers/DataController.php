<?php

class DataController extends BaseController {

	
	protected $layout = 'layouts.master';

	public function showFileUpload() {


		$this->layout->content = View::make('data/upload');

	}

	public function showFileDelete($fileID = null) {

		/*
			$fileID may be set to 'all'.

			the database command is slightly different in this instance
		*/

		$userID = Auth::user()->id;
		if($fileID === "all") {

			$files = LMongo::collection('uploads')->where('user_id', $userID)->get();

			foreach ($files as $file) {

				//TODO: store this somewhere in config?
				$filePath = public_path() . '/uploads/' . $userID . "/" . $file['name'];

				echo("filePath: " . $filePath . " <br>");

				//delete() checks if the path is there, for us!
				File::delete($filePath);

			}

			//Delete the file database record
			LMongo::collection('uploads')->where('user_id', $userID)->remove();
			
			//TODO: error catching?
			return Redirect::to('/data')->with('success', 'Files Deleted Sucessfully!');

		} else {

			echo("fileid: " . $fileID . "<br>");

			$files = LMongo::collection('uploads')->where('_id', new MongoID($fileID))->get();

			foreach ($files as $file) {

				$filePath = public_path() . '/uploads/' . $userID . "/" . $file['name'];
				
				//delete() checks if the path is there, for us!
				File::delete($filePath);

			}

			//Delete the file database record
			LMongo::collection('uploads')->where('_id', new MongoID($fileID))->remove();

			//TODO: error catching?
			return Redirect::to('/data')->with('success', 'File Deleted Sucessfully!');

		}
		
	}

	public function showDataRoot() {

		/*

			we need to connect to the database
				- get all the files for a given user ID
		*/

		$files = LMongo::collection('uploads')->where('user_id', Auth::user()->id)->get();

		// now that we have the files, send it off to the view to make the table!
		$rows = array();
		foreach ( $files as $row ) {

			$rows[] = array(
				'fileID'	=> $row['_id'], 
				'product' 	=> $row['productName'],
				'type' 		=> DataController::determineReportTypeFromCode($row['type']), 
				'range'		=> $row['dateRange']
				);
		}

		$tableData = array(
			'caption'	=> 'someComment', 
			'threads'	=> array('Report', 'Product', 'Report Type', 'Report Range'),
			'rows'		=> $rows
			);

		$this->layout->content = View::make('data/data')->with('tableData', $tableData);

	}

	public function processFileUpload() {


		// the JSON reply that we send will be built from this
		$reply = array();

		// file must exist and must be CSV; the default file name is 'file'
		//TODO: file name lengh must be less than 128 characters
		$rules = array('file' => array('mimes:csv,txt', 'Required'));
		$f = array('file'=>Input::file('file'));

		$validator = Validator::make($f, $rules);

		if ($validator->fails()) {

			// send back server error code
			return Response::json($validator->messages()->first(), 500);

		} 

		// clean up
		unset($f);
		unset($rules);


		// all things OK! time to upload the file and then process it

		$file = Input::file('file');
		$user = Auth::user();

		// remove spaces from file name, replace with '_' character
		// just incase the user has changed the file name
		$fileName = str_replace(' ', '_', $file->getClientOriginalName());

		// every file is stored in each user ID folder
		$destPath = public_path() . '/uploads/' . $user->id;
		
		/*
			TODO: check if the file already exists (is file name enough or do i need a hash check, too?)
			- overwrite or alert user?

			CURRENT BAHAVIOR: overwrite the file if it exists
		*/
		
		$file->move($destPath, $fileName);

		// get NOW
		$now = new DateTime('NOW');


		//TODO: sanity checks! - what do if no match?
		// send alert to user; ask them to re-specify with upload
		// discard file!
		// NOTE: new record is added to database even with overwrite of file. this is bad!
		$fileType 		= DataController::determineReportType($fileName);
		$dateRange 		= DataController::determineReportDateRange($fileName);
		$productName 	= DataController::determineProductName($fileName);


		//TODO: add a record of the file to the database?!
		$now = new DateTime('NOW');

		$fileRecord = array(
			'user_id' 		=> $user->id, 
			'name' 	=> $fileName, 
			'uploaded' => new MongoDate( $now->getTimestamp() ),
			'size' 	=> $file->getClientSize(),
			'type' 	=> $fileType[0],
			'dateRange'=> $dateRange,
			'productName'=> $productName
		);

        $insertID = LMongo::collection('uploads')->insert($fileRecord);



		$filePath = $destPath . '/' . $fileName;

		echo("FILE PATH : " . $filePath . "<br>");

        $csvLoader = new CSVLoader(LMongo);
		echo("CSV Loader: <br>");

		$csvLoader->importCSV($filePath);

		//TODO: check to make sure there was no error with DB!
		return Response::json("File #" . $insertID . " uploaded OK!", 200);

	}

	/**
	*	file name goes in, file type goes out
	*/
	protected function determineReportType($fileName) {
		// get report types
		$reportTypes = Config::get('reportTypes');

		foreach ($reportTypes as $reportType => $chars) {

			$match = preg_match($chars['regex'], $fileName);

			if($match) {
				
				return array($chars['typeCode'], $reportType);

			}
			
		}

	}

	/**
	*	file name goes in, file type goes out
	*/
	protected function determineReportTypeFromCode($reportCode) {
		// get report types
		$reportTypes = Config::get('reportTypes');

		foreach ($reportTypes as $reportType => $chars) {


			if($chars['typeCode'] == $reportCode) {
				
				return $reportType;

			}
			
		}

	}

	/**
	*	file name goes in, product name goes out
	*/
	protected function determineProductName($fileName) {
		if( preg_match('/[a-zA-Z]+(?!Download)/i', $fileName, $productName) ) {

			// cool! we got the string, now we need to make it nice to read before storage!
			return str_replace("_", " ", $productName[0]);

		}

	}

	/**
	*
	*/
	protected function determineReportDateRange($fileName) {
		
		if( preg_match('/\d{2}_\w{3}_\d{4}_to_\d{2}_\w{3}_\d{4}/i', $fileName, $dateString) ) {

			// cool! we got the string, now we need to make it nice to read before storage!
			return str_replace("_", " ", $dateString[0]);

		}
			
	}

}
