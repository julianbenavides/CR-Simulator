<?php
	include "config.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Cognitive Radio Simulator - Final Project</title>
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
	                    	<h1>Welcome to the CR Simulator</h1>
	                    	<img src="images/front.jpg" class="fimage">
	                    	<p class="caption">
	                    		<a href="http://www.ee.umanitoba.ca/~ekram/research_presentations.htm" target="_blank">Source: Prof. E. Hossain Presentations</a>
	                    	</p>
	                    	<p>This project is aimed to simulate collisions presented when secondary (unlicensed) users try to oportunistically place data packets on a licensed channel when primary (licensed) users are not using it.
	                    	</p>
	                    	<p>This simulator will allow users to simulate primary or secondary users, single or multiple at a time and will monitor how many collisions occur when using a determined algorithm to try to oportunistically place packets on unused slots in a licensed channel frame.
	                    	</p>
	                    	<p>As part of this project for the ECE 7202 Final Project, there is going to be a user interface for:
	                    	</p>
	                    	<ul>
	                    		<li>Simulate Primary (Licensed) Users</li>
	                    		<li>Simulate Secondary (Unlicensed) Users</li>
	                    		<li>Monitor channel(s) activity</li>
	                    		<li>Export data for analysis</li>
	                    	</ul>
	                    	<p>If you have any particular interest you can <a href="contact.php">contact us.</a></p>
	                 </div>
                    </div>						
		</div>
		<div class="footerwrap"><? include_once "footer.php"; ?></div>
	</div>

</body>
</html>