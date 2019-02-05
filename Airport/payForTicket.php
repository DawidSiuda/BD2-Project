<?php

	require_once "config.php";

	echo "pay for ticket";

	//
 	//	Set variables.
 	//

	if(isset($_GET["passangerid"]))
	{
		$passangerid = htmlspecialchars($_GET["passangerid"]);
	}
	else
	{
		$passangerid = NULL;

		echo "payForTicket: error-1";

		//header("location: index.php");
	}

	echo $passangerid;

	//
	// Set pay as true for passaner_id gets as argument
	//

	if ( $passangerid != NULL)
	{
		$sql = "UPDATE passangers SET payed = 1 where passanger_id = ?;";

		if($stmt = mysqli_prepare($link, $sql))
		{
	        // Bind variables to the prepared statement as parameters
	        mysqli_stmt_bind_param($stmt, "d", $passangerid);

	        // Attempt to execute the prepared statement
	        if(mysqli_stmt_execute($stmt))
	        {
	        	mysqli_stmt_close($stmt);
			}
			else 
			{
				echo "payForTicket: error-3";
			}
		}
		else 
		{
			echo "payForTicket: error-4";
		}
 	}

 	header("location: index.php?page=cancelation&action=success");

// ?>