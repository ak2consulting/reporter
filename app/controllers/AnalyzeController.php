<?php

class AnalyzeController extends BaseController {

	
	protected $layout = 'layouts.master';

	public function showFileAnalyze($fileID = null) {

		// 
		if($fileID === null) {

			
			//TODO: error catching?
			return Redirect::to('/data')->with('error', 'something broke');

		} else {

			die("ANALYZING FILE: " . $fileID);		
			// $files = DB::table('files')->where('file_ID', '=', $fileID)->where('user_ID', '=', $userID)->get();

			// foreach ($files as $file) {

			// 	$filePath = public_path() . '/uploads/' . $userID . "/" . $file->file_name;

			// 	//delete() checks if the path is there, for us!
			// 	File::delete($filePath);

			// }

			// //Delete the file database record
			// DB::table('files')->where('user_ID', '=', $userID)->delete();

			// //TODO: error catching?
			// return Redirect::to('/data')->with('success', 'File Deleted Sucessfully!');

		}
		
	}


	public function showAnalyzeRoot() {


		// for now, we just re-direct to /data as there's nothing significant to see here, now
		return Redirect::to('/data');


	}

}
