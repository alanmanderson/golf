<?php
$curPage=$_SESSION['curPage'];
require_once("checkauth.php");
require_once("dbconnect.php");
echo "<div id=\"navBar\"><ul>";
$query = "SELECT * FROM pages ORDER BY OrderCode";
$results = mysql_query($query) or die(mysql_error());
while($row=mysql_fetch_array($results)){
  echo "<li><a href=\"$row[Address]\">$row[LinkCode]</a></li>";
}
echo "</ul></div>";
?>