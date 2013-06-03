<?php namespace Reporter\Util\CSV;

use \LMongo\Facades\LMongo;
use MongoId;
use MongoDate;
use Auth;

class CsvLoader {

	// each row lives here
	private $csvData = array();

	// the column headers live here
	private $csvHeaders = array();


	public function importCSV($filePath){

	
		// try to get a a stream from the file
		$fileHandel = CsvLoader::openCSV($filePath);

		if($fileHandel == false){
			//TODO: WHAT DO?
			die("something bad happened!");
		}

		// all things good!

		// we cool?
		if( !CsvLoader::parseCSV($fileHandel) ) {

			//TODO: log! shits fucked up, man!
			die("oh lawdy.  you ain't got time for this! ");

		} 

		//TODO: verify the column headings to make sure it's a legit file
		// for now, we just assume it is!


		//we need to look up the product ID; this is taken from the first cell in the CSV
		//this means users can not upload files with more than one product per csv.
		$productID = CsvLoader::getProductIDfromName($this->csvData[0][0]);

		if($productID == false){
			//TODO: log error
			die("death. horrible death!");
		}

		//TODO: verify this went ok?
		CsvLoader::storeCSV($productID);

	}

	/**
	*	feed me a string productName and i'll create it if it does not exist
	*	if it exists, i'll give you the ID
	*/
	protected function getProductIDfromName($productName){

		/*

			if that comes back with nothing, we need to make one and return that ID
		*/

		$productID = LMongo::collection('products')->where('name', $productName)->pluck('_id');

		// cool!, and ID came back!
		if(count($productID) == 1){
			
			return $productID->{'$id'};

		} else {

			// we need to insert a record!
			$pid = LMongo::collection('products')->insert(
						array(

							'name' 	=> $productName,
							'owner'	=> new MongoId(Auth::user()->id)

							)
					);

			return $pid->{'$id'};
		}




	}


	/**
	*	throws the CSV data into the database
	*/
	protected function storeCSV($productID) {

		// now we build up the array we want to batch insert
		$insert = array();

		foreach($this->csvData as $row) {

			$doc = array('product' => $productID,
					'bundle' => $row[1],
					'appVer' => $row[2],
					'when' => new MongoDate(strtotime($row[3])),
					'device' => $row[4],
					'osVer' => $row[5],
					'carrier' => $row[6],
					'locale' => $row[7],
					'country' => $row[8]
				);

			$insert[]=$doc;
		}

		LMongo::collection('records')->batchInsert($insert);
	}


	protected function parseCSV($filePointer) {

		// iterator for the row
		$i = 0;


		//TODO: there's got to be a better way other than making repeated calls to empty()!  (is there a skip X lines argument??)


		// we can use the defaults of ',' and '\n'
		while (($row = fgetcsv($filePointer, 4096)) !== false) {

			// get the columns
			if (empty($this->csvHeaders)) {
				$this->csvHeaders = $row;
				continue;
			}

			// build the rows
			foreach ($row as $key=>$value) {
				$this->csvData[$i][$key] = $value;
			}

			$i++;

		}


		// if we get to the end of the CSV and it's not EOF, then there's something wrong
		if (!feof($filePointer)) {
			//TODO: log error?
			//echo "Error: unexpected fgets() fail\n";

			// close the file 
			fclose($filePointer);

			return false;
		}

		fclose($filePointer);
		return true;
	}

	/**
	* takes a file path, fills in the headers and the data
	*/
	protected function openCSV($filePath) {

		// open the file path for reading only!
		$handle = @fopen($filePath, "r");

		if (!$handle) {
			// TODO: send an error to logger or something?
			return false;
		} else {
			return $handle;
		}

		
	}

}

?>