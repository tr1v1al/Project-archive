<?php

require 'functions.php';

if(sizeof($_FILES) > 0) {

	$fileUploader = new FileUploader($_FILES);
}

class FileUploader{
	public function __construct($uploads){
		
		// Checking for user in case of error
		session_start();
		$user = $_SESSION['u_type'];


		$projectname = $_POST['projectname'];

		//require ('Connection.php');
		require '../../connect.inc';
		$conn = Connect();
		mysqli_set_charset($conn,'utf8');	

		// Checking whether project already exists
		$sql = "SELECT * FROM projects_data WHERE project_name = '".$projectname."'";
		$result = mysqli_query($conn, $sql);  
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck > 0) {
			if ($user == "user") {
				header("Location: main.php?upload=invname");
				$conn->close();
				exit();
			} elseif($user == "admin") {
				header("Location: admin.php?upload=invname");
				$conn->close();
				exit();
			}
		} else {
			$conn->close();
		}



		/*
		$sql = "SELECT * FROM projects_data WHERE project_name = '".$projectname."'";
		$result = mysqli_query($conn, $sql);  
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck > 0) {
			$date = date("d.m.Y");
			$projectname = $projectname." ".$date;

			// Double checking in case another upload was done the same day
			
			$sequence = "SELECT * FROM projects_data WHERE project_name = '".$projectname."'";
			$altresult = mysqli_query($conn, $sequence);  
			$resultDoubleCheck = mysqli_num_rows($altresult);
			if ($resultDoubleCheck > 0) {
				if ($user == "user") {
					header("Location: main.php?upload=error");
					exit();
				} elseif($user == "admin") {
					header("Location: admin.php?upload=error");
					exit();
				}
				
			}
		}*/

		$dirnameCheck = russian2translit($projectname);
		
		if (!mkdir('uploads/'.$dirnameCheck)) {
			header("Location: main.php?upload=error");
		}		

		$uploadDir='uploads/'.$dirnameCheck.'/';
		// Split the string containing the list of file paths into an array 
		$paths = explode("###",rtrim($_POST['paths'],"###"));
		
		// Loop through files sent
		foreach($uploads as $key => $current)
		{
			// Stores full destination path of file on server
			$this->uploadFile=$uploadDir.rtrim($paths[$key],"/.");
			// Stores containing folder path to check if dir later
			$this->folder = russian2translit(substr($this->uploadFile,0,strrpos($this->uploadFile,"/")));
			//$this->folder = preg_replace('/[^a-zA-Z0-9 \-_;]/', '', $this->folder);
			// Check whether the current entity is an actual file or a folder (With a . for a name)
			if(strlen($current['name'])!=1)
				// Upload current file
				if($this->upload($current,$this->uploadFile))
					echo "The file ".$paths[$key]." has been uploaded\n";
				else 
					echo "Error";
		}
	}
	
	private function upload($current,$uploadFile){
		// Checks whether the current file's containing folder exists, if not, it will create it.
		if(!is_dir($this->folder)){
			mkdir($this->folder,0700,true);
		}
		// Moves current file to upload destination
		//$filenameCheck = iconv("UTF-8","Windows-1251",$uploadFile);

		$filenameCheck = russian2translit($uploadFile);
		//$filenameCheck = preg_replace('/[^a-zA-Z0-9 \-_;]/', '', $filenameCheck);

		if(move_uploaded_file($current['tmp_name'],$filenameCheck))
			return true;
		else 
			return false;
	}
}
?>