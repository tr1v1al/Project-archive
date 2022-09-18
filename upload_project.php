<?php

//require ('Connection.php');
require '../../connect.inc';
require 'functions.php';

$conn = Connect();
mysqli_set_charset($conn,'utf8');

// Checking for user in case of error
session_start();
$user = $_SESSION['u_type'];

function translit2url($str) {
    $str = rus2translit($str);
    $str = strtolower($str);
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    $str = trim($str, "-");
    return $str;
}

if ($user == "user") {
	$fallback = "main.php";
} elseif($user == "admin") {
	$fallback = "admin.php";
}	

if (isset($_POST['submit'])) {

	//Getting file and all file variables
	$textfile = $_FILES['textfile'];
	$textfilename = $_FILES['textfile']['name'];
	$textfileTmpName = $_FILES['textfile']['tmp_name'];	
	$textfileSize = $_FILES['textfile']['size'];
	$textfileType = $_FILES['textfile']['type'];
	$textfileError = $_FILES['textfile']['error'];

	$ppfile = $_FILES['ppfile'];
	$ppfilename = $_FILES['ppfile']['name'];
	$ppfileTmpName = $_FILES['ppfile']['tmp_name'];	
	$ppfileSize = $_FILES['ppfile']['size'];
	$ppfileType = $_FILES['ppfile']['type'];
	$ppfileError = $_FILES['ppfile']['error'];

	$imagefile = $_FILES['imagefile'];
	$imagefilename = $_FILES['imagefile']['name'];
	$imagefileTmpName = $_FILES['imagefile']['tmp_name'];	
	$imagefileSize = $_FILES['imagefile']['size'];
	$imagefileType = $_FILES['imagefile']['type'];
	$imagefileError = $_FILES['imagefile']['error'];

	//Getting archive



	//Getting file extensions
	$textfileExt = explode('.', $textfilename);
	$ppfileExt = explode('.', $ppfilename);
	$imagefileExt = explode('.', $imagefilename);

	//Make it lowercase so JPG turns into jpg, and get the extension itself
	$textfileActualExt = strtolower(end($textfileExt));
	$ppfileActualExt = strtolower(end($ppfileExt));
	$imagefileActualExt = strtolower(end($imagefileExt));

	//Allowed extensions
	$textallowed = array('docx', 'doc', 'txt', 'text', 'pdf');
	$ppallowed = array('ppt', 'pptx');
	$imageallowed = array('png', 'jpg', 'jpeg');

	//Getting project data for database
	$project_name  = $conn->real_escape_string($_POST['project_name']);
	$authors       = $conn->real_escape_string($_POST['authors']);
	$supervisor    = $conn->real_escape_string($_POST['supervisor']);
	$faculty 	   = $conn->real_escape_string($_POST['faculty']);
	$description   = $conn->real_escape_string($_POST['description']);
	$start_date    = $conn->real_escape_string($_POST['start_date']);
	$submit_date   = $conn->real_escape_string($_POST['submit_date']);
	$grade         = $conn->real_escape_string($_POST['grade']);
	$releasedate   = date("d.m.Y"); 
	$downloads     = 0;

	$dateArray = explode('.', $submit_date);
	$month     = intval($dateArray[1]);
	$year      = intval($dateArray[2]);

	if ($month <= 8) {
		$varyear = $year - 1;
		$schoolyear = $varyear.'-'.$year;
	} else {
		$varyear = $year + 1;
		$schoolyear = $year.'-'.$varyear;
	}

	$sql = "SELECT * FROM projects_data WHERE project_name = '".$project_name."'";
	$result = mysqli_query($conn, $sql);  
	$resultCheck = mysqli_num_rows($result);
	if ($resultCheck > 0) {	
		header("Location: ".$fallback."?upload=samename");
		exit();			
	}
	/*
	$sql = "SELECT * FROM projects_data WHERE project_name = '".$project_name."'";
	$result = mysqli_query($conn, $sql);  
	$resultCheck = mysqli_num_rows($result);
	if ($resultCheck > 0) {
		$date = date("d.m.Y");
		$project_name = $project_name." ".$date;

		// Double checking in case another upload was done the same day
		
		$sql = "SELECT * FROM projects_data WHERE project_name = '".$project_name."'";
		$result = mysqli_query($conn, $sql);  
		$resultDoubleCheck = mysqli_num_rows($result);
		if ($resultDoubleCheck > 0) {
			if ($user == "user") {
				header("Location: main.php?upload=error");
				exit();
			} elseif($user == "admin") {
				header("Location: admin.php?upload=error");
				exit();
			}
		}
	}
	*/

	//Error handling the text
	$query = "
	INSERT INTO projects_data (project_name, authors, supervisor, faculty, 
	grade, description, release_date, start_date, submit_date, school_year, downloads)
	VALUES ('".$project_name."', '".$authors."', '".$supervisor."',
	 '".$faculty."', '".$grade."', '".$description."', '".$releasedate."', 
	 '".$start_date."', '".$submit_date."', '".$schoolyear."', '".$downloads."')
	";

	$success = $conn->query($query);
	
	if (!$success) {

	die ("Не получилось загрузить проект:".$conn->error);
	} else {
		$conn->close();
		//header("Location: main.php?upload=success");
		//exit();	
	}
	
	// Uploading files

	$dirnameCheck = 'uploads/'.$project_name;
	$dirnameCheck = russian2translit($dirnameCheck);


	if (!file_exists($dirnameCheck)) {
		if (!mkdir($dirnameCheck)) {
			header("Location: ".$fallback."?upload=mkdirfailure");
			exit();				
		}	
	}

	// Creating project card

	$myfile = fopen($dirnameCheck."/readme.txt", "w");

	$inputdata = "Название проекта: ".$project_name."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Авторы: ".$authors."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Куратор: ".$supervisor."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Кафедра: ".$faculty."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Класс: ".$grade."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Описание: ".$description."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Дата публикации: ".$releasedate."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Дата начала проекта: ".$start_date."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Дата сдачи проекта: ".$submit_date."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Школьный год: ".$schoolyear."\r\n";
	fwrite($myfile, $inputdata);

	fclose($myfile);
	$translitname = russian2translit($project_name);

	if ($textfileSize != 0) {
		if (in_array($textfileActualExt, $textallowed)) {
			if ($textfileError === 0) {
				if ($textfileSize < 100000000) {
					$textfileDestination = 'uploads/'.$project_name.'/Текст проекта - '.$textfilename;
					$textfilenameCheck = russian2translit($textfileDestination);
					move_uploaded_file($textfileTmpName, $textfilenameCheck);
				} else {
					echo "Слишком большой размер текстового файла";
				}
			} else {
				echo "Не получилось загрузить текст, код ошибки: ".$textfileError;
			}
		} else {
			echo "Не подходит расширение текстового файла";
		}
	}

	if ($ppfileSize != 0) {
		if (in_array($ppfileActualExt, $ppallowed)) {
			if ($ppfileError === 0) {
				if ($ppfileSize < 100000000) {
					$ppfileDestination = 'uploads/'.$project_name.'/Презентация проекта - '.$ppfilename;
					$ppfilenameCheck =	russian2translit($ppfileDestination);
					move_uploaded_file($ppfileTmpName, $ppfilenameCheck);
				} else {
					echo "Слишком большой размер презентации";
				}
			} else {
				echo "Не получилось загрузить презентацию, код ошибки: ".$ppfileError;
			}
		} else {
			echo "Не подходит расширение презентации";
		}
	}

	if ($imagefileSize != 0) {
		if (in_array($imagefileActualExt, $imageallowed)) {
			if ($imagefileError === 0) {
				if ($imagefileSize < 100000000) {
					$imagefileDestination = 'uploads/'.$project_name.
									'/аватар_проекта.'.$imagefileActualExt;
					$imagefilenameCheck = russian2translit($imagefileDestination);
					move_uploaded_file($imagefileTmpName, $imagefilenameCheck);
				} else {
					echo "Слишком большой размер аватара проекта";
				}
			} else {
				echo "Не получилось загрузить аватар проекта, код ошибки: ".$imagefileError;
			}
		} else {
			echo "Не подходит расширение аватара проекта";
		}
	}

	class GoodZipArchive extends ZipArchive 
	{
		
		public function __construct($a=false, $b=false) { $this->create_func($a, $b);  }
		
		public function create_func($input_folder=false, $output_zip_file=false)
		{
			if($input_folder && $output_zip_file)
			{
				$res = $this->open($output_zip_file, ZipArchive::CREATE);
				if($res === TRUE) 	{ $this->addDir($input_folder, basename($input_folder)); $this->close(); }
				else  				{ echo 'Could not create a zip archive. Contact Admin.'; }
			}
		}
		
	    // Add a Dir with Files and Subdirs to the archive
	    public function addDir($location, $name) {
	        $this->addEmptyDir($name);
	        $this->addDirDo($location, $name);
	    }

	    // Add Files & Dirs to archive 
	    private function addDirDo($location, $name) {
	        $name .= '/';         $location .= '/';
	      // Read all Files in Dir
	        $dir = opendir ($location);
	        while ($file = readdir($dir))    {
	            if ($file == '.' || $file == '..') continue;
	          // Rekursiv, If dir: GoodZipArchive::addDir(), else ::File();
	            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
	            $this->$do($location . $file, $name . $file);
	        }
	    } 
	}	

	new GoodZipArchive('uploads/'.$translitname, 
		'uploads/'.$translitname.'/'.$translitname.'.zip');

	header("Location: ".$fallback."?upload=success");	
	exit();
}

/*__________________________________Old code_______________________________*/

/*
	if (in_array($textfileActualExt, $textallowed) && 
		in_array($ppfileActualExt, $ppallowed)) {
		if ($textfileError === 0 && $ppfileError === 0) {
			if ($textfileSize < 20000000 && $ppfileSize < 20000000) {
				
				$textfileDestination = 'uploads/'.$project_name.'/Текст проекта - '.$textfilename;
				$ppfileDestination = 'uploads/'.$project_name.'/Презентация проекта - '.$ppfilename;
				$imagefileDestination = 'uploads/'.$project_name.
				'/аватар_проекта.'.$imagefileActualExt;

				$textfilenameCheck = russian2translit($textfileDestination);
				$ppfilenameCheck =	russian2translit($ppfileDestination);
				$imagefilenameCheck = russian2translit($imagefileDestination);


				move_uploaded_file($textfileTmpName, $textfilenameCheck);
				move_uploaded_file($ppfileTmpName, $ppfilenameCheck);		
				move_uploaded_file($imagefileTmpName, $imagefilenameCheck);		
			}
		} else {
			echo "Couldn't upload your file".$textfileError.$ppfileError;
		}
	} else {
		echo "Wrong extension";
	}


*/

?>