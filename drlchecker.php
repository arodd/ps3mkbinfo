<?php
function hex_dump($data, $newline="\n<br>")
{
  $alldata = $data[0] . $data[1] . $data[2] . $data[3] . $data[4] . $data[5] . $data[6] . $data[7] . $data[8] . $data[9] . $data[10];
  $hex = bin2hex($alldata);
  $bytes = str_split($hex, 2);
  echo "<br>Bytes Analyzed: " . count($bytes) . "<br>";
  if((count($bytes)) != 32768 ) {
  echo "<br>Not a valid DRL File size: " , count($bytes) . "<br><br>Please make sure to use overwrite mode and use these blank DRL templates. <a href=http://goo.gl/7EHS0></a><br><br>";
  foreach($bytes as $byte){
  echo " $byte ";
  return 0;
  }
  }
  else {
  foreach($bytes as $i => $byte){
  echo " $byte ";   
    if( $i == 0 && $byte != 10 ) {
    echo "<br>Not a valid DRL File beginning.<br><br>Please make sure to use overwrite mode and use these blank DRL <a href=http://goo.gl/7EHS0>templates.</a><br><br>";
    return 0;
    }
    else {
    echo "<br>Appears to be a valid DRL file size." . "<br><br>Please make sure to add to the <a href=http://goo.gl/mFNaT>Spreadsheet.</a><br><br>";
    }
  }
  }

}

  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . " ";
    $mkb = file($_FILES["file"]["tmp_name"]);
    hex_dump($mkb);
    }
    echo "<br><a href=index.html>Generate MKB/DRL</a> -- <a href=showtitles.php>Show Uploaded Movies</a><br>";

?>
