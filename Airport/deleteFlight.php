<?php

	require_once "config.php";

	echo "delete Flight";

	//
	//	Set variables.
	//

	if(isset($_GET["id"]))
	{
		$id = htmlspecialchars($_GET["id"]);
	}
	else
	{
		$id = NULL;

		echo "deleteFlight: error-1";

		//header("location: index.php");
	}

	if(isset($_GET["username"]))
	{
		$username= htmlspecialchars($_GET["username"]);
	}
	else
	{
		$username = NULL;

		echo "deleteFlight: error-2";

		//header("location: index.php");
	}

	//
	//	Remove flight.
	//

	if ( $username != NULL && $id != NULL)
	{
		$sql = "delete from passangers 
				where  user_id = (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = ?)
				and flight_id = ?;";

		if($stmt = mysqli_prepare($link, $sql))
		{
	        // Bind variables to the prepared statement as parameters
	        mysqli_stmt_bind_param($stmt, "sd", $username, $id);

	        // Attempt to execute the prepared statement
	        if(mysqli_stmt_execute($stmt))
	        {
	        	mysqli_stmt_close($stmt);
			}
			else 
			{
				echo "deleteFlight: error-3";
			}
		}
		else 
		{
			echo "deleteFlight: error-4";
		}
	}

	header("location: index.php?page=cancelation&action=refund");

?>