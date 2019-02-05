<?php

	require_once "config.php";

	$my_personName = $_REQUEST["user_name"];
	$my_flyId= $_REQUEST["flyid"];
	$my_weight = $_REQUEST["weight"];
	$my_peyed = $_REQUEST["peyed"];

	echo $my_personName."<br>";
	echo $my_flyId."<br>";
	echo $my_weight."<br>";
	echo $my_peyed."<br>";

	$is_unique_flag = true;

	//
	// Check if user had booked ticket in the flight before this transaction.
	//

	 $sql = "Select * from passangers
			where user_id =  (select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = ?)
		    and
			flight_id = ?;";

	if($stmt = mysqli_prepare($link, $sql))
	{
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sd", $my_personName, $my_flyId);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt))
        {
			
			$result = mysqli_stmt_get_result($stmt);

			if ($result->num_rows > 0) 
			{
				mysqli_stmt_close($stmt);

				header("location: index.php?page=cancelation&action=block");

				$is_unique_flag = false;
			}
		}
		else 
		{
			echo "bookTicket: error-2";
		}
	}
	else 
	{
		echo "bookTicket: error-3";
	}

	//
	// Book ticket.
	//
	if ($is_unique_flag == true)
	{

		$sql = "Insert into PASSANGERS
				(USER_ID, FLIGHT_ID, WEIGHT_OF_LUGGAGE, PAYED)
			    values
				((select user_id from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q where username = ?), ?, ?, ?);";

		if($stmt = mysqli_prepare($link, $sql))
	    {
	        // Bind variables to the prepared statement as parameters
	        mysqli_stmt_bind_param($stmt, "sddi", $my_personName, $my_flyId, $my_weight, $my_peyed);

	     	if(mysqli_stmt_execute($stmt))
	        {
	            mysqli_stmt_store_result($stmt);	
	             
	            mysqli_stmt_close($stmt);

	            header("location: index.php?page=cancelation&action=success");
	        } 
	        else
	        {
	            echo "Something went wrong. Please try again later.";

	            mysqli_stmt_close($stmt);

	            header("location: index.php?page=cancelation&action=failure");
	        }
		}
	}
?>