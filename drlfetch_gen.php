<?php
function hextoobin($str) {
    return pack("H*" , $str);
}
$drl = $_POST[drl];
$bytes = str_split($drl, 2);
$drlbig = '';
for($i = 0; $i < 32768; $i++){
  if($i < count($bytes)) {
  $drlbig .= $bytes[$i];
  }
  if($i >= count($bytes)) {
  $drlbig .= "00";
  }
}
$drlbin = hextoobin($drlbig);
$zip = new ZipArchive();
$zipfilename = tempnam("tmp", "zip");
$zip->open($zipfilename, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
$zip->addFromString("DRL1", $drlbin);
$zip->addFromString("DRL2", $drlbin);
$zip->close();
$size = filesize($zipfilename);
$name = "gen_drls.zip";

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
