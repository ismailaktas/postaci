<?php
/*
        $mailHost          =	"mail.zekmedya.com";
        $mailFromName      =	"Zek Medya";
        $mailFrom          =	"duyuru@zekmedya.com";
        $mailPassword      =	"*HMT*KS*";
        $mailBcc           =	"ahmet@zekmedya.com";
        $mailBccName       =	"Ahmet";
        $mailPort          =	"587"; 
*/

require("class.phpmailer.php");
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1; // Hata ayıklama değişkeni: 1 = hata ve mesaj gösterir, 2 = sadece mesaj gösterir
$mail->SMTPAuth = true; //SMTP doğrulama olmalı ve bu değer değişmemeli
$mail->SMTPSecure = 'ssl'; // Normal bağlantı için tls , güvenli bağlantı için ssl yazın
$mail->Host = "mail.zekmedya.com"; // Mail sunucusunun adresi (IP de olabilir)
$mail->Port = 465; // Normal bağlantı için 587, güvenli bağlantı için 465 yazın
$mail->IsHTML(true);
$mail->SetLanguage("tr", "phpmailer/language");
$mail->CharSet  ="utf-8";
$mail->Username = "duyuru@zekmedya.com"; // Gönderici adresinizin sunucudaki kullanıcı adı (e-posta adresiniz)
$mail->Password = "*HMT*KS*"; // Mail adresimizin sifresi
$mail->SetFrom("duyuru@zekmedya.com", "Zek Medya"); // Mail atıldığında gorulecek isim ve email (genelde yukarıdaki username kullanılır)
$mail->AddAddress("aktasismail@yahoo.com"); // Mailin gönderileceği alıcı adres
$mail->Subject = "Kampanya"; // Email konu başlığı
$mail->Body = "Kampanya Metni"; // Mailin içeriği
if(!$mail->Send()){
	echo "Email Gönderim Hatasi: ".$mail->ErrorInfo;
} else {
	echo "Email Gonderildi";
}
?>