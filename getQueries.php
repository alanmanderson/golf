<?php
include_once("dbconnect.php");

function getQuery($key, $args=Array()){
  $query="SELECT query, argument_count FROM queries WHERE query_key='$key'";
  $results = mysql_query($query) or die(mysql_error());
  $row = mysql_fetch_array($results);
#  echo $row['query'];
  if (count($args)!=$row['argument_count']){
    throw Exception();
  }
  $query=$row['query'];
  for($i=0;$i<count($args);$i++){
    $query = str_replace("\$args[$i]",$args[$i],$query);
  }
  return $query;
}

?>