<?php
include "functions.php";

//database login configuration
global $dbhost,$dbuser,$dbpass,$dbname,$link;

$dbhost="localhost"; //Usually localhost
$dbuser="crsimula_db";//mysql username
$dbpass="crsimula_db" ; //sql password
$dbname="crsimula_db";//The name of the database for mail

$link=mysql_connect($dbhost,$dbuser,$dbpass)or die("Could not connect to server.");
mysql_select_db($dbname) or die("Could not select database");

?>