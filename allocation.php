<?php

//$cycle=$_GET['cycle'];

$queryx=mysql_query("SELECT `usage`.cycle FROM `usage` ORDER BY `usage`.uid DESC LIMIT 1;");
$w = mysql_fetch_array($queryx);
$cycle=$w[0];

$datype=$_GET['datype'];
$blog="";
//we want to check which free spots on current cycle, the one before if not the first cycle and the one after. If free in all send, if free in 2 random if not don't send
$query=mysql_query("SELECT `usage`.bandid,`usage`.chanid, `usage`.framid, `usage`.slotid FROM `usage` WHERE `usage`.cycle = ".$cycle." AND `usage`.used='N'");

$suser=mysql_query("SELECT `users`.user_id, `users`.name FROM `users` WHERE `users`.is_primary='N'");


$candidates = array();

//automatic new integer key higher then the highest existing integer 
//key in the array, starts at 0


	//echo("hello");
	while($row = mysql_fetch_array($query))
	{
		$chdown=$cycle-1;
		
		$query2=mysql_query("SELECT `usage`.used FROM `usage` WHERE `usage`.cycle=".$chdown." AND `usage`.bandid=".$row['bandid']." AND `usage`.chanid=".$row['chanid']." AND `usage`.framid=".$row['framid']." AND `usage`.slotid=".$row['slotid']);
		$query2 = mysql_fetch_array($query2);
		
		if($query2[0]=='N')
		{
			$chup=$cycle-2;
			
			$query3=mysql_query("SELECT `usage`.used FROM `usage` WHERE `usage`.cycle=".$chup." AND `usage`.bandid=".$row['bandid']." AND `usage`.chanid=".$row['chanid']." AND `usage`.framid=".$row['framid']." AND `usage`.slotid=".$row['slotid']);
			$query3 = mysql_fetch_array($query3);
			
			if($query3[0]=='N')
			{
				//Candidate for sending ... store info somewhere
				$candidates[]= array($row['chanid'], $row['framid'] , $row['slotid']);
				$blog.="<p class=\"tiny\">Ch: ".$row['chanid'].", Fr: ".$row['framid'].", Sl: ".$row['slotid']." - Candidate for oportunistic allocation<br /></p>";
			}
		}
		else
		{
			//
		}
		
		//echo("Sdown: ".$query2[0].", Sup: ".$query3[0]."<br />");
	}
	
	//Lets display the monitor blog
	echo("<div class=\"blog mediums\"><strong>Frame/Channel Estimation Cycle [".$cycle."]</strong><hr />".$blog."</div>");
	
	
	$ncycle=$cycle+1;
	$op=count($candidates);
	$usr=mysql_num_rows($suser);
	if($usr>$op)
	{
		$rem=$usr-$op;
	}
	else
	{
		$rem=0;
	}
	
	$i=0;
	echo("<br /><div class=\"blog smalls\"><strong>Decision Making Process</strong><hr />");
	while($row2 = mysql_fetch_array($suser))
	{
		$j=0;
		foreach($candidates as &$value)
		{	
			if($j==$i)
			{
				echo("<p class=\"tiny scrmsg\">User: <strong>".$row2['name']."</strong> has decided to use the slot position: <strong>".$value[2]."</strong>, for the next frame in channel: <strong>".$value[0]."</strong></p>");
				
				//Here goes the insert query...
				$date = date('Y-m-d H:i:s');
				$final=mysql_query("INSERT INTO `crsimula_db`.`usage` (`uid`, `usrid`, `bandid`, `chanid`, `framid`, `slotid`, `cycle`, `used`, `timestamp`) VALUES (NULL, '".$row2['user_id']."', '1', '".$value[0]."', '".$value[0]."', '".$value[2]."', '".$ncycle."', 'Y', '".$date."')");
			}
			$j++;		
		}		
		$i++;
	}
	echo("</div>");
	
	echo("<br /><p class=\"warning\">Number of secondary users that will not send on the next cycle: ".$rem."</p>");
	
	$query=mysql_query("SELECT ongoing FROM sim_progress");
	$row = mysql_fetch_array($query);
	if($row[0]=="Y")
	{
		echo("<br /><div class=\"blog smalls\">Simulation still in Process [refresh in 2 seconds]<hr />");
		echo("<META HTTP-EQUIV=\"Refresh\" Content=\"2; URL=msecondary.php?process=true&cycle=".$ncycle."&datype=".$_GET['datype']."\">");
	}
	else
	{
		echo("<br /><div class=\"blog smalls\">Simulation terminated by the primary users process.");
	}
		
?>