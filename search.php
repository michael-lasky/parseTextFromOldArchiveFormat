<html> 
<head> 
<title>WGN Archive Search Results</title> 
<style type="text/css"> 
<!-- 
.style0 { 
color: #666666; 
font-weight: bold; 
font-family: Verdana, Arial, Helvetica, sans-serif; 
font-size: 10px;+++ 
color: #999999; 
} 

.style1 { 
font-size: 10px; 
color: #000000; 
font-family: Verdana, Arial, Helvetica, sans-serif; 
font-weight: normal;
vertical-align: top;
text-align-align: left;
} 

.style2 { 
font-size: 10px; 
color: #000000; 
font-family: Verdana, Arial, Helvetica, sans-serif; 
white-space: pre-wrap;
font-weight: normal;
vertical-align: top;
text-align-align: left;
} 

--> 
</style> 
</head> 
<body> 

<p class="style0"> WGN Search Test </span></a>
<p class="style0"> Search Again: </span></a>
<form action="/wgn/search/search.php" method="post">
  <input type="text" name="q" />
  <input type="submit" value="Search" />
</form>


<p class="style0">
<FORM method=post action=/wgn/search/search.php><P><span class="style0">Or </span>
  <INPUT value=Show type=submit name=submit>
  <span class="style0">all entries: </span></P>
</FORM>
<p class="style0">
</body></html>
<?php 
@mysql_connect ("localhost", "root", "") or die (mysql_error()) ; 
mysql_select_db ("inewsarchives") ; 
// escape the keywords properly
function escape($s) {
    if (get_magic_quotes_gpc()) {
        $s=stripslashes($s);
    }
    if (!is_numeric($s)) {
        $s=mysql_real_escape_string($s);
    }
    return $s;
}
// highlight the searched keywords in our results
function highlight($keyword, $text) {
    $text=preg_replace("|($keyword)|Ui", "<span style=\"background:#FFFF00;font-weight:bold;\">$1</span>", $text );
    return $text;
    
}

if (isset($_POST['q'])) {
    if (strlen($_POST['q'])>=2) {
        // where to search?
        $searchin=array('id', 'title', 'content', 'backfields');
        
        // prepare the keyword array
        $q=trim($_POST['q']);
        $q=escape($q);
        $q=explode(' ',$q);
        
        // remove equivalent keywords
        $q=array_unique($q);
        
        // let's create the search query
        $query = "select * from wgn where " ;
		
        foreach ($searchin as $column) {
            foreach ($q as $keyword) {
                // make sure keyword contains at least 2 letters
                if (strlen($keyword)>=2) {
                    $query .= "`".$column."` LIKE '%".$keyword."%' OR ";
                }
            }
        } 

        // remove the last occurance of 'OR'
        $query=rtrim($query,' OR ');

		
?>

<table border="2" align="left" cellpadding="3" cellspacing="5"> 
 

<td ><span class="style0">TITLE</span></td> 
<td ><span class="style0">CONTENT</span></td> 
<td ><span class="style0">BACKFIELDS</span></td> 
 
</tr> 

<?php
        // Print results (if any) and highlight the searched keywords
        $result=mysql_query($query) or die("Error: ". mysql_error(). " with query ". $query);

        echo '<p class="style1">Search results for: ';
        foreach ($q as $keyword) {
            echo '<strong>'.$keyword.'</strong> </p>';
        }
        if (mysql_num_rows($result)) {
            while ($object=mysql_fetch_object($result)) {
                $id=$object->id;
				$title=$object->title;
                $content=$object->content;
				$backfields=$object->backfields;
                foreach ($q as $keyword) {
                    if (strlen($keyword)>=3) {
                        // highlight the keywords
						$id=highlight($keyword,$id);
						$title=highlight($keyword,$title);
						$content=highlight($keyword,$content);
						$backfields=highlight($keyword,$backfields);
						
                    }
                }
                // Print results
?> 

<tr align="left" valign="top"> 

<td ><span class="style1"> 
><?php echo $title; ?></a>
</span></td>       
<td ><span class="style2"> 
<?php echo $content; ?> 
</span></td> 
<td ><span class="style1"> 
<?php echo $backfields; ?> 
</span></td> 

</tr> 

<span class="style1"> 
<?php
	}
            }

        } else {
            echo '<p>Your search did not return any results.</p>';
        }
    } else {
        echo '<p>Please enter a search term, longer than 3 symbols.</p>';
    }

?>
</span>
</body></html></table> 