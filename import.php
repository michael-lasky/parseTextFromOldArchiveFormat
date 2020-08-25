<?php

echo "\r\n \r\n WGN Import \r\n \r\n" ;

//Change memory limit to infinite for this particular script
@ini_get('memory_limit', '-1');

//place this before any script you want to calculate time

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

// Enter absoute path of folder with HTML files in it here (NO trailing slash):
$directory = "D:\\WGNTV-ENPSArchives\\P_WGNTV";

// Enter MySQL database variables here:

$db_hostname = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "inewsarchives";
$db_tablename = "wgntv";

//Connect to the database
@mysql_connect($db_hostname, $db_username, $db_password) or trigger_error("Unable to connect to the database host: " . mysql_error()); 
mysql_select_db($db_name) or trigger_error("Unable to switch to the database: " . mysql_error()); 

//scan the directory
function import_dir($directory, $db_tablename) { 

   $cdir = scandir($directory); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($directory . DIRECTORY_SEPARATOR . $value)) 
         { 
            // Item in this directory is sub-directory...
			import_dir($directory . DIRECTORY_SEPARATOR . $value,$db_tablename); 
         } 
         else 
		    // Item in this directory is a file...
         { 
			$html = file_get_contents($directory . DIRECTORY_SEPARATOR . $value);
			$html0 = str_replace("\000","",$html);
			
			//Get the filename of the file from which it will extract	
			$filename = mysql_real_escape_string($value);
			//$filename = mysql_real_escape_string($value);
			
			//define the path of the files
			$path = mysql_real_escape_string($directory . DIRECTORY_SEPARATOR);
			//$path = mysql_real_escape_string($directory . DIRECTORY_SEPARATOR);
			
			$short_file = substr($value,strrpos($value,'NewStar'));
			echo "Processing $short_file ..." ;
			
			// first check if $html->find exists
			$entries = explode("<zDelimCS>",$html);
			echo "(".sizeof($entries)." found)\n$path\nEntered into Database\n";
			// echo "<pre>";
			// print_r($entries);
			// echo "</pre>";
			foreach($entries as $entry){
				$entry = trim($entry);
			//var_dump($entries);
				//echo ".";
				if(!strpos($entry,"zSequence")){
				
					$title = substr($entry,0,strpos($entry,"\\"));
					$content = substr($entry,strpos($entry,"\\")+2,strpos($entry,"zOwner")-strpos($entry,"\\")-2);
					$backfields = substr($entry,strpos($entry,"zOwner"));

					//make it so illegal characters get recognizede
					$title = mysql_real_escape_string($title);
					$content = mysql_real_escape_string($content);
					$backfields = mysql_real_escape_string($backfields);
					echo "Title: $title\n";
					//echo "Entry: $content\n";
					//echo "\n---------------\n";
					//echo $backfields;
					//echo "\n+++++++++++++++\n";
					//echo ".";
					
					// Insert variables into a row in the MySQL table:
					$sql = "INSERT INTO " . $db_tablename . " (`path`, `filename`, `title`, `content`, `backfields` ) VALUES ('" . $path . "','" . $short_file . "', '" . $title . "', '" . $content . "', '" . $backfields . "');"; 
					$sql_return = mysql_query($sql) or trigger_error("Query Failed: " . mysql_error()); 
				}
			}
			echo "\n";
		

				}
				
         } 
      } 
   } 

import_dir($directory, $db_tablename);

//$fi = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
$fi = new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS);
printf("There were %d Files", iterator_count($fi));

$time_end = microtime_float();
$time = $time_end - $time_start;
$execution_time = ($time_end - $time_start)/60;
$hours = $execution_time/60;

echo " processed in '.$execution_time.' Mins or $time seconds or $hours hours!! \r\nThat was easy!";

?>