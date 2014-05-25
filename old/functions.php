<?php

function connect(){
    $con = mysql_connect("localhost", "root", "") or die(mysql_error());
    $db = mysql_select_db("eastword", $con);
}

function CleanString($string){
    $string = mysql_real_escape_string($string);
    $string = htmlentities($string);
    $string = addslashes($string);
    $string = trim($string);
    return $string;
}

function MessageCode($value) {
    if($value == 0){
        echo "<div class=\"blank\" align=\"center\">\n";
        echo "</div>";
    }
    echo "<div class=\"errorcode\" align=\"center\">\n";
    if($value == 1) {
        echo "The password does not match the username. Try Again!\n";
        
    } elseif ($value == 2) {
        echo "The username you entered does not exist!\n";
    }
    echo "</div>";
}

function Login($value) {
    echo MessageCode($value);
    echo "<br />";
    echo "<form action=\"index.php\" method=\"POST\">\n";
    echo "<table class=\"table\" align=\"center\" border=\"0\" width=\"300\">\n";
    echo "<tr><td colspan=\"2\"><h2>Please Login</h2></td></tr>\n";
    echo "<tr><td>Username:</td><td><input class=\"input\" type=\"text\" name=\"username\" length=\"50\"></td></tr>\n";
    echo "<tr><td>Password:</td><td><input class=\"input\" type=\"password\" name=\"password\" length=\"50\"></td></tr>\n";
    echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\"Login\" name=\"login_submit\"></td></tr>\n";
    echo "</table>";
}

function CheckLogin($Username, $Password) {
	$UserExists = "SELECT * FROM `radian_users` WHERE username='" . $Username . "'";
	$QueryUserExists = mysql_query($UserExists);
	if(mysql_num_rows($QueryUserExists) > 0) {
		$UserAndPassCorrect = "SELECT * FROM `radian_users` WHERE username='" . $Username . "' AND password ='" . $Password . "'";
		$QueryUserAndPassCorrect = mysql_query($UserAndPassCorrect);
		if(mysql_num_rows($QueryUserAndPassCorrect) > 0) {
			$UserRecord = mysql_fetch_assoc($QueryUserAndPassCorrect);
			$_SESSION['radian_user_id'] = $UserRecord['id'];
			$_SESSION['radian_user_alias'] = $UserRecord['user_alias'];
			header("Location: index.php");
		} else {
			Login(1);
		}
	} else {
		Login(2);
	}	
}

function Logout() {
	unset($_SESSION['radian_user_id']);
	unset($_SESSION['radian_user_alias']);
	session_destroy();
	echo "<div class=\"successcode\" align=\"center\">You have been successfully logged out!\n";
	echo "<br /><a href=\"index.php\">Continue</a></div>\n";
}

function Navigation($page) {
	$selected[0] = "";
	$selected[1] = "";
	$selected[2] = "";
	$selected[3] = "";
	$selected[4] = "";
	$selected[5] = "";
	if($page == 'home') {
		$selected[0] = " class=\"selected\"";
	} elseif($page == 'view') {
		$selected[1] = " class=\"selected\"";
	} elseif($page == 'add' || $page == 'ap') {
		$selected[2] = " class=\"selected\"";
	} elseif($page == 'edit' || $page == 'ep') {
		$selected[3] = " class=\"selected\"";
	} elseif($page == 'search' || $page == 'results') {
		$selected[4] = " class=\"selected\"";
	} elseif($page == 'backup') {
		$selected[5] = " class=\"selected\"";
	}
	echo "
	<ul class=\"navtab\">
	<li" . $selected[0] . "><a href=\"index.php?page=home\">Home</a></li>
	<li" . $selected[1] . "><a href=\"index.php?page=view\">View All Jobs</a></li>
	<li" . $selected[2] . "><a href=\"index.php?page=add\">Add Job</a></li>
	<li" . $selected[3] . "><a href=\"index.php?page=edit\">Edit Job</a></li>
	<li" . $selected[4] . "><a href=\"index.php?page=search\">Search</a></li>
	<li" . $selected[5] . "><a href=\"index.php?page=backup\">Backup</a></li>
	</ul>";
}

function Search($query, $field) {
	$query = CleanString($query);
	$field = CleanString($field);
	$SearchJobsTable = "SELECT * FROM radian_jobs WHERE " . $field . " LIKE '%" . $query . "%'";
	$QuerySearchJobsTable = mysql_query($SearchJobsTable);
	if(mysql_num_rows($QuerySearchJobsTable) > 0) {
		echo "<tr><td><h3>Search Results</h3></td></tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
			echo "<tr><td>You searched for <span style=\"color:red; font-weight: bold;\">" . $query . "</span> in the <span style=\"color:red; font-weight: bold;\">" . $field . "</span> field.</td></tr>";
			echo "<tr><td><table class=\"listtable\" border=\"1\" width=\"970px\" align=\"center\">\n";
			echo "<tr style=\"font-weight: bold;\"><td width=\"50px\">Job No.</td><td>Lang</td><td width=\"150px\">Client</td><td width=\"70px\">Date</td><td>Description</td><td width=\"25px\">TR</td><td width=\"25px\">I/P</td><td width=\"25px\">P/M</td><td width=\"70px\">Invoice No.</td><td>Job Done By</td><td>B/I</td><td width=\"70px\">Date Due</td><td width=\"80px\">Word Count</td><td width=\"30px\">Done</td></tr>";
		while($SearchResult = mysql_fetch_assoc($QuerySearchJobsTable)) {
			if($SearchResult['translation'] == 1) {
				$SearchResultTranslation = "<img src=\"images/tick.png\">";
			} else {
				$SearchResultTranslation = "";
			}
			if($SearchResult['input'] == 1) {
				$SearchResultInput = "<img src=\"images/tick.png\">";
			} else {
				$SearchResultInput = "";
			}
			if($SearchResult['pagemakeup'] == 1) {
				$SearchResultPageMakeup = "<img src=\"images/tick.png\">";
			} else {
				$SearchResultPageMakeup = "";
			}
			if($SearchResult['done'] == 1) {
				$SearchResultDone = "<img src=\"images/tick.png\">";
			} else {
				$SearchResultDone = "";
			}
			if($SearchResult['bi'] == 1) {
				$SearchResultBI = "<img src=\"images/tick.png\">";
			} else {
				$SearchResultBI = "";
			}
			echo "<tr><td><a href=\"index.php?page=edit&id=" . $SearchResult['jobnumber'] . "\">" . $SearchResult['jobnumber'] . "</a></td><td>" . str_replace(";", "<br />", $SearchResult['language']) . "</td><td>" . $SearchResult['client'] . "</td><td>" . $SearchResult['date'] . "</td><td>" . $SearchResult['description'] . "</td><td>" . $SearchResultTranslation . "</td><td>" . $SearchResultInput . "</td><td>" . $SearchResultPageMakeup . "</td><td>" . $SearchResult['invoicenumber'] . "</td><td valign=\"top\">" . str_replace(";", "<br />", $SearchResult['jobdoneby']) . "</td><td>" . $SearchResultBI . "</td><td>" . $SearchResult['datedue'] . "</td><td>" . $SearchResult['wordcount'] . "</td><td>" . $SearchResultDone . "</td></tr>\n";
		}
		echo "</table>\n";
		echo "</td></tr>\n";
	} else {
		echo "There are no jobs that have those details in the database.";
	}
}
?>