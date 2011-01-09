<?php

function hex_dump($data, $title)
{
  $ipaddy=$_SERVER['REMOTE_ADDR'];
  $hex = bin2hex(substr($data, 0, 65536));
  $bytes = str_split($hex, 2);
  if(count($bytes) < 1000) {
  echo "<br><br>Potential Error grabbing HexData<br><br>" . $hex . $alldata . "<br><br> report to <a href=http://psx-scene.com/forums/f6/working-way-restore-your-blu-ray-playback-now-easy-use-php-script-73780/index23.html>PSX-Scene Forums.</a> <br>";
  return 0;
  }
  echo "<br>Bytes Analyzed: " . count($bytes) . "<br>";
  $hrlstart = 12;
  $drlstart = 1000;
  $drlend = 1000;
  $drlszst = 1000;
  foreach ($bytes as $i => $out)
  {
    if ( $i == 0 ){
    $drl = "$out";
    $hrl = " ";
    }
    if ( $i == 11 ){
    $mkbvers = hexdec($out);
    }
    if ( $i < $hrlstart && $i > 0 ){
    $drl .= "$out";
    }
    if ( $i == 15 ) {
    $hrlsz = hexdec($out);
    $drlstart = $hrlstart + $hrlsz;
    $drlszst = $drlstart + 3;
    }
    if ( $i == $drlszst ) {
    $drlsz = hexdec($out);
 	if( $drlsz != 52 && $drlsz != 60 ) {
	echo "<br><br>Potential Error finding DRL size, DEBUG: " . $drlsz . " <br> report to <a href=http://psx-scene.com/forums/f6/working-way-restore-your-blu-ray-playback-now-easy-use-php-script-73780/index23.html>PSX-Scene Forums.</a> <br>";
	return 0;	
	}
    $drlend = (( $drlstart + $drlsz ) -1 );
    }
    if ( $i >= $drlstart && $i <= $drlend ) 
    {
    	if($i == $drlstart && $out != 20)
	{
	echo "<br><br>Potential Error grabbing DRL, First byte not 20. DEBUG: " . $out . " <br>report to <a href=http://psx-scene.com/forums/f6/working-way-restore-your-blu-ray-playback-now-easy-use-php-script-73780/index23.html>PSX-Scene Forums.</a> <br>";
	return 0;
    	}
	else 
	{
		$drl .= "$out";
	}
    }
    if ( $i >= $hrlstart && $i < $drlstart )
    	if($i == $hrlstart && $out != 21)
	{
	echo "<br><br>Potential Error grabbing HRL, First byte not 21. Debug: " . $out . " <br>report to <a href=http://psx-scene.com/forums/f6/working-way-restore-your-blu-ray-playback-now-easy-use-php-script-73780/index23.html>PSX-Scene Forums.</a> <br>";
	return 0;
    	}
	else 
	{
		$hrl .= "$out";
	}
  }
    echo "HRL Start: " . $hrlstart . "<br>HRL Size: " . $hrlsz . "<br>DRL Start: " . $drlstart . "<br>DRL Size: " . $drlsz . "<br>DRL End: " . $drlend . "<br>MKB Version: " . $mkbvers . "<br>";
    echo "<br /><b>PS3 DRL1/DRL2 File Content followed by padding 00's to reach 32768\n\n</b><br><br>";
    $patterns = array();
    $replace = array();
    $patterns[0] = "{[ \t]+}";
    $patterns[1] = "{[ \s]+}";
    $replace[0] = '';
    $replace[1] = '';
    $drlstrip = preg_replace($patterns, $replace, $drl);
    $hrlstrip = preg_replace($patterns, $replace, $hrl);
    $drlupper = strtoupper($drlstrip);
    $hrlupper = strtoupper($hrlstrip);
    echo "$drlupper";
    echo "<br><br><b>HRL(For Reference but not yet needed)</b><br><br>";
    echo "$hrlupper";
    $myconn = mysql_connect('localhost','user','password');
    if (!$myconn) {
     die('Could not connect: ' . mysql_error());
    }
    mysql_select_db("ps3mkb", $myconn);
    $data = addslashes($data);
    $checkdup = "SELECT title, drl, hrl from mkbfiles_autoincrement where title = '$title' and drl ='$drlupper'";
    $result = mysql_query($checkdup, $myconn);
    $row = mysql_fetch_row($result);
    $sql = "INSERT INTO mkbfiles_autoincrement (title, mkb, drl, hrl, ipadd, mkbdata ) VALUES ( '$title', '$mkbvers', '$drlupper' , '$hrlupper' , '$ipaddy' , compress('$data') )";
    if( !is_array($row) ) { 
        mysql_query($sql, $myconn);
        echo "<br><br>Unique entry, adding data to the database.<br>";
        }
    else { 
        echo "<br><br>Duplicate Record already Found, not added to database<br>";
        }
    mysql_close($myconn);
    $form = <<<EOT
"<form name="drl_data" method="post" action="drlfetch_gen.php"><input type="hidden" name="drl" value="$drlupper"><script language="JavaScript">document.drl_data.submit();</script></form>
EOT;
    echo "$form";
}

  if (($_FILES["file"]["error"] > 0) or (!$_POST[title]))
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    echo "You Must enter a title. ";
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . " ";
    $tmpName = $_FILES["file"]["tmp_name"];
    $fp      = fopen($tmpName, 'r');
    $mkb = fread($fp, filesize($tmpName));
    fclose($fp);

    $title = $_POST[title];
    hex_dump($mkb, $title);
    }
    echo "<br><br><a href=index.html>Generate MKB/DRL</a> -- <a href=showtitles.php>Show Uploaded Movies</a> -- <a href=http://goo.gl/7EHS0>Blank DRL Templates</a><br>";

?>
