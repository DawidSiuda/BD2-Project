
<?php
	if ($_SESSION["type"] == "CLIENT")
	{
		echo "<ul id=\"listmenu\">
				<li><a href=\"index.php?page=showflights\">Przeglądaj loty</a></li>
				<li><a href=\"index.php?page=findconnection\">Znajdz połączenie</a></li>
				<li><a href=\"index.php?page=buyticket\">Kup bilet</a></li>
				<li><a href=\"index.php?page=cancelation\">Zarządzaj biletami</a></li>
			 </ul>";
	}
	else if ($_SESSION["type"] == "EMPLOYEE")
	{
		echo "<ul id=\"listmenu\">
				<li><a href=\"index.php?page=showflights\">Przeglądaj loty</a></li>
				<li><a href=\"index.php?page=employeeinf\">Dane osobowe kadry</a></li>
				<li><a href=\"index.php?page=passnagers\">Pasażerowie</a></li>
			 </ul>";
	}
	else if ($_SESSION["type"] == "ADMIN")
	{			
		echo "<ul id=\"listmenu\">
				<li><a href=\"index.php?page=showflights\">Przeglądaj loty</a></li>
				<li><a href=\"index.php?page=findconnection\">Znajdz połączenie</a></li>
				<li><a href=\"index.php?page=buyticket\">Kup bilet</a></li>
				<li><a href=\"index.php?page=cancelation\">Zarządzaj swoimi biletami</a></li>
				<li><a href=\"index.php?page=employeeinf\">Dane osobowe kadry</a></li>
				<li><a href=\"index.php?page=passnagers\">Pasażerowie</a></li>
			 </ul>";

	}
?>
