<?php
ini_set('max_execution_time', 28800);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(- 1);

header ( "Content-type: text/html; charset=utf-8" );
date_default_timezone_set ( 'Europe/Istanbul' );
setlocale ( LC_ALL, "tr_TR" );

require_once 'db.php';
require("class.phpmailer.php");

$baglanti = mysqli_connect($hostadresi, $kullaniciadi, $sifre, $veritabani);
if (mysqli_connect_errno())
{
    echo "MySQL bağlantısı başarısız: " . mysqli_connect_error();
}

/// Template
$resultTmp = mysqli_query($baglanti,"select mailSubject, mailBody from mailTemplate where ID = 1");
$mailSubject = "";
$mailBody = "";
while($rowTmp = $resultTmp->fetch_assoc()) {
    $mailSubject = $rowTmp["mailSubject"];
    $mailBody = $rowTmp["mailBody"];
}
///

$result = mysqli_query($baglanti,"select ID, mailAdress from mails where sendDate is null and mailAdress is not null order by ID desc limit 100;");
while($row = $result->fetch_assoc()) {
    sendMailFromQ($row["ID"], $row["mailAdress"], $mailSubject, $mailBody);
    //echo $row["ID"]."--".$row["mailAdress"]."<br>";
}


function sendMailFromQ($recipientEmailID, $recipientEmail, $mailSubject, $mailBody)
{
    if ($recipientEmail != "") {
        
        $mailHost          =	"mail.zekmedya.com";
        $mailFromName      =	"Zek Medya";
        $mailFrom          =	"duyuru@zekmedya.com";
        $mailPassword      =	"*HMT*KS*";
        $mailBcc           =	"ahmet@zekmedya.com";
        $mailBccName       =	"Ahmet";
        $mailPort          =	"587";
        
        $hostadresi        =	"srvc20.turhost.com";
        $kullaniciadi      =	"zekmedya_zek583";
        $sifre             =	"zekmedya_zek583";
        $veritabani        =	"zekmedya_zek583";
        
        $baglanti = mysqli_connect($hostadresi, $kullaniciadi, $sifre, $veritabani);
        if (mysqli_connect_errno())
        {
            echo "MySQL bağlantısı başarısız: " . mysqli_connect_error();
        }
        
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 1; // Hata ayıklama değişkeni: 1 = hata ve mesaj gösterir, 2 = sadece mesaj gösterir
        $mail->SMTPAuth = true; //SMTP doğrulama olmalı ve bu değer değişmemeli
        $mail->SMTPSecure = 'ssl'; // Normal bağlantı için tls , güvenli bağlantı için ssl yazın
        $mail->Host = $mailHost; // Mail sunucusunun adresi (IP de olabilir)
        $mail->Port = 465; // Normal bağlantı için 587, güvenli bağlantı için 465 yazın
        $mail->IsHTML(true);
        $mail->SetLanguage("tr", "phpmailer/language");
        $mail->CharSet  ="utf-8";
        $mail->Username = "duyuru@zekmedya.com"; // Gönderici adresinizin sunucudaki kullanıcı adı (e-posta adresiniz)
        $mail->Password = "*HMT*KS*"; // Mail adresimizin sifresi
        $mail->SetFrom($mailFrom, $mailFromName); // Mail atıldığında gorulecek isim ve email (genelde yukarıdaki username kullanılır)
        $mail->AddAddress ( str_replace ( 'I', 'i', $recipientEmail ), $recipientEmail ); // Mailin gönderileceği alıcı adres
        $mail->Subject = $mailSubject; // Email konu başlığı
        $mail->Body = $mailBody; // Mailin içeriği
        
        if(!$mail->Send()){
            echo "Email Gönderim Hatasi: ".$mail->ErrorInfo;
            mysqli_query($baglanti,"UPDATE mails SET description = 'gonderilemedi. ".$mail->ErrorInfo."' where ID = ".$recipientEmailID);
            echo "Email Gonderildi";
        } else {
            mysqli_query($baglanti,"UPDATE mails SET description = 'gonderildi', sendDate = now() where ID = ".$recipientEmailID);
            echo "Email Gonderildi";
        }
        
        mysqli_close($baglanti);
    
    }
    
    
}
?>
