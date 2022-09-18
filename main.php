<?php
	include_once 'header.php';
	//require 'connection.php';
	require '../../connect.inc';
	require 'functions.php';


	$conn = Connect();
	mysqli_set_charset($conn,'utf8');
	if (!isset($_SESSION['u_type'])) {
		header("Location: index.php");
	} elseif($_SESSION['u_type'] != "user") {
		header("Location: index.php");
	}

?>

<script type="text/javascript" src="js/main.js"></script>
<!-- Filter button -->
<!--
<div class="trigger-button-block">
	<button onclick="document.getElementById('filter-box-wrapper').style.display='block'"
	class="trigger-button">Фильтровать</button>	
</div>
-->

<div class="content-wrapper">

<!--Popupbox for filtering-->


<div id="filter-box-wrapper">
	<div id="filter-box-content" class="popup-animation">
		<div class="header-block">
			<h3>Фильтр проектов</h3>
			<span onclick="document.getElementById('filter-box-wrapper').style.display='none'" class="close" title="Закрыть">&times;</span>
		</div>
		<form class="filter-form" action="filter.php" method="POST"> 
			<div class="filter-wrapper">
<?php

// Successful upload notification

if (isset($_GET['upload'])) {
	if ($_GET['upload'] == "success") {
		echo '
		<script type="text/javascript">alert("Проект успешно загружен!");</script>
		';
	} elseif ($_GET['upload'] == "samename") {
		echo '
		<script type="text/javascript">alert("Проект с таким названием уже существует.");</script>
		';
	}
}

// Error notifications

if (isset($_GET['error'])) {
	if ($_GET['error'] == "notext") {
		echo '
		<script type="text/javascript">alert("У этого проекта нет текста");</script>
		';
	} elseif($_GET['error'] == "nopres") {
		echo '
		<script type="text/javascript">alert("У этого проекта нет презентации");</script>
		';
	}
}

$checkArray = array("", "", "", "", "", "", "", "", "", "", "", "", "");
$facultytagsArray = array("f", "s", "p", "t", "l", "b", "n");

if (isset($_GET['filter'])) {
	$filterdata = $_GET['filter'];

	$filterdataArray = explode("~", $filterdata);
	$gradeArray = $filterdataArray[0];
	$facultyArray = $filterdataArray[1];

	$gradeArray = explode("*", $gradeArray);
	$facultyArray = explode("*", $facultyArray);	
	$r = 1;

		for ($i=0; $i < 6; $i++) { 
			if ($r < sizeof($gradeArray)) {
				if ($gradeArray[$r] == $i + 6) {
					$checkArray[$i] = "checked";
					++$r;
				}			
			}

		}

	$f = 1;

		for ($i=0; $i < sizeof($facultytagsArray); $i++) { 
			if ($f < sizeof($facultyArray)) {
				if ($facultytagsArray[$i] == $facultyArray[$f]) {
					$checkArray[$i + 6] = "checked";
					++$f;
				}				
			}

		}
}

?>
		<!--Grades-->
		<div class="multiselect grade">
			<div class="selectBox" onclick="showgradeCheckboxes()">
				<select>
					<option>Выберите класс</option>
				</select>
			    <div class="overSelect" id="select-grade"></div>
			</div>

			<div class="checkboxes" id="checkboxes-grade">
				<label for="6">
			    <input type="checkbox" name="sixth" id="6" <?php echo $checkArray[0]; ?> >6 класс</label>
			  	<label for="7">
			    <input type="checkbox" name="seventh" id="7" <?php echo $checkArray[1]; ?> >7 класс</label>
			  	<label for="8">
			    <input type="checkbox" name="eighth" id="8" <?php echo $checkArray[2]; ?> >8 класс</label>
			  	<label for="9">
			    <input type="checkbox" name="ninth" id="9" <?php echo $checkArray[3]; ?> >9 класс</label>
			  	<label for="10">
			    <input type="checkbox" name="tenth" id="10" <?php echo $checkArray[4]; ?> >10 класс</label>
			  	<label for="11">
			    <input type="checkbox" name="eleventh" id="11" <?php echo $checkArray[5]; ?> >11 класс</label>
			    <div class="filter-input-block">
			    	<input type="submit" name="submit" id="submit" value="Фильтровать">
				</div>
			</div>
		</div>

		<!--Faculties-->

		<div class="multiselect faculty">
			<div class="selectBox" onclick="showfacultyCheckboxes()">
				<select>
					<option>Выберите кафедру</option>
				</select>
			    <div class="overSelect" id="select-faculty"></div>
			</div>

			<div class="checkboxes" id="checkboxes-faculty">
				<label for="fiztekh">
			    <input type="checkbox" name="fiztekh" id="fiztekh" <?php echo $checkArray[6]; ?> >Физтех</label>
			  	<label for="sotzgum">
			    <input type="checkbox" name="sotzgum" id="sotzgum" <?php echo $checkArray[7]; ?> >Соцгум</label>	  	
			  	<label for="proga">
			    <input type="checkbox" name="proga" id="proga" <?php echo $checkArray[8]; ?> >Программирование</label>
			  	<label for="teormat">
			    <input type="checkbox" name="teormat" id="teormat" <?php echo $checkArray[9]; ?> >Теормат</label>
			  	<label for="lingvo">
			    <input type="checkbox" name="lingvo" id="lingvo" <?php echo $checkArray[10]; ?> >Лингвистика</label>
			  	<label for="biokhim">
			    <input type="checkbox" name="biokhim" id="biokhim" <?php echo $checkArray[11]; ?> >Биохим</label>
			    <label for="bez">
			    <input type="checkbox" name="bez" id="bez" <?php echo $checkArray[12]; ?> >Без кафедры</label>
			    <div class="filter-input-block">
			    	<input type="submit" name="submit" id="submit" value="Фильтровать">
				</div>
			</div>
		</div>	

		<div class="multiselect school-year">
			<div class="selectBox" onclick="showschoolyearCheckboxes()">
				<select>
					<option>Выберите учебный год</option>
				</select>
			    <div class="overSelect" id="select-schoolyear"></div>
			</div>

			<div class="checkboxes" id="checkboxes-schoolyear">
<?php

if (isset($_GET['filter'])) {
	$filterdata = $_GET['filter'];

	$filterdataArray = explode("~", $filterdata);
	$gradeArray = $filterdataArray[0];
	$facultyArray = $filterdataArray[1];
	$schoolyearArray = $filterdataArray[2];

	$gradeArray = explode("*", $gradeArray);
	$facultyArray = explode("*", $facultyArray);
	$schoolyearArray = explode("*", $schoolyearArray);	
	$filtering = true;
} else {
	$filtering = false;
}

	$minyear = 2014;
	$curryear = intval(date('Y'));
	$currmonth = intval(date('m'));

	if ($currmonth >= 1 && $currmonth <= 5) {
		$counter = $curryear - $minyear;
	} else {
		$counter = $curryear - $minyear + 1;
	}

	$gap = $minyear;
	$count = 1;

	for ($i=0; $i < $counter; $i++) { 
		$filler = $gap.'-';
		++$gap;
		$filler = $filler.$gap;
		$checked = "";
		if ($filtering == true) {
			if ($count < sizeof($schoolyearArray)) {
				if ($filler == $schoolyearArray[$count]) {
					$checked = "checked";
					++$count;
				}	
			}
		}

		echo '
			<label for="'.$filler.'">
			<input type="checkbox" name="'.$filler.'" id="'.$filler.'" '.$checked.'>'.$filler.'</label>
		';
		
	}

?>	
			    <div class="filter-input-block">
			    	<input type="submit" name="submit" id="submit" value="Фильтровать">
				</div>
			</div>
		</div>	
	</div>
</form>
		<div id="reset-block">
			<button onclick="location.href='main.php'">Сбросить</button>
		</div>	
	</div>
</div>




<script type="text/javascript"> 
var expandedfaculty = false;
var expandedgrade = false;
var expandedschoolyear = false;
function scrollFunction() {
	var checkboxesgrade = document.getElementById("checkboxes-grade");
	var checkboxesfaculty = document.getElementById("checkboxes-faculty");
	var checkboxesschoolyear = document.getElementById("checkboxes-schoolyear");	
	checkboxesfaculty.style.display = "none";
	expandedfaculty = false;
	checkboxesschoolyear.style.display = "none";
	expandedschoolyear = false;	
	checkboxesgrade.style.display = "none";
	expandedgrade = false;	
}
document.addEventListener("scroll", scrollFunction);
</script>







<!--Popupbox for uploading-->

<div id="upload-box-wrapper" class="upload-box">
	<form class="upload-box-content popup-animation" onsubmit="return processSubmit()" 
	action="upload_project.php"	method="POST" enctype="multipart/form-data">
		<div class="header-block">
			<h3>Загрузите свой проект</h3>
			<span onclick="document.getElementById('upload-box-wrapper').style.display='none'" class="close" title="Закрыть">&times;</span>
		</div>

		<!--Upload <section></section>-->

		<div class="upload-block">
			<input type="file" name="imagefile" id="imagefile" class="inputfile inputfile-1">
			<label for="imagefile"><span>Загрузить аватар</span></label>
			<input type="file" name="textfile" id="textfile" class="inputfile inputfile-1">
			<label for="textfile"><span>Загрузить текст</span></label>
			<input type="file" name="ppfile" id="ppfile" class="inputfile inputfile-1">
			<label for="ppfile"><span>Загрузить презентацию</span></label>
			<input type="file" name="archive[]" id="archive" class="inputfile inputfile-1" data-multiple-caption="{count} файлов выбрано" webkitdirectory multiple="">
			<label for="archive"><span>Загрузить все материалы</span></label>		
			<select name="faculty" id="faculty">
				<option value="Выбрать кафедру">Выбрать кафедру</option>
				<option value="Физтех">Физтех</option>
				<option value="Программирование">Программирование</option>
				<option value="Теормат">Теормат</option>
				<option value="Биохим">Биохим</option>
				<option value="Лингвистика">Лингвистика</option>
				<option value="Соцгум">Соцгум</option>
				<option value="Без кафедры">Без кафедры</option>
			</select>	
			<p id="upload-box-warning">Максимальное число файлов на загрузку 100, максимальный размер файла 100 Мб</p>			
		</div><div class="text-block">

		
			<input type="text" name="project_name" id="project_name"  
			placeholder="Название проекта" autocomplete="off" 
			maxlength="60" class="big-input">
			<input type="text" name="authors" id="authors" 
			placeholder="Автор(ы) проекта" autocomplete="off" 
			maxlength="60" class="big-input">
			<input type="text" name="supervisor" id="supervisor"
			placeholder="Куратор(ы) проекта" autocomplete="off" 
			maxlength="50" class="big-input">
			<input type="text" name="start_date" id="start_date" 
			placeholder="Дата начала: Д.М.Г" autocomplete="off" 
			maxlength="10" class="small-input">
			<input type="text" name="submit_date" id="submit_date" 
			placeholder="Дата сдачи: Д.М.Г" autocomplete="off" 
			maxlength="10" class="small-input">
			<input type="text" name="grade" id="grade" 
			placeholder="Класс" autocomplete="off" 
			maxlength="2" class="grade-small-input">
			<textarea name="description" id="description" 
			placeholder="Краткое описание проекта:" maxlength="500"></textarea>
		</div>
		<div class="submit-block">
			<button type="submit" name="submit">Загрузить</button>
		</div>
		
	</form>
</div>

<!--Main page with display of projects-->
<script type="text/javascript" src="js/custom-file-input.js"></script>


<?php

//If search is active

if (isset($_GET['search'])) {
	$searchdata   = $conn->real_escape_string($_GET['search']);
	//$searchdata = $_GET['search'];
	$searchArray = explode(" ", $searchdata);
	$arrayLength = sizeof($searchArray);

	$sql = "SELECT * FROM projects_data WHERE ";

	for ($i=0; $i < $arrayLength - 1; $i++) { 
		$sql = $sql."(project_name LIKE '%$searchArray[$i]%' OR
	 authors LIKE '%$searchArray[$i]%' OR supervisor LIKE '%$searchArray[$i]%' OR
	  faculty LIKE '%$searchArray[$i]%' OR description LIKE '%$searchArray[$i]%' OR
	   release_date LIKE '%$searchArray[$i]%' OR project_type LIKE '%$searchArray[$i]%' OR project_status LIKE '%$searchArray[$i]%') OR ";
	}
	$i = $arrayLength - 1;
	$sql = $sql."(project_name LIKE '%$searchArray[$i]%' OR
	 authors LIKE '%$searchArray[$i]%' OR supervisor LIKE '%$searchArray[$i]%' OR
	  faculty LIKE '%$searchArray[$i]%' OR description LIKE '%$searchArray[$i]%' OR
	   release_date LIKE '%$searchArray[$i]%' OR project_type LIKE '%$searchArray[$i]%' OR project_status LIKE '%$searchArray[$i]%') ORDER BY ID DESC ;";

//If filter is active

} elseif(isset($_GET['filter'])) {
	$filterdata = $_GET['filter'];

	$filterdataArray = explode("~", $filterdata);
	$gradeArray = $filterdataArray[0];
	$facultyArray = $filterdataArray[1];
	$schoolyearArray = $filterdataArray[2];


	$gradeArray = explode("*", $gradeArray);
	$facultyArray = explode("*", $facultyArray);
	$schoolyearArray = explode("*", $schoolyearArray);


	$sql = "SELECT * FROM projects_data WHERE (";

	
	if (sizeof($gradeArray) > 1) {
		$gradearrayLength = sizeof($gradeArray);
		for ($i=1; $i < $gradearrayLength - 1; $i++) { 
			$sql = $sql."grade = '".$gradeArray[$i]."' OR ";
		}
		$sql = $sql."grade = '".$gradeArray[$gradearrayLength - 1]."')";
		if (sizeof($schoolyearArray) > 1 || sizeof($facultyArray) > 1) {
			$sql = $sql." AND (";
		} else {
			$sql = $sql." ORDER BY ID DESC;";
		}
	}

	if (sizeof($schoolyearArray) > 1) {
		$schoolyeararrayLength = sizeof($schoolyearArray);
		for ($i=1; $i < $schoolyeararrayLength - 1; $i++) { 
			$sql = $sql."school_year = '".$schoolyearArray[$i]."' OR ";
		}	

		$sql = $sql."school_year = '".$schoolyearArray[$schoolyeararrayLength - 1]."')";
		if (sizeof($facultyArray) > 1) {
			$sql = $sql." AND (";
		} else {
			$sql = $sql." ORDER BY ID DESC;";
		}
	}	

	if (sizeof($facultyArray) > 1) {
		$facultyarrayLength = sizeof($facultyArray);
		for ($i=1; $i < $facultyarrayLength - 1; $i++) { 
			$convert = facultyConverter($facultyArray[$i]);
			$sql = $sql."faculty = '".$convert."' OR ";
		}
		$convert = facultyConverter($facultyArray[$facultyarrayLength - 1]);
		$sql = $sql."faculty = '".$convert."') ORDER BY ID DESC ;";		
	}

	if ($sql == "SELECT * FROM projects_data WHERE (") {
		$sql = "SELECT * FROM projects_data ORDER BY ID DESC;";
	}


// If nothing is active

} else {
	$sql = "SELECT * FROM projects_data ORDER BY ID DESC;";

}

// Displaying data

$query = mysqli_query($conn, $sql);
$resultCheck = mysqli_num_rows($query);

$truestart = "найдено";
$trueending = "результатов";

$ending = $resultCheck % 10;
if ($ending == 1) {
	$truestart = "найден";
	$trueending = "результат";
} elseif ($ending == 2 || $ending == 3 || $ending == 4) {
	$trueending = "результата";
}

echo '
<p class="result-counter">По вашему запросу '.$truestart.' '.$resultCheck.' '.$trueending.'</p>
';
if ($resultCheck > 0) {

	while ($row = mysqli_fetch_assoc($query)) {

		$translit = russian2translit($row['project_name']);
		//Getting the path to the avatar pic
		$result = glob("uploads/".$translit."/avatar_proekta.*");
		$altresult = glob("tools/defaultpic.*");

		//Checking whether there is an avatar
		if ($result) {
			$imagePath = $result[0];
		} else {
			$imagePath = $altresult[0];
		}


		$projecttypeCheck = $row['project_type'];
		$projectstatusCheck = $row['project_status'];
		if ($projecttypeCheck == "") {
			$projecttypeCheck = "не установлен";
		}
		if ($projectstatusCheck == "") {
			$projectstatusCheck = "не установлена";
		}

		echo '
		<div class=project-box>
			<div class=image-display-block>
				<img class=avatar src="'.$imagePath.'">
			</div>
			<div class=other-display-block>
				<div class="download-ttpp-block">
					<a id="tbutton" href=download_counter.php?id='.$row["ID"].'&target=text title="Скачать текст проекта">
					<button><img src="tools/word.png"></button></a>
					<a id="pbutton" href=download_counter.php?id='.$row["ID"].'&target=pp title="Скачать презентацию проекта">
					<button><img src="tools/powerpoint.png"></button></a>					
				</div>
				<div class="download-all-block">
					<a href=download_counter.php?id='.$row["ID"].'&target=all>
					<button>Скачать все</button></a>
				</div>
				<div class="downloads-counter">
					<p>Скачиваний: '.$row["downloads"].'</p>		
				</div>
			</div>

			<div class=text-display-block>
				<div class=text-info-wrapper>
					<div class=text-info-block>
						<b>Кафедра:</b> '.$row["faculty"].'
					</div>				
					<div class=big-text-info-block>
						<b>Проект:</b> '.$row["project_name"].'
					</div>
					<div class=big-text-info-block>
						<b>Автор(ы):</b> '.$row["authors"].'
					</div>
					<div class=big-text-info-block>
						<b>Куратор(ы):</b> '.$row["supervisor"].'
					</div>					
					<div class=text-info-block>
						<b>Период работы:</b> '.$row["start_date"].'-'.$row["submit_date"].'
					</div>	
					<div class=text-info-block>
						<div class=bighalf-text-info-block>
							<b>Дата публикации:</b> '.$row["release_date"].'
						</div>
						<div class=smallhalf-text-info-block>
							<b>Класс:</b> '.$row["grade"].'
						</div>		
					</div>	
					<div class=text-info-block>
						<b>Тип проекта:</b> '.$projecttypeCheck.'
					</div>
					<div class=text-info-block>
						<b>Стадия проекта:</b> '.$projectstatusCheck.'
					</div>									
				</div>

				<div class="description-info-wrapper">
					<div class=description-info-block>
						<b>Описание:</b> '.$row["description"].' 
					</div>
				</div>
			</div>	


		</div>

		';
	}
}
$conn->close();
/*

				<div class="download-title-block">
					<p>Скачать</p>
				</div>
				<div class=download-ttpp-block>
					<div class=download-tt-block>
						<a href=download_counter.php?id='.$row["ID"].'&target=text>
						<button>Текст проекта</button></a>
					</div>					
					<div class=download-pp-block>
						<a href=download_counter.php?id='.$row["ID"].'&target=pp>
						<button>Презентация</button></a>
					</div>
				</div>
				<div class=download-all-button-block>
					<a href=download_counter.php?id='.$row["ID"].'&target=all>
					<button class=download-button>Скачать все</button></a><br><br>
				</div>	
				<div class=downloads-counter>
					<p>Скачиваний: '.$row["downloads"].'</p>		
				</div>
*/
?>
</div>
<?php
	include_once 'footer.php';
?>