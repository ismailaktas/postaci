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

$result = mysqli_query($baglanti,"select mailAdress, createdDate, sendDate, description from mails order by ID desc");
$subject = "";
$body = "";
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

<script type="text/javascript"
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


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
					<li class="nav-item active"><a class="nav-link" href="index.php">Şablon&Excel </a></li>
					<li class="nav-item"><a class="nav-link" href="sentMails.php">Gönderilenler<span
							class="sr-only">(current)</span></a>
					</li>
				</ul>
			</div>
		</nav>
	</header>

	<main role="main" class="flex-shrink-0">
    	<div class="container">
    		<h3 class="mt-5">Gönderilen Mailler</h3>
    
    		<div class="row" style="margin-top: 20px;">
    		
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">Email</th>
                      <th scope="col">Eklenme Tarihi</th>
                      <th scope="col">Gönderilme Tarihi</th>
                      <th scope="col">Açıklama</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  while($row = $result->fetch_assoc()) {
                  ?>
                    <tr>
                      <td><?php echo $row["mailAdress"] ?></td>
                      <td><?php echo $row["createdDate"] ?></td>
                      <td><?php echo $row["sendDate"] ?></td>
                      <td><?php echo $row["description"] ?></td>
                    </tr>
                    <?php 
                  }
                  ?>
                  </tbody>
                </table>    		

    		</div>
    
    	</div>
	</main>
	<?php 
    mysqli_close($baglanti);
    ?>
</html>