<?php
    $titleid = $_GET['titleid'];
    $myconn = mysql_connect('localhost','user','password');
    if (!$myconn) {
     die('Could not connect: ' . mysql_error());
    }
    mysql_select_db("ps3mkb", $myconn);
    $sql = "select id, title, uncompress(mkbdata) as mkbdata from mkbfiles where id = '$titleid'";
    $result = mysql_query($sql, $myconn);
    mysql_close($myconn);
while($row = mysql_fetch_array($result))
    {
    $mkb = $row['mkbdata'];
    $title = $row['title'];
    }
$zip = new ZipArchive();
$zipfilename = tempnam("tmp", "zip");
$zip->open($zipfilename, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
$zip->addFromString("MKB_RO.inf", $mkb);
$zip->close();
$size = filesize($zipfilename);
$name = $titleid;
$name .= "_mkb.zip";

$mime_type = "multipart/x-zip";
// required for IE, otherwise Content-Disposition may be ignored
 if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');
 
 header('Content-Type: ' . $mime_type);
 header('Content-Disposition: attachment; filename="'.$name.'"');
 header("Content-Transfer-Encoding: binary");
 header('Accept-Ranges: bytes');
 
 /* The three lines below basically make the 
    download non-cacheable */
 header("Cache-control: private");
 header('Pragma: private');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Length: ".$size);
readfile($zipfilename); //echo($buffer); // is also possible
flush();
?>
