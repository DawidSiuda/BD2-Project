<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Airport</title>
  <meta name="description" content="Menage flyights and pilots accounts">

  <link rel="stylesheet" type="text/css" href="css/styles.css" media="screen"/>

</head>

<body>
	<div class = "mainDiv">
		<div id="logoDiv">
			<?php include 'Logo.html';?>
		</div>
		<div id="my_login">
			<?php
				// Initialize the session
				session_start();

				if (isset($_SESSION["type"])) 
				{ 
					echo "Jesteś zalogowany jako: ".$_SESSION["type"]."<br>";
				}
				
				// Check if the user is logged in, if not then redirect him to login page
				if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
				{
					echo "
						<p> Nie jesteś zalogowany.</p>	
						<div id=\"mainLogin\" class=\"form-group\">


							<button onclick=\"window.location.href='Login.php'\" class=\"btn btn-primary\">
								Zaloguj się
							</button>
			            </div>

			            <p id=\"logORSing\">lub</p>

						<div id=\"mainRegister\" class=\"form-group\">
							<button onclick=\"window.location.href='Register.php'\" class=\"btn btn-primary\">
								Zarejestruj
							</button>
			            </div>";
				}
				else
				{
					echo "
						<div id=\"mainLogin\" class=\"form-group\">


							<button onclick=\"window.location.href='reset-password.php'\" class=\"btn btn-primary\">
								Resetuj hasło
							</button>
			            </div>

			            <p id=\"logORSing\">lub</p>

						<div id=\"mainRegister\" class=\"form-group\">
							<button onclick=\"window.location.href='logout.php'\" class=\"btn btn-primary\">
								Wyloguj się
							</button>
			            </div>
			            <div id=\"my_menu\">";

					include 'menu.php';

					echo "</div>";
				}

			?>

		</div>	
		<div id="my_content">
			<!--<h2>Content</h2> --!>
			<?php
				require_once "config.php";

				if(isset($_GET["page"]))
				{
					$page = htmlspecialchars($_GET["page"]);
				}
				else
				{
					$page = "showflights";
				}
				
				if (isset($_SESSION["type"])) 
				{ 
					if($_SESSION["type"] == "CLIENT")
					{
						switch($page)
						{
							case "showflights":
								{
									if(isset($_SESSION["loggedin"]) and $_SESSION["loggedin"] == true)
									{
										echo '<h2>Dostępne loty:</h2>';

										// //$sql = "SELECT * FROM PLANES";
										// $sql =	"select f.flight_id as fid, f.departure_date,
										// 		(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
										// 		(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
										// 		f.ticket_price
										// 		from flights f
										// 		order by f.departure_date asc;";


										$sql = "select	f.flight_id as fid, 
												f.departure_date,
										        (select a.city from airports a where a.airport_id =
													(select START_AIRPORT_ID from flights where FLIGHT_ID = fid)) as start,
										        (select a.city from airports a where a.airport_id =
													(select FINISH_AIRPORT_ID from flights where FLIGHT_ID = fid)) as finish,
												f.TICKET_PRICE
												from	flights f
												where	f.departure_date > curdate()
												order by f.departure_date asc;";

										$result = $link->query($sql);

										if ($result->num_rows > 0) 
										{
										    echo "<table align=\"center\">";
										    echo "<tr><th>Nr Lotu</th><th>Trasa</th><th>Data</th><th>Cena</th></tr>";

										    while($row = $result->fetch_assoc())
										    {
												echo "<tr>".
										       		 " <td align\"center\">" . $row["fid"]."</td>".
										        	 " <td>" . $row["start"]. " --> ". $row["finish"]."</td>".
										        	 " <td align=\"left\">" . $row["departure_date"]."</td>".
										        	 " <td align=\"left\">" . $row["TICKET_PRICE"]."</td>".
													 " <td align=\"left\">".
													 " 		<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href='index.php?page=buyticket&flyid=". $row["fid"]."'\">Rezerwuj</button>".
													 " </td>".
													 " </tr>\n";
										    }

										    echo "</table>";
										}
										else 
										{
										    echo "0 results";
										}	
									}
									else
									{
										echo '<h2>Dostępne loty:</h2>';

										//$sql = "SELECT * FROM PLANES";
										// $sql =	"select f.flight_id as fid, f.departure_date,
										// 		(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
										// 		(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
										// 		f.ticket_price
										// 		from flights f
										// 		order by f.departure_date asc;";

										$sql = "select	f.flight_id as fid, 
												f.departure_date,
										        (select a.city from airports a where a.airport_id =
													(select START_AIRPORT_ID from flights where FLIGHT_ID = fid)) as start,
										        (select a.city from airports a where a.airport_id =
													(select FINISH_AIRPORT_ID from flights where FLIGHT_ID = fid)) as finish,
												f.TICKET_PRICE
												from	flights f
												where	f.departure_date > curdate()
												order by f.departure_date asc;";

										$result = $link->query($sql);

										if ($result->num_rows > 0) 
										{
											echo "<table align=\"center\">";

										    while($row = $result->fetch_assoc())
										    {
										    //     echo "<b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
													 // " <b> Skąd:</b>" . $row["start"]. " <b> Dokoąd:</b>" . $row["finish"]. " <b> Cena biletu:</b>" . $row["ticket_price"]. " <br>";

											echo " <tr> ".
									        	 " <td>" . $row["start"]. " --> ". $row["finish"]."</td>".
									        	 " <td align=\"left\"> <b> Data odlotu:</b> " . $row["departure_date"]."</td>".
									        	 " <td align=\"left\"> <b> Cena biletu:</b>" . $row["TICKET_PRICE"]."</td>".
												 " </tr>";
										    }

										    echo "</table>";
										}
										else 
										{
										    echo "0 results";
										}
									}
								}
								break;

							case "findconnection":
								{
									if(isset($_GET["start"]) && isset($_GET["destination"])  )
									{
										$my_start = htmlspecialchars($_GET["start"]);
										$my_destination = htmlspecialchars($_GET["destination"]);
									}
									else
									{
										$my_start = NULL;
										$my_destination = NULL;
									}
									
									echo " <form action=\"index.php\" method=\"get\">";

									echo " <div> <label class=\"registerLabel\">Skad: </label> <input type=\"text\" name=\"start\" class=\"form-control\"> </div>";
									
									echo " <div> <label class=\"registerLabel\">Dokąd: </label> <input type=\"text\" name=\"destination\" class=\"form-control\"> </div>";

									echo " <div> <input type=\"submit\" class=\"btn btn-primary\" value=\"Szukaj\"> </div>";

									echo " <input type=\"hidden\" name=\"page\" value=\"findconnection\" />";

									echo " </form>";

									echo " </br></br></br>";

									if ($my_destination != NULL and $my_start != NULL)
									{
									    // Prepare a select statement
			    						$sql = "select * from (select f.flight_id as fid, f.departure_date,
												(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as fstart,
												(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as ffinish,
												f.ticket_price
												from flights f
												order by f.departure_date asc) as t
												where fstart = ? and ffinish = ?;";

										if($stmt = mysqli_prepare($link, $sql))
										{
								            // Bind variables to the prepared statement as parameters
								            mysqli_stmt_bind_param($stmt, "ss", $my_start, $my_destination);
								            

								            // Attempt to execute the prepared statement
								            if(mysqli_stmt_execute($stmt))
								            {
												
												$result = mysqli_stmt_get_result($stmt);

											    while($row = $result->fetch_assoc())
											    {
											        echo "<a href=\"index.php?page=buyticket&flyid=". $row["fid"]." \"> <b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
														 " <b> Skąd:</b>" . $row["fstart"]. " <b> Dokoąd:</b>" . $row["ffinish"]. " <b> Cena biletu:</b>" . $row["ticket_price"]. "</a> <br>";
											    }
								            } 
								            else
								            {
												echo "Something went wrong. Please try again later.";
								            }

											mysqli_stmt_close($stmt);
							            }
									}
								}
								break;

							case "buyticket":
								{
									if(isset($_GET["flyid"]))
									{
										$my_flyid = htmlspecialchars($_GET["flyid"]);
																
										$sql =	"select f.flight_id as fid, f.departure_date,
												(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
												(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
												f.ticket_price
												from flights f
												where f.flight_id = ?
												order by f.departure_date asc;";

										if($stmt = mysqli_prepare($link, $sql))
										{
								            // Bind variables to the prepared statement as parameters
								            mysqli_stmt_bind_param($stmt, "d", $my_flyid);

								            // Attempt to execute the prepared statement
								            if(mysqli_stmt_execute($stmt))
								            {
												
												$result = mysqli_stmt_get_result($stmt);

												if ($result->num_rows > 0) 
												{	
													echo " <h2> Wybrano lot: </h2>";
												    // output data of each row
												    while($row = $result->fetch_assoc())
												    {
												        echo "<a href=\"index.php?page=buyticket&flyid=". $row["fid"]." \"><b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
															 " <b> Skąd:</b>" . $row["start"]. " <b> Dokoąd:</b>" . $row["finish"]. " <b> Cena biletu:</b>" . $row["ticket_price"]. "</a> <br>";
												    }
												}
											}
											mysqli_stmt_close($stmt);
										}

										echo " </br></br></br>";
									}
									else
									{
										$my_flyid = NULL;
									}

									echo " <form action=\"bookTicket.php\" method=\"post\" >";

									echo " <input type=\"hidden\" name=\"user_name\" value=\"".$_SESSION["username"]."\" />";
										
									if ($my_flyid == NULL)
									{
										echo " <div> <label class=\"registerLabel\">Numer lotu: </label> <input type=\"text\" name=\"flyid\" value=\"".$my_flyid." \" class=\"form-control\"> </div>";
									}
									else
									{
										echo " <div> <input type=\"hidden\" name=\"flyid\" value=\"".$my_flyid." \" class=\"form-control\"> </div>";
									}

									echo " <div> <label class=\"registerLabel\">Waga bagażu: </label> <input type=\"number\" min=\"0\" step=\"any\" name=\"weight\" value=\"0\" class=\"form-control\"> kg </div> ";

									echo " <input type=\"hidden\" name=\"peyed\" value=\"false\" />";

									echo " <div> <input type=\"submit\" class=\"btn btn-primary\" value=\"Rezerwuj\"> </div>";

									echo " </form>";

									echo " </br></br></br>";							
								}
								break;

							case "payforticket":
							case "cancelation":
								{
									if(isset($_GET["action"]))
									{
										if (htmlspecialchars($_GET["action"]) == "success")
										{
											echo "<h1> Operacja zakończyłą się pomyślnie.</h1>";
										}
										else if (htmlspecialchars($_GET["action"]) == "refund")
										{
											echo "<h1> Operacja zakończyłą się pomyślnie.</h1>";
											echo "<h1> Pieniądze zostaną zwrócone na twoje konto.</h1>";
										}
										else if (htmlspecialchars($_GET["action"]) == "block")
										{
											echo "<h1> Możesz kupić tylko jeden bilet na dany lot.</h1>";
										}
										else 
										{
											echo "<h1> Operacja zakończyła się niepowodzeniem.</h1>";
										}
									}
									
									echo "<br/><h1> Twoje loty:</h1>";

									if(isset($_SESSION["loggedin"]) and $_SESSION["loggedin"] == true)
									{
										$sql =  "select b.passanger_id, b.flight_id as fid, b.user_id, b.departure_date, b.ticket_price, b.payed,
												(select a.city from airports a right join booked b on a.airport_id=b.start_airport_id where b.flight_id = fid limit 1) as start,
												(select a.city from airports a right join booked b on a.airport_id=b.finish_airport_id  where b.flight_id = fid limit 1) as finish
												 from booked b
												 where user_id =  (select user_id 
												 				   from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q 
												 				   where username = ?);";

										if($stmt = mysqli_prepare($link, $sql))
										{
								            // Bind variables to the prepared statement as parameters
								            mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
								            

								            // Attempt to execute the prepared statement
								            if(mysqli_stmt_execute($stmt))
								            {
												
												$result = mysqli_stmt_get_result($stmt);
												
												echo " <table align=\"center\">";
												echo "<tr><th>Trasa</th><th>Data</th><th>Cena</th></tr>";
												
											    while($row = $result->fetch_assoc())
											    {
											    	if ($row["payed"] == 0)
											    	{
														// echo " <b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
														// 	 " <b> Skąd:</b>" . $row["start"]. " <b> Dokoąd:</b>" . $row["finish"]. " <b> Cena biletu:</b>" . $row["ticket_price"].
														// 	 " <input type=\"hidden\" name=\"id\" value=\"". $row["fid"]."\" />".
														// 	 " <input type=\"hidden\" name=\"username\" value=\"".  $_SESSION["username"]."\" />".
														// 	 " <input type=\"hidden\" name=\"passangerid\" value=\"".  $row["passanger_id"]."\" />".
														// 	 " <button onclick=\"window.location.href='deleteFlight.php?id=". $row["fid"]."&username=".$_SESSION["username"]."'\"> Rezygnuj </button>".
														// 	 " <button onclick=\"window.location.href='payForTicket.php?id=". $row["fid"]."&username=".$_SESSION["username"]."'\"> Zapłać </button>".
														// 	 "<label>".$row["payed"]."</label>".
														// 	 //" <input type=\"submit\" class=\"btn btn-primary\" value=\"Usun Rezerwacje\">".
														// 	 //" <input type=\"submit\" class=\"btn btn-primary\" value=\"Zapłać\"> </br>";
														//   //<a id=\"a_button\" href=\"index.php?dawid=tak\">button</a> <br>";
														//   	 " <br/>";

														echo " <tr> ".
															 " <td>". $row["start"]. " --> " . $row["finish"]."</td>".
															 " <td align=\"left\">" . $row["departure_date"]."</td>".
															 " <td align=\"left\">" . $row["ticket_price"]."</td>".
															 " <td align=\"left\">".
															 " <form action=\"payForTicket.php\" method=\"get\">".
															 " <input type=\"hidden\" name=\"id\" value=\"". $row["fid"]."\" />".
															 " <input type=\"hidden\" name=\"username\" value=\"".  $_SESSION["username"]."\" />".
															 " <input type=\"hidden\" name=\"passangerid\" value=\"".  $row["passanger_id"]."\" />".
															 " <button class=\"btn btn-primary\" onclick=\"window.location.href='deleteFlight.php\" > Rezygnuj </button>".
															 " <button class=\"btn btn-primary\" onclick=\"window.location.href='payForTicket.php\"> Zapłać </button>".
														     " </form>".
									        	 			 " </td>".
									        	 			 " </tr> ";
											    	}
											    	else
											    	{
														echo// " <b>ID lotu: </b>" . $row["fid"].
															 " <tr> ".
									        	 			 " <td>". $row["start"]. " --> " . $row["finish"] ."</td>".
															 " <td align=\"left\">" . $row["departure_date"]."</td>".
															 " <td align=\"left\">" . $row["ticket_price"].
															 " <input type=\"hidden\" name=\"id\" value=\"". $row["fid"]."\" />".
															 " <input type=\"hidden\" name=\"username\" value=\"".  $_SESSION["username"]."\" />"."</td>".
															 " <td align=\"left\">".
															 " 		<button class=\"btn btn-primary\" onclick=\"window.location.href='deleteFlight.php?id=". $row["fid"]."&username=".$_SESSION["username"]."'\">".
															 "			 Rezygnuj".
															 " 		</button>".
															 " 		<label class=\"peyedLabel\">Bilet opłacony</label>".
															 " </tr> ".
									        	 			 " </td>";
											    	}
											       
											    }

											    echo "</table>";

								            } 
								            else
								            {
												echo "Something went wrong. Please try again later.";
								            }

											mysqli_stmt_close($stmt);
							            }
									}

									break;
								}

								break;

							default:
								//echo 'Hello ' . htmlspecialchars($_GET["page"]) . '!';

						}
					}
					else if ($_SESSION["type"] == "EMPLOYEE")
					{
						switch($page)
						{
							case "showflights":
							{
								echo '<h2>Dostępne loty:</h2>';

								//$sql = "SELECT * FROM PLANES";
								// $sql =	"select f.flight_id as fid, f.departure_date,
								// 		(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
								// 		(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
								// 		f.ticket_price
								// 		from flights f
								// 		order by f.departure_date asc;";

								$sql = "select	f.flight_id as fid, 
										f.departure_date,
								        (select a.city from airports a where a.airport_id =
											(select START_AIRPORT_ID from flights where FLIGHT_ID = fid)) as start,
								        (select a.city from airports a where a.airport_id =
											(select FINISH_AIRPORT_ID from flights where FLIGHT_ID = fid)) as finish,
										f.TICKET_PRICE
										from	flights f
										where	f.departure_date > curdate()
										order by f.departure_date asc;";

								$result = $link->query($sql);

								if ($result->num_rows > 0) 
								{
									echo "<table align=\"center\">";

								    while($row = $result->fetch_assoc())
								    {
								    //     echo "<b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
											 // " <b> Skąd:</b>" . $row["start"]. " <b> Dokoąd:</b>" . $row["finish"]. " <b> Cena biletu:</b>" . $row["ticket_price"]. " <br>";

									echo " <tr> ".
							        	 " <td>" . $row["start"]. " --> ". $row["finish"]."</td>".
							        	 " <td align=\"left\"> <b> Data odlotu:</b> " . $row["departure_date"]."</td>".
							        	 " <td align=\"left\"> <b> Cena biletu:</b>" . $row["TICKET_PRICE"]."</td>".
										 " </tr>";
								    }

								    echo "</table>";
								}
								else 
								{
								    echo "0 results";
								}
							}
							break;
								
							case "employeeinf":
							{
								echo '<h2>Kadra:</h2>';

								$sql = "SELECT p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres
										FROM PERSONS p  right join users u on p.person_id=u.person_id
										WHERE PERMISSION_TYPE =\"EMPLOYEE\";";

								$result = $link->query($sql);

								if ($result->num_rows > 0) 
								{
									echo "<table id=\"employeeList\">";
									//echo "<tr><th>Nick</th><th>Imię</th><th>Nazwisko</th><th>Adres</th></tr>";

								    while($row = $result->fetch_assoc())
								    {
										echo " <tr> ".
								        	 " <td>" . 
								        	 "<strong>".$row["first_name"]. " ". $row["last_name"]."</strong></br>".
								        	 "      Nick:   ".$row["USERNAME"]."</br>".
								        	 "      Adres:  ".$row["address_for_letters"]."</br>".
								        	 "      Telefon:".$row["telephone"]."</br>".
								        	 "      Email:  ".$row["email_adres"]."</br>".
								        	 "</td>"." </tr>";	 
								    }
								    echo "</table>";

								}
								else 
								{
								    echo "0 results";
								}
							}
							break;

							case "passnagers":
							{
								if(isset($_GET["flyid"]))
								{
									echo '<h2>Pasażerowie wybranego lotu:</h2>';

									$sql = "select p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres 
											from PASSANGERS u right join view_person_onUserId p on p.user_id = u.user_id
											where FLIGHT_ID = ?";
								
									if($stmt = mysqli_prepare($link, $sql))
									{
							            // Bind variables to the prepared statement as parameters
							            mysqli_stmt_bind_param($stmt, "d", $_GET["flyid"]);

							            // Attempt to execute the prepared statement
							            if(mysqli_stmt_execute($stmt))
							            {
											$result = mysqli_stmt_get_result($stmt);

											if ($result->num_rows > 0) 
											{	
												echo "<table id=\"employeeList\">";

											    while($row = $result->fetch_assoc())
											    {
													echo " <tr> ".
											        	 " <td>" . 
											        	 "<strong>".$row["first_name"]. " ". $row["last_name"]."</strong></br>".
											        	 "      Nick:   ".$row["USERNAME"]."</br>".
											        	 "      Adres:  ".$row["address_for_letters"]."</br>".
											        	 "      Telefon:".$row["telephone"]."</br>".
											        	 "      Email:  ".$row["email_adres"]."</br>".
											        	 "</td>"." </tr>";	 
											    }
											    echo "</table>";

											}
											mysqli_stmt_close($stmt);
										}
									}

									echo "<br><br><button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href='index.php?page=passnagers'\">".
										 "Powrót do listy lotów </button>";
								}
								else 
								{
									echo '<h2>Wybierz lot:</h2>';

									$sql = "select	f.flight_id as fid, 
											f.departure_date,
									        (select a.city from airports a where a.airport_id =
												(select START_AIRPORT_ID from flights where FLIGHT_ID = fid)) as start,
									        (select a.city from airports a where a.airport_id =
												(select FINISH_AIRPORT_ID from flights where FLIGHT_ID = fid)) as finish,
											f.TICKET_PRICE
											from	flights f
											order by f.departure_date asc;";

									$result = $link->query($sql);

									if ($result->num_rows > 0) 
									{
									    echo "<table align=\"center\">";
									    echo "<tr><th>Nr Lotu</th><th>Trasa</th><th>Data</th><th>Cena</th></tr>";

									    while($row = $result->fetch_assoc())
									    {
											echo "<tr>".
									       		 " <td align\"center\">" . $row["fid"]."</td>".
									        	 " <td>" . $row["start"]. " --> ". $row["finish"]."</td>".
									        	 " <td align=\"left\">" . $row["departure_date"]."</td>".
									        	 " <td align=\"left\">" . $row["TICKET_PRICE"]."</td>".
												 " <td align=\"left\">".
												 " 		<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href='index.php?page=passnagers&flyid=". $row["fid"]."'\">Pokaż pasazerów</button>".
												 " </td>".
												 " </tr>\n";
									    }

									    echo "</table>";
									}
								}
							}
							break;

							case "":
							{
	
							}
							break;
						}
					}

					else if ($_SESSION["type"] == "ADMIN")
					{
						switch($page)
						{
							case "showflights":
							{
								echo '<h2>Dostępne loty:</h2>';

								//$sql = "SELECT * FROM PLANES";
								// $sql =	"select f.flight_id as fid, f.departure_date,
								// 		(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
								// 		(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
								// 		f.ticket_price
								// 		from flights f
								// 		order by f.departure_date asc;";

								$sql = "select	f.flight_id as fid, 
										f.departure_date,
								        (select a.city from airports a where a.airport_id =
											(select START_AIRPORT_ID from flights where FLIGHT_ID = fid)) as start,
								        (select a.city from airports a where a.airport_id =
											(select FINISH_AIRPORT_ID from flights where FLIGHT_ID = fid)) as finish,
										f.TICKET_PRICE
										from	flights f
										where	f.departure_date > curdate()
										order by f.departure_date asc;";

								$result = $link->query($sql);

								if ($result->num_rows > 0) 
								{
									echo "<table align=\"center\">";

								    while($row = $result->fetch_assoc())
								    {
								    //     echo "<b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
											 // " <b> Skąd:</b>" . $row["start"]. " <b> Dokoąd:</b>" . $row["finish"]. " <b> Cena biletu:</b>" . $row["ticket_price"]. " <br>";

									echo " <tr> ".
							        	 " <td>" . $row["start"]. " --> ". $row["finish"]."</td>".
							        	 " <td align=\"left\"> <b> Data odlotu:</b> " . $row["departure_date"]."</td>".
							        	 " <td align=\"left\"> <b> Cena biletu:</b>" . $row["TICKET_PRICE"]."</td>".
										 " </tr>";
								    }

								    echo "</table>";
								}
								else 
								{
								    echo "0 results";
								}
							}
							break;

							case "employeeinf":
							{
								echo '<h2>Kadra:</h2>';

								$sql = "SELECT p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres
										FROM PERSONS p  right join users u on p.person_id=u.person_id
										WHERE PERMISSION_TYPE =\"EMPLOYEE\";";

								$result = $link->query($sql);

								if ($result->num_rows > 0) 
								{
									echo "<table id=\"employeeList\">";
									//echo "<tr><th>Nick</th><th>Imię</th><th>Nazwisko</th><th>Adres</th></tr>";

								    while($row = $result->fetch_assoc())
								    {
										echo " <tr> ".
								        	 " <td>" . 
								        	 "<strong>".$row["first_name"]. " ". $row["last_name"]."</strong></br>".
								        	 "      Nick:   ".$row["USERNAME"]."</br>".
								        	 "      Adres:  ".$row["address_for_letters"]."</br>".
								        	 "      Telefon:".$row["telephone"]."</br>".
								        	 "      Email:  ".$row["email_adres"]."</br>".
								        	 "</td>"." </tr>";	 
								    }
								    echo "</table>";

								}
								else 
								{
								    echo "0 results";
								}
							}
							break;

							case "passnagers":
							{
								if(isset($_GET["flyid"]))
								{
									echo '<h2>Pasażerowie wybranego lotu:</h2>';

									$sql = "select p.USERNAME, p.first_name, p.last_name, p.address_for_letters, p.telephone, p.email_adres 
											from PASSANGERS u right join view_person_onUserId p on p.user_id = u.user_id
											where FLIGHT_ID = ?";
								
									if($stmt = mysqli_prepare($link, $sql))
									{
							            // Bind variables to the prepared statement as parameters
							            mysqli_stmt_bind_param($stmt, "d", $_GET["flyid"]);

							            // Attempt to execute the prepared statement
							            if(mysqli_stmt_execute($stmt))
							            {
											$result = mysqli_stmt_get_result($stmt);

											if ($result->num_rows > 0) 
											{	
												echo "<table id=\"employeeList\">";

											    while($row = $result->fetch_assoc())
											    {
													echo " <tr> ".
											        	 " <td>" . 
											        	 "<strong>".$row["first_name"]. " ". $row["last_name"]."</strong></br>".
											        	 "      Nick:   ".$row["USERNAME"]."</br>".
											        	 "      Adres:  ".$row["address_for_letters"]."</br>".
											        	 "      Telefon:".$row["telephone"]."</br>".
											        	 "      Email:  ".$row["email_adres"]."</br>".
											        	 "</td>"." </tr>";	 
											    }
											    echo "</table>";

											}
											mysqli_stmt_close($stmt);
										}
									}

									echo "<br><br><button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href='index.php?page=passnagers'\">".
										 "Powrót do listy lotów </button>";
								}
								else 
								{
									echo '<h2>Wybierz lot:</h2>';

									$sql = "select	f.flight_id as fid, 
											f.departure_date,
									        (select a.city from airports a where a.airport_id =
												(select START_AIRPORT_ID from flights where FLIGHT_ID = fid)) as start,
									        (select a.city from airports a where a.airport_id =
												(select FINISH_AIRPORT_ID from flights where FLIGHT_ID = fid)) as finish,
											f.TICKET_PRICE
											from	flights f
											order by f.departure_date asc;";

									$result = $link->query($sql);

									if ($result->num_rows > 0) 
									{
									    echo "<table align=\"center\">";
									    echo "<tr><th>Nr Lotu</th><th>Trasa</th><th>Data</th><th>Cena</th></tr>";

									    while($row = $result->fetch_assoc())
									    {
											echo "<tr>".
									       		 " <td align\"center\">" . $row["fid"]."</td>".
									        	 " <td>" . $row["start"]. " --> ". $row["finish"]."</td>".
									        	 " <td align=\"left\">" . $row["departure_date"]."</td>".
									        	 " <td align=\"left\">" . $row["TICKET_PRICE"]."</td>".
												 " <td align=\"left\">".
												 " 		<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href='index.php?page=passnagers&flyid=". $row["fid"]."'\">Pokaż pasazerów</button>".
												 " </td>".
												 " </tr>\n";
									    }

									    echo "</table>";
									}
								}
							}
							break;
			
							case "findconnection":
							{
								if(isset($_GET["start"]) && isset($_GET["destination"])  )
								{
									$my_start = htmlspecialchars($_GET["start"]);
									$my_destination = htmlspecialchars($_GET["destination"]);
								}
								else
								{
									$my_start = NULL;
									$my_destination = NULL;
								}
								
								echo " <form action=\"index.php\" method=\"get\">";

								echo " <div> <label class=\"registerLabel\">Skad: </label> <input type=\"text\" name=\"start\" class=\"form-control\"> </div>";
								
								echo " <div> <label class=\"registerLabel\">Dokąd: </label> <input type=\"text\" name=\"destination\" class=\"form-control\"> </div>";

								echo " <div> <input type=\"submit\" class=\"btn btn-primary\" value=\"Szukaj\"> </div>";

								echo " <input type=\"hidden\" name=\"page\" value=\"findconnection\" />";

								echo " </form>";

								echo " </br></br></br>";

								if ($my_destination != NULL and $my_start != NULL)
								{
								    // Prepare a select statement
		    						$sql = "select * from (select f.flight_id as fid, f.departure_date,
											(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as fstart,
											(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as ffinish,
											f.ticket_price
											from flights f
											order by f.departure_date asc) as t
											where fstart = ? and ffinish = ?;";

									if($stmt = mysqli_prepare($link, $sql))
									{
							            // Bind variables to the prepared statement as parameters
							            mysqli_stmt_bind_param($stmt, "ss", $my_start, $my_destination);
							            

							            // Attempt to execute the prepared statement
							            if(mysqli_stmt_execute($stmt))
							            {
											
											$result = mysqli_stmt_get_result($stmt);

										    while($row = $result->fetch_assoc())
										    {
										        echo "<a href=\"index.php?page=buyticket&flyid=". $row["fid"]." \"> <b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
													 " <b> Skąd:</b>" . $row["fstart"]. " <b> Dokoąd:</b>" . $row["ffinish"]. " <b> Cena biletu:</b>" . $row["ticket_price"]. "</a> <br>";
										    }
							            } 
							            else
							            {
											echo "Something went wrong. Please try again later.";
							            }

										mysqli_stmt_close($stmt);
						            }
								}
							}
							break;

							case "buyticket":
							{
								if(isset($_GET["flyid"]))
								{
									$my_flyid = htmlspecialchars($_GET["flyid"]);
															
									$sql =	"select f.flight_id as fid, f.departure_date,
											(select a.city from airports a right join flights f on a.airport_id=f.start_airport_id where f.flight_id = fid limit 1) as start,
											(select a.city from airports a right join flights f on a.airport_id=f.finish_airport_id  where f.flight_id = fid limit 1) as finish,
											f.ticket_price
											from flights f
											where f.flight_id = ?
											order by f.departure_date asc;";

									if($stmt = mysqli_prepare($link, $sql))
									{
							            // Bind variables to the prepared statement as parameters
							            mysqli_stmt_bind_param($stmt, "d", $my_flyid);

							            // Attempt to execute the prepared statement
							            if(mysqli_stmt_execute($stmt))
							            {
											
											$result = mysqli_stmt_get_result($stmt);

											if ($result->num_rows > 0) 
											{	
												echo " <h2> Wybrano lot: </h2>";
											    // output data of each row
											    while($row = $result->fetch_assoc())
											    {
											        echo "<a href=\"index.php?page=buyticket&flyid=". $row["fid"]." \"><b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
														 " <b> Skąd:</b>" . $row["start"]. " <b> Dokoąd:</b>" . $row["finish"]. " <b> Cena biletu:</b>" . $row["ticket_price"]. "</a> <br>";
											    }
											}
										}
										mysqli_stmt_close($stmt);
									}

									echo " </br></br></br>";
								}
								else
								{
									$my_flyid = NULL;
								}

								echo " <form action=\"bookTicket.php\" method=\"post\" >";

								echo " <input type=\"hidden\" name=\"user_name\" value=\"".$_SESSION["username"]."\" />";
									
								if ($my_flyid == NULL)
								{
									echo " <div> <label class=\"registerLabel\">Numer lotu: </label> <input type=\"text\" name=\"flyid\" value=\"".$my_flyid." \" class=\"form-control\"> </div>";
								}
								else
								{
									echo " <div> <input type=\"hidden\" name=\"flyid\" value=\"".$my_flyid." \" class=\"form-control\"> </div>";
								}

								echo " <div> <label class=\"registerLabel\">Waga bagażu: </label> <input type=\"number\" min=\"0\" step=\"any\" name=\"weight\" value=\"0\" class=\"form-control\"> kg </div>";

								echo " <input type=\"hidden\" name=\"peyed\" value=\"false\" />";

								echo " <div> <input type=\"submit\" class=\"btn btn-primary\" value=\"Rezerwuj\"> </div>";

								echo " </form>";

								echo " </br></br></br>";							
							}
							break;

							case "payforticket":
							case "cancelation":
							{
								if(isset($_GET["action"]))
								{
									if (htmlspecialchars($_GET["action"]) == "success")
									{
										echo "<h1> Operacja zakończyłą się pomyślnie.</h1>";
									}
									else if (htmlspecialchars($_GET["action"]) == "refund")
									{
										echo "<h1> Operacja zakończyłą się pomyślnie.</h1>";
										echo "<h1> Pieniądze zostaną zwrócone na twoje konto.</h1>";
									}
									else if (htmlspecialchars($_GET["action"]) == "block")
									{
										echo "<h1> Możesz kupić tylko jeden bilet na dany lot.</h1>";
									}
									else 
									{
										echo "<h1> Operacja zakończyła się niepowodzeniem.</h1>";
									}
								}
								
								echo "<br/><h1> Twoje loty:</h1>";

								if(isset($_SESSION["loggedin"]) and $_SESSION["loggedin"] == true)
								{
									$sql =  "select b.passanger_id, b.flight_id as fid, b.user_id, b.departure_date, b.ticket_price, b.payed,
											(select a.city from airports a right join booked b on a.airport_id=b.start_airport_id where b.flight_id = fid limit 1) as start,
											(select a.city from airports a right join booked b on a.airport_id=b.finish_airport_id  where b.flight_id = fid limit 1) as finish
											 from booked b
											 where user_id =  (select user_id 
											 				   from (select u.user_id, p.username from users u right join persons p on u.person_id=p.person_id) as Q 
											 				   where username = ?);";

									if($stmt = mysqli_prepare($link, $sql))
									{
							            // Bind variables to the prepared statement as parameters
							            mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]);
							            

							            // Attempt to execute the prepared statement
							            if(mysqli_stmt_execute($stmt))
							            {
											
											$result = mysqli_stmt_get_result($stmt);
											
											echo " <table align=\"center\">";
											echo "<tr><th>Trasa</th><th>Data</th><th>Cena</th></tr>";
											
										    while($row = $result->fetch_assoc())
										    {
										    	if ($row["payed"] == 0)
										    	{
													// echo " <b>ID lotu: </b>" . $row["fid"]. " - <b> Data odlotu:</b> " . $row["departure_date"].
													// 	 " <b> Skąd:</b>" . $row["start"]. " <b> Dokoąd:</b>" . $row["finish"]. " <b> Cena biletu:</b>" . $row["ticket_price"].
													// 	 " <input type=\"hidden\" name=\"id\" value=\"". $row["fid"]."\" />".
													// 	 " <input type=\"hidden\" name=\"username\" value=\"".  $_SESSION["username"]."\" />".
													// 	 " <input type=\"hidden\" name=\"passangerid\" value=\"".  $row["passanger_id"]."\" />".
													// 	 " <button onclick=\"window.location.href='deleteFlight.php?id=". $row["fid"]."&username=".$_SESSION["username"]."'\"> Rezygnuj </button>".
													// 	 " <button onclick=\"window.location.href='payForTicket.php?id=". $row["fid"]."&username=".$_SESSION["username"]."'\"> Zapłać </button>".
													// 	 "<label>".$row["payed"]."</label>".
													// 	 //" <input type=\"submit\" class=\"btn btn-primary\" value=\"Usun Rezerwacje\">".
													// 	 //" <input type=\"submit\" class=\"btn btn-primary\" value=\"Zapłać\"> </br>";
													//   //<a id=\"a_button\" href=\"index.php?dawid=tak\">button</a> <br>";
													//   	 " <br/>";

													echo " <tr> ".
														 " <td>". $row["start"]. " --> " . $row["finish"]."</td>".
														 " <td align=\"left\">" . $row["departure_date"]."</td>".
														 " <td align=\"left\">" . $row["ticket_price"]."</td>".
														 " <td align=\"left\">".
														 " <form action=\"payForTicket.php\" method=\"get\">".
														 " <input type=\"hidden\" name=\"id\" value=\"". $row["fid"]."\" />".
														 " <input type=\"hidden\" name=\"username\" value=\"".  $_SESSION["username"]."\" />".
														 " <input type=\"hidden\" name=\"passangerid\" value=\"".  $row["passanger_id"]."\" />".
														 " <button class=\"btn btn-primary\" onclick=\"window.location.href='deleteFlight.php\" > Rezygnuj </button>".
														 " <button class=\"btn btn-primary\" onclick=\"window.location.href='payForTicket.php\"> Zapłać </button>".
													     " </form>".
								        	 			 " </td>".
								        	 			 " </tr> ";
										    	}
										    	else
										    	{
													echo// " <b>ID lotu: </b>" . $row["fid"].
														 " <tr> ".
								        	 			 " <td>". $row["start"]. " --> " . $row["finish"] ."</td>".
														 " <td align=\"left\">" . $row["departure_date"]."</td>".
														 " <td align=\"left\">" . $row["ticket_price"].
														 " <input type=\"hidden\" name=\"id\" value=\"". $row["fid"]."\" />".
														 " <input type=\"hidden\" name=\"username\" value=\"".  $_SESSION["username"]."\" />"."</td>".
														 " <td align=\"left\">".
														 " 		<button class=\"btn btn-primary\" onclick=\"window.location.href='deleteFlight.php?id=". $row["fid"]."&username=".$_SESSION["username"]."'\">".
														 "			 Rezygnuj".
														 " 		</button>".
														 " 		<label class=\"peyedLabel\">Bilet opłacony</label>".
														 " </tr> ".
								        	 			 " </td>";
										    	}
										       
										    }

										    echo "</table>";

							            } 
							            else
							            {
											echo "Something went wrong. Please try again later.";
							            }

										mysqli_stmt_close($stmt);
						            }
								}
							}
							break;
						}
					}
				}
				else 
				{
					echo '<h2>Dostępne loty:</h2>';

					$sql = "select	f.flight_id as fid, 
							f.departure_date,
					        (select a.city from airports a where a.airport_id =
								(select START_AIRPORT_ID from flights where FLIGHT_ID = fid)) as start,
					        (select a.city from airports a where a.airport_id =
								(select FINISH_AIRPORT_ID from flights where FLIGHT_ID = fid)) as finish,
							f.TICKET_PRICE
							from	flights f
							where	f.departure_date > curdate()
							order by f.departure_date asc;";

					$result = $link->query($sql);

					if ($result->num_rows > 0) 
					{
						echo "<table align=\"center\">";

					    while($row = $result->fetch_assoc())
					    {

						echo " <tr> ".
				        	 " <td>" . $row["start"]. " --> ". $row["finish"]."</td>".
				        	 " <td align=\"left\"> <b> Data odlotu:</b> " . $row["departure_date"]."</td>".
				        	 " <td align=\"left\"> <b> Cena biletu:</b>" . $row["TICKET_PRICE"]."</td>".
							 " </tr>";
					    }

					    echo "</table>";
					}
					else 
					{
					    echo "0 results";
					}
				}
			?>
		</div>
	</div>
</body>
</html>
