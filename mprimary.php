<?php
	include "config.php";
	//include "functions.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Primary User Simulation Interfase - CR Simulator</title>
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
	                    	
	                    	<?php 
					/* This screen will allow the user to start the simulation of several primary users filling channel frames randomly using a random function */ 
				?>
				
				<?php if(empty($_GET)) : ?>
				
				<?php //lets check if there is already a simulation in process
					$veri="";
					$query=mysql_query("SELECT ongoing FROM sim_progress");
					$row = mysql_fetch_array($query);
					if($row[0]=="Y")
					{
						$veri="There is already a Primary User simulation in progress. Add new Secondary Users to the simulation if you want or wait until the simulation is over to create a new Primary User Simulation";
					}
				?>
					<h1>Primary Users Simulation</h1>
					<p>This module will simulate enough primary (licensed) users to fill 5 transmission channels per every frame of transmission. They will decide if they want to transmit or not in every frame based on a probability function.</p>
					<p class="warning"><?php echo($veri); ?></p>
					<div class="reglayout">
			  			<p><strong>Primary User Simulation</strong></p>
			  			
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
							<p>3. Do you want to enable the simulation monitor?</p>
							<input type="radio" name="monitor" value="yes" checked>Yes
							<input type="radio" name="monitor" value="no">No
							<p>4. Which probability function do you want use for data transmission decision?</p>
							<select name="function">
							<option value="default">functions.php</option>
							
							<?php if($veri=="") :?>
								<input class="butt" type="submit" value="Start Simulation Now" />
					   			<?php else :?>	
			   						<input class="butt" type="submit" value="Start Simulation Now" disabled="disabled" />			
				   			<?php endif; ?>	
							
						</form>
			   		</div>
		
		<?php elseif(isset($_GET['datatype']) && isset($_GET['datasize']) && isset($_GET['monitor']) && isset($_GET['function']) ) : ?>
						   
					   <h1>Simulation in Progress</h1>
					   <p> Attempting to create/subscribe primary users in the database...</p>
					   <div class="reglayout">
						   <?php 
						   	
							   //Determine the number of channels to create the multiplier
							$numch= mysql_query("SELECT * FROM channels");
							$numch=mysql_num_rows($numch);
							//Defining Number of users that the system will be creating'
							$prot=cal_size($_GET['datatype']);
							
							/*if($_GET['datatype']=="http")
							{
								$prot=$GLOBALS['httpsize'];
							}else 
								if ($_GET['datatype']=="sms")
								{
									$prot=$GLOBALS['smssize'];
								}
								else
									if($_GET['datatype']=="video")
									{
										$prot=$GLOBALS['videosize'];
									}*/
								
							$numusrs=$GLOBALS['frsize']/$prot;
							//First we check how many users do we have in the db
						   	$result = mysql_query("SELECT * FROM users WHERE is_primary='Y'");
							if (mysql_num_rows($result)<=0) 
							{
								echo("<p>Simulation Frame Size: <strong>".$GLOBALS['frsize']." bytes</strong></p>".
								"<p>Packet Size for selected protocol: <strong>".$prot." bytes</strong></p>".
								"<p>Number of channels to be filled with primary users: <strong>".$numch." channels</strong></p>".
								"<p>Number of primary users per channel: <strong>".round($GLOBALS['frsize']/$prot)." users</strong></p>".
								"<p><strong>Total Number of Users for the Simulation: ".round(($GLOBALS['frsize']/$prot)*$numch)." USERS</strong></p>".
								"<br /><p>Attempting to create the users...<p>");
							   	//no users lets create all of them
							    	$numusr=($GLOBALS['frsize']/$prot)*$numch;
							    	$ban=true;
							    	$sizer=$_GET['datasize']*1000;
								for ($i = 1; $i <= $numusr; $i++) 
								{
									$query= mysql_query("INSERT INTO users VALUES ('','".$_SERVER['REMOTE_ADDR']."','P".$i."','Y')");
									$query= mysql_query("INSERT INTO data_transmitted VALUES ('','$i','".$sizer."')");
									$query= mysql_query("UPDATE  `crsimula_db`.`sim_progress` SET  `ongoing` =  'Y' WHERE  `sim_progress`.`id` =1");
								if (!$query)
								{
									$ban=false;
								    	die('Invalid query: ' . mysql_error());
								}
								}
								if($ban)
								{
								$i=$i-1;
								echo("<p class=\"scrmsg\">Users P1, P2....P".$i." created succesfully. Data transmission simulation will start in 5 seconds.</p>");
					
								//Lets send the new header with the variables we need to roll the ball
									if(!isset($_GET['cycle']))
									{
										$cycle=1;
									}
									else
									{
										$cycle=$_GET['cycle'];
									}
									//redirecting to the good stuff
									echo("<META HTTP-EQUIV=\"Refresh\" Content=\"4; URL=mprimary.php?process=true&cycle=".$cycle."&datype=".$_GET['datatype']."\">");

								}
								else
								{
									echo("There were some errors during the user creation process; you should check the database for data integrity issues.");}
								}
							else
							{
								echo("<h2>Processing...</h2><p>There are already <strong>".mysql_num_rows($result)."</strong> users created in the database, so we will try to subscribe these users and start the simulation using them. Data transmission simulation will start in 5 seconds.");
		
								
								//Lets send the new header with the variables we need to roll the ball
								if(!isset($_GET['cycle']))
								{
									$cycle=1;
								}
								else
								{
									$cycle=$_GET['cycle'];
								}
								//redirecting to the good stuff
								echo("<META HTTP-EQUIV=\"Refresh\" Content=\"4; URL=mprimary.php?process=true&cycle=".$cycle."&datype=".$_GET['datatype']."\">");
							}
						   ?>
					   </div>
					
					<?php elseif(isset($_GET['process']) && isset($_GET['cycle']) && isset($_GET['datype'])) : ?>
					<h1>Sumulation Running [Cycle:<?php echo $_GET['cycle'];?>]</h1>
					<p>Primary user transmission decision making process for this cycle.</p>
					<?php
						$numch= mysql_num_rows(mysql_query("SELECT * FROM channels"));
						$numusr=mysql_num_rows(mysql_query("SELECT * FROM users WHERE is_primary='Y'"));
						
						//globally defined to generate a random number between A and B to decide wether to send or not
						//Perc will determine how many users will decide if transmitting or not the remaining will send for sure
						$perc=round(rand($GLOBALS['percentdown'],$GLOBALS['percentup']));
						$willsend=$GLOBALS['willsend']-round(rand($GLOBALS['wdown'],$GLOBALS['wup']));
						$var=(string)$_GET['datype'];
						$numslot=round($GLOBALS['frsize']/cal_size($var));
						
						//following variables will be to determine if sending or not
						$limit=round($numusr/(round(($numusr*$perc)/100)));
						
						//Define the size of the td
						$tdsize=round($GLOBALS['src']/($numusr/$numch));
						
						
						
						echo("<p>In this cycle <strong>".$perc."%</strong> of the primary users will evaluate if they want to transmit or not <strong>(".$numusr." users)</strong>. For that <strong>".$perc."%</strong> the simulation will set a <strong>".$willsend."% of sending certainty</strong> that will generate a decision making process of wheter to send or not.<br /><br />All remaining users will transmit no matter what.</p>");
						
						//Having defined the values let's do the heavy duty process
						echo("<div class=\"fix\"><table>");
						//looping through the number of channels
						
						$pos=1;
						for($i=1; $i<=$numch; $i++)
						{
							echo("<tr>");
							//Lets create a frame for each channel and then the slots for each frame
							//Every cycle should have 5 frames - 1 per channel - with x number of slots deppending on the type of data transmitted
							$date = date('Y-m-d H:i:s');
							$query=mysql_query("INSERT INTO frames VALUES ('','1','".$i."','".$_GET['cycle']."','".$GLOBALS['frsize']."','".$date."')");
							
							//Counter to determine every time we will take a decision of wether to send or not
							$cont=0;
							//then we proceed to create the slots for that defined frame
							for($j=1; $j<=$numslot; $j++)
							{
								$query=mysql_query("INSERT INTO slots VALUES ('','1','".$i."','".$i."','".cal_size($_GET['datype'])."')");
								
								//Here we decide for each slot/user if tranmision is made or not
								//include "channelsim.php";
								
								//this flag will let us know if all data has been already sent to end the simulation
								$banend=true;
								
								
								//echo("limit: ".$limit);
								
								//update data_transmitted table
								$query=mysql_query("SELECT data_left FROM data_transmitted WHERE id= ".$pos);
								$datarem= mysql_fetch_array($query);
								$datarem=$datarem[0];
								if($datarem>0)
								{
									$banend=false;
								}
								//echo("SELECT data_left FROM data_transmitted WHERE 'id'= ".$pos);
								
								//check if slot is being used
								$check2=mysql_query("SELECT `usage`.used, `usage`.usrid FROM `usage` WHERE `usage`.cycle=".$_GET['cycle']." AND `usage`.bandid=1 AND `usage`.chanid=".$i." AND `usage`.framid=".$i." AND `usage`.slotid=".$j);
								$check=mysql_num_rows($check2);
								
								if($check>0)
								{
									//gather the info of this user
									while($col= mysql_fetch_array($check2))
									{
										$susrid=$col['usrid'];
									}
									
									$queryx=mysql_query("SELECT `users`.name FROM `users` WHERE `users`.user_id=".$susrid);
									$queryx= mysql_fetch_array($queryx);
									$susrid=$queryx[0];
								}
								
								if($cont==$limit)
								{
									$cont=0;
									
									//let's create the decision making proces
									//first divide the will to send in 4 and take the middle portion of that range
									$lim1= $willsend/4;
									$lim2= ($willsend/4)*3;
									//Then we generate a random number in between that range
									$inc=round(rand($lim1, $lim2));
									//Then use a condition to define if we are sending
									$tot=($inc*100)/$willsend;
									
									
									
									
									//if true we send
									if($willsend>$tot)
									{
										//add value usage table
										$query=mysql_query("INSERT INTO `crsimula_db`.`usage` (`uid`, `usrid`, `bandid`, `chanid`, `framid`, `slotid`, `cycle`, `used`, `timestamp`) VALUES (NULL, '".$i."', '1', '".$i."', '".$i."', '".$j."', '".$_GET['cycle']."', 'Y', '".$date."')");
										
										//here we check if the size is greater than 0
										if($datarem>0)
										{
											$banend=false;
											$datarem=$datarem-cal_size($_GET['datype']);
											$query=mysql_query("UPDATE data_transmitted SET data_left=".$datarem." WHERE id=".$pos);
										}
										if($check<=0)
										{
											echo("<td class=\"sim used\" style=\"width:".$tdsize."px\"></td>");
										}
										else
										{
											echo("<td class=\"sim collided\" style=\"width:".$tdsize."px\"></td>");
											
											//Add data to collision log table
											$collider=mysql_query("INSERT INTO `crsimula_db`.`collision_log` (`id`, `timestamp`, `puser_collided`, `suser_collided`, `band`, `channel`, `frame`, `slot`,`cycle`) VALUES (NULL, '".$date."', 'P".$i."', '".$susrid."', '1', '".$i."', '".$i."', '".$j."','".$_GET['cycle']."')");
											
										}
									}
									else //we do not send
									{
										//add value usage table
										$query=mysql_query("INSERT INTO `crsimula_db`.`usage` (`uid`, `usrid`, `bandid`, `chanid`, `framid`, `slotid`, `cycle`, `used`, `timestamp`) VALUES (NULL, '".$i."', '1', '".$i."', '".$i."', '".$j."', '".$_GET['cycle']."', 'N', '".$date."')");
										echo("<td class=\"sim notused\" style=\"width:".$tdsize."px\"></td>");
										
									}
								}
								else
								{
									$datarem=$datarem-cal_size($_GET['datype']);
									//add value usage table
									$query=mysql_query("INSERT INTO `crsimula_db`.`usage` (`uid`, `usrid`, `bandid`, `chanid`, `framid`, `slotid`, `cycle`, `used`, `timestamp`) VALUES (NULL, '".$i."', '1', '".$i."', '".$i."', '".$j."', '".$_GET['cycle']."', 'Y', '".$date."')");
									$query=mysql_query("UPDATE data_transmitted SET data_left=".$datarem." WHERE id=".$pos);
									
									
									if($check<=0)
									{
										echo("<td class=\"sim used\" style=\"width:".$tdsize."px\"></td>");
									}
									else
									{
										echo("<td class=\"sim collided\" style=\"width:".$tdsize."px\"></td>");
										//Add data to collision log table
										$collider=mysql_query("INSERT INTO `crsimula_db`.`collision_log` (`id`, `timestamp`, `puser_collided`, `suser_collided`, `band`, `channel`, `frame`, `slot`,`cycle`) VALUES (NULL, '".$date."', 'P".$i."', '".$susrid."', '1', '".$i."', '".$i."', '".$j."','".$_GET['cycle']."')");
									}
								}
								$cont=$cont+1;
								$pos=$pos+1;
							}
							echo("</tr>");
							
						}
						echo("</table></div>");
					
					   if($banend)
					   {
					   	echo("<div class=\"reglayout\">All data from the primary user has been sent. Simulation over.</div>");
					   	$query=mysql_query("UPDATE  `crsimula_db`.`sim_progress` SET  `ongoing` =  'N' WHERE  `sim_progress`.`id` =1");	
					   }
					   else
					   {
					   	//simulation continues
					   	echo("<div class=\"reglayout\">Simulation still in Process [refresh in 2 seconds]</div>");
					   	$next=$_GET['cycle']+1;
					   	$dat=$_GET['datype'];
					   	echo("<META HTTP-EQUIV=\"Refresh\" Content=\"2; URL=mprimary.php?process=true&cycle=".$next."&datype=".$dat."\">");
					   }
					?>   
					
					<p>Reference values:</p>
					<div class="reglayout">
						<table>
							<tr>
								<td class="sim notused" style="width: 8px"></td>
								<td>Free Slot</td>
							</tr>
							<tr>
								<td class="sim used" style="width: 8px"></td>
								<td>Used Slot</td>
							<tr>
							</tr>
								<td class="sim collided" style="width: 8px"></td>
								<td>Collision Detected</td>
							</tr>
						</table>
					</div>
					<?php endif; ?>
				
	                    	<?php 
	                    		if(isset($_GET['reset']))
	                    		{
		                    		reset_all();
		                    		echo("<p class=\"notice\">All values cleared in the database</p>");
	                    		}
	                    	?>
	                 </div>
                    </div>						
		</div>
		<div class="footerwrap"><? include_once "footer.php"; ?></div>
	</div>

</body>
</html>