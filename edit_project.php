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

// ID of the project that is being edited

$id = $_GET['id'];

//Main part

if (isset($_POST['submit'])) {

	//Getting file and all file variables

	$textfile = $_FILES['edit_textfile'];
	$textfilename = $_FILES['edit_textfile']['name'];
	$textfileTmpName = $_FILES['edit_textfile']['tmp_name'];	
	$textfileSize = $_FILES['edit_textfile']['size'];
	$textfileType = $_FILES['edit_textfile']['type'];
	$textfileError = $_FILES['edit_textfile']['error'];

	$ppfile = $_FILES['edit_ppfile'];
	$ppfilename = $_FILES['edit_ppfile']['name'];
	$ppfileTmpName = $_FILES['edit_ppfile']['tmp_name'];	
	$ppfileSize = $_FILES['edit_ppfile']['size'];
	$ppfileType = $_FILES['edit_ppfile']['type'];
	$ppfileError = $_FILES['edit_ppfile']['error'];

	$imagefile = $_FILES['edit_imagefile'];
	$imagefilename = $_FILES['edit_imagefile']['name'];
	$imagefileTmpName = $_FILES['edit_imagefile']['tmp_name'];	
	$imagefileSize = $_FILES['edit_imagefile']['size'];
	$imagefileType = $_FILES['edit_imagefile']['type'];
	$imagefileError = $_FILES['edit_imagefile']['error'];

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


	/*_________________________Data for the database________________________________*/


	$project_name   = $conn->real_escape_string($_POST['project_name']);
	$authors        = $conn->real_escape_string($_POST['authors']);
	$supervisor     = $conn->real_escape_string($_POST['supervisor']);
	$faculty 	    = $conn->real_escape_string($_POST['faculty']);
	$description    = $conn->real_escape_string($_POST['description']);
	$start_date     = $conn->real_escape_string($_POST['start_date']);
	$submit_date    = $conn->real_escape_string($_POST['submit_date']);
	$grade          = $conn->real_escape_string($_POST['grade']);
	$project_status = $conn->real_escape_string($_POST['status']);
	$project_type   = $conn->real_escape_string($_POST['type']);
	$project_log    = $conn->real_escape_string($_POST['log']);
	
	// Getting school year

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

	//The release date and the download counter both remain the same

	$sql = "SELECT * FROM projects_data WHERE ID = '".$id."'";
	$result = mysqli_query($conn, $sql);  
	$resultCheck = mysqli_num_rows($result);

	if ($resultCheck > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$releasedate = $row['release_date'];
			$downloads = $row['downloads'];
			$oldproject_name = $row['project_name'];
		}
	}

	// Checking whether this new project name already exists

	$sql = "SELECT * FROM projects_data WHERE project_name = '".$project_name."'";
	$result = mysqli_query($conn, $sql);  
	$resultCheck = mysqli_num_rows($result);
	if ($resultCheck > 0 && $project_name!=$oldproject_name) {	
		header("Location: admin.php?upload=samename");
		exit();			
	}

	$oldproject_name = russian2translit($oldproject_name);

	// Updating project data in the database

	$query = "
	UPDATE projects_data
	SET project_name = '".$project_name."', authors = '".$authors."', 
	supervisor = '".$supervisor."', faculty = '".$faculty."', 
	grade = '".$grade."', description = '".$description."', release_date = '".$releasedate."',
	start_date = '".$start_date."', submit_date = '".$submit_date."', school_year = '".$schoolyear."',
	downloads = '".$downloads."', project_status = '".$project_status."', 
	project_type = '".$project_type."',	project_log = '".$project_log."'
	WHERE ID = '".$id."'
	";

	$success = $conn->query($query);
	
	if (!$success) {

	die ("Не получилось обновить проект:".$conn->error);
	} else {
		$conn->close();
		//header("Location: main.php?upload=success");
		//exit();	
	}



	/*_________________________Uploading files_____________________________*/

	$newproject_name = russian2translit($project_name);
	$dirname = "uploads/".$newproject_name;

	if (!rename("uploads/".$oldproject_name, "uploads/".$newproject_name)) {
		header("Location: admin.php?update=renameerror");
		exit();	
	}


	// Deleting old project card

	if (!unlink($dirname."/readme.txt")) {
		header("Location: admin.php?update=delcardfailure");
		exit();			
	}
	
	// Deleting old zip file

	if (!unlink($dirname."/".$oldproject_name.".zip")) {
		header("Location: admin.php?update=delzipfailure");
		exit();			
	}

	// Creating new project card

	$myfile = fopen($dirname."/readme.txt", "w");

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
	$inputdata = "Тип проекта: ".$project_type."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "Стадия проекта: ".$project_status."\r\n";
	fwrite($myfile, $inputdata);
	$inputdata = "История проекта: ".$project_log."\r\n";
	fwrite($myfile, $inputdata);

	fclose($myfile);


	// Updating text file


	if ($textfileSize != 0) {
		$result = glob($dirname."/Tekst proekta -*");
		if ($result) {
			unlink($result[0]);
		}		
		if (in_array($textfileActualExt, $textallowed)) {
			if ($textfileError === 0) {
				if ($textfileSize < 100000000) {					
					$textfileDestination = $dirname.'/Текст проекта - '.$textfilename;
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



	// Updating presentation file

	if ($ppfileSize != 0) {
		$result = glob($dirname."/Prezentaciya proekta -*");
		if ($result) {
			unlink($result[0]);
		}
		if (in_array($ppfileActualExt, $ppallowed)) {
			if ($ppfileError === 0) {
				if ($ppfileSize < 100000000) {
					$ppfileDestination = $dirname.'/Презентация проекта - '.$ppfilename;
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



	// Updating image file

	if ($imagefileSize != 0) {
		$result = glob($dirname."/avatar_proekta*");
		if ($result) {
			unlink($result[0]);
		}		
		if (in_array($imagefileActualExt, $imageallowed)) {
			if ($imagefileError === 0) {
				if ($imagefileSize < 100000000) {
					$imagefileDestination = $dirname.'/аватар_проекта.'.$imagefileActualExt;
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

	new GoodZipArchive($dirname, $dirname.'/'.$newproject_name.'.zip');



	header("Location: admin.php?update=success");
	exit();

}

?>