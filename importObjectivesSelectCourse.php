<?php
ini_set('max_execution_time', 28800);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(- 1);

header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Europe/Istanbul');
setlocale(LC_ALL, "tr_TR");

$academicYearID = 1;
require_once dirname(dirname(__FILE__)) . "/BL/Models/courses.php";
$courses = new courses();
$result = $courses->getAllCourses();

$result = $courses->executenonquery("call prcGetCourses(" . $_SESSION['accountID'] . ");");
$strHtml = "";

if (isset($_POST["submit"])) {
    
    $fileName = $_FILES["fileToUpload"]["tmp_name"];
    $uploadFolder = dirname(dirname(__FILE__)) . "/Process/files/";
    $target_file = $uploadFolder . basename($_FILES["fileToUpload"]["name"]);
    
    $cmbYesNo = $_POST["cmbYesNo"];
    $cmbCourse = $_POST["cmbCourse"];
    
    if($cmbYesNo=="sec"){
        echo '<script language="javascript">';
        echo 'alert("Lutfen Secim Yapiniz...")';
        echo '</script>';
    }
    
    if (move_uploaded_file($fileName, $target_file)) {      
        
        if ($cmbYesNo == "true") {
            $courses->executenonquery("call prcObjectiveTransferDelete('$cmbCourse');");
        }
        $courses->redirectUrl("importObjectives.php?courseCode=" . $cmbCourse);
        echo "upload succesfully";
    } else {
        echo "upload failed";
    }
}

?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="tr">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
<meta charset="utf-8" />
<title>Kazanim Aktarim</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta content="" name="author" />

<!-- GLOBAL STYLES BEGIN -->
	
	<link href="../Library/Metronic/theme/assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="../Library/Metronic/theme/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
	<link href="../Library/Metronic/theme/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
	<link href="../Library/Metronic/theme/assets/layouts/layout/css/layout.min.css?ver=1" rel="stylesheet" type="text/css" />
	<link href="../Library/Metronic/theme/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
	<link href="../Library/Metronic/theme/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
	<link href="../Library/Metronic/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
	
		
<!-- GLOBAL STYLES END -->

<link
	href="../Library/Metronic/theme/assets/global/plugins/select2/css/select2.css"
	rel="stylesheet" type="text/css" />
<link
	href="../Library/Metronic/theme/assets/global/plugins/select2/css/select2-bootstrap.min.css"
	rel="stylesheet" type="text/css" />
<link
	href="../Library/Metronic/theme/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"
	rel="stylesheet" type="text/css" />
<link
	href="../Library/Metronic/theme/assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css"
	rel="stylesheet" type="text/css" />
<link
	href="../Library/Metronic/theme/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"
	rel="stylesheet" type="text/css" />
	
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-closed" style="background-color: #fff;">

<div class="page-wrapper">

<div class="col-lg-12">
	<div class="col">
		<div class="col-md-6">
			<form action="importObjectivesSelectCourse.php" method="post"
				enctype="multipart/form-data">
				<div class="form-group">
					<label class="control-label col-md-4">Ders</label>
					<div class="col-md-8">
						<div class="input-icon input-large right">
							<select class="bs-select form-control  input-large"
								name="cmbCourse" id="cmbCourse">
    								        <?php
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<option value='" . $row["code"] . "'>" . $row["code"] . " - " . $row["courseName"] . "</option>";
                    }
                    ?>
    								</select>
						</div>
					</div>
				</div>
				&nbsp;
				<div class="form-group">
					<label class="control-label col-md-4">Unite/Alt Unite/Kazanimlar
						silinsin mi?</label>
					<div class="col-md-8">
						<div class="input-icon input-large  right">
							<select class="bs-select form-control  input-large"
								name="cmbYesNo" id="cmbYesNo">
								<option value="sec">Seçiniz</option> 
								<option value="true">Evet</option>
								<option value="false">Hayır</option>
							</select>
						</div>
					</div>
				</div>
				&nbsp;
				<br>
				<div class="col-md-3">
					<input type="file" name="fileToUpload" id="fileToUpload" value="Dosya Yükle"> <br>
					<input type="submit" value="Kazanımları Aktar" name="submit">
				</div>
			</form>
		</div>
	</div>
</div>
<a href="https://deksis.darussafaka.k12.tr/Process/files/sampleObjectives.xlsx" class="pull-left" style="display: inline-block;margin-top: 15px; margin-left: 40px;">Örnek Aktarım Formatı <i style="text-decoration: underline;" class="fa fa-file-excel-o" aria-hidden="true"></i></a>
</div>

<script type="text/javascript">
	function gonder()
	{
		var  courseCode = document.getElementById("cmbCourse").value;
		location.href = "importObjectives.php?courseCode="+courseCode;
	}
</script>

</body>
</html>