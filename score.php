<?php
include_once("checkauth.php");
include_once("CourseInfoEnum.php");
include_once("dbconnect.php");
include_once("cssclass.php");
$_SESSION['curPage']="course.php";
include("navBar.php");

$action = $_GET['action'];
if ($action=='edit'){
  $roundID=$_GET['roundid'];
} else if ($action=='add') {
  $courseID=$_GET['courseid'];
  if (isset($_POST['submit'])){
    $strokes=$_POST['strokes'];
    $fairways=$_POST['fairway'];
    $greens=$_POST['green'];
    $putts=$_POST['putts'];
    $penalties=$_POST['penalties'];
    $date=$_POST['date'];
    $courseID=$_POST['selected_course'];
    $hole_type=$_POST['hole_type'];
    for ($z=1;$z<=18;$z++) {
      if (isset($greens[$z])) $greens[$z]="TRUE";
      else $greens[$z]="FALSE";
      if (isset($fairways[$z])) $fairways[$z]="TRUE";
      else $fairways[$z]="FALSE";
      echo $greens[$z]." ".$fairways[$z]."</BR>";
    }
    #insert round and get ID
    try {
      mysql_query("BEGIN");
      $query = "INSERT INTO rounds (Course_ID, Golfer_ID, Date)".
	       "VALUES($courseID,$userID,'$date')";
      $results = mysql_query($query);
      $roundID = mysql_insert_id();

      #Get The holeIDs for the course
      $query = "SELECT ID, Hole_Number FROM holes WHERE Course_ID=$courseID";
      $holeIDResults = mysql_query($query);
      while($holeID=mysql_fetch_array($holeIDResults)) {
	$hole_number = $holeID["Hole_Number"];
	$query="INSERT INTO scores ".
	       "(Hole_ID, Round_ID,Fairway, Green_In_Regulation, ".
	       "Strokes, Putts, Penalties, Tee_Type)".
	       "VALUES($holeID[ID],$roundID,$fairways[$hole_number],$greens[$hole_number],".
	       "$strokes[$hole_number],$putts[$hole_number],$penalties[$hole_number],$hole_type)";
	$results = mysql_query($query);
      }
      mysql_query("COMMIT");
    } catch(Exception $e) {
      mysql_query("ROLLBACK");
    }
    echo $query;
  }
}
?>

<html>
<head>
<title>AlanMAnderson.com</title>
<style type="text/css">
  @import 'stylesheets/css.php?c=demo.css';
</style>
<script type="text/javascript" src="javascripts/updatescore.js"></script>
</head>
<body>
<form method="post" action="<?php echo $PHP_SELF;?>">
  <?php 
    if ($action=='add'){
      $courseQuery = "SELECT ID,Club_Name,Course_Name, City FROM courses";
      $results = mysql_query($courseQuery) or die(mysql_error());
      echo "<select name='selected_course' >";
      while($row=mysql_fetch_array($results)) {
	if (!isset($courseID)) $courseID=$row['ID'];
	if ($courseID==$row['ID']) $selected="selected";
	else $selected="";
	echo "<option value=\"$row[ID]\" $selected>".
	     "$row[Club_Name] - $row[Course_Name] in $row[City]".
	     "</option>";
      }
      echo "</select>";
      $selectedCourseQuery = "SELECT * FROM courses WHERE ID=$courseID";
      $courseResults = mysql_query($selectedCourseQuery) or die(mysql_error());
      $course_info = mysql_fetch_array($courseResults);
      $selectedCourseHoles = "SELECT * FROM holes WHERE Course_ID=$courseID";
      $holeResults = mysql_query($selectedCourseHoles) or die(mysql_error());
      $hole_info = array();
      $hole=1;
      while($row=mysql_fetch_array($holeResults)) {
	$hole_info[$hole++] = $row;
      }
    }
    
    $query="SELECT * FROM tee_types";
    $tee_types=mysql_query($query);
    $far_tee_id=mysql_result($tee_types,0,"ID");
    $middle_tee_id=mysql_result($tee_types,1,"ID");
    $close_tee_id=mysql_result($tee_types,2,"ID");
    echo "Date (YYYY-MM-DD): <input type=\"text\" name=\"date\" />";
    $html = <<<ScoreCardMarker
<table >
    <tr style="text-align:right" >
      <th colspan="3">Club Name: </th>
      <td colspan="7">$row[Club_Name]</td>
      <td></td>
      <th colspan="4">Course Name: </th>
      <td colspan="7">$row[Course_Name]</td>
      <td></td>
    </tr>
    <tr style="text-align:right" >
      <th colspan="3">City: </th>
      <td colspan="7">$row[City]</td>
      <td></td>
  <th colspan="4">State: </th>
      <td colspan="7">$row[State]</td>
    </tr>
    <tr style="text-align:right" >
      <th>Hole</th>
      <th class="scorecardBox">1</th>
      <th class="scorecardBox">2</th>
      <th class="scorecardBox">3</th>
      <th class="scorecardBox">4</th>
      <th class="scorecardBox">5</th>
      <th class="scorecardBox">6</th>
      <th class="scorecardBox">7</th>
      <th class="scorecardBox">8</th>
      <th class="scorecardBox">9</th>
      <th class="scorecardBox">OUT</th>
      <th class="scorecardBox">10</th>
      <th class="scorecardBox">11</th>
      <th class="scorecardBox">12</th>
      <th class="scorecardBox">13</th>
      <th class="scorecardBox">14</th>
      <th class="scorecardBox">15</th>
      <th class="scorecardBox">16</th>
      <th class="scorecardBox">17</th>
      <th class="scorecardBox">18</th>
      <th class="scorecardBox">IN</th>
      <th class="scorecardBox">TOT</th>
    </tr>
    <tr class="farTee">
      <th><input type="radio" name="hole_type" value="$far_tee_id"/>Far Tee Distance</th>
  <td class="scorecardBox">{$hole_info[1][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[2][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[3][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[4][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[5][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[6][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[7][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[8][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[9][Far_Tee_Length]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox">{$hole_info[10][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[11][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[12][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[13][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[14][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[15][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[16][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[17][Far_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[18][Far_Tee_Length]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox"></td>
    </tr>
    <tr class="middleTee">
      <th><input type="radio" name="hole_type" value="$middle_tee_id"/>Middle Tee Distance</th>
      <td class="scorecardBox">{$hole_info[1][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[2][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[3][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[4][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[5][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[6][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[7][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[8][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[9][Middle_Tee_Length]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox">{$hole_info[10][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[11][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[12][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[13][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[14][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[15][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[16][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[17][Middle_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[18][Middle_Tee_Length]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox"></td>
    </tr>
    <tr class="closeTee">
      <th><input type="radio" name="hole_type" value="$close_tee_id"/>Close Tee Distance</th>
      <td class="scorecardBox">{$hole_info[1][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[2][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[3][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[4][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[5][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[6][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[7][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[8][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[9][Close_Tee_Length]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox">{$hole_info[10][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[11][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[12][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[13][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[14][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[15][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[16][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[17][Close_Tee_Length]}</td>
      <td class="scorecardBox">{$hole_info[18][Close_Tee_Length]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox"></td>
    </tr>
    <tr>
      <th>Handicap</th>
      <td class="scorecardBox">{$hole_info[1][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[2][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[3][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[4][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[5][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[6][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[7][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[8][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[9][Handicap]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox">{$hole_info[10][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[11][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[12][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[13][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[14][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[15][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[16][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[17][Handicap]}</td>
      <td class="scorecardBox">{$hole_info[18][Handicap]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox"></td>
    </tr>
    <tr>
      <th>Par</th>
      <td class="scorecardBox">{$hole_info[1][Par]}</td>
      <td class="scorecardBox">{$hole_info[2][Par]}</td>
      <td class="scorecardBox">{$hole_info[3][Par]}</td>
      <td class="scorecardBox">{$hole_info[4][Par]}</td>
      <td class="scorecardBox">{$hole_info[5][Par]}</td>
      <td class="scorecardBox">{$hole_info[6][Par]}</td>
      <td class="scorecardBox">{$hole_info[7][Par]}</td>
      <td class="scorecardBox">{$hole_info[8][Par]}</td>
      <td class="scorecardBox">{$hole_info[9][Par]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox">{$hole_info[10][Par]}</td>
      <td class="scorecardBox">{$hole_info[11][Par]}</td>
      <td class="scorecardBox">{$hole_info[12][Par]}</td>
      <td class="scorecardBox">{$hole_info[13][Par]}</td>
      <td class="scorecardBox">{$hole_info[14][Par]}</td>
      <td class="scorecardBox">{$hole_info[15][Par]}</td>
      <td class="scorecardBox">{$hole_info[16][Par]}</td>
      <td class="scorecardBox">{$hole_info[17][Par]}</td>
      <td class="scorecardBox">{$hole_info[18][Par]}</td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox"></td>
    </tr>
    <tr>
      <th>Strokes</th>
      <td class="scorecardBox"><input type="text" name="strokes[1]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[2]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[3]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[4]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[5]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[6]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[7]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[8]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[9]" onblur="update('strokes')"/></td>
      <td class="scorecardBox" id="strokesOut"></td>
      <td class="scorecardBox"><input type="text" name="strokes[10]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[11]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[12]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[13]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[14]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[15]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[16]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[17]" onblur="update('strokes')"/></td>
      <td class="scorecardBox"><input type="text" name="strokes[18]" onblur="update('strokes')"/></td>
      <td class="scorecardBox" id="strokesIn"></td>
      <td class="scorecardBox" id="strokesTotal"></td>
    </tr>
    <tr>
      <th>Fairway</th>
      <td class="scorecardBox"><input type="checkbox" name="fairway[1]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[2]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[3]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[4]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[5]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[6]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[7]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[8]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[9]" onchange="update('fairway')"/></td>
      <td class="scorecardBox" id="fairwayOut"></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[10]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[11]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[12]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[13]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[14]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[15]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[16]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[17]" onchange="update('fairway')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="fairway[18]" onchange="update('fairway')"/></td>
      <td class="scorecardBox" id="fairwayIn"></td>
      <td class="scorecardBox" id="fairwayTotal"></td>
    </tr>
    <tr>
      <th>Green</th>
      <td class="scorecardBox"><input type="checkbox" name="green[1]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[2]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[3]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[4]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[5]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[6]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[7]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[8]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[9]" onchange="update('green')"/></td>
      <td class="scorecardBox" id="greenOut"></td>
      <td class="scorecardBox"><input type="checkbox" name="green[10]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[11]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[12]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[13]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[14]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[15]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[16]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[17]" onchange="update('green')"/></td>
      <td class="scorecardBox"><input type="checkbox" name="green[18]" onchange="update('green')"/></td>
      <td class="scorecardBox" id="greenIn"></td>
      <td class="scorecardBox" id="greenTotal"></td>
    </tr>
    <tr>
      <th>Putts</th>
      <td class="scorecardBox"><input type="text" name="putts[1]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[2]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[3]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[4]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[5]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[6]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[7]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[8]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[9]" onblur="update('putts')"/></td>
      <td class="scorecardBox" id="puttsOut"></td>
      <td class="scorecardBox"><input type="text" name="putts[10]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[11]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[12]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[13]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[14]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[15]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[16]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[17]" onblur="update('putts')"/></td>
      <td class="scorecardBox"><input type="text" name="putts[18]" onblur="update('putts')"/></td>
      <td class="scorecardBox" id="puttsIn"></td>
      <td class="scorecardBox" id="puttsTotal"></td>
    </tr>
    <tr>
      <th>Penalties</th>
      <td class="scorecardBox"><input type="text" name="penalties[1]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[2]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[3]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[4]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[5]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[6]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[7]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[8]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[9]" onblur="update('penalties')"/></td>
      <td class="scorecardBox" id="penaltiesOut"></td>
      <td class="scorecardBox"><input type="text" name="penalties[10]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[11]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[12]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[13]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[14]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[15]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[16]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[17]" onblur="update('penalties')"/></td>
      <td class="scorecardBox"><input type="text" name="penalties[18]" onblur="update('penalties')"/></td>
      <td class="scorecardBox" id="penaltiesIn"></td>
      <td class="scorecardBox" id="penaltiesTotal"></td>
    </tr>
    <tr>
      <td colspan="21"></td>
        <table>
          <tr>
            <th></th>
            <th colspan="3">Rating</th>
            <th colspan="3">Slope</th>
          </tr>
          <tr>
            <th></th>
            <th>Front</th>
            <th>Back</th>
            <th>Total</th>
            <th>Front</th>
            <th>Back</th>
            <th>Total</th>
          </tr>
          <tr class="farTee">
            <th>Far Tees</th>
            <td>$course_info[Front_Rating_Far]</td>
            <td>$course_info[Back_Rating_Far]</td>
            <td></td>
            <td>$course_info[Front_Slope_Far]</td>
            <td>$course_info[Back_Slope_Far]</td>
            <td></td>
          </tr>
          <tr class="middleTee">
            <th>Middle Tees</th>
            <td>$course_info[Front_Rating_Middle]</td>
            <td>$course_info[Back_Rating_Middle]</td>
            <td></td>
            <td>$course_info[Front_Slope_Middle]</td>
            <td>$course_info[Back_Slope_Middle]</td>
            <td></td>
          </tr>
          <tr class="closeTee">
            <th>Close Tees</th>
            <td>$course_info[Front_Rating_Close]</td>
            <td>$course_info[Back_Rating_Close]</td>
            <td></td>
            <td>$course_info[Front_Slope_Close]</td>
            <td>$course_info[Back_Slope_Close]</td>
            <td></td>
          </tr>
        </table>
      <td><input type="submit" name="submit"/></td>
    </tr>
  </table>
</form>
</body>
</html>
ScoreCardMarker;
echo $html;

?>