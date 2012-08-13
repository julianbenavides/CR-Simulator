<?php
	include "config.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Secondary Users Simulation - CR Simulator</title>
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
                    		<h2>Secondary (unlicensed) Users Simulation</h2>
                    		<div class="mobsim">
		                    	<?php 
						/* following statement will define which layout to show possible layouts:
						Startup layout (to choose between single or multiple users
						Single secondary User Simulator Layout
						Multiple secondary Users Simulator Layout
						Simulator in process - constantly refreshing */
		
					?>
					<?php if(empty($_GET)) : ?>
					    <div class="reglayout">
					    	<p><strong>Secondary Users Simulation</strong></p>
					    	<p>What do you want to simulate?</p>
					    	
					    	<form name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
							<input type="radio" name="simtype" value="Single" onclick = "submit(this.form)"> Single Secondary User<br>
							<input type="radio" name="simtype" value="Multiple" onclick = "submit(this.form)"> Multiple Secondary Users<br>	
						</form>
					    	
					    </div>
						<?php elseif(isset($_GET['simtype']) && $_GET['simtype']=="Single" && !isset($_GET['datatype'])) : ?>
					   		<div class="reglayout">
					   			<p><strong>Single User Simulation</strong></p>
					   			<form name="form2" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
					   				<p>1. What type of data do you want to transmit during the simulation?</p>
						   			<select name="datatype">
										<option value="http">HTTP <span>512 bytes</span></option>
										<option value="sms">SMS <span>140 bytes</span></option>
										<option value="video">Video Stream <span>1.6 Kbytes</span></option>
									</select>
									<p>2. How much data do you want send during the simulation?</p>
									<select name="datasize">
									<option value="10">10 MB</option>
									<option value="25">25 MB</option>
									<option value="50">50 MB</option>
									<option value="75">75 MB</option>
									<option value="100">100 MB</option>
									<option value="1000">1000 MB</option>
									</select>
									<p>3. Do you want to enable the simulation monitor for this user?</p>
									<input type="radio" name="monitor" value="yes" checked>Yes
									<input type="radio" name="monitor" value="no">No
									<p>4. Which "Oportunistic" Allocation Algorithm do you want to use?</p>
									<select name="algorithm">
									<option value="allocation">allocation.php</option>
									<input type="hidden" name="simtype" value="Single">
									<input class="butt" type="submit" value="Start Simulation Now" />
								</form>
					   		</div>
								<?php elseif(isset($_GET['simtype']) && $_GET['simtype']=="Multiple" && !isset($_GET['datatype'])) : ?>
								   <div class="reglayout">
								   
								   	<p><strong>Multiple User Simulation</strong></p>
						   			<form name="form3" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
						   				<p>1. How many secondary users do you want to simulate?</p>
							   			<select name="numusers">
							   				<option value="5">5</option>
											<option value="10">10</option>
											<option value="20">20</option>
											<option value="50">50</option>
											<option value="100">100</option>
										</select>
						   				<p>2. What type of data do you want to transmit during the simulation?</p>
							   			<select name="datatype">
											<option value="http">HTTP <span>512 bytes</span></option>
											<option value="sms">SMS <span>140 bytes</span></option>
											<option value="voice">Voice <span>11.2 Kbytes</span></option>
										</select>
										<p>3. How much data do you want send during the simulation?</p>
										<select name="datasize">
										<option value="10">10 MB</option>
										<option value="25">25 MB</option>
										<option value="50">50 MB</option>
										<option value="75">75 MB</option>
										<option value="100">100 MB</option>
										<option value="1000">1000 MB</option>
										</select>
										<p>4. Which "Oportunistic" Allocation Algorithm do you want to use?</p>
										<select name="algorithm">
										<option value="allocation">allocation.php</option>
										</select>
										
										
										<input type="hidden" name="simtype" value="Multiple">
										<input class="butt" type="submit" value="Start Simulation Now" />
									</form>
								   
								   </div>
								
								<?php elseif(isset($_GET['datatype']) && isset($_GET['datasize'])  && isset($_GET['algorithm']) && isset($_GET['simtype'])) : ?>
									   <div class="reglayout"><strong>Simulation Setup Summary</strong><hr />
									   
									   <?php 
									   	if($_GET['simtype']=="Single")
									   	{
									   		echo("<p>Single secondary user will be simulated</p>");
									   	}
									   	else
									   	{
									   		echo("<p>Multiple secondary user will be simulated</p>
									   		<p>Number of users to simulate: <strong>".$_GET['numusers']."</strong></p>");
									   	}
									   ?>	
									   	<p>Simulation Frame Size: <strong><?php echo($GLOBALS['frsize']); ?> bytes</strong></p>
									   	<p>Packet Size for selected protocol: <strong><?php $prot=cal_size($_GET['datatype']); echo ($prot);?></strong></p>
									   	<p>How much data will be sent: <strong><?php echo($_GET['datasize']*1000);?> bytes</strong></p>
									   	<p>Number of channels for the simulation: <strong><?php echo(mysql_num_rows(mysql_query("SELECT * FROM channels")));?></strong></p>
									   	<p>Oportunistic Algorithm to be used: <strong><?php echo($_GET['algorithm']);?></strong></p>
									   	<br /><p>Attempting to create the user and subscribe the algorithm to the simulator...<p>
									   	
									   	<?php						   		
									   		$i=mysql_num_rows(mysql_query("SELECT * FROM users"))+1;
									   		//First of all let's try to create a secondary user(s) in the database
									   		if($_GET['simtype']=="Single")
									   		{
										   		$query= mysql_query("INSERT INTO users VALUES ('','".$_SERVER['REMOTE_ADDR']."','S".$i."','N')");
												$sizer=$_GET['datasize']*1000;
												$query= mysql_query("INSERT INTO data_transmitted VALUES ('','".$i."','".$sizer."')");
												$ban=true;
												
												if (!$query)
												{
													$ban=false;
												    	die('Invalid query: ' . mysql_error());
												}
												if($ban)
												{
													echo("<p class=\"scrmsg\">User S".$i." created succesfully. Algorithm was subscribed to the simulation. Data transmission simulation will start in 5 seconds.</p>");
													//redirecting to the good stuff
													//checking the current cycle
													$cycle=mysql_num_rows(mysql_query("SELECT * FROM frames"))/mysql_num_rows(mysql_query("SELECT * FROM channels"));
													
													echo("<META HTTP-EQUIV=\"Refresh\" Content=\"3; URL=msecondary.php?process=true&cycle=".$cycle."&datype=".$_GET['datatype']."\">");
												}
											}
											else if($_GET['simtype']=="Multiple")
											{
												$cont=$_GET['numusers'];
												for($x=0; $x<$cont; $x++)
												{
													$sus=$i+$x;
													$query= mysql_query("INSERT INTO users VALUES ('','".$_SERVER['REMOTE_ADDR']."','S".$sus."','N')");
													$sizer=$_GET['datasize']*1000;
													$query= mysql_query("INSERT INTO data_transmitted VALUES ('','".$sus."','".$sizer."')");
													$ban=true;
												}
												
												if (!$query)
												{
													$ban=false;
												    	die('Invalid query: ' . mysql_error());
												}
												if($ban)
												{
													$tot=$i+$_GET['numusers'];
													echo("<p class=\"scrmsg\">Users S".$i.",...,S".$tot." created succesfully. Algorithm was subscribed to the simulation. Data transmission simulation will start in 5 seconds.</p>");
													//redirecting to the good stuff
													//checking the current cycle
													$cycle=mysql_num_rows(mysql_query("SELECT * FROM frames"))/mysql_num_rows(mysql_query("SELECT * FROM channels"));
													
													echo("<META HTTP-EQUIV=\"Refresh\" Content=\"3; URL=msecondary.php?process=true&cycle=".$cycle."&datype=".$_GET['datatype']."\">");
												}
											}
									   	?>
									   	
									   </div>
									   
									   
								
								<?php elseif(isset($_GET['process']) && isset($_GET['cycle']) && isset($_GET['datype'])) : ?>
									   <div class="reglayout">
									   	<strong>Simulation in Process</strong><hr />
									   
									   	<?php include('allocation.php'); ?>
									   
									   </div>
					<?php endif; ?>
				</div>
	                 </div>
                    </div>						
		</div>
		<div class="footerwrap"><? include_once "footer.php"; ?></div>
	</div>

</body>
</html>