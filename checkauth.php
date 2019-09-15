<?php
session_start();
header("Cache-control: private");
if ($_SESSION["access"] != "granted") {
  header("Location: ../Login/login.php?referrer=".$_SERVER['PHP_SELF']);
} else {
  $access=$_SESSION["access"];
  $username=$_SESSION["username"];
  $first = $_SESSION["first"];
  $userID= $_SESSION["userID"];
}
?>