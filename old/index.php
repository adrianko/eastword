<?php

ob_start();

session_start();
include "functions.php";
connect();
$_SESSION['radian_user_id'] = 1;
if(isset($_GET['page'])) {
	$page = CleanString($_GET['page']);
} else {
	$page = "";
}
echo "<html>\n";
echo "<head>\n";
echo "<title>Radian Project Management</title>\n";
echo "<link href=\"includes/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
echo "</head>\n"; 
echo "<body>\n";
echo "<table class=\"table\" width=\"980px\" border=\"0\" align=\"center\">\n";
echo "<tr><td><a href=\"index.php\"><img src=\"images/ewlogo.jpg\" width=\"100\" height=\"90\"></a></td>\n";
echo "<td align=\"right\"><img src=\"images/ewlogotext.jpg\"></td></tr>\n";
echo "<tr><td colspan=\"2\" align=\"right\">";
if(isset($_SESSION['radian_user_id'])) {
	echo "Welcome";
}
echo "</table>\n";
if(!isset($_SESSION['radian_user_id'])) {
	echo "&nbsp;";
} else {
	if($page == 'view') {
		Navigation('view');
	} elseif($page == 'add') {
		Navigation('add');
	} elseif($page == 'edit') {
		Navigation('edit');
	} elseif($page == 'ap') {
		Navigation('add');
	} elseif($page == 'ep') {
		Navigation('edit');
	} elseif($page == 'search') {
		Navigation('search');
	} elseif($page == 'results') {
		Navigation('results');
	} elseif($page == 'backup') {
		Navigation('backup');
	} else {
		Navigation('home');
	}
}
echo "<div class=\"border\">";
echo "<table class=\"table\" width=\"980px\" border=\"0\" align=\"center\">\n";
if(isset($_GET['mode']) == 'logout'){
	Logout();
} 
if(!isset($_SESSION['radian_user_id'])) {
	if(isset($_POST['login_submit'])) {
		$Username = CleanString($_POST['username']);
		$Password = $_POST['password'];
		CheckLogin($Username, $Password);	
	} else {
		Login(0);
	}
} else {
	if($page == 'view') { //View All Jobs
		echo "<tr><td><h3>View All Jobs</h3></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td><table class=\"listtable\" border=\"1\" width=\"970px\" align=\"center\">\n";
		echo "<tr style=\"font-weight: bold;\"><td width=\"50px\">Job No.</td><td>Lang</td><td width=\"150px\">Client</td><td width=\"70px\">Date</td><td>Description</td><td width=\"25px\">TR</td><td width=\"25px\">I/P</td><td width=\"25px\">P/M</td><td width=\"70px\">Invoice No.</td><td>Job Done By</td><td>B/I</td><td width=\"70px\">Date Due</td><td width=\"80px\">Word Count</td><td width=\"30px\">Done</td></tr>";
		$GetAllFromJobTable = "SELECT * FROM radian_jobs ORDER BY jobnumber DESC";
		$QueryGetAllFromJobTable = mysql_query($GetAllFromJobTable);
		$i = 0;
		while($RadianJobsAll = mysql_fetch_assoc($QueryGetAllFromJobTable)){
			if($RadianJobsAll['translation'] == 1) {
				$RadianJobsAllTranslation = "<img src=\"images/tick.png\">";
			} else {
				$RadianJobsAllTranslation = "";
			}
			if($RadianJobsAll['input'] == 1) {
				$RadianJobsAllInput = "<img src=\"images/tick.png\">";
			} else {
				$RadianJobsAllInput = "";
			}
			if($RadianJobsAll['pagemakeup'] == 1) {
				$RadianJobsAllPageMakeup = "<img src=\"images/tick.png\">";
			} else {
				$RadianJobsAllPageMakeup = "";
			}
			if($RadianJobsAll['done'] == 1) {
				$RadianJobsAllDone = "<img src=\"images/tick.png\">";
			} else {
				$RadianJobsAllDone = "";
			}
			if($RadianJobsAll['bi'] == 1) {
				$RadianJobsAllBI = "<img src=\"images/tick.png\">";
			} else {
				$RadianJobsAllBI = "";
			}
			if($i == 0) {
				$oldmonth = $newmonth = substr($RadianJobsAll['date'], 3, 2);
				$i++;
			} else  {
				$newmonth = substr($RadianJobsAll['date'], 3, 2);
			}
			if($newmonth != $oldmonth) {
				echo "<tr style=\"border-top: 1px solid #ff0000;\">";
			} else {
				echo "<tr>";
			}
			$oldmonth = substr($RadianJobsAll['date'], 3, 2);
			echo "<td><a href=\"index.php?page=edit&id=" . $RadianJobsAll['jobnumber'] . "\">" . $RadianJobsAll['jobnumber'] . "</a></td><td>" . strtoupper(str_replace(";", "<br />", $RadianJobsAll['language'])) . "</td><td>" . $RadianJobsAll['client'] . "</td><td>" . $RadianJobsAll['date'] . "</td><td>" . $RadianJobsAll['description'] . "</td><td>" . $RadianJobsAllTranslation . "</td><td>" . $RadianJobsAllInput . "</td><td>" . $RadianJobsAllPageMakeup . "</td><td>" . str_replace(";", "<br />", $RadianJobsAll['invoicenumber']) . "</td><td valign=\"top\">" . ucfirst(str_replace(";", "<br />", $RadianJobsAll['jobdoneby'])) . "</td><td>" . $RadianJobsAllBI . "</td><td>" . $RadianJobsAll['datedue'] . "</td><td>" . $RadianJobsAll['wordcount'] . "</td><td>" . $RadianJobsAllDone . "</td></tr>\n";
		}
		echo "</table>\n";
		echo "</td></tr>\n";
	} elseif($page == 'add') { //Add Job
		echo "<tr><td><h3>Add Job</h3></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<form action=\"index.php?page=ap\" method=\"POST\">";
		echo "<tr><td><table class=\"table\" border=\"0\">\n";
		echo "<tr><td>Language</td><td><input class=\"input\" type=\"text\" name=\"language\"></td></tr>\n";
		echo "<tr><td>Client</td><td><input class=\"input\" type=\"text\" name=\"client\"></td></tr>\n";
		echo "<tr><td>Description</td><td><textarea class=\"input\" name=\"description\" rows=\"3\"></textarea></td></tr>\n";
		echo "<tr><td>Translation</td><td><input class=\"input\" type=\"checkbox\" name=\"translation\"></td></tr>\n";
		echo "<tr><td>Input</td><td><input class=\"input\" type=\"checkbox\" name=\"input\"></td></tr>\n";
		echo "<tr><td>Page Makeup</td><td><input class=\"input\" type=\"checkbox\" name=\"pagemakeup\"></td></tr>\n";
		echo "<tr><td>Invoice Number</td><td><input class=\"input\" type=\"text\" name=\"invoicenumber\"></td></tr>\n";
		echo "<tr><td>Job Done By</td><td><input class=\"input\" type=\"text\" name=\"jobdoneby\"></td></tr>\n";
		echo "<tr><td>Being Invoiced</td><td><input class=\"input\" type=\"checkbox\" name=\"bi\"></td></tr>\n";
		echo "<tr><td>Date Due</td><td><input class=\"input\" type=\"text\" name=\"datedue\"></td></tr>\n";
		echo "<tr><td>Word Count</td><td><input class=\"input\" type=\"text\" name=\"wordcount\"></td></tr>\n";
		echo "<tr><td><input type=\"submit\" value=\"Add Job\" name=\"add_job_submit\" class=\"buttons\"></td></tr>";
		echo "</table>\n";
		echo "</td></tr>\n";
		echo "</form>";
	} elseif($page == 'edit') { //Edit Job
		if(!$_GET['id']) {
			header("Location: index.php?page=view");
		} else {
			$id = $_GET['id'];
			$GetDataToEditFromRadianJobs = "SELECT * FROM radian_jobs WHERE jobnumber='" . $id . "'";
			$QueryGetDataToEditFromRadianJobs = mysql_query($GetDataToEditFromRadianJobs);
			$QueryDataToEdit = mysql_fetch_assoc($QueryGetDataToEditFromRadianJobs); 
			$QueryDataToEditTranslation = $QueryDataToEditInput = $QueryDataToEditPageMakeup = $QueryDataToEditDone = $QueryDataToEditBI = "";
			if($QueryDataToEdit['translation'] == 1) {
				$QueryDataToEditTranslation = "checked=\"checked\" ";
			}
			if($QueryDataToEdit['input'] == 1) {
				$QueryDataToEditInput = "checked=\"checked\" ";
			}
			if($QueryDataToEdit['pagemakeup'] == 1) {
				$QueryDataToEditPageMakeup = "checked=\"checked\" ";
			}
			if($QueryDataToEdit['done'] == 1) {
				$QueryDataToEditDone = "checked=\"checked\" ";
			}
			if($QueryDataToEdit['bi'] == 1) {
				$QueryDataToEditBI = "checked=\"checked\" "; 
			}
			echo "<tr><td><h3>Edit Job</h3></td></tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
			echo "<form action=\"index.php?page=ep\" method=\"POST\">";
			echo "<tr><td><table class=\"table\" border=\"0\">\n";
			echo "<tr><td>Language</td><td><input class=\"input\" type=\"text\" name=\"language\" value=\"" . $QueryDataToEdit['language'] . "\"></td></tr>\n";
			echo "<tr><td>Client</td><td><input class=\"input\" type=\"text\" name=\"client\" value=\"" . $QueryDataToEdit['client'] . "\"></td></tr>\n";
			echo "<tr><td>Description</td><td><textarea class=\"input\" name=\"description\" rows=\"3\">" . $QueryDataToEdit['description'] . "</textarea></td></tr>\n";
			echo "<tr><td>Translation</td><td><input class=\"input\" type=\"checkbox\" " . $QueryDataToEditTranslation . "name=\"translation\"></td></tr>\n";
			echo "<tr><td>Input</td><td><input class=\"input\" type=\"checkbox\" " . $QueryDataToEditInput . "name=\"input\"></td></tr>\n";
			echo "<tr><td>Page Makeup</td><td><input class=\"input\" type=\"checkbox\" " . $QueryDataToEditPageMakeup . "name=\"pagemakeup\"></td></tr>\n";
			echo "<tr><td>Invoice Number</td><td><input class=\"input\" type=\"text\" name=\"invoicenumber\" value=\"" . $QueryDataToEdit['invoicenumber'] . "\"></td></tr>\n";
			echo "<tr><td>Job Done By</td><td><input class=\"input\" type=\"text\" name=\"jobdoneby\" value=\"" . $QueryDataToEdit['jobdoneby'] . "\"></td></tr>\n";
			echo "<tr><td>Being Invoiced</td><td><input class=\"input\" type=\"checkbox\" " . $QueryDataToEditBI . "name=\"bi\"></td></tr>\n";			
			echo "<tr><td>Date Due</td><td><input class=\"input\" type=\"text\" name=\"datedue\" value=\"" . $QueryDataToEdit['datedue'] . "\"></td></tr>\n";
			echo "<tr><td>Word Count</td><td><input class=\"input\" type=\"text\" name=\"wordcount\" value=\"" . $QueryDataToEdit['wordcount'] . "\"></td></tr>\n";
			echo "<tr><td>Done</td><td><input class=\"input\" type=\"checkbox\" " . $QueryDataToEditDone . "name=\"done\"></td></tr>\n";
			echo "<tr><td><input type=\"submit\" value=\"Edit Job\" name=\"edit_job_submit\" class=\"buttons\"></td></tr>";
			echo "</table>\n";
			echo "<input type=\"hidden\" name=\"jobnumber\" value=\"" . $QueryDataToEdit['jobnumber'] . "\">";
			echo "</td></tr>\n";
			echo "</form>";
		}
	} elseif($page == 'ap') {
		$language = $client = $description = $jobdoneby = $translation = $input = $pagemakeup = $date = $bi = $datedue = $invoicenumber = $wordcount = $jobnumber = $done = "";
		if(isset($_POST['language'])) {
			$language = CleanString($_POST['language']);
		}
		if(isset($_POST['client'])) {
			$client = CleanString($_POST['client']);
		}
		if(isset($_POST['description'])) {
			$description = CleanString($_POST['description']);
		}
		if(isset($_POST['jobdoneby'])) {
			$jobdoneby = CleanString($_POST['jobdoneby']);
		}
		if(isset($_POST['translation'])) {
			$translation = CleanString($_POST['translation']);
		}
		if(isset($_POST['input'])) {
			$input = CleanString($_POST['input']);
		}
		if(isset($_POST['pagemakeup'])) {
			$pagemakeup = CleanString($_POST['pagemakeup']);
		}
		$date = date("d/m/Y", time());
		if(isset($_POST['bi'])) {
			$bi = CleanString($_POST['bi']);
		}
		if(isset($_POST['datedue'])) {
			$datedue = CleanString($_POST['datedue']);
		}
		if(isset($_POST['invoicenumber'])) {
			$invoicenumber = CleanString($_POST['invoicenumber']);
		}
		if(isset($_POST['wordcount'])) {
			$wordcount = CleanString($_POST['wordcount']);
		}
		if(isset($_POST['jobnumber'])) {
			$jobnumber = $_POST['jobnumber'];
		}
		$errors = array();
        if($language == "") {
            $errors[] = "You have not specified a language. What will this job be translated into?";
        }
		if($client == "") {
			$errors[] = "You have not specified a client. Who is it for?";
		}
		if($description == "") {
			$errors[] = "You have not specified a description. What is this job about?";
		}
		if($jobdoneby == "") {
			$errors[] = "You have not specified who will do the job. Who is doing this job?";
		}
		if(count($errors) > 0){
			echo "<div class=\"errorcode\" align=\"center\">\n";
            foreach($errors AS $error){
                echo $error . "<br>\n";
            }
			echo "<a href=\"javascript:history.go(-1)\">Back</a>";
			echo "</div>";
        } else {
			if($translation == 'on') {
				$translation = "1";
			} else {
				$translation = "0";
			}
			if($input == 'on') {
				$input = "1";
			} else {
				$input = "0";
			}
			if($pagemakeup == 'on') {
				$pagemakeup = "1";
			} else {
				$pagemakeup = "0";
			}
			if($bi == 'on') {
				$bi = "1";
			} else {
				$bi = "0";
			}
			$AddToRadianJobsTable = "INSERT INTO radian_jobs (language, client, date, description, translation, input, pagemakeup, invoicenumber, jobdoneby, bi, datedue, wordcount)	VALUES ('" . $language . "', '" . $client . "', '" . $date . "', '" . $description . "', '" . $translation . "', '" . $input . "', '" . $pagemakeup . "', '" . $invoicenumber . "', '" . $jobdoneby . "', '" . $bi . "', '" . $datedue . "', '" . $wordcount . "')";
            mysql_query($AddToRadianJobsTable);
			header("Location: index.php?page=view");
			
        }
	
	} elseif($page == 'ep') {
		$language = $client = $description = $jobdoneby = $translation = $input = $pagemakeup = $date = $bi = $datedue = $invoicenumber = $wordcount = $jobnumber = $done = "";
		if(isset($_POST['language'])) {
			$language = CleanString($_POST['language']);
		}
		if(isset($_POST['client'])) {
			$client = CleanString($_POST['client']);
		}
		if(isset($_POST['description'])) {
			$description = CleanString($_POST['description']);
		}
		if(isset($_POST['jobdoneby'])) {
			$jobdoneby = CleanString($_POST['jobdoneby']);
		}
		if(isset($_POST['translation'])) {
			$translation = CleanString($_POST['translation']);
		}
		if(isset($_POST['input'])) {
			$input = CleanString($_POST['input']);
		}
		if(isset($_POST['pagemakeup'])) {
			$pagemakeup = CleanString($_POST['pagemakeup']);
		}
		if(isset($_POST['date'])) {
			$date = date("d/m/Y", time());
		}
		if(isset($_POST['bi'])) {
			$bi = CleanString($_POST['bi']);
		}
		if(isset($_POST['datedue'])) {
			$datedue = CleanString($_POST['datedue']);
		}
		if(isset($_POST['invoicenumber'])) {
			$invoicenumber = CleanString($_POST['invoicenumber']);
		}
		if(isset($_POST['wordcount'])) {
			$wordcount = CleanString($_POST['wordcount']);
		}
		if(isset($_POST['jobnumber'])) {
			$jobnumber = $_POST['jobnumber'];
		}
		if(isset($_POST['done'])) {
			$done = CleanString($_POST['done']);
		}
		$errors = array();
        if(!$language) {
            $errors[] = "You have not specified a language. What will this job be translated into?";
        }
		if(!$client) {
			$errors[] = "You have not specified a client. Who is it for?";
		}
		if(!$description) {
			$errors[] = "You have not specified a description. What is this job about?";
		}
		if(count($errors) > 0){
			echo "<div class=\"errorcode\" align=\"center\">\n";
            foreach($errors AS $error){
                echo $error . "<br>\n";
            }
			echo "<a href=\"history.go(-1)\">Back</a>";
			echo "</div>";
        } else {
			if($translation == 'on') {
				$translation = "1";
			} else {
				$translation = "0";
			}
			if($input == 'on') {
				$input = "1";
			} else {
				$input = "0";
			}
			if($pagemakeup == 'on') {
				$pagemakeup = "1";
			} else {
				$pagemakeup = "0";
			}
			if($bi == 'on') {
				$bi = "1";
			} else {
				$bi = "0";
			}
			if($done == 'on') {
				$done = "1";
			} else {
				$done = "0";
			}
			$UpdateRadianJobsTable = "UPDATE radian_jobs SET language='" . $language . "', client='" . $client . "', description='" . $description . "', translation='" . $translation . "', input='" . $input . "', pagemakeup='" . $pagemakeup . "', invoicenumber='" . $invoicenumber . "', jobdoneby='" . $jobdoneby . "', bi='" . $bi . "', datedue='" . $datedue . "', wordcount='" . $wordcount . "', done='" . $done . "' WHERE jobnumber='" . $jobnumber . "'";
            mysql_query($UpdateRadianJobsTable);
			header("Location: index.php?page=view");
        }
	} elseif($page == 'search') {
		echo "<tr><td><h3>Search</h3></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<form action=\"index.php?page=results\" method=\"POST\">";
		echo "<tr><td><input type=\"text\" style=\"width: 200px;\" name=\"search_query\">";
		echo "<select name=\"search_field\">";
		echo "<option value=\"jobnumber\">Job No.</option>";
		echo "<option value=\"language\">Language</option>";
		echo "<option value=\"client\">Client</option>";
		echo "<option value=\"date\">Date</option>";
		echo "<option value=\"description\">Description</option>";
		echo "<option value=\"invoicenumber\">Invoice Number</option>";
		echo "<option value=\"jobdoneby\">Job Done By</option>";
		echo "<option value=\"datedue\">Date Due</option>";
		echo "<option value=\"wordcount\">Word Count</option>";
		echo "</select>";
		echo "<input type=\"submit\" value=\"Search\" name=\"search_submit\"></td></tr>";
		echo "</form>";
	} elseif($page == 'results') {
		if($_POST['search_submit']) {
			Search($_POST['search_query'], $_POST['search_field']);
		} else {
			header("Location: index.php?page=search");
		}
	} elseif($page == 'backup') {
		if(isset($_GET['b']) == "all") {
			$sql = "SELECT * FROM radian_jobs";
			$res = mysql_query($sql);
			$output = "CREATE TABLE IF NOT EXISTS `radian_jobs` (
`jobnumber` int(11) NOT NULL AUTO_INCREMENT,
`language` varchar(255) NOT NULL,
`client` varchar(255) NOT NULL,
`date` varchar(11) NOT NULL,
`description` varchar(500) NOT NULL,
`translation` int(1) NOT NULL,
`input` int(1) NOT NULL,
`pagemakeup` int(1) NOT NULL,
`invoicenumber` varchar(255) NOT NULL,
`jobdoneby` varchar(255) NOT NULL,
`bi` int(1) NOT NULL,
`datedue` varchar(11) NOT NULL,
`wordcount` varchar(11) NOT NULL,
`done` int(1) NOT NULL,
PRIMARY KEY (`jobnumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;";
			 
			while($data = mysql_fetch_assoc($res)) {
				$output .= "INSERT INTO `radian_jobs` (`jobnumber`, `language`, `client`, `date`, `description`, `translation`, `input`, `pagemakeup`, `invoicenumber`, `jobdoneby`, `bi`, `datedue`, `wordcount`, `done`) VALUES ('".$data['jobnumber']."', '".$data['language']."', '".$data['client']."', '".$data['date']."', '".$data['description']."', '".$data['translation']."', '".$data['input']."', '".$data['pagemakeup']."', '".$data['invoicenumber']."', '".$data['jobdoneby']."', '".$data['bi']."', '".$data['datedue']."', '".$data['wordcount']."', '".$data['done']."')\n";
			}
			$file = fopen("backup.sql", "w");
			fwrite($file, $output);
			fclose($file);
			header("Location: download.php");
		} else {
			echo "<p>";
			echo "<a href=\"index.php?page=backup&b=all\">Backup All</a>";
			echo "</p>";
		}
		
	} elseif($page == 'home') { //Home
		echo "<tr><td colspan=\"3\"><h3>Welcome to East Word Job Manager</h3></td></tr>\n";
		echo "<tr><td colspan=\"3\"&nbsp;</td></tr>\n";
		echo "<tr><td colspan=\"3\"><h4>Outstanding Jobs</h4></td></tr>\n";
		$JobsNotDone = "SELECT * FROM  radian_jobs WHERE done = '0' ORDER BY jobnumber ASC";
		$QueryJobsNotDone = mysql_query($JobsNotDone);
		while($RadianJobsDoneF = mysql_fetch_assoc($QueryJobsNotDone)) {
			echo "<tr><td><a href=\"?page=edit&id=" . $RadianJobsDoneF['jobnumber'] . "\">" . $RadianJobsDoneF['jobnumber'] . "</a></td><td>" . $RadianJobsDoneF['client'] . "</td><td>" . $RadianJobsDoneF['description'] . "</td></tr>";
		}
		echo "<tr><td colspan=\"3\">&nbsp;</td></tr>\n";
		echo "<tr><td colspan=\"3\"><h4>New Jobs</h4></td></tr>\n";
		$NewJobs = "SELECT * FROM radian_jobs ORDER BY jobnumber DESC LIMIT 0,5";
		$QueryNewJobs = mysql_query($NewJobs);
		while($RadianJobsNewJobs = mysql_fetch_assoc($QueryNewJobs)) {
			echo "<tr><td><a href=\"?page=edit&id=" . $RadianJobsNewJobs['jobnumber'] . "\">" . $RadianJobsNewJobs['jobnumber'] . "</a></td><td>" . $RadianJobsNewJobs['client'] . "</td><td>" . $RadianJobsNewJobs['description'] . "</td></tr>";
		}
	} else {
		if(!$_SESSION['radian_user_id']) {
			header("Location: index.php");
		} else {
			header("Location: index.php?page=home");
		}
	}
}
echo "</table>";
echo "</div>";
echo "</body>\n";
echo "</html>\n";

ob_end_flush();

?>