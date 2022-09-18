
// Filter select boxes

/*________________________Testing grounds_______________________________*/


var x, i, j, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < selElmnt.length; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        h = this.parentNode.previousSibling;
        for (i = 0; i < s.length; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            for (k = 0; k < y.length; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);


/*_______________________________________________________*/



function showgradeCheckboxes() {
	var checkboxesgrade = document.getElementById("checkboxes-grade");
	var checkboxesfaculty = document.getElementById("checkboxes-faculty");
	var checkboxesschoolyear = document.getElementById("checkboxes-schoolyear");
	if (!expandedgrade) {
	checkboxesgrade.style.display = "block";
	expandedgrade = true;
	checkboxesfaculty.style.display = "none";
	expandedfaculty = false;
	checkboxesschoolyear.style.display = "none";
	expandedschoolyear = false;
	} else {
	checkboxesgrade.style.display = "none";
	expandedgrade = false;
	}
}

function showfacultyCheckboxes() {
	var checkboxesgrade = document.getElementById("checkboxes-grade");
	var checkboxesfaculty = document.getElementById("checkboxes-faculty");
	var checkboxesschoolyear = document.getElementById("checkboxes-schoolyear");
	if (!expandedfaculty) {
	checkboxesgrade.style.display = "none";
	expandedgrade = false;
	checkboxesfaculty.style.display = "block";
	expandedfaculty = true;
	checkboxesschoolyear.style.display = "none";
	expandedschoolyear = false;
	} else {
	checkboxesfaculty.style.display = "none";
	expandedfaculty = false;
	}
}

function showschoolyearCheckboxes() {
	var checkboxesgrade = document.getElementById("checkboxes-grade");
	var checkboxesfaculty = document.getElementById("checkboxes-faculty");
	var checkboxesschoolyear = document.getElementById("checkboxes-schoolyear");
	if (!expandedschoolyear) {
	checkboxesgrade.style.display = "none";
	expandedgrade = false;
	checkboxesfaculty.style.display = "none";
	expandedfaculty = false;
	checkboxesschoolyear.style.display = "block";
	expandedschoolyear = true;
	} else {
	checkboxesschoolyear.style.display = "none";
	expandedschoolyear = false;
	}
}

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

document.addEventListener("click", function(e){
	e = e || window.event;
	var target = e.target || e.srcElement;
	if ((target.id != "select-grade") && (target.id != "select-faculty") && 
		(target.id != "select-schoolyear") && (target.tagName != "LABEL") && (target.tagName != "INPUT")) {
		document.getElementById("checkboxes-grade").style.display = "none";
		document.getElementById("checkboxes-faculty").style.display = "none";
		document.getElementById("checkboxes-schoolyear").style.display = "none";	
		expandedgrade = false;
		expandedfaculty = false;	
		expandedschoolyear = false;
	}

});


//Once we click outside the popup boxes they disappear

window.onload = function(){
	var popupbox = document.getElementById('upload-box-wrapper');
	var filterbox = document.getElementById('filter-box-wrapper');
	window.onclick = function(event) {
		if (event.target == popupbox) {
			popupbox.style.display = "none";
		}
		if (event.target == filterbox) {
			filterbox.style.display = "none";
		}
	}
}


function deleteProject(event) {
	var confirmation = confirm('Вы точно хотите удалить проект?');
	if (!confirmation) {
		event.preventDefault();
	} 
}

function closeEdit(event) {
	var confirmation = confirm('Вы точно хотите выйти не сохранив изменения?');
	if (!confirmation) {
		event.preventDefault();
	} else {
		document.getElementById('edit-box-wrapper').style.display='none';
	}
}

function isValidDate(dateString)
{
    // First check for the pattern
    if(!/^\d{1,2}\.\d{1,2}\.\d{4}$/.test(dateString))
        return false;

    // Parse the date parts to integers
    var parts = dateString.split(".");
    var month = parseInt(parts[1], 10);
    var day = parseInt(parts[0], 10);
    var year = parseInt(parts[2], 10);

    // Check the ranges of month and year
    if(year < 2000 || year > 2100 || month == 0 || month > 12)
        return false;

    var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

    // Adjust for leap years
    if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        monthLength[1] = 29;

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
};


// Validating the soon-to-be uploaded project beforehand	


function processSubmit() {
	
	var text = document.getElementById('textfile').files.length;
	var pp = document.getElementById('ppfile').files.length;
	var image = document.getElementById('imagefile').files.length;
	var projectname = document.getElementById('project_name').value;
	var authors = document.getElementById('authors').value;
	var supervisor = document.getElementById('supervisor').value;
	var faculty = document.getElementById('faculty').value;
	var confirmation = confirm('Вы уверены что хотите загрузить проект?');
	var textallowed = ["docx", "doc", "txt", "text", "pdf"];
	var ppallowed = ["ppt", "pptx"];
	var imageallowed = ["png", "jpg", "jpeg"];
	var intro = "Проверьте расширения текста, презентации и аватара. Доступны для текста: ";
	var textallowedstr = ".docx, .doc, .txt, .text, .pdf";
	var ppallowedstr = ".ppt, .pptx";
	var imageallowedstr = ".jpg, .jpeg, png";
	var middle = ". Доступны для презентации: ";
	var secondmiddle = ". Доступны для аватара: ";
	var startdate = document.getElementById('start_date').value;
	var submitdate = document.getElementById('submit_date').value;
	var grade = document.getElementById('grade').value;
	var description = document.getElementById('description').value;
	var projectnameCheck = /^([a-zA-Z0-9А-Яа-яЁё \-_;]+)$/u;


	// Validating text and presentation extensions and sizes

	if (text != 0) {
		textActualExtArray = textfile.value.split('.');
		textActualExt = textActualExtArray[textActualExtArray.length - 1];	
		textvalid = textallowed.includes(textActualExt);
		textsize = document.getElementById('textfile').files[0].size;
	} else {
		textvalid = true;
		textsize = 0;
	}

	if (pp != 0) {
		ppActualExtArray = ppfile.value.split('.');	
		ppActualExt = ppActualExtArray[ppActualExtArray.length - 1];	
		ppvalid = ppallowed.includes(ppActualExt);
		ppsize = document.getElementById('ppfile').files[0].size;			
	} else {
		ppvalid = true;
		ppsize = 0;
	}

	if (image != 0) {
		imageActualExtArray = imagefile.value.split('.');	
		imageActualExt = imageActualExtArray[imageActualExtArray.length - 1];	
		imagevalid = imageallowed.includes(imageActualExt);
		imagesize = document.getElementById('imagefile').files[0].size;			
	} else {
		imagevalid = true;
		imagesize = 0;
	}

	// Checking input lengths

	var nameArray = projectname.split(" ");
	var authorsArray = authors.split(" ");
	var supervisorsArray = supervisor.split(" ");
	var descriptionArray = description.split(" ");
	var invLength = false;

	for (var i = 0; i < nameArray.length; i++) {
		if (nameArray[i].length > 30) {
			invLength = true;
			break;
		}
	}

	if (invLength == false) {
		for (var i = 0; i < authorsArray.length; i++) {
			if (authorsArray[i].length > 30) {
				invLength = true;
				break;
			}
		}		
	}

	if (invLength == false) {
		for (var i = 0; i < supervisorsArray.length; i++) {
			if (supervisorsArray[i].length > 30) {
				invLength = true;
				break;
			}
		}		
	}

	if (invLength == false) {
		for (var i = 0; i < descriptionArray.length; i++) {
			if (descriptionArray[i].length > 30) {
				invLength = true;
				break;
			}
		}		
	}
	

	// Checking whether the start date is less than the submit date
	var dateError = false;

	if (isValidDate(submitdate) && isValidDate(startdate)) {

		var submitdateArr = submitdate.split(".");
		var startdateArr = startdate.split(".");
		var submitdateObj = new Date(submitdateArr[2], submitdateArr[1], submitdateArr[0]);
		var startdateObj = new Date(startdateArr[2], startdateArr[1], startdateArr[0]);
		if (startdateObj >= submitdateObj) {
			dateError = true;
		}
	}


	// Validating submitted values

	archivefilesvalid = true;
	var archive = document.getElementById('archive');
	for (var i = 0; i < archive.files.length; i++) {
		if (archive.files[i].size > 100000000) {
			archivefilesvalid = false;
			break;
		}		
	}

	if (confirmation == false) {
		return false;
	} else if (faculty == "Выбрать кафедру") {

		alert('Выберите кафедру.');
		return false;

	} else if(textsize > 100000000 || ppsize > 100000000 || imagesize > 100000000 || !archivefilesvalid) {
		alert('Максимальный размер файлов на загрузку 100 Мб');
		return false;
	} else if(!textvalid || !ppvalid || !imagevalid) {

		alert(intro.concat(textallowedstr,middle,ppallowedstr,secondmiddle,imageallowedstr));
		return false;

	} else if(projectname == "" || authors == "" || supervisor == "" || 
		faculty == "" || submitdate == "" || grade == "" ||
		description == "" || projectname == null || authors == null || 
		supervisor == null || faculty == null || submitdate == null || 
		grade == null || description == null) {

		alert('Заполните все поля.');
		return false;
	} else if(!isValidDate(submitdate) || !isValidDate(startdate)) {
		alert('Дата не корректна. Формат даты: ДД.ММ.ГГГГ, например 01.01.1984. Введенный год не меньше 2000 и не больше 2100.');
		return false;
	} else if(dateError) {
		alert('Дата сдачи строго больше даты начала проекта.');
		return false;
	} else if(isNaN(grade)) {
		alert('Класс должен быть числом от 6 до 11');
		return false;
	} else if(parseInt(grade, 10) > 11 || parseInt(grade, 10) < 6) {
		alert('Класс должен быть числом от 6 до 11');
		return false;
	} else if(invLength) {
		alert('Каждое слово содержит не больше 30 символов');
		return false;
	} else if(projectnameCheck.test(projectname) == false) {
		alert('Недопустимые символы в названии проекта. Допустима кириллица, латиница, "-", "_" и ";"');
		return false;
	} else if(archive.files.length > 103) {
		alert("Максимальное количество файлов на загрузку 100");
		return false;
	} else {
		if (archive.files.length != 0) {
			uploadFiles(archive.files);					
		}
		return true;
	}
}


	//Uploading archive


function uploadFiles(files){
	
	// Create a new HTTP request, Form data item (data we will send to the server) 
	//and an empty string for the file paths.
	xhr = new XMLHttpRequest();
	data = new FormData();
	paths = "";
	projectname = document.getElementById('project_name').value;
	// Set how to handle the response text from the server
	xhr.onreadystatechange = function(ev){
		console.debug(xhr.responseText);
	};
	
	// Loop through the file list
	for (var i in files){
		// Append the current file path to the paths variable (delimited by tripple hash signs - ###)
		paths += files[i].webkitRelativePath+"###";
		// Append current file to our FormData with the index of i
		data.append(i, files[i]);
	};
	// Append the paths variable to our FormData to be sent to the server
	// Currently, As far as I know, HTTP requests do not natively carry the path data
	// So we must add it to the request manually.
	data.append('paths', paths);
	// Gonna append project name
	data.append('projectname', projectname);		
	// Open and send HTTP requests to upload.php
	xhr.open('POST', "upload_archive.php", true);
	xhr.send(this.data);
	
}


/*________________________Project updating functions_________________________*/


function update(id) {

	// Shaping the update box to fit the chosen project
	document.getElementById('edit-box-wrapper').style.display='block';
	document.getElementById('edit-box-form').action = "edit_project.php?id="+id;
	document.getElementById('edit-box-form').setAttribute("onsubmit", "return processEdit("+id+")");
	//document.getElementById('edit-box-form').submit = "return processEdit("+id+")";
	document.getElementById('delete-button-link').href = "delete_project.php?id="+id;

	// Requesting existing data belonging to the chosen project from the server

	var request = new XMLHttpRequest();

	request.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			
			// Getting requested data from the server

			var responseData = this.responseText;
			var dataArray = responseData.split("@@@");

			// Filling the inputs with existing data

			document.getElementById("edit_project_name").value = dataArray[0];
			document.getElementById("edit_authors").value = dataArray[1];
			document.getElementById("edit_supervisor").value = dataArray[2];	
			document.getElementById("edit_grade").value = dataArray[4];
			document.getElementById("edit_description").value = dataArray[5];
			document.getElementById("edit_start_date").value = dataArray[6];
			document.getElementById("edit_submit_date").value = dataArray[7];
			document.getElementById("edit_status").value = dataArray[8];
			document.getElementById("edit_type").value = dataArray[9];
			document.getElementById("edit_log").value = dataArray[10];

			//Selecting the right faculty 

			var faculty = dataArray[3];
			var facultySelect = document.getElementById("edit_faculty");
			var opts = facultySelect.options;
			for (var opt, j = 0; opt = opts[j]; j++) {
				if (opt.value == faculty) {
			 		facultySelect.selectedIndex = j;
			  		break;
				}
			}			

		}
	};	

	// Requesting data

	request.open("POST", "request_data.php", true); // false if synchronous
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send("id=" + id);

}


// Validating the edited project


function processEdit(id) {

	
	var text = document.getElementById('edit_textfile').files.length;  //Side note: length is the amount
	var pp = document.getElementById('edit_ppfile').files.length;	   //of files in the file input, not
	var image = document.getElementById('edit_imagefile').files.length;//the actual size of the file
	var projectname = document.getElementById('edit_project_name').value;
	var authors = document.getElementById('edit_authors').value;
	var supervisor = document.getElementById('edit_supervisor').value;
	var faculty = document.getElementById('edit_faculty').value;
	var confirmation = confirm('Вы уверены что хотите обновить проект? Если вы загружаете новые файлы, старые будут удалены');
	var textallowed = ["docx", "doc", "txt", "text", "pdf"];
	var ppallowed = ["ppt", "pptx"];
	var imageallowed = ["png", "jpg", "jpeg"];
	var intro = "Проверьте расширения текста, презентации и аватара. Доступны для текста: ";
	var textallowedstr = ".docx, .doc, .txt, .text, .pdf";
	var ppallowedstr = ".ppt, .pptx";
	var imageallowedstr = ".jpg, .jpeg, png";
	var middle = ". Доступны для презентации: ";
	var secondmiddle = ". Доступны для аватара: ";
	var startdate = document.getElementById('edit_start_date').value;
	var submitdate = document.getElementById('edit_submit_date').value;
	var grade = document.getElementById('edit_grade').value;
	var description = document.getElementById('edit_description').value;
	var projectnameCheck = /^([a-zA-Z0-9А-Яа-яЁё \-_;]+)$/u;


	// Validating text and presentation extensions and sizes

	if (text != 0) {
		textActualExtArray = edit_textfile.value.split('.');
		textActualExt = textActualExtArray[textActualExtArray.length - 1];	
		textvalid = textallowed.includes(textActualExt);
		textsize = document.getElementById('edit_textfile').files[0].size;
	} else {
		textvalid = true;
		textsize = 0;
	}

	if (pp != 0) {
		ppActualExtArray = edit_ppfile.value.split('.');	
		ppActualExt = ppActualExtArray[ppActualExtArray.length - 1];	
		ppvalid = ppallowed.includes(ppActualExt);
		ppsize = document.getElementById('edit_ppfile').files[0].size;			
	} else {
		ppvalid = true;
		ppsize = 0;
	}

	if (image != 0) {
		imageActualExtArray = edit_imagefile.value.split('.');	
		imageActualExt = imageActualExtArray[imageActualExtArray.length - 1];	
		imagevalid = imageallowed.includes(imageActualExt);
		imagesize = document.getElementById('edit_imagefile').files[0].size;			
	} else {
		imagevalid = true;
		imagesize = 0;
	}

	// Checking input lengths

	var nameArray = projectname.split(" ");
	var authorsArray = authors.split(" ");
	var supervisorsArray = supervisor.split(" ");
	var descriptionArray = description.split(" ");
	var invLength = false;

	for (var i = 0; i < nameArray.length; i++) {
		if (nameArray[i].length > 30) {
			invLength = true;
			break;
		}
	}

	if (invLength == false) {
		for (var i = 0; i < authorsArray.length; i++) {
			if (authorsArray[i].length > 30) {
				invLength = true;
				break;
			}
		}		
	}

	if (invLength == false) {
		for (var i = 0; i < supervisorsArray.length; i++) {
			if (supervisorsArray[i].length > 30) {
				invLength = true;
				break;
			}
		}		
	}

	if (invLength == false) {
		for (var i = 0; i < descriptionArray.length; i++) {
			if (descriptionArray[i].length > 30) {
				invLength = true;
				break;
			}
		}		
	}

	// Checking whether the start date is less than the submit date
	var dateError = false;

	if (isValidDate(submitdate) && isValidDate(startdate)) {

		var submitdateArr = submitdate.split(".");
		var startdateArr = startdate.split(".");
		var submitdateObj = new Date(submitdateArr[2], submitdateArr[1], submitdateArr[0]);
		var startdateObj = new Date(startdateArr[2], startdateArr[1], startdateArr[0]);
		if (startdateObj >= submitdateObj) {
			dateError = true;
		}
	}

	// Validating submitted values



	archivefilesvalid = true;
	var archive = document.getElementById('edit_archive');
	for (var i = 0; i < archive.files.length; i++) {
		if (archive.files[i].size > 100000000) {
			archivefilesvalid = false;
			break;
		}		
	}

	if (confirmation == false) {
		return false;
	} else if (faculty == "Выбрать кафедру") {

		alert('Выберите кафедру.');
		return false;

	} else if(textsize > 100000000 || ppsize > 100000000 || imagesize > 100000000 || !archivefilesvalid) {
		alert('Максимальный размер файлов на загрузку 100 Мб');
		return false;
	} else if(!textvalid || !ppvalid || !imagevalid) {

		alert(intro.concat(textallowedstr,middle,ppallowedstr,secondmiddle,imageallowedstr));
		return false;

	} else if(projectname == "" || authors == "" || supervisor == "" || 
		faculty == "" || submitdate == "" || grade == "" ||
		description == "" || projectname == null || authors == null || 
		supervisor == null || faculty == null || submitdate == null || 
		grade == null || description == null) {

		alert('Заполните все поля. Тип, стадия и история проекта необязательны.');
		return false;
	} else if(!isValidDate(submitdate) || !isValidDate(startdate)) {
		alert('Дата не корректна. Формат даты: ДД.ММ.ГГГГ, например 01.01.1984');
		return false;
	} else if(dateError) {
		alert('Дата сдачи строго больше даты начала проекта.');
		return false;
	} else if(isNaN(grade)) {
		alert('Класс должен быть числом от 6 до 11');
		return false;
	} else if(parseInt(grade, 10) > 11 || parseInt(grade, 10) < 6) {
		alert('Класс должен быть числом от 6 до 11');
		return false;
	} else if(invLength) {
		alert('Каждое слово содержит не больше 30 символов');
		return false;
	} else if(projectnameCheck.test(projectname) == false) {
		alert('Недопустимые символы в названии проекта. Допустима кириллица, латиница, "-", "_" и ";"');
		return false;
	} else if(archive.files.length > 103) {
		alert("Максимальное количество файлов на загрузку 100");
		return false;
	} else {
		if (archive.files.length != 0) {
			updateFiles(archive.files, id);					
		}
		return true;
	}
}


//Updating archive


function updateFiles(files, id){
	
	// Create a new HTTP request, Form data item (data we will send to the server) 
	//and an empty string for the file paths.
	xhr = new XMLHttpRequest();
	data = new FormData();
	paths = "";
	projectname = document.getElementById('edit_project_name').value;
	// Set how to handle the response text from the server
	xhr.onreadystatechange = function(ev){
		console.debug(xhr.responseText);
	};
	
	// Loop through the file list
	for (var i in files){
		// Append the current file path to the paths variable (delimited by tripple hash signs - ###)
		paths += files[i].webkitRelativePath+"###";
		// Append current file to our FormData with the index of i
		data.append(i, files[i]);
	};
	// Append the paths variable to our FormData to be sent to the server
	// Currently, As far as I know, HTTP requests do not natively carry the path data
	// So we must add it to the request manually.
	data.append('paths', paths);
	// Gonna append project name
	data.append('projectname', projectname);
	data.append('id', id);		
	// Open and send HTTP requests to upload.php
	xhr.open('POST', "update_archive.php", true);
	xhr.send(this.data);
	
}