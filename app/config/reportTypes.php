<?php
/*
	
	every report type has a string type
	this string type is identified by an array of characteristics

	in this case, the report type of 'Summary' is any report that can match a regex of 'DownloadSummary' or has a typeCode of 1

*/
return array(
	
	'Summary'	=> array('regex' => "/DownloadSummary/", 'typeCode' => 1),
	'Detail'	=> array('regex' => "/Downloads_for_/", 'typeCode' => 2)

);