<?php
header('content-type:text/css');
header("Expires: ".gmdate("D, d M Y H:i:s", (time()+900)) . " GMT"); 
/* Company Colours */ 
$blue='#369';
$green='#363';
$lgreen='#cfc';
$color1='#ddeef6';		/* Light blue-green*/
$color2='#27b';				/* Deep Blue */
$color3='#88bbd4';		/* Light blue ocean */
$color4='#666';				/* Greyish bluish*/
$color5='#789';				/* Grey */
$color6='#6AC';				/* Lightish Blue*/
$color7='#39d';				/* Solid blue*/
$white='#fff';				/* White*/
$black='#000';
print <<< ENDCSS

body, html {
  background: $white;
  text-align: center;
}

body {
  color:$black;
}

table {
  border-collapse: collapse;
}

tr {

}

th {

}

.scorecardBox {
  width:10px;
  height:10px;
  border:3px solid green;
}

.scorecardBox input {
  width:30px;
}

.farTee, .farTee input {
  background:$black;
  color:$white;
}

.middleTee, .middleTee input {
  background:$white;
}

.closeTee, .closeTee input {
  background:#FFFFCC;
}

ENDCSS;

?>