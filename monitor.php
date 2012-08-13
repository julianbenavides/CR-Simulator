<?php
	include "config.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Monitor Console - CR Simulator</title>
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
                    		<h1>Simulation Monitor</h1>
                    		<?php if(empty($_GET)) : ?>
                    		<p>This screen will display a live monitor of the channels involved in the simulation. The monitor will aim to display the frame usage per round and will try to display if collisions are presented at any time in any slot of the frame. Click on "Start Monitoring" to begin.</p>
                    		<form name="form2" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                    			<input type="hidden" name="process" value="true">
                    			<input type="submit" value="Start Monitoring" />
                    		</form>
                    		<?php elseif(isset($_GET['process']) && isset($_GET['run'])) : ?>
					   <div class="simuprocess">Monitor in Process</div>
					   <?php /* This screen will display a monitor tool for the channels involved in the simulation*/ ?>
				<?php endif; ?>

	                 </div>
                    </div>						
		</div>
		<div class="footerwrap"><? include_once "footer.php"; ?></div>
	</div>

</body>
</html>