<?php
include_once("checkauth.php");
include_once("CourseInfoEnum.php");
include_once("dbconnect.php");
include_once("cssclass.php");
$_SESSION['curPage']="score.php";
include("navBar.php");
$action = $_GET['action'];
if (isset($_POST['submit'])){
  $front=CourseInfo::FRONT;
  $back=CourseInfo::BACK;
  $rating=CourseInfo::RATING;
  $slope=CourseInfo::SLOPE;
  $far=CourseInfo::FAR;
  $close=CourseInfo::CLOSE;
  $middle=CourseInfo::MIDDLE;
  
  $club_name = $_POST["club_name"];
  $course_name= $_POST["course_name"];
  $city= $_POST["city"];
  $state= $_POST["state"];
  $course_info=$_POST["course_info"];
  $par=$_POST["par"];
  $handicap=$_POST["handicap"];
  $hole_length=$_POST["hole_length"];
  $course_par=0;
  $rating_far=$course_info[$front][$rating][$far]+$course_info[$back][$rating][$far];
  $rating_close=$course_info[$front][$rating][$close]+$course_info[$back][$rating][$close];
  $rating_middle=$course_info[$front][$rating][$middle]+$course_info[$back][$rating][$middle];
  $slope_far=ceil(($course_info[$front][$slope][$far]+$course_info[$back][$slope][$far])/2);
  $slope_middle=ceil(($course_info[$front][$slope][$middle]+$course_info[$back][$slope][$middle])/2);
  $slope_close=ceil(($course_info[$front][$slope][$close]+$course_info[$back][$slope][$close])/2);

  foreach($par as $p) {
    $course_par+=$p;
  }

  try {
    mysql_query("BEGIN");
    $courseQuery = <<<CourseQueryMarker
INSERT INTO courses
(Club_Name, Course_Name, City, State, 
Front_Slope_Middle, Back_Slope_Middle, Slope_Middle, 
Front_Rating_Middle, Back_Rating_Middle, Rating_Middle,
Front_Slope_Far, Back_Slope_Far, Slope_Far,
Front_Rating_Far, Back_Rating_Far, Rating_Far,
Front_Slope_Close, Back_Slope_Close, Slope_Close,
Front_Rating_Close, Back_Rating_Close, Rating_Close,
Par) 
VALUES(
'$club_name', '$course_name', '$city', '$state',
{$course_info[$front][$slope][$middle]},{$course_info[$back][$slope][$middle]}, $slope_middle,
{$course_info[$front][$rating][$middle]},{$course_info[$back][$rating][$middle]}, $rating_middle,
{$course_info[$front][$slope][$far]},{$course_info[$back][$slope][$far]}, $slope_far,
{$course_info[$front][$rating][$far]},{$course_info[$back][$rating][$far]}, $rating_far,
{$course_info[$front][$slope][$close]},{$course_info[$back][$slope][$close]}, $slope_close,
{$course_info[$front][$rating][$close]},{$course_info[$back][$rating][$close]}, $rating_close,
$course_par
)
CourseQueryMarker;
    echo $courseQuery;
    $results = mysql_query($courseQuery);
    $courseID = mysql_insert_id();
    for ($i=1;$i<19;$i++) {
      $holeQuery = <<<HoleQueryMarker
INSERT INTO holes 
(Course_ID, Hole_Number, Par, Handicap,
 Far_Tee_Length,Middle_Tee_Length, Close_Tee_Length)
VALUE(
$courseID, $i, {$par[$i]}, {$handicap[$i]},
{$hole_length[$far][$i]}, {$hole_length[$middle][$i]}, {$hole_length[$close][$i]})

HoleQueryMarker;
      echo $holeQuery;
      mysql_query($holeQuery);
    }
    mysql_query("COMMIT");
  } catch (Exception $e) {
    mysql_query("ROLLBACK");
  }
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
#$= $_POST[""];
}
?>

<html>
<head>
<title>AlanMAnderson.com</title>
<style type="text/css">
  @import 'stylesheets/css.php?c=demo.css';
</style>
<script type="text/javascript" src="javascripts/updatecourse.js"></script>
</head>
<body>
<form method="post" action="<?php echo $PHP_SELF;?>">
  <table>
    <tr>
      <th colspan="3">Club Name: </th>
      <td colspan="7"><input type="text" name="club_name"/></td>
      <td></td>
      <th colspan="4">Course Name: </th>
      <td colspan="7"><input type="text" name="course_name"/></td>
      <td></td>
    </tr>
    <tr>
      <th colspan="3">City: </th>
      <td colspan="7"><input type="text" name="city"/></td>
      <td></td>
      <th colspan="4">State: </th>
      <td colspan="7"><input type="text" name="state"/></td>
    </tr>
    <tr>
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
      <th>Far Tee Distance</th>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][1]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][2]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][3]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][4]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][5]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][6]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][7]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][8]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][9]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox" id="hlFarOut"></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][10]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][11]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][12]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][13]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][14]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][15]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][16]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][17]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[FAR][18]" onblur="update('hole_length_far')"/></td>
      <td class="scorecardBox" id="hlFarIn"></td>
      <td class="scorecardBox" id="hlFarTotal"></td>
    </tr>
    <tr class="middleTee">
      <th>Middle Tee Distance</th>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][1]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][2]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][3]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][4]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][5]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][6]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][7]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][8]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][9]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox" id="hlMidOut"></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][10]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][11]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][12]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][13]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][14]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][15]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][16]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][17]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[MIDDLE][18]" onblur="update('hole_length_mid')"/></td>
      <td class="scorecardBox" id="hlMidIn"></td>
      <td class="scorecardBox" id="hlMidTotal"></td>
    </tr>
    <tr class="closeTee">
      <th>Close Tee Distance</th>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][1]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][2]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][3]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][4]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][5]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][6]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][7]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][8]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][9]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox" id="hlCloseOut"></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][10]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][11]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][12]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][13]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][14]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][15]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][16]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][17]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox"><input type="text" name="hole_length[CLOSE][18]" onblur="update('hole_length_close')"/></td>
      <td class="scorecardBox" id="hlCloseIn"></td>
      <td class="scorecardBox" id="hlCloseTotal"></td>
    </tr>
    <tr>
      <th>Handicap</th>
      <td class="scorecardBox"><input type="text" name="handicap[1]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[2]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[3]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[4]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[5]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[6]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[7]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[8]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[9]"/></td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox"><input type="text" name="handicap[10]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[11]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[12]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[13]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[14]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[15]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[16]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[17]"/></td>
      <td class="scorecardBox"><input type="text" name="handicap[18]"/></td>
      <td class="scorecardBox"></td>
      <td class="scorecardBox"></td>
    </tr>
    <tr>
      <th>Par</th>
      <td class="scorecardBox"><input type="text" name="par[1]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[2]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[3]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[4]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[5]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[6]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[7]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[8]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[9]" onblur="update('par')"/></td>
      <td class="scorecardBox" id="parOut"></td>
      <td class="scorecardBox"><input type="text" name="par[10]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[11]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[12]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[13]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[14]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[15]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[16]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[17]" onblur="update('par')"/></td>
      <td class="scorecardBox"><input type="text" name="par[18]" onblur="update('par')"/></td>
      <td class="scorecardBox" id="parIn"></td>
      <td class="scorecardBox" id="parTotal"></td>
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
            <td><input type="text" name="course_info[FRONT][RATING][FAR]"/></td>
            <td><input type="text" name="course_info[BACK][RATING][FAR]"/></td>
            <td></td>
            <td><input type="text" name="course_info[FRONT][SLOPE][FAR]"/></td>
            <td><input type="text" name="course_info[BACK][SLOPE][FAR]"/></td>
            <td></td>
          </tr>
          <tr class="middleTee">
            <th>Middle Tees</th>
            <td><input type="text" name="course_info[FRONT][RATING][MIDDLE]"/></td>
            <td><input type="text" name="course_info[BACK][RATING][MIDDLE]"/></td>
            <td></td>
            <td><input type="text" name="course_info[FRONT][SLOPE][MIDDLE]"/></td>
            <td><input type="text" name="course_info[BACK][SLOPE][MIDDLE]"/></td>
            <td></td>
          </tr>
          <tr class="closeTee">
            <th>Close Tees</th>
            <td><input type="text" name="course_info[FRONT][RATING][CLOSE]"/></td>
            <td><input type="text" name="course_info[BACK][RATING][CLOSE]"/></td>
            <td></td>
            <td><input type="text" name="course_info[FRONT][SLOPE][CLOSE]"/></td>
            <td><input type="text" name="course_info[BACK][SLOPE][CLOSE]"/></td>
            <td></td>
          </tr>
        </table>
      <td><input type="submit" name="submit"/></td>
    </tr>
  </table>
</form>
</body>
</html>