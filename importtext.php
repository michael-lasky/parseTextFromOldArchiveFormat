<?php

$size = "";
//Change "MyFolder" with the path and name of the directory where the .txt files are located.
$dir = "C:\\wamp\\www\\wgn\\search\\files";
$filetype = "*.txt";

//Look in the directory for all the filesd
if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
    if($file == '.' || $file == '..') continue;
		
		//define the files and open
        $myFile = "$dir/$file";
        $fh = fopen($myFile, 'r');

		$q = file_get_contents($myFile);
		
		
		//check the size to make sure there's text in the file and if there is, read it
        $size = filesize($myFile);
        if ($size > 0) {
        $content = fread($fh, $size);

		//Echo the filename on the webpage
        $title = $file; 
        echo $title." has been imported successfully</br><br> and here's the text:<br>";
		echo $q."<br><br><br><br>";
        }
		//connect to the database
		$con=mysqli_connect("localhost","root","","inewsarchives");
        // Check connection
        if (mysqli_connect_errno())
        {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }     
		//insert the title and content into the database
        mysqli_query($con,"INSERT INTO wgn_legacy (Title, Content) VALUES ('$title', '$content')");

		//close the database onnections
        mysqli_close($con);  

		//close the file
        //fclose($fh);
    }
	
	//when done, close the directory
closedir($handle);
}
?>