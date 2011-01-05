<html>
	<META http-equiv=Content-Type content='text/html; charset=windows-1252'>

		<style type="text/css">

		TABLE 		{
						TABLE-LAYOUT: fixed; 
						FONT-SIZE: 100%; 
						align: left;
						border-collapse:collapse;
					}
		*
					{
						margin:0
					}

		.dspcont 	{
	
						BORDER-RIGHT: none;
						BORDER-TOP: none;
						PADDING-LEFT: 0px;
						FONT-SIZE: 8pt;
						MARGIN-BOTTOM: -1px;
						PADDING-BOTTOM: 5px;
						MARGIN-LEFT: 0px;
						BORDER-LEFT: #none;
						WIDTH: 100%;
						COLOR: #000000;
						MARGIN-RIGHT: 0px;
						PADDING-TOP: 4px;
						BORDER-BOTTOM: none;
						FONT-FAMILY: Tahoma;
						POSITION: relative;
						BACKGROUND-COLOR: #ffffff
					}
					
		.filler 	{
						BORDER-RIGHT: medium none; 
						BORDER-TOP: medium none; 
						DISPLAY: block; 
						BACKGROUND: none transparent scroll repeat 0% 0%; 
						MARGIN-BOTTOM: -1px; 
						FONT: 100%/8px Tahoma; 
						MARGIN-LEFT: 43px; 
						BORDER-LEFT: medium none; 
						COLOR: #FFFFFF; 
						MARGIN-RIGHT: 0px; 
						PADDING-TOP: 4px; 
						BORDER-BOTTOM: medium none; 
						POSITION: relative
					}

		.pageholder	{
						margin: 0px auto;
					}
					
		.dsp
					{
						BORDER-RIGHT: #bbbbbb 1px solid;
						PADDING-RIGHT: 0px;
						BORDER-TOP: #bbbbbb 1px solid;
						DISPLAY: block;
						PADDING-LEFT: 0px;
						FONT-WEIGHT: bold;
						FONT-SIZE: 8pt;
						MARGIN-BOTTOM: -1px;
						MARGIN-LEFT: 0px;
						BORDER-LEFT: #bbbbbb 1px solid;
						COLOR: #FFFFFF;
						MARGIN-RIGHT: 0px;
						PADDING-TOP: 4px;
						BORDER-BOTTOM: #bbbbbb 1px solid;
						FONT-FAMILY: Tahoma;
						POSITION: relative;
						HEIGHT: 2.25em;
						WIDTH: 95%;
						TEXT-INDENT: 10px;
					}

		.dsphead1	{
						BACKGROUND-COLOR: #7ba7c7;
					}
					
	.dspcomments 	{
						BACKGROUND-COLOR:#FFFFE1;
						COLOR: #000000;
						FONT-STYLE: ITALIC;
						FONT-WEIGHT: normal;
						FONT-SIZE: 8pt;
					}

	td 				{
						VERTICAL-ALIGN: TOP; 
						FONT-FAMILY: Tahoma;
						padding:2px;
					}
					
	th 				{
						VERTICAL-ALIGN: TOP; 
						COLOR: #FFFFFF; 
						BACKGROUND-COLOR: #7ba7c7;
						TEXT-ALIGN: left;
					}
					
	BODY 			{
						margin-left: 4pt;
						margin-right: 4pt;
						margin-top: 6pt;
					} 
		</style>
	</head>
	<body>
	<div class="pageholder">
	<h2 class="dsp dsphead1">PS3 MKB Info -- <a href=index.html>Generate DRL/HRL</a> -- <a href=drlcheck.html>Check DRL Size</a> -- <a href=http://goo.gl/7EHS0>Blank DRL Templates</a> -- <a href=http://goo.gl/sksFR>Source Code</a> --</h2>
<?php
function addhrl() {
$page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
if ($_SERVER['QUERY_STRING']) $page .= "?". $_SERVER['QUERY_STRING'] . "&hrlvw=1";
else $page .= "?hrlvw=1";
return $page;
}
function removehrl() {
$page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
$page .= "?". $_SERVER['QUERY_STRING'];
$page = preg_replace("/&hrlvw=1/",'', $page);
$page = preg_replace("/\?hrlvw=1/",'', $page);
return $page;
}
$addhrlpage = addhrl();
$remhrlpage = removehrl();
$myconn = mysql_connect('localhost','user','password');
mysql_select_db("ps3mkb", $myconn);
$sort = $_GET['sort'];
$hrlshow = $_GET['hrlvw'];
if( $hrlshow == 1 ) $columns = "id, title, mkb, drl, hrl";
else $columns = "id, title, mkb, drl";
if ($sort == 1) {
$result = mysql_query("select $columns from mkbfiles_autoincrement order by title, drl");
$result2 = mysql_query("select drl from mkbfiles_autoincrement group by drl");
$result3 = mysql_query("select hrl from mkbfiles_autoincrement group by hrl");
}
elseif ($sort ==2) {
$result = mysql_query("select $columns from mkbfiles_autoincrement order by drl, title");
$result2 = mysql_query("select drl from mkbfiles_autoincrement group by drl");
$result3 = mysql_query("select hrl from mkbfiles_autoincrement group by hrl");
}
elseif ($sort ==3) {
if( $hrlshow == 1 ) $result = mysql_query("select id, mkb, drl, hrl, group_concat(title order by title asc separator ', ') as title from mkbfiles_autoincrement group by drl");
else $result = mysql_query("select id, mkb, drl, hrl, group_concat(title order by title asc separator ', ') as title from mkbfiles_autoincrement group by drl");
$result2 = mysql_query("select drl from mkbfiles_autoincrement group by drl");
$result3 = mysql_query("select hrl from mkbfiles_autoincrement group by hrl");
}
else {
if( $hrlshow == 1 ) $result = mysql_query("select id, mkb, drl, hrl, group_concat(title order by title asc separator ', ') as title from mkbfiles_autoincrement group by drl");
else $result = mysql_query("select id, mkb, drl, hrl, group_concat(title order by title asc separator ', ') as title from mkbfiles_autoincrement group by drl");
$result2 = mysql_query("select drl from mkbfiles_autoincrement group by drl");
$result3 = mysql_query("select hrl from mkbfiles_autoincrement group by hrl");
}
$num_rows = mysql_num_rows($result);
$num_drls = mysql_num_rows($result2);
$num_hrls = mysql_num_rows($result3);
if ( $hrlshow == 1 ){
$head2 = <<<EOT
<div class="dsp dspcomments">Results Returned: $num_rows ($num_drls unique DRLs/$num_hrls unique HRLs) -- Sort By: <a href=showtitles.php?sort=1&hrlvw=1>Title</a> -- <a href=showtitles.php?sort=2&hrlvw=1>DRL</a> -- <a href=showtitles.php?sort=3&hrlvw=1>Unique</a> -- <a href=$remhrlpage>Hide HRL</a> -- Click Movie Title To Download proper DRL1 file fully padded to 32768 bytes</div>
<div class="filler"></div>
<div class="dspcont">
EOT;
echo $head2;
}
else {
$head2 = <<<EOT
<div class="dsp dspcomments">Results Returned: $num_rows ($num_drls unique DRLs/$num_hrls unique HRLs) -- Sort By: <a href=showtitles.php?sort=1>Title</a> -- <a href=showtitles.php?sort=2>DRL</a> -- <a href=showtitles.php?sort=3>Unique</a> -- <a href=$addhrlpage>Show HRL</a> -- Click Movie Title To Download proper DRL1 file fully padded to 32768 bytes</div>
<div class="filler"></div>
<div class="dspcont">
EOT;
echo $head2;
}
if ( $hrlshow == 1 ) echo "<table border=1><colgroup><col/><col/><col/><tr><th>Title</th><th>MKB</th><th>DRL</th><th>HRL</th></tr>";
else echo "<table border=1><colgroup><col/><col/><col/><tr><th>Title</th><th>MKB</th><th>DRL</th></tr>";
while($row = mysql_fetch_array($result))
 {
 echo "<tr>";

 echo "<td>" . $row['title'] . " <a href=drlfetch.php?titleid=" . $row['id'] . ">Download</a></td>";
 echo "<td>" . $row['mkb'] . "</td>";
 echo "<td>" . $row['drl'] . "</td>";
if( $hrlshow == 1 ) echo "<td>" . $row['hrl'] . "</td>";
 echo "</tr>";
 }
echo "</table>";
mysql_free_result($result);
mysql_close($myconn);
?>
</div>
<div class="filler"></div>
</div>
</div>
</body>
</html>
