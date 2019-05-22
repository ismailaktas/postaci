<?php
ini_set('max_execution_time', 28800);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(- 1);

date_default_timezone_set('Europe/Istanbul');
setlocale(LC_ALL, "tr_TR");

require_once 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
require_once 'db.php';
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
<title>Postacı</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta content="" name="author" />

<style>
.bd-placeholder-img {
	font-size: 1.125rem;
	text-anchor: middle;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

@media ( min-width : 768px) {
	.bd-placeholder-img-lg {
		font-size: 3.5rem;
	}
}
</style>

<link rel="stylesheet" href="cssjs/css/bootstrap.min.css"
	integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
	crossorigin="anonymous">
<script src="cssjs/js/bootstrap.min.js"
	integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
	crossorigin="anonymous"></script>
<link href="cssjs/css/sticky-footer-navbar.css" rel="stylesheet">
<script src="cssjs/js/jquery-3.4.1.min.js" crossorigin="anonymous"></script>

<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="src/richtext.min.css">
<script type="text/javascript"
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="src/jquery.richtext.js"></script>

</head>

<body class="d-flex flex-column h-100">
	<header>
		<!-- Fixed navbar -->
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<a class="navbar-brand" href="#">Postacı</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse"
				data-target="#navbarCollapse" aria-controls="navbarCollapse"
				aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active"><a class="nav-link" href="index.php">Şablon&Excel <span
							class="sr-only">(current)</span></a></li>
					<li class="nav-item"><a class="nav-link" href="sentMails.php">Gönderilenler</a>
					</li>
				</ul>
			</div>
		</nav>
	</header>

	<main role="main" class="flex-shrink-0">
	<div class="container">
		<h3 class="mt-5">Postacı Mail Gönderim Uygulaması</h3>

		<div class="row" style="margin-top: 20px;">
			<div class="card col-sm-12">
				<div class="card-body">
    			<?php
    			
    			$baglanti = mysqli_connect($hostadresi,$kullaniciadi,$sifre,$veritabani);
    			if (mysqli_connect_errno())
    			{
    			    echo "MySQL bağlantısı başarısız: " . mysqli_connect_error();
    			}
    			
                $objPHPExcel = PHPExcel_IOFactory::load("files/mail.xlsx");
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;
                    // echo "<br>The worksheet " . $worksheetTitle . " has ";
                    // echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
                    // echo ' and ' . $highestRow . ' row.';
                    $frow = 0;
                    $appID = 0;
                    $mlCount = 0;
                    $mls = "";
                    
                    for ($row = 2; $row <= $highestRow; ++ $row) {
                        $mailCell = $worksheet->getCellByColumnAndRow(0, $row);
                        $mailAdress = $mailCell->getValue();
                        
                        mysqli_query($baglanti,"INSERT INTO mails (mailAdress) VALUES ('$mailAdress')");
                        
                        // kaydet
                        $mls .= $mailAdress . "<br>";
                        $mlCount = $mlCount + 1;
                    }
                    
                    //echo "<p>$mlCount Email Eklendi</p>" . "";
                    ;
                }
        
                mysqli_close($baglanti);
        ?>
        <h5 class="card-title"><?php echo "$mlCount Email Eklendi" ?></h5>
        <?php 
        echo "<p>$mls</p>";
        ?>
    				</div>
			</div>
		</div>



	</div>
	</main>

</html>


