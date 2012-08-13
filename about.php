<?php
	include "config.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>About - CR Simulator</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
	<script src="js/scripts.js"></script>
</head>
<body>
	<div class="wrapper">
		<div class="headerwrap"><? include_once "header.php"; ?></div>
		<div class="leftwrap">
                    <? include_once "left.php"; ?>
                    <div class="contents">
                    	<div class="content_in">
	                    	<h1>About this Project</h1>
	                    	<p>This project will try to simulate the behavior of a licensed channel when multiple unlicensed (secondary) users try to oportunistically place data packets on unused frame slots.</p>
	                    	<p>The simulator was developed used PHP as the script language and MySQL as relational database. It was decided to use database technology for the simulation as this technology efficiently process asynchronous calls, event locks and concurrent transactions which is what it was desired for the simulator.</p>
	                    	<p>It is also very efficient for post processing tasks as it was needed to have some data for post analysis.</p>
	                    	<p>The simulator was developed as a framework as one of the main objectives was to create it for the users to test their own allocation algorithm within the simulator; an <a href="api.php">API reference document</a> is currently being developed for potential users to start using the simulator as a web service.</p>
	                 </div>
                    </div>						
		</div>
		<div class="footerwrap"><? include_once "footer.php"; ?></div>
	</div>

</body>
</html>