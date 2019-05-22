<?php
ini_set('max_execution_time', 28800);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(- 1);

date_default_timezone_set('Europe/Istanbul');
setlocale(LC_ALL, "tr_TR");

if ( isset($_FILES["fileToUpload"]["tmp_name"]) ) {
    
    $fileName = $_FILES["fileToUpload"]["tmp_name"];
    $uploadFolder = "files/";
    $target_file = $uploadFolder . basename($_FILES["fileToUpload"]["name"]);
    
    if (move_uploaded_file($fileName, $target_file)) {
        
        echo '<script language="javascript">';
        echo 'alert("Dosya Yüklendi. Email adresleri aktarılana kadar bekleyiniz..."); location.href = "importMails.php";';
        echo '</script>';
        
    } else {

        echo '<script language="javascript">';
        echo 'alert("Hata! Dosya Yüklenemedi...")';
        echo '</script>';
        
    }
}

require_once 'db.php';

$baglanti = mysqli_connect($hostadresi,$kullaniciadi,$sifre,$veritabani);
if (mysqli_connect_errno())
{
    echo "MySQL bağlantısı başarısız: " . mysqli_connect_error();
}

$result = mysqli_query($baglanti,"select mailSubject, mailBody from mailTemplate where ID = 1");
$subject = "";
$body = "";

while($row = $result->fetch_assoc()) {
    $subject = $row["mailSubject"];
    $body = $row["mailBody"];
}

mysqli_close($baglanti);

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
    					<h5 class="card-title">Şablon Belirle</h5>
    					<form action="saveTemplate.php" method="post" enctype="multipart/form-data">
    						<div class="form-group">
    							<label for="inputAddress">Konu</label> <input type="text"
    								class="form-control" id="txtSubject" name="txtSubject" placeholder="Konu" value="<?php echo $subject; ?>">
    						</div>
    						<div class="form-group">
    							<label for="inputAddress">Mesaj</label> 
    							<div class="page-wrapper box-content">
    								<textarea class="content" name="txtBody" id="txtBody"><?php echo $body;?></textarea>
    							</div>
    						</div>						
    						<div class="form-row">
    							<input class="btn btn-primary" type="submit" value="Kaydet">
    						</div>
    					</form>
    				</div>
    			</div>
    		</div>
    
    		<div class="row" style="margin-top: 20px;">
    			<div class="card col-sm-12">
    				<div class="card-body">
    					<h5 class="card-title">Excel Yükle</h5>
    					<form action="index.php" method="post" enctype="multipart/form-data">
    						<div class="form-row">
                              <div class="form-group">
                                <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload">
                              </div>
    						</div>
    						<div class="form-row">
    							<input class="btn btn-primary" type="submit" value="Yükle">
    						</div>
    					</form>
    				</div>
    			</div>
    		</div>
    
    	</div>
	</main>

	<script>
        $(document).ready(function() {
            $('.content').richText();
        });

   </script>

</html>