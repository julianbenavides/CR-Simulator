<?php
	//GLOBALS
	$src=600;
	$frsize=64000;
	$httpsize=512;
	$smssize=140;
	$videosize=1600;
	$percentdown=30;
	$percentup=60;
	$willsend=70;
	$wup=40;
	$wdown=30;
	
	function reset_all()
	{
		$clrusage=mysql_num_rows(mysql_query("SELECT * FROM  `usage` "));
		for($i=1; $i<=$clrusage; $i++)
		{
			$query=mysql_query("DELETE FROM `crsimula_db`.`usage` WHERE `usage`.`uid` = ".$i);
		}
		$query=mysql_query("ALTER TABLE  `usage` AUTO_INCREMENT =1");
		
		$result = mysql_num_rows(mysql_query("SELECT * FROM users"));
		for($i=1; $i<=$result; $i++)
		{
			$query=mysql_query("DELETE FROM `crsimula_db`.`data_transmitted` WHERE `data_transmitted`.`id` = ".$i."");
			$query=mysql_query("DELETE FROM `crsimula_db`.`users` WHERE `users`.`user_id` = ".$i."");
		}
		$query=mysql_query("ALTER TABLE  `data_transmitted` AUTO_INCREMENT =1");
		$query=mysql_query("ALTER TABLE  `users` AUTO_INCREMENT =1");
		
		$result = mysql_num_rows(mysql_query("SELECT * FROM frames"));
		for($i=1; $i<=$result; $i++)
		{
			$result2 = mysql_num_rows(mysql_query("SELECT * FROM slots"));
			for($j=1; $j<=$result2; $j++)
			{
				
				$query=mysql_query("DELETE FROM `crsimula_db`.`slots` WHERE `slots`.`slot_id` = ".$j);	
			}
			//delete frame
			$query=mysql_query("DELETE FROM `crsimula_db`.`frames` WHERE `frames`.`frame_id` = ".$i);
		}
		$query=mysql_query("ALTER TABLE  `frames` AUTO_INCREMENT =1");
		$query=mysql_query("ALTER TABLE  `slots` AUTO_INCREMENT =1");
		
		
		$query=mysql_query("TRUNCATE TABLE  `collision_log`");
		
		$query=mysql_query("UPDATE  `crsimula_db`.`sim_progress` SET  `ongoing` =  'N' WHERE  `sim_progress`.`id` =1");	
	}
	
	function cal_size($datype)
	{
		//echo($datype);
		if($datype=="http")
		{
			return($GLOBALS['httpsize']);
		}else 
			if ($datype=="sms")
			{
				return($GLOBALS['smssize']);
			}
			else
				if($datype=="video")
				{
					return($GLOBALS['videosize']);
				}
	}
?>